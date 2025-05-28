<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RestrictAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::guard('admin')->check()){
            Log::warning('Admin attempted to access customer page', [
                'admin_id' => Auth::guard('admin')->id(),
                'ip' => $request->ip(),
                'path' => $request->path()
            ]);
            return response()->json([
                'message' => 'Tài khoản quản trị không được phép truy cập trang này'
            ], 403);
        }
        return $next($request);
    }
}
