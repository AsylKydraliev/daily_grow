<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BranchController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $branches = Branch::query()
            ->filter($request->all())
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $branchesCount = Branch::query()->count('id');

        return Inertia::render('Branches/Index', [
            'branches' => $branches,
            'branchesCount' => $branchesCount,
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Branches/Create');
    }

    /**
     * @param StoreBranchRequest $request
     * @return RedirectResponse
     */
    public function store(StoreBranchRequest $request): RedirectResponse
    {
        Branch::query()->create($request->validated());

        return to_route('branches.index')
            ->with('success', 'Филиал успешно создан');
    }

    /**
     * @param Branch $branch
     * @return Response
     */
    public function show(Branch $branch): Response
    {
        return Inertia::render('Branches/Show', [
            'branch' => $branch,
        ]);
    }

    /**
     * @param Branch $branch
     * @return Response
     */
    public function edit(Branch $branch): Response
    {
        return Inertia::render('Branches/Edit', [
            'branch' => $branch,
        ]);
    }

    /**
     * @param UpdateBranchRequest $request
     * @param Branch $branch
     * @return RedirectResponse
     */
    public function update(UpdateBranchRequest $request, Branch $branch): RedirectResponse
    {
        $branch->update($request->validated());

        return to_route('branches.index')
            ->with('success', 'Филиал успешно обновлен');
    }

    /**
     * @param Branch $branch
     * @return RedirectResponse
     */
    public function destroy(Branch $branch): RedirectResponse
    {
        $branch->delete();

        return to_route('branches.index')
            ->with('success', 'Филиал успешно удален');
    }
}
