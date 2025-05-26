<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class CustomerStoreRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'email' => ['required_if:phone,null', Rule::unique('customers')->whereNot('email', null)->where('restaurant_id', restaurantId())],
            'phone' => ['required_if:email,null', Rule::unique('customers')->whereNot('phone', null)->where('restaurant_id', restaurantId())],
            'address' => 'nullable|string|max:255',
        ];
    }
}
