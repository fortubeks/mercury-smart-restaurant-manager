<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'restaurant_id' => 'required|exists:restaurants,id',
            'role_id' => 'required',
            'phone' => 'nullable|numeric|digits:11',
            'photo' => 'nullable|max:2048',
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
            'is_active' => 'nullable|in:1,0',
        ];
    }
}
