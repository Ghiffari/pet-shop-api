<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'address' => 'required|array',
            'address.shipping' => 'required|array',
            'address.shipping.first_name' => 'required|string|max:255',
            'address.shipping.last_name' => 'required|string|max:255',
            'address.shipping.line1' => 'required|string|max:255',
            'address.shipping.line2' => 'required|string|max:255',
            'address.shipping.city' => 'string|max:255',
            'address.shipping.state' => 'string|max:255',
            'address.shipping.zip_code' => 'required|string|max:255',
            'address.shipping.country' => 'required|string|max:255',
            'address.billing' => 'required|array',
            'address.billing.first_name' => 'required|string|max:255',
            'address.billing.last_name' => 'required|string|max:255',
            'address.billing.line1' => 'required|string|max:255',
            'address.billing.line2' => 'required|string|max:255',
            'address.billing.city' => 'string|max:255',
            'address.billing.state' => 'string|max:255',
            'address.billing.zip_code' => 'required|string|max:255',
            'address.billing.country' => 'required|string|max:255',
        ];
    }
}
