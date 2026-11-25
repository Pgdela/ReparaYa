<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Gestora;
use App\Models\Zona;
use Illuminate\Http\Request;

class ComunidadController extends Controller
{
    private function comprobarAdministrador(): void
    {
        abort_unless(
            auth()->check() && auth()->user()->role === 'admin',
            403,
            'No tienes permiso para gestionar comunidades.'
        );
    }

    public function index()
    {
        $this->comprobarAdministrador();

        $comunidades = Comunidad::with(['gestora', 'zona'])
            ->orderBy('nombre')
            ->paginate(10);

        return view('admin.comunidades.index', compact('comunidades'));
    }

    public function create()
    {
        $this->comprobarAdministrador();

        $gestoras = Gestora::where('activa', true)->orderBy('nombre')->get();
        $zonas = Zona::orderBy('nombre')->get();

        return view('admin.comunidades.create', compact('gestoras', 'zonas'));
    }

    public function store(Request $request)
    {
        $this->comprobarAdministrador();

        $datos = $request->validate([
            'gestora_id' => ['required', 'exists:gestoras,id'],
            'zona_id' => ['required', 'exists:zonas,id'],
            'nombre' => ['required', 'string', 'max:150'],
            'direccion' => ['required', 'string', 'max:255'],
        ]);

        $gestora = Gestora::where('activa', true)
            ->findOrFail($datos['gestora_id']);

        Comunidad::create($datos);

        return redirect()->route('admin.comunidades.index')
            ->with('success', 'Comunidad creada correctamente.');
    }

    public function edit(Comunidad $comunidad)
    {
        $this->comprobarAdministrador();

        $gestoras = Gestora::where('activa', true)->orderBy('nombre')->get();
        $zonas = Zona::orderBy('nombre')->get();

        return view('admin.comunidades.edit', compact(
            'comunidad',
            'gestoras',
            'zonas'
        ));
    }

    public function update(Request $request, Comunidad $comunidad)
    {
        $this->comprobarAdministrador();

        $datos = $request->validate([
            'gestora_id' => ['required', 'exists:gestoras,id'],
            'zona_id' => ['required', 'exists:zonas,id'],
            'nombre' => ['required', 'string', 'max:150'],
            'direccion' => ['required', 'string', 'max:255'],
        ]);

        Gestora::where('activa', true)->findOrFail($datos['gestora_id']);

        $comunidad->update($datos);

        return redirect()->route('admin.comunidades.index')
            ->with('success', 'Comunidad actualizada correctamente.');
    }

    public function destroy(Comunidad $comunidad)
    {
        $this->comprobarAdministrador();

        if ($comunidad->incidencias()->exists()) {
            return back()->with(
                'error',
                'No puedes eliminar una comunidad que tiene incidencias asociadas.'
            );
        }

        $comunidad->delete();

        return redirect()->route('admin.comunidades.index')
            ->with('success', 'Comunidad eliminada correctamente.');
    }
}