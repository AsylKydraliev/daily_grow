<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
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

    /**
     * Товары филиала с остатком > 0 (для модалки «Баланс ТМЗ»).
     *
     * @param Branch $branch
     * @return JsonResponse
     */
    public function branchProducts(Branch $branch): JsonResponse
    {
        $products = Product::query()
            ->where('branch_id', $branch->id)
            ->where('current_quantity', '>', 0)
            ->orderBy('name')
            ->get()
            ->map(function (Product $product) {
                $quantity = $product->current_quantity ?? 0;
                $purchaseUsd = $product->purchase_price_usd !== null ? (float) $product->purchase_price_usd : null;
                $purchaseKzt = $product->purchase_price_kzt !== null ? (float) $product->purchase_price_kzt : null;
                // Итоговая сумма по товару: цена закупа × остаток
                $totalUsd = $purchaseUsd !== null ? round($purchaseUsd * $quantity, 2) : null;
                $totalKzt = $purchaseKzt !== null ? round($purchaseKzt * $quantity, 2) : null;

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $quantity,
                    'purchase_price_usd' => $purchaseUsd !== null ? round($purchaseUsd, 2) : null,
                    'purchase_price_kzt' => $purchaseKzt !== null ? round($purchaseKzt, 2) : null,
                    'wholesale_price_usd' => $product->wholesale_price_usd !== null ? round((float) $product->wholesale_price_usd, 2) : null,
                    'wholesale_price_kzt' => $product->wholesale_price_kzt !== null ? round((float) $product->wholesale_price_kzt, 2) : null,
                    'total_usd' => $totalUsd,
                    'total_kzt' => $totalKzt,
                ];
            })
            ->values()
            ->all();

        return response()->json([
            'branch' => [
                'id' => $branch->id,
                'name' => $branch->name,
            ],
            'products' => $products,
        ]);
    }
}

