<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'badge' => 'required|numeric|digits_between:1,8',
            'password' => 'required|max:50',
        ]);
        if(Auth::attempt($request ->only('badge', 'password'), $request->remember)){
            if(Auth::user()->role == 'karyawan') return redirect('/landing-page');
            return redirect('/dashboard');
        }
        return back()->with('failed', 'Wrong Badge or Password');
    }

    function register(Request $request) {
        $request->validate([
            'badge' => 'required|string|size:8',
            'name' => 'required|min:4|max:50',
            'email' => 'required|email|max:50',
            'contact' =>'required|numeric|digits_between:1,13',
            'password' => 'required|max:50|min:8',
            'confirm_password' => 'required|max:50|min:8|same:password',
        ]);
        $request['status'] = "pending";
        $user = User::create($request->all());
        Auth::login($user);
        return redirect('/landing-page');
    }

    public function logout() {
        Auth::logout();
        return redirect('/');
    }
}
