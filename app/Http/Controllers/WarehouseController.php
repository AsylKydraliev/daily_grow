<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductReceipt;
use App\Models\Sale;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WarehouseController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $branchId = $request->input('branch_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Получаем товары с расчетами для склада
        $products = Product::query()
            ->with('branch')
            ->when($branchId, function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->get()
            ->map(function ($product) use ($dateFrom, $dateTo) {
                // Общее количество прихода (без фильтров по датам для расчета остатка)
                $totalReceiptQuantity = ProductReceipt::query()
                    ->where('product_id', $product->id)
                    ->where('branch_id', $product->branch_id)
                    ->sum('quantity') ?? 0;

                // Общее количество продажи (без фильтров по датам для расчета остатка)
                $totalSaleQuantity = Sale::query()
                    ->where('product_id', $product->id)
                    ->where('branch_id', $product->branch_id)
                    ->sum('quantity') ?? 0;

                // Оставшееся количество (общее, без фильтров по датам)
                $remainingQuantity = max(0, $totalReceiptQuantity - $totalSaleQuantity);

                // Запрос для приходов в выбранном периоде (для отображения)
                $receiptsQuery = ProductReceipt::query()
                    ->where('product_id', $product->id)
                    ->where('branch_id', $product->branch_id);

                if ($dateFrom) {
                    $receiptsQuery->where('receipt_date', '>=', $dateFrom);
                }
                if ($dateTo) {
                    $receiptsQuery->where('receipt_date', '<=', $dateTo);
                }

                // Количество прихода в выбранном периоде
                $receiptQuantity = $receiptsQuery->sum('quantity') ?? 0;

                // Запрос для продаж в выбранном периоде (для отображения)
                $salesQuery = Sale::query()
                    ->where('product_id', $product->id)
                    ->where('branch_id', $product->branch_id);

                if ($dateFrom) {
                    $salesQuery->where('sale_date', '>=', $dateFrom);
                }
                if ($dateTo) {
                    $salesQuery->where('sale_date', '<=', $dateTo);
                }

                // Количество продажи в выбранном периоде
                $saleQuantity = $salesQuery->sum('quantity') ?? 0;

                // Цена прихода (цена товара)
                $receiptPrice = $product->price ?? 0;

                // Цена продажи (средняя взвешенная)
                $salePrice = 0;
                $salesData = $salesQuery->get();
                if ($salesData->isNotEmpty()) {
                    $totalAmount = $salesData->sum(function ($sale) {
                        return $sale->price * $sale->quantity;
                    });
                    $totalQuantity = $salesData->sum('quantity');
                    if ($totalQuantity > 0) {
                        $salePrice = $totalAmount / $totalQuantity;
                    }
                }

                // Прибыль (цена продажи - цена прихода)
                $profit = $salePrice > 0 ? $salePrice - $receiptPrice : 0;

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'branch_id' => $product->branch_id,
                    'branch_name' => $product->branch->name ?? 'N/A',
                    'receipt_quantity' => $receiptQuantity,
                    'sale_quantity' => $saleQuantity,
                    'remaining_quantity' => $remainingQuantity,
                    'receipt_price' => round($receiptPrice, 2),
                    'sale_price' => round($salePrice, 2),
                    'profit' => round($profit, 2),
                ];
            })
            ->filter(function ($item) {
                /** Показываем только товары с фактическим количеством (оставшимся) больше 0 */
                return $item['remaining_quantity'] > 0;
            })
            ->values();

        return Inertia::render('Warehouse/Index', [
            'products' => $products,
            'branches' => Branch::all(),
            'filters' => $request->only(['branch_id', 'date_from', 'date_to']),
        ]);
    }
}

