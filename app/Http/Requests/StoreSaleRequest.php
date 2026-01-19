<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'branch_id' => 'required|exists:branches,id',
            'counterparty_id' => 'required|exists:counterparties,id',
            'sale_date' => 'required|date',
            'sales' => 'required|array|min:1',
            'sales.*.product_id' => 'required|exists:products,id',
            'sales.*.quantity' => 'required|integer|min:1',
            'sales.*.price' => 'required|numeric|min:0',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $sales = $this->input('sales', []);
            $branchId = $this->input('branch_id');
            $hasValidSale = false;

            foreach ($sales as $index => $sale) {
                if (isset($sale['quantity']) && (int)$sale['quantity'] > 0) {
                    $hasValidSale = true;
                    
                    // Проверяем, что товар принадлежит выбранному филиалу
                    if (isset($sale['product_id']) && $branchId) {
                        $productModel = \App\Models\Product::find($sale['product_id']);
                        if (!$productModel) {
                            $validator->errors()->add(
                                "sales.{$index}.product_id",
                                "Товар не найден."
                            );
                        } elseif ($productModel->branch_id != $branchId) {
                            $validator->errors()->add(
                                "sales.{$index}.product_id",
                                "Товар '{$productModel->name}' не принадлежит выбранному филиалу."
                            );
                        }
                    }
                }
            }

            if (!$hasValidSale) {
                $validator->errors()->add('sales', 'Необходимо указать количество хотя бы для одного товара.');
            }
        });
    }
}
