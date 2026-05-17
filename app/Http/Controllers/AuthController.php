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

    public function showForgotPassword()
    {
        return view('pages.forgot-password');
    }

    public function processForgotPassword(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'rw' => ['required', Rule::in(['1', '2', '3', '4', '5', '6'])],
        ]);

        // Find user by name, phone, and RW
        $user = User::where('name', $request->name)
                    ->where('phone', $request->phone)
                    ->where('rw', $request->rw)
                    ->first();

        if (!$user) {
            return back()->withErrors([
                'name' => 'Data yang Anda masukkan tidak cocok dengan data utilisateur.',
            ])->onlyInput('name', 'phone', 'rw');
        }

        // Store user ID in session for password reset
        session(['reset_password_user_id' => $user->id]);

        return redirect('/reset-password')->with('success', 'Identitas terverifikasi! Silakan masukkan password baru.');
    }

    public function showResetPassword()
    {
        if (!session()->has('reset_password_user_id')) {
            return redirect('/forgot-password')->with('error', 'Silakan verifikasi identitas terlebih dahulu.');
        }

        return view('pages.reset-password');
    }

    public function processResetPassword(Request $request)
    {
        if (!session()->has('reset_password_user_id')) {
            return redirect('/forgot-password')->with('error', 'Silakan verifikasi identitas terlebih dahulu.');
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $userId = session()->get('reset_password_user_id');
        $user = User::find($userId);

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        session()->forget('reset_password_user_id');

        return redirect('/login')->with('success', 'Password berhasil diubah! Silakan login dengan password baru.');
    }
}

