<?php

namespace App\Http\Controllers;

use App\Models\Comunidad;
use App\Models\Gestora;
use App\Models\Zona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GestoraComunidadController extends Controller
{
    private function obtenerGestora(): Gestora
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'gestora',
            403,
            'No tienes permiso para gestionar comunidades.'
        );

        return Gestora::where('user_id', Auth::id())
            ->where('activa', true)
            ->firstOrFail();
    }

    public function index()
    {
        $gestora = $this->obtenerGestora();

        $comunidades = Comunidad::with('zona')
            ->withCount('incidencias')
            ->where('gestora_id', $gestora->id)
            ->orderBy('nombre')
            ->paginate(10);

        return view('gestora.comunidades.index', compact('gestora', 'comunidades'));
    }

    public function create()
    {
        $this->obtenerGestora();

        $zonas = Zona::orderBy('nombre')->get();

        return view('gestora.comunidades.create', compact('zonas'));
    }

    public function store(Request $request)
    {
        $gestora = $this->obtenerGestora();

        $datos = $request->validate([
            'zona_id' => ['required', 'exists:zonas,id'],
            'nombre' => ['required', 'string', 'max:150'],
            'direccion' => ['required', 'string', 'max:255'],
        ]);

        $datos['gestora_id'] = $gestora->id;

        Comunidad::create($datos);

        return redirect()->route('gestora.comunidades.index')
            ->with('success', 'Comunidad creada correctamente.');
    }

    public function edit(int $id)
    {
        $gestora = $this->obtenerGestora();

        $comunidad = Comunidad::where('gestora_id', $gestora->id)
            ->findOrFail($id);

        $zonas = Zona::orderBy('nombre')->get();

        return view('gestora.comunidades.edit', compact(
            'gestora',
            'comunidad',
            'zonas'
        ));
    }

    public function update(Request $request, int $id)
    {
        $gestora = $this->obtenerGestora();

        $comunidad = Comunidad::where('gestora_id', $gestora->id)
            ->findOrFail($id);

        $datos = $request->validate([
            'zona_id' => ['required', 'exists:zonas,id'],
            'nombre' => ['required', 'string', 'max:150'],
            'direccion' => ['required', 'string', 'max:255'],
        ]);

        $comunidad->update($datos);

        return redirect()->route('gestora.comunidades.index')
            ->with('success', 'Comunidad actualizada correctamente.');
    }

    public function destroy(int $id)
    {
        $gestora = $this->obtenerGestora();

        $comunidad = Comunidad::where('gestora_id', $gestora->id)
            ->findOrFail($id);

        if ($comunidad->incidencias()->exists()) {
            return back()->with(
                'error',
                'No puedes eliminar una comunidad que tiene incidencias asociadas.'
            );
        }

        $comunidad->delete();

        return redirect()->route('gestora.comunidades.index')
            ->with('success', 'Comunidad eliminada correctamente.');
    }
}