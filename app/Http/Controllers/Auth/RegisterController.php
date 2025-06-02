<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Service\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    protected $customerService;
    public function showRegisterForm(){
        return view('auth.register');
    }
    public function __construct(CustomerService $customerService){
        $this->customerService=$customerService;
    }
    public function register(RegisterRequest $request){
         if ($this->customerService->isEmailExists($request->validated()['email'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email đã tồn tại. Vui lòng nhập email khác.'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
        $data = $request->validated();
        Log::info('Validated data:', $data);
        $createCustomer = [
            'customer_name' => $data['name'],
            'email' => $data['email'],
            'tel_num'=>$data['tel_num'],
            'password' => Hash::make($data['password']),
        ];
        $customer = $this->customerService->create($createCustomer);

        return response()->json([
            'status' => 'success',
            'message' => 'Người dùng đã được thêm thành công.',
            'user' => $customer
        ]);
    }
}
