<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->intended('');
        } else {
            return view('login');
        }
    }

    public function checklogin(Request $request)
    {
        $user = User::where('Username', $request->username)->first();
        if ($user && ($request->password == $user->password)) {
            Auth::login($user);
            $name = $user->member->name;
            $username = $user->username;
            $role = $user->role;
            $firstCharacter = $user->member->getFirstCharacter;
            $request->session()->put('name', $name);
            $request->session()->put('username', $username);
            $request->session()->put('role', $role);
            $request->session()->put('firstCharacter', $firstCharacter);
            return redirect()->intended('')
                ->with('type', 'success')
                ->with('message', 'Đăng nhập thành công');
        } else {
            return redirect()->back()->withInput()->with('type', 'warning')->with('message', 'Tên đăng nhập hoặc mật khẩu không chính xác');
        }
    }
    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login')->with('type', 'success')->with('message', 'Đăng xuất thành công');
    }
}
