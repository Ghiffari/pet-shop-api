<?php

namespace App\Http\Requests\Payment;

use Illuminate\Validation\Rule;
use App\Constants\OrderConstant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(OrderConstant::LIST_OF_PAYMENTS)],
            'details' => 'array',
        ];
    }
}
