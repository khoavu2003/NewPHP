<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminLoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        
        $userId = $request->cookie('remember_user_id');
        $token = $request->cookie('remember_token');

        if ($userId && $token) {
            $user = Admin::where('id', $userId)
                ->where('remember_token', $token)
                ->where('is_delete', 0)
                ->first();

            if ($user) {
                Auth::guard('admin')->login($user);
                session([
                    'admin_id' => $user->id,
                    'admin_name' => $user->name,
                    'admin_email' => $user->email,
                    'admin_group' => $user->group_role,
                ]);
                return redirect()->intended('/admin/servicesManager');
            } else {
                Log::warning('Tự động đăng nhập thất bại: Token không hợp lệ', [
                    'user_id' => $userId,
                    'ip' => $request->ip()
                ]);
            }
        }

        return view('Admin.index');
    }

    public function login(Request $request)
    {
        Log::info('Dữ liệu login:', $request->except('password'));

        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required'],
        ]);
        Log::info('Dữ liệu validate hợp lệ', ['email' => $request->email]);

        $user = Admin::where('email', $request->email)->first();

        if (!$user) {
            Log::warning('Đăng nhập thất bại: Email không tồn tại', ['email' => $request->email]);
            return back()->withErrors(['email' => 'Email không tồn tại.']);
        }

        if (!Hash::check($request->password, $user->password)) {
            Log::warning('Đăng nhập thất bại: Mật khẩu không đúng', ['email' => $request->email]);
            return back()->withErrors(['password' => 'Mật khẩu không đúng.']);
        }

        if ($user->is_delete != 0) {
            Log::warning('Đăng nhập thất bại: Tài khoản không khả dụng', ['email' => $request->email]);
            return back()->withErrors(['email' => 'Tài khoản không khả dụng.']);
        }

        // Clear any existing web guard session
        Auth::guard('web')->logout();
        $request->session()->forget(['user_id', 'user_name', 'user_email', 'user_group']);

        // Log in with admin guard
        Auth::guard('admin')->login($user);

        // Handle remember me
        if ($request->has('rememberMe')) {
            $token = bin2hex(random_bytes(32));
            $user->update(['remember_token' => $token]);
            cookie()->queue('remember_user_id', $user->id, 60 * 24 * 7);
            cookie()->queue('remember_token', $token, 60 * 24 * 7);
        }

        // Store admin session data
        session([
            'admin_id' => $user->id,
            'admin_name' => $user->name,
            'admin_email' => $user->email,
            'admin_group' => $user->group_role,
        ]);

    

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        return redirect()->intended('/admin/servicesManager');
    }

    public function logout(Request $request)
    {
        Log::info('Admin đăng xuất', [
            'admin_id' => Auth::guard('admin')->id(),
            'ip' => $request->ip()
        ]);

        Auth::guard('admin')->logout();
        $request->session()->forget(['admin_id', 'admin_name', 'admin_email', 'admin_group']);

        cookie()->queue(cookie()->forget('remember_user_id'));
        cookie()->queue(cookie()->forget('remember_token'));

        return redirect('/');
    }
}