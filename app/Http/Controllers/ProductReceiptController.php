<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductReceiptRequest;
use App\Http\Requests\UpdateProductReceiptRequest;
use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductReceipt;
use App\Models\User;
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
            ->with(['product', 'branch', 'user'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $productReceiptsCount = ProductReceipt::query()->count('id');

        return Inertia::render('ProductReceipts/Index', [
            'productReceipts' => $productReceipts,
            'productReceiptsCount' => $productReceiptsCount,
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('ProductReceipts/Create', [
            'products' => Product::all(),
            'branches' => Branch::all(),
            'users' => User::all(),
        ]);
    }

    /**
     * @param StoreProductReceiptRequest $request
     * @return RedirectResponse
     */
    public function store(StoreProductReceiptRequest $request): RedirectResponse
    {
        ProductReceipt::query()->create($request->validated());

        return to_route('product-receipts.index')
            ->with('success', 'Приход товара успешно создан');
    }

    /**
     * @param ProductReceipt $productReceipt
     * @return Response
     */
    public function show(ProductReceipt $productReceipt): Response
    {
        $productReceipt->load(['product', 'branch', 'user']);

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
        return Inertia::render('ProductReceipts/Edit', [
            'productReceipt' => $productReceipt,
            'products' => Product::all(),
            'branches' => Branch::all(),
            'users' => User::all(),
        ]);
    }

    /**
     * @param UpdateProductReceiptRequest $request
     * @param ProductReceipt $productReceipt
     * @return RedirectResponse
     */
    public function update(UpdateProductReceiptRequest $request, ProductReceipt $productReceipt): RedirectResponse
    {
        $productReceipt->update($request->validated());

        return to_route('product-receipts.index')
            ->with('success', 'Приход товара успешно обновлен');
    }

    /**
     * @param ProductReceipt $productReceipt
     * @return RedirectResponse
     */
    public function destroy(ProductReceipt $productReceipt): RedirectResponse
    {
        $productReceipt->delete();

        return to_route('product-receipts.index')
            ->with('success', 'Приход товара успешно удален');
    }
}
