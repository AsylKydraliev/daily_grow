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
            ->withQueryString()
            ->through(function ($receipt) {
                // Добавляем current_quantity из продукта
                $receipt->current_quantity = $receipt->product->current_quantity ?? 0;
                return $receipt;
            });

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
        // Получаем товары с текущим количеством из базы данных
        $products = Product::query()
            ->with('branch')
            ->get();

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
                    'wholesale_price_usd' => $productData['wholesale_price_usd'] ?? null,
                    'receipt_date' => $receiptDate,
                ]);

                // Обновляем текущее количество товара: прибавляем количество прихода
                $product->increment('current_quantity', $productData['quantity']);
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
        // Получаем товары с текущим количеством из базы данных
        $products = Product::query()
            ->with('branch')
            ->get();

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

        // Сохраняем старое количество прихода для корректировки current_quantity
        $oldQuantity = $productReceipt->quantity;
        $oldProductId = $productReceipt->product_id;

        // Удаляем старый приход товара
        $productReceipt->delete();

        // Вычитаем старое количество из current_quantity товара
        if ($oldProductId) {
            $oldProduct = Product::find($oldProductId);
            if ($oldProduct) {
                $oldProduct->decrement('current_quantity', $oldQuantity);
            }
        }

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
                    'wholesale_price_usd' => $productData['wholesale_price_usd'] ?? null,
                    'receipt_date' => $receiptDate,
                ]);

                // Обновляем текущее количество товара: прибавляем количество прихода
                $product->increment('current_quantity', $productData['quantity']);
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
        // Сохраняем данные прихода перед удалением
        $productId = $productReceipt->product_id;
        $quantity = $productReceipt->quantity;

        // Удаляем приход
        $productReceipt->delete();

        // Вычитаем количество из current_quantity товара
        if ($productId) {
            $product = Product::find($productId);
            if ($product) {
                $product->decrement('current_quantity', $quantity);
            }
        }

        // Сохраняем фильтры при редиректе
        return redirect()->route('product-receipts.index', $request->only(['branch_id', 'date_from', 'date_to']))
            ->with('success', 'Приход товара успешно удален');
    }
}
