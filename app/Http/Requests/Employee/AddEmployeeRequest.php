<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class AddEmployeeRequest extends FormRequest
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
            'employee_name' => ['required', 'regex:/^[a-zA-Z0-9\s@._\-ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠ-ỹ]*$/u'],
            'email' => ['required', 'regex:/^[a-zA-Z0-9\s@._\-]*$/u'],
            'is_active' => ['required', 'boolean'],
            'tel_num' => ['required', 'regex:/^[0-9+\s\-()]{9,11}$/'],
        ];
    }
}
