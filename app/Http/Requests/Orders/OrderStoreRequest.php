<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'outlet_id' => 'required|exists:outlets,id',
            'created_by' => 'required|exists:users,id',
            'cutomer_id' => 'nullable|exists:customers,id',
            'order_date' => 'required|date',
            'amount' => 'required|numeric',
            'tax_rate' => 'required|numeric',
            'tax_amount' => 'required|numeric',
            'payment_method' => 'required',
            'total_amount' => 'required|numeric',
        ];
    }
    protected function prepareForValidation()
    {
        // Add values to the request data
        $this->merge([
            'outlet_id' => outlet()->id,
            'created_by' => auth()->id(),
            'order_date' => auth()->user()->current_shift ? auth()->user()->current_shift : now(),
            'status' => 'settled',
            'tax_rate' => 0,
            'tax_amount' => 0,
            'total_amount' => 0,
            'discount_rate' => 0,
            'discount_amount' => 0,
        ]);
    }
}
