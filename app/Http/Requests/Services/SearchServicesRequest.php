<?php

namespace App\Http\Requests\Services;

use Illuminate\Foundation\Http\FormRequest;

class SearchServicesRequest extends FormRequest
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
            'service_name' => ['nullable', 'string', 'max:255', 'regex:/^[\p{L}0-9\s._@\-ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠ-ỹ]+$/u'],
            'duration_minute' => ['nullable', 'numeric'],
            'price' => ['nullable', 'numeric', 'min:0.01'],
        ];
    }
}
