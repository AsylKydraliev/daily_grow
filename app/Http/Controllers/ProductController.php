<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Branch;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $products = Product::query()
            ->with('branch')
            ->filter($request->all())
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $productsCount = Product::query()->count('id');

        return Inertia::render('Products/Index', [
            'products' => $products,
            'productsCount' => $productsCount,
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Products/Create', [
            'branches' => Branch::all(),
        ]);
    }

    /**
     * @param StoreProductRequest $request
     * @return RedirectResponse
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::query()->create($request->validated());

        return to_route('products.index')
            ->with('success', 'Товар успешно создан');
    }

    /**
     * @param Product $product
     * @return Response
     */
    public function show(Product $product): Response
    {
        $product->load('branch');

        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }

    /**
     * @param Product $product
     * @return Response
     */
    public function edit(Product $product): Response
    {
        return Inertia::render('Products/Edit', [
            'product' => $product,
            'branches' => Branch::all(),
        ]);
    }

    /**
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return to_route('products.index')
            ->with('success', 'Товар успешно обновлен');
    }

    /**
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return to_route('products.index')
            ->with('success', 'Товар успешно удален');
    }
}
