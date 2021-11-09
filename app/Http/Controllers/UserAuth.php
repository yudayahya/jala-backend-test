<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UserAuth extends Controller
{
    public function index()
    {
        $data['title'] = 'Sign In';
        return view('UserAuth.index', $data);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($request->remember == "on") {
            $remember = true;
        } else {
            $remember = false;
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/home');
        }

        return back()->with('LoginError', 'Opss.. pastikan email dan password anda sudah benar.');
    }

    public function register()
    {
        $data['title'] = "Sign Up";
        return view('UserAuth.register', $data);
    }

    public function register_proses(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email:dns',
            'password' => 'required|confirmed',
        ];

        $customMessages = [
            'name.required' => 'Nama tidak boleh kosong.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Pastikan format email anda benar.',
            'password.required' => 'Password tidak boleh kosong.',
            'password.confirmed' => 'Password yang anda ulangi tidak sama.',
        ];

        $this->validate($request, $rules, $customMessages);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole('User');

        return redirect('/login')->with('Register', 'Akun anda berhasil dibuat silahkan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
