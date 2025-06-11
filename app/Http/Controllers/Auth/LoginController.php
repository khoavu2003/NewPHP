<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm(){
        return view('auth.login');
    }
    public function checkLogin(){
        return response()->json([
            'isLoggedIn'=>Session::has('customer_id')
        ]);
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
    public function sendOtp()
    {
        $tel_num = request('tel_num');
        if (!$tel_num) {
            return response()->json(['status' => 'error', 'message' => 'Vui lòng nhập số điện thoại'], 400);
        }
        $customer = Customer::where('tel_num', $tel_num)->first();
        if (!$customer) {
            return response()->json(['status' => 'error', 'message' => 'Số điện thoại không tồn tại'], 404);
        }
        $otp_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $expires_at = now()->addMinutes(5);
        Otp::create([
            'tel_num' => $tel_num,
            'otp_code' => $otp_code,
            'expires_at' => $expires_at,
            'is_used' => false,
        ]);
        Mail::raw("Mã OTP của bạn là: $otp_code. Hết hạn sau 5 phút.", function ($message) use ($customer) {
            $message->to($customer->email)->subject('Mã OTP Đăng Nhập');
        });
        

        return response()->json(['status' => 'success', 'message' => 'OTP đã được gửi']);
    }
    public function verifyOtp()
    {
        $tel_num = request('tel_num');
        $otp_code = request('otp_code');

        $otp = Otp::where('tel_num', $tel_num)
            ->where('otp_code', $otp_code)
            ->where('is_used', false)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$otp) {
            return response()->json(['status' => 'error', 'message' => 'OTP không hợp lệ hoặc đã hết hạn'], 400);
        }

        $customer = Customer::where('tel_num', $tel_num)->first();
        if (!$customer) {
            return response()->json(['status' => 'error', 'message' => 'Khách hàng không tồn tại'], 404);
        }
        
        $otp->update(['is_used' => true]);

        Session::put([
            'customer_id' => $customer->customer_id,
            'customer_name' => $customer->customer_name,
            'customer_email' => $customer->email,
            'tel_num' => $customer->tel_num
        ]);

        return response()->json(['status' => 'success', 'message' => 'Đăng nhập thành công']);
    }
    public function showOtpLogin(){
        return view('auth.otp');
    }
}
