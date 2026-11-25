<?php

namespace App\Http\Controllers;

use App\Models\Gestora;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class GestoraController extends Controller
{
    private function comprobarAdministrador(): void
    {
        abort_unless(
            Auth::user()?->role === 'admin',
            403,
            'No tienes permiso para gestionar empresas gestoras.'
        );
    }

    public function index()
    {
        $this->comprobarAdministrador();

        $gestoras = Gestora::with('usuario')->orderBy('nombre')->paginate(10);

        return view('admin.gestoras.index', compact('gestoras'));
    }

    public function create()
    {
        $this->comprobarAdministrador();

        return view('admin.gestoras.create');
    }

    public function store(Request $request)
    {
        $this->comprobarAdministrador();

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'cif' => ['nullable', 'string', 'max:30', 'unique:gestoras,cif'],
            'email' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'porcentaje_comision' => ['required', 'numeric', 'min:0', 'max:100'],
            'activa' => ['required', 'boolean'],
            'usuario_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'usuario_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated) {
            $usuario = User::create([
                'name' => $validated['nombre'],
                'email' => $validated['usuario_email'],
                'password' => $validated['usuario_password'],
                'role' => 'gestora',
                'telefono' => $validated['telefono'] ?? null,
            ]);

            Gestora::create([
                'user_id' => $usuario->id,
                'nombre' => $validated['nombre'],
                'cif' => $validated['cif'] ?? null,
                'email' => $validated['email'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'direccion' => $validated['direccion'] ?? null,
                'porcentaje_comision' => $validated['porcentaje_comision'],
                'activa' => $validated['activa'],
            ]);
        });

        return redirect()->route('admin.gestoras.index')
            ->with('success', 'Gestora y cuenta de acceso creadas correctamente.');
    }

    public function edit(Gestora $gestora)
    {
        $this->comprobarAdministrador();

        $gestora->load('usuario');

        return view('admin.gestoras.edit', compact('gestora'));
    }

    public function update(Request $request, Gestora $gestora)
    {
        $this->comprobarAdministrador();

        $gestora->load('usuario');
        $usuario = $gestora->usuario;

        $reglaEmailUsuario = Rule::unique('users', 'email');
        if ($usuario) {
            $reglaEmailUsuario->ignore($usuario->id);
        }

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'cif' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('gestoras', 'cif')->ignore($gestora->id),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'porcentaje_comision' => ['required', 'numeric', 'min:0', 'max:100'],
            'activa' => ['required', 'boolean'],
            'usuario_email' => ['required', 'email', 'max:255', $reglaEmailUsuario],
            'usuario_password' => [$usuario ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated, $gestora, $usuario) {
            if ($usuario) {
                $datosUsuario = [
                    'name' => $validated['nombre'],
                    'email' => $validated['usuario_email'],
                    'telefono' => $validated['telefono'] ?? null,
                ];

                if (!empty($validated['usuario_password'])) {
                    $datosUsuario['password'] = $validated['usuario_password'];
                }

                $usuario->update($datosUsuario);
            } else {
                $usuario = User::create([
                    'name' => $validated['nombre'],
                    'email' => $validated['usuario_email'],
                    'password' => $validated['usuario_password'],
                    'role' => 'gestora',
                    'telefono' => $validated['telefono'] ?? null,
                ]);

                $gestora->user_id = $usuario->id;
            }

            $gestora->update([
                'nombre' => $validated['nombre'],
                'cif' => $validated['cif'] ?? null,
                'email' => $validated['email'] ?? null,
                'telefono' => $validated['telefono'] ?? null,
                'direccion' => $validated['direccion'] ?? null,
                'porcentaje_comision' => $validated['porcentaje_comision'],
                'activa' => $validated['activa'],
                'user_id' => $gestora->user_id,
            ]);
        });

        return redirect()->route('admin.gestoras.index')
            ->with('success', 'Gestora actualizada correctamente.');
    }

    public function destroy(Gestora $gestora)
    {
        $this->comprobarAdministrador();

        abort_if(
            $gestora->comunidades()->exists(),
            422,
            'No se puede eliminar una gestora que todavía tiene comunidades.'
        );

        $gestora->delete();

        return redirect()->route('admin.gestoras.index')
            ->with('success', 'Empresa gestora eliminada correctamente.');
    }
}