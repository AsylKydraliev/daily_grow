<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Branch;
use App\Models\Counterparty;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
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
            ->with(['product', 'counterparty', 'branch', 'user'])
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $salesCount = Sale::query()->count('id');

        return Inertia::render('Sales/Index', [
            'sales' => $sales,
            'salesCount' => $salesCount,
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Sales/Create', [
            'products' => Product::all(),
            'counterparties' => Counterparty::all(),
            'branches' => Branch::all(),
            'users' => User::all(),
        ]);
    }

    /**
     * @param StoreSaleRequest $request
     * @return RedirectResponse
     */
    public function store(StoreSaleRequest $request): RedirectResponse
    {
        Sale::query()->create($request->validated());

        return to_route('sales.index')
            ->with('success', 'Продажа успешно создана');
    }

    /**
     * @param Sale $sale
     * @return Response
     */
    public function show(Sale $sale): Response
    {
        $sale->load(['product', 'counterparty', 'branch', 'user']);

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
            'users' => User::all(),
        ]);
    }

    /**
     * @param UpdateSaleRequest $request
     * @param Sale $sale
     * @return RedirectResponse
     */
    public function update(UpdateSaleRequest $request, Sale $sale): RedirectResponse
    {
        $sale->update($request->validated());

        return to_route('sales.index')
            ->with('success', 'Продажа успешно обновлена');
    }

    /**
     * @param Sale $sale
     * @return RedirectResponse
     */
    public function destroy(Sale $sale): RedirectResponse
    {
        $sale->delete();

        return to_route('sales.index')
            ->with('success', 'Продажа успешно удалена');
    }
}
