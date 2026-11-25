<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private function redirigirSegunRol(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.usuarios.index');
        }

        if ($user->role === 'gestora') {
            return redirect()->route('gestora.panel');
        }

        return redirect()->route('reparaya');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $guard = Auth::guard('web');

        if (!$guard->attempt($credentials)) {
            return back()
                ->withErrors([
                    'email' => 'Correo electrónico o contraseña incorrectos.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = $guard->user();

        $request->session()->save();

        return $this->redirigirSegunRol($user);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'particular',
        ]);

        Auth::guard('web')->login($user);

        $request->session()->regenerate();
        $request->session()->save();

        return $this->redirigirSegunRol($user);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}