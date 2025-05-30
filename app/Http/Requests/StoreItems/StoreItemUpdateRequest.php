<?php

namespace App\Http\Requests\StoreItems;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemUpdateRequest extends FormRequest
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
            'item_category_id' => 'required|exists:store_item_categories,id',
            'name' => 'required|string',
            'image' => 'nullable|image',
            'description' => 'nullable|string',
            'unit_measurement' => 'required|string',
            'cost_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'low_stock_alert' => 'nullable|numeric|min:0',
            'for_sale' => 'boolean',
        ];
    }
}
