<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout()
    {
        Auth::logout();
        session()->flush(); 
        cookie()->queue(cookie()->forget('remember_user_id'));
        cookie()->queue(cookie()->forget('remember_token'));
        return redirect('/login');
    }
}
