<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function comprobarAdministrador(): void
    {
        abort_unless(
            Auth::guard()->check() && Auth::user()->role === 'admin',
            403,
            'No tienes permiso para gestionar usuarios.'
        );
    }

    public function index()
    {
        $this->comprobarAdministrador();

        $users = User::orderBy('name')->paginate(10);

        return view('admin.usuarios.index', compact('users'));
    }

    public function create()
    {
        $this->comprobarAdministrador();

        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $this->comprobarAdministrador();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'tecnico', 'particular'])],
        ]);

        User::create($validated);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        $this->comprobarAdministrador();

        return view('admin.usuarios.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->comprobarAdministrador();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => ['required', Rule::in(['admin', 'tecnico', 'particular'])],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $this->comprobarAdministrador();

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
