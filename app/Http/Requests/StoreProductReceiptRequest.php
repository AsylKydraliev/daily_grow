<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductReceiptRequest extends FormRequest
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
            'receipt_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $products = $this->input('products', []);
            $branchId = $this->input('branch_id');
            $hasValidProduct = false;

            foreach ($products as $index => $product) {
                if (isset($product['quantity']) && (int)$product['quantity'] > 0) {
                    $hasValidProduct = true;
                    
                    // Проверяем, что товар принадлежит выбранному филиалу
                    if (isset($product['product_id']) && $branchId) {
                        $productModel = \App\Models\Product::find($product['product_id']);
                        if (!$productModel) {
                            $validator->errors()->add(
                                "products.{$index}.product_id",
                                "Товар не найден."
                            );
                        } elseif ($productModel->branch_id != $branchId) {
                            $validator->errors()->add(
                                "products.{$index}.product_id",
                                "Товар '{$productModel->name}' не принадлежит выбранному филиалу."
                            );
                        }
                    }
                }
            }

            if (!$hasValidProduct) {
                $validator->errors()->add('products', 'Необходимо увеличить количество хотя бы для одного товара.');
            }
        });
    }
}
