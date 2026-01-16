<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCounterpartyRequest;
use App\Http\Requests\UpdateCounterpartyRequest;
use App\Models\Counterparty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CounterpartyController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $counterparties = Counterparty::query()
            ->filter($request->all())
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $counterpartiesCount = Counterparty::query()->count('id');

        return Inertia::render('Counterparties/Index', [
            'counterparties' => $counterparties,
            'counterpartiesCount' => $counterpartiesCount,
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Counterparties/Create');
    }

    /**
     * @param StoreCounterpartyRequest $request
     * @return RedirectResponse
     */
    public function store(StoreCounterpartyRequest $request): RedirectResponse
    {
        Counterparty::query()->create($request->validated());

        return to_route('counterparties.index')
            ->with('success', 'Контрагент успешно создан');
    }

    /**
     * @param Counterparty $counterparty
     * @return Response
     */
    public function show(Counterparty $counterparty): Response
    {
        return Inertia::render('Counterparties/Show', [
            'counterparty' => $counterparty,
        ]);
    }

    /**
     * @param Counterparty $counterparty
     * @return Response
     */
    public function edit(Counterparty $counterparty): Response
    {
        return Inertia::render('Counterparties/Edit', [
            'counterparty' => $counterparty,
        ]);
    }

    /**
     * @param UpdateCounterpartyRequest $request
     * @param Counterparty $counterparty
     * @return RedirectResponse
     */
    public function update(UpdateCounterpartyRequest $request, Counterparty $counterparty): RedirectResponse
    {
        $counterparty->update($request->validated());

        return to_route('counterparties.index')
            ->with('success', 'Контрагент успешно обновлен');
    }

    /**
     * @param Counterparty $counterparty
     * @return RedirectResponse
     */
    public function destroy(Counterparty $counterparty): RedirectResponse
    {
        $counterparty->delete();

        return to_route('counterparties.index')
            ->with('success', 'Контрагент успешно удален');
    }
}
