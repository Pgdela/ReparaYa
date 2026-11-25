<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PerfilController extends Controller
{
    public function edit()
    {
        return view('reparaya.perfil', [
            'usuario' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $usuario = Auth::user();

        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($usuario->id),
            ],
        ]);

        $usuario->update($datos);

        return redirect()
            ->route('reparaya.perfil.edit')
            ->with('success', 'Tus datos se han actualizado correctamente.');
    }
}
