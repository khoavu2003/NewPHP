<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;

class AuthAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('admin')->check()) {
            Log::warning('Unauthorized admin access attempt', [
                'ip' => $request->ip(),
                'email' => $request->user('web')?->customer_name ?? 'Guest',
                'path' => $request->path(),
                'session' => $request->session()->all(),
                'admin_session' => null // Avoid toArray() for null user
            ]);
           if ($request->expectsJson()) {
                return response()->json(['message' => 'Vui lòng đăng nhập với tài khoản quản trị'], 401);
            }
            return response()->view('errors.access-denied', ['statusCode' => 401], 401);
        }

        $admin = Auth::guard('admin')->user();
        Log::info('Admin authenticated', [
            'admin_id' => $admin->id,
            'email' => $admin->email,
            'group_role' => $admin->group_role,
            'ip' => $request->ip(),
            'session' => $request->session()->all(),
            'admin_session' => $admin instanceof Admin ? $admin->toArray() : null // Safe toArray()
        ]);
        return $next($request);
    }
}