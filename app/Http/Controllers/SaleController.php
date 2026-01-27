<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Branch;
use App\Models\Counterparty;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SaleController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $sales = Sale::query()
            ->with(['product', 'counterparty', 'branch'])
            ->filter($request->all())
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $salesCount = Sale::query()
            ->filter($request->all())
            ->count('id');

        return Inertia::render('Sales/Index', [
            'sales' => $sales,
            'salesCount' => $salesCount,
            'branches' => Branch::all(),
            'filters' => $request->only(['branch_id', 'date_from', 'date_to']),
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        // Получаем товары с текущим количеством и продажами в каждом филиале
        $products = Product::query()
            ->with('branch')
            ->get()
            ->map(function ($product) {
                // Используем current_quantity из продукта
                $product->current_quantity = $product->current_quantity ?? 0;
                
                // Считаем количество продаж товара в филиале
                $saleQuantity = Sale::query()
                    ->where('product_id', $product->id)
                    ->where('branch_id', $product->branch_id)
                    ->sum('quantity');

                $product->sale_quantity = $saleQuantity ?? 0;
                return $product;
            });

        return Inertia::render('Sales/Create', [
            'products' => $products,
            'counterparties' => Counterparty::all(),
            'branches' => Branch::all(),
        ]);
    }

    /**
     * @param StoreSaleRequest $request
     * @return RedirectResponse
     */
    public function store(StoreSaleRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $branchId = $validated['branch_id'];
        $counterpartyId = $validated['counterparty_id'];
        $saleDate = $validated['sale_date'];

        // Создаем продажу для каждого товара с количеством > 0
        foreach ($validated['sales'] as $saleData) {
            if ($saleData['quantity'] > 0) {
                // Проверяем, что товар принадлежит выбранному филиалу
                $product = Product::find($saleData['product_id']);
                if (!$product || $product->branch_id != $branchId) {
                    continue; // Пропускаем товары, которые не принадлежат филиалу
                }

                Sale::query()->create([
                    'product_id' => $saleData['product_id'],
                    'counterparty_id' => $counterpartyId,
                    'branch_id' => $branchId,
                    'quantity' => $saleData['quantity'],
                    'price' => $saleData['price'],
                    'sale_date' => $saleDate,
                ]);

                // Вычитаем количество из current_quantity товара
                $product->decrement('current_quantity', $saleData['quantity']);
            }
        }

        $count = count(array_filter($validated['sales'], fn($s) => $s['quantity'] > 0));
        $message = $count === 1
            ? 'Продажа успешно создана'
            : "Продажи успешно созданы ({$count} товаров)";

        return to_route('sales.index')
            ->with('success', $message);
    }

    /**
     * @param Sale $sale
     * @return Response
     */
    public function show(Sale $sale): Response
    {
        $sale->load(['product', 'counterparty', 'branch']);

        return Inertia::render('Sales/Show', [
            'sale' => $sale,
        ]);
    }

    /**
     * @param Sale $sale
     * @return Response
     */
    public function edit(Sale $sale): Response
    {
        return Inertia::render('Sales/Edit', [
            'sale' => $sale,
            'products' => Product::all(),
            'counterparties' => Counterparty::all(),
            'branches' => Branch::all(),
        ]);
    }

    /**
     * @param UpdateSaleRequest $request
     * @param Sale $sale
     * @return RedirectResponse
     */
    public function update(UpdateSaleRequest $request, Sale $sale): RedirectResponse
    {
        // Сохраняем старое количество продажи
        $oldQuantity = $sale->quantity;
        $productId = $sale->product_id;

        // Обновляем продажу
        $sale->update($request->validated());

        // Получаем новое количество
        $newQuantity = $sale->quantity;

        // Корректируем current_quantity товара
        if ($productId) {
            $product = Product::find($productId);
            if ($product) {
                $difference = $newQuantity - $oldQuantity;
                if ($difference > 0) {
                    // Увеличилось количество - вычитаем разницу
                    $product->decrement('current_quantity', $difference);
                } elseif ($difference < 0) {
                    // Уменьшилось количество - прибавляем разницу
                    $product->increment('current_quantity', abs($difference));
                }
            }
        }

        return to_route('sales.index')
            ->with('success', 'Продажа успешно обновлена');
    }

    /**
     * @param Request $request
     * @param Sale $sale
     * @return RedirectResponse
     */
    public function destroy(Request $request, Sale $sale): RedirectResponse
    {
        // Сохраняем данные продажи перед удалением
        $productId = $sale->product_id;
        $quantity = $sale->quantity;

        // Удаляем продажу
        $sale->delete();

        // Прибавляем количество обратно к current_quantity товара
        if ($productId) {
            $product = Product::find($productId);
            if ($product) {
                $product->increment('current_quantity', $quantity);
            }
        }

        // Сохраняем фильтры при редиректе
        return redirect()->route('sales.index', $request->only(['branch_id', 'date_from', 'date_to']))
            ->with('success', 'Продажа успешно удалена');
    }
}
