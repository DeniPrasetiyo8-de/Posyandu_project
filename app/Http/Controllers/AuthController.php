<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('pages.login');
    }

    public function login(Request $request)
    {
        $role = $request->role ?? 'orang_tua';

        if ($role === 'admin') {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $credentials['email'])->first();

            if ($user && $user->role === 'admin' && Hash::check($credentials['password'], $user->password)) {
                Auth::login($user);
                return redirect('/admin/dashboard')->with('success', 'Selamat datang Admin!');
            }

            return back()->withErrors([
                'email' => 'Email atau password admin salah.',
            ])->onlyInput('email', 'password');
        } else {
            // Orang Tua login (old logic)
            $credentials = $request->validate([
                'name' => 'required|string|max:255',
                'rw' => ['required', Rule::in(['1', '2', '3', '4', '5', '6'])],
                'password' => 'required',
            ]);

            $user = User::where('name', $credentials['name'])
                        ->where('rw', $credentials['rw'])
                        ->first();

            if ($user && Hash::check($credentials['password'], $user->password)) {
                Auth::login($user);
                return redirect('/dashboard')->with('success', 'Login berhasil!');
            }

            return back()->withErrors([
                'email' => 'Nama, RW, atau password salah.',
            ])->onlyInput('name', 'rw', 'password');
        }
    }

    public function showRegister()
    {
        return view('pages.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'rt' => 'required|string|max:10',
            'rw' => ['required', Rule::in(['1', '2', '3', '4', '5', '6'])],
            'password' => 'required|min:8|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'orang_tua';
        $data['email'] = 'user_' . time() . '@posyandu.local'; // Dummy email untuk migration constraint

        User::create($data);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }



    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logout berhasil!');
    }
}

