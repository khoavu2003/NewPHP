<?php

namespace App\Http\Requests\Otp;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tel_num' => ['required', 'string', 'regex:/^[0-9]{10,11}$/'],
            'otp_code' => ['required', 'string', 'size:6']
        ];
    }
}
