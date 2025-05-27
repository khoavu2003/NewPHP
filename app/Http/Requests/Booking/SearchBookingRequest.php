<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class SearchBookingRequest extends FormRequest
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
            'customer_name' => ['nullable', 'string', 'max:255', 'regex:/^[\p{L}0-9\s._@\-ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠ-ỹ]+$/u'],
            'customer_email' => ['nullable', 'regex:/^[a-zA-Z0-9\s@._\-]*$/u'],
            'status' => ['nullable', 'string'],
            'booking_date' => ['nullable', 'string', 'max:255']
        ];
    }
}
