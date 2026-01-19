<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductReceiptRequest;
use App\Http\Requests\UpdateProductReceiptRequest;
use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductReceipt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductReceiptController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $productReceipts = ProductReceipt::query()
            ->with(['product', 'branch'])
            ->filter($request->all())
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $productReceiptsCount = ProductReceipt::query()
            ->filter($request->all())
            ->count('id');

        return Inertia::render('ProductReceipts/Index', [
            'productReceipts' => $productReceipts,
            'productReceiptsCount' => $productReceiptsCount,
            'branches' => Branch::all(),
            'filters' => $request->only(['branch_id', 'date_from', 'date_to']),
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        // Получаем товары с текущим количеством в каждом филиале
        $products = Product::query()
            ->with('branch')
            ->get()
            ->map(function ($product) {
                // Считаем текущее количество товара в филиале (сумма всех приходов)
                $currentQuantity = ProductReceipt::query()
                    ->where('product_id', $product->id)
                    ->where('branch_id', $product->branch_id)
                    ->sum('quantity');

                $product->current_quantity = $currentQuantity ?? 0;
                return $product;
            });

        return Inertia::render('ProductReceipts/Create', [
            'products' => $products,
            'branches' => Branch::all(),
        ]);
    }

    /**
     * @param StoreProductReceiptRequest $request
     * @return RedirectResponse
     */
    public function store(StoreProductReceiptRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $branchId = $validated['branch_id'];
        $receiptDate = $validated['receipt_date'];

        // Создаем приход для каждого товара с количеством > 0
        foreach ($validated['products'] as $productData) {
            if ($productData['quantity'] > 0) {
                // Проверяем, что товар принадлежит выбранному филиалу
                $product = Product::find($productData['product_id']);
                if (!$product || $product->branch_id != $branchId) {
                    continue; // Пропускаем товары, которые не принадлежат филиалу
                }

                ProductReceipt::query()->create([
                    'product_id' => $productData['product_id'],
                    'branch_id' => $branchId,
                    'quantity' => $productData['quantity'],
                    'receipt_date' => $receiptDate,
                ]);
            }
        }

        $count = count(array_filter($validated['products'], fn($p) => $p['quantity'] > 0));
        $message = $count === 1
            ? 'Приход товара успешно создан'
            : "Приход товаров успешно создан ({$count} товаров)";

        return to_route('product-receipts.index')
            ->with('success', $message);
    }

    /**
     * @param ProductReceipt $productReceipt
     * @return Response
     */
    public function show(ProductReceipt $productReceipt): Response
    {
        $productReceipt->load(['product', 'branch']);

        return Inertia::render('ProductReceipts/Show', [
            'productReceipt' => $productReceipt,
        ]);
    }

    /**
     * @param ProductReceipt $productReceipt
     * @return Response
     */
    public function edit(ProductReceipt $productReceipt): Response
    {
        // Получаем товары с текущим количеством в каждом филиале
        $products = Product::query()
            ->with('branch')
            ->get()
            ->map(function ($product) use ($productReceipt) {
                // Считаем текущее количество товара в филиале (сумма всех приходов)
                $currentQuantity = ProductReceipt::query()
                    ->where('product_id', $product->id)
                    ->where('branch_id', $product->branch_id)
                    ->sum('quantity');
                
                // Если это товар из редактируемого прихода, вычитаем его количество
                // чтобы показать количество без этого прихода
                if ($product->id == $productReceipt->product_id && 
                    $product->branch_id == $productReceipt->branch_id) {
                    $currentQuantity -= $productReceipt->quantity;
                }
                
                $product->current_quantity = max(0, $currentQuantity ?? 0);
                return $product;
            });

        return Inertia::render('ProductReceipts/Edit', [
            'productReceipt' => $productReceipt,
            'products' => $products,
            'branches' => Branch::all(),
        ]);
    }

    /**
     * @param UpdateProductReceiptRequest $request
     * @param ProductReceipt $productReceipt
     * @return RedirectResponse
     */
    public function update(UpdateProductReceiptRequest $request, ProductReceipt $productReceipt): RedirectResponse
    {
        $validated = $request->validated();
        $branchId = $validated['branch_id'];
        $receiptDate = $validated['receipt_date'];

        // Удаляем старый приход товара
        $productReceipt->delete();

        // Создаем новые приходы для каждого товара с количеством > 0
        foreach ($validated['products'] as $productData) {
            if ($productData['quantity'] > 0) {
                // Проверяем, что товар принадлежит выбранному филиалу
                $product = Product::find($productData['product_id']);
                if (!$product || $product->branch_id != $branchId) {
                    continue; // Пропускаем товары, которые не принадлежат филиалу
                }

                ProductReceipt::query()->create([
                    'product_id' => $productData['product_id'],
                    'branch_id' => $branchId,
                    'quantity' => $productData['quantity'],
                    'receipt_date' => $receiptDate,
                ]);
            }
        }

        $count = count(array_filter($validated['products'], fn($p) => $p['quantity'] > 0));
        $message = $count === 1
            ? 'Приход товара успешно обновлен'
            : "Приход товаров успешно обновлен ({$count} товаров)";

        return to_route('product-receipts.index')
            ->with('success', $message);
    }

    /**
     * @param Request $request
     * @param ProductReceipt $productReceipt
     * @return RedirectResponse
     */
    public function destroy(Request $request, ProductReceipt $productReceipt): RedirectResponse
    {
        $productReceipt->delete();

        // Сохраняем фильтры при редиректе
        return redirect()->route('product-receipts.index', $request->only(['branch_id', 'date_from', 'date_to']))
            ->with('success', 'Приход товара успешно удален');
    }
}
