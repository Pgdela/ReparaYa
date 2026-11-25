<?php

namespace App\Http\Controllers;

use App\Models\Zona;
use Illuminate\Http\Request;

class ZonaController extends Controller
{
    private function comprobarAdministrador(): void
    {
        abort_unless(
            auth()->check() && auth()->user()->role === 'admin',
            403,
            'No tienes permiso para gestionar zonas.'
        );
    }

    public function index()
    {
        $this->comprobarAdministrador();

        $zonas = Zona::withCount('comunidades')
            ->orderBy('nombre')
            ->paginate(10);

        return view('admin.zonas.index', compact('zonas'));
    }

    public function create()
    {
        $this->comprobarAdministrador();

        return view('admin.zonas.create');
    }

    public function store(Request $request)
    {
        $this->comprobarAdministrador();

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:150', 'unique:zonas,nombre'],
        ]);

        Zona::create($datos);

        return redirect()->route('admin.zonas.index')
            ->with('success', 'Zona creada correctamente.');
    }

    public function edit(Zona $zona)
    {
        $this->comprobarAdministrador();

        return view('admin.zonas.edit', compact('zona'));
    }

    public function update(Request $request, Zona $zona)
    {
        $this->comprobarAdministrador();

        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:150',
                'unique:zonas,nombre,' . $zona->id,
            ],
        ]);

        $zona->update($datos);

        return redirect()->route('admin.zonas.index')
            ->with('success', 'Zona actualizada correctamente.');
    }

    public function destroy(Zona $zona)
    {
        $this->comprobarAdministrador();

        if ($zona->comunidades()->exists()) {
            return back()->with(
                'error',
                'No puedes eliminar una zona que tiene comunidades asignadas.'
            );
        }

        $zona->delete();

        return redirect()->route('admin.zonas.index')
            ->with('success', 'Zona eliminada correctamente.');
    }
}