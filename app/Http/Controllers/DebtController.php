<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDebtRequest;
use App\Http\Requests\UpdateDebtRequest;
use App\Models\Branch;
use App\Models\Debt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DebtController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $debts = Debt::query()
            ->with('branch')
            ->orderByDesc('debt_date')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $debtsCount = Debt::query()->count('id');

        return Inertia::render('Debts/Index', [
            'debts' => $debts,
            'debtsCount' => $debtsCount,
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Debts/Create', [
            'branches' => Branch::all(),
        ]);
    }

    /**
     * @param StoreDebtRequest $request
     * @return RedirectResponse
     */
    public function store(StoreDebtRequest $request): RedirectResponse
    {
        Debt::query()->create($request->validated());

        return to_route('debts.index')
            ->with('success', 'Долг успешно создан');
    }

    /**
     * @param Debt $debt
     * @return Response
     */
    public function show(Debt $debt): Response
    {
        $debt->load('branch');

        return Inertia::render('Debts/Show', [
            'debt' => $debt,
        ]);
    }

    /**
     * @param Debt $debt
     * @return Response
     */
    public function edit(Debt $debt): Response
    {
        return Inertia::render('Debts/Edit', [
            'debt' => $debt,
            'branches' => Branch::all(),
        ]);
    }

    /**
     * @param UpdateDebtRequest $request
     * @param Debt $debt
     * @return RedirectResponse
     */
    public function update(UpdateDebtRequest $request, Debt $debt): RedirectResponse
    {
        $debt->update($request->validated());

        return to_route('debts.index')
            ->with('success', 'Долг успешно обновлен');
    }

    /**
     * @param Debt $debt
     * @return RedirectResponse
     */
    public function destroy(Debt $debt): RedirectResponse
    {
        $debt->delete();

        return to_route('debts.index')
            ->with('success', 'Долг успешно удален');
    }
}

