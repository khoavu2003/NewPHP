<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
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
            'booking_date' => 'required',
            'service_id' => 'required',
            'start_time' => 'required',
            'guest_name' => ['required', 'string', 'max:255','regex:/^[a-zA-Z0-9\s@._\-ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠ-ỹ]*$/u'],
            'guest_email' => ['required', 'email', 'max:255',],
            'guest_phone' => ['required', 'string', 'max:15','regex:/^[0-9+\s\-()]{9,11}$/'],
        ];
    }
    public function messages()
    {
        return[
            'booking_date.required'=>'Vui lòng chọn ngày',
            'service_id.required'=>'Vui lòng chọn dịch vụ',
            'start_time.required'=>'Vui lòng chọn giờ bắt đầu',
            'guest_name.required'=>'Vui lòng nhập tên',
            'guest_phone'=>'Vui lòng nhập số điện thoại',
            'guest_email'=>'Vui lòng nhập email',
            'guest_name.regex'=>'Tên không được chứa kí tự đặc biệt',
            'guest_phone.regex'=>'Số điện thoại không hợp lệ',
            'guest_email.email'=>'Email không hợp lệ'
        ];
    }
}
