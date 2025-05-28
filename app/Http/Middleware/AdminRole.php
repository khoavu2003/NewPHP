<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->Check()) {
            Log::warning('Unauthorized admin access attempt', [
                'ip' => $request->ip(),
                'email' => $request->user('web')?->customer_name ?? 'Guest'
            ]);
             if ($request->expectsJson()) {
                return response()->json(['message' => 'Vui lòng đăng nhập với tài khoản quản trị'], 403);
            }
            return response()->view('errors.access-denied', ['statusCode' => 403], 403);
        }
        $admin = Auth::guard('admin')->user();
        if ($admin->group_role !== 'admin') {
            Log::warning('Admin role access denied', [
                'admin_id' => $admin->id,
                'group_role' => $admin->group_role,
                'ip' => $request->ip()
            ]);
            return response()->json([
                'message' => 'Bạn không có quyền quản trị'
            ], 403);
        }
        Log::info('Admin access granted', ['admin_id' => $admin->id, 'ip' => $request->ip()]);
        return $next($request);
    }
}
