<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm(){
        return view('auth.login');
    }
      public function login(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'max:255'
            ],
        ]);
      Log::info('Dữ liệu login:', $request->all());
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        Log::info('Dữ liệu validate hợp lệ');

        $customer = Customer::where('email', $request->email)->first();
        if (!$customer) {
            Log::info('sai tài khoản');
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.']);
        }

        if (!Hash::check($request->password, $customer->password)) {
            Log::info('sai mật khẩu');
            return back()->withErrors(['password' => 'Email hoặc mật khẩu không đúng.']);
        }

        
        
        Auth::login($customer);
        session([
            'customer_id' => $customer->customer_id,
            'customer_name' => $customer->customer_name,
            'customer_email' => $customer->email,
            'tel_num'=>$customer->tel_num 
        ]);
        Log::info(' Đăng nhập thành công', ['Customer_id' => $customer->customer_id]);

        return redirect()->intended('/booking');
    }
}
