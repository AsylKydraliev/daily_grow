<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BalanceController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        // Получаем все филиалы с расчетами ТМЗ
        $branches = Branch::query()
            ->get()
            ->map(function ($branch) {
                // Получаем все товары филиала
                $products = Product::query()
                    ->where('branch_id', $branch->id)
                    ->get();

                // Считаем сумму итогового остатка всех товаров (current_quantity * purchase_price_usd)
                $totalAmount = $products->sum(function ($product) {
                    $quantity = $product->current_quantity ?? 0;
                    $price = $product->purchase_price_usd ?? 0;
                    return $quantity * $price;
                });

                // Считаем общее количество товаров (сумма current_quantity)
                $totalQuantity = $products->sum('current_quantity') ?? 0;

                return [
                    'id' => $branch->id,
                    'name' => $branch->name,
                    'address' => $branch->address,
                    'total_amount' => round($totalAmount, 2),
                    'total_quantity' => $totalQuantity,
                ];
            });

        return Inertia::render('Balance/Index', [
            'branches' => $branches,
        ]);
    }
}

