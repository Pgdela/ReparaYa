<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\Gestora;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComisionController extends Controller
{
    private function comprobarAdministrador(): void
    {
        abort_unless(
            auth()->check() && auth()->user()->role === 'admin',
            403,
            'No tienes permiso para gestionar comisiones.'
        );
    }

    public function index()
    {
        $this->comprobarAdministrador();

        $comisiones = Comision::with(['gestora', 'servicio'])
            ->latest()
            ->paginate(10);

        return view('admin.comisiones.index', compact('comisiones'));
    }

    public function create()
    {
        $this->comprobarAdministrador();

        $gestoras = Gestora::where('activa', true)->get();
        $servicios = Servicio::where('activo', true)->get();

        return view('admin.comisiones.create', compact(
            'gestoras',
            'servicios'
        ));
    }

    public function store(Request $request)
    {
        $this->comprobarAdministrador();

        $datos = $request->validate([
            'gestora_id' => ['required', 'exists:gestoras,id'],
            'servicio_id' => ['required', 'exists:servicios,id'],
        ]);

        $gestora = Gestora::where('activa', true)
            ->findOrFail($datos['gestora_id']);

        $servicio = Servicio::where('activo', true)
            ->findOrFail($datos['servicio_id']);

        $precioBase = (float) $servicio->precio_base;
        $porcentaje = (float) $gestora->porcentaje_comision;
        $importe = round($precioBase * $porcentaje / 100, 2);

        DB::transaction(function () use (
            $gestora,
            $servicio,
            $precioBase,
            $porcentaje,
            $importe
        ) {
            Comision::create([
                'gestora_id' => $gestora->id,
                'servicio_id' => $servicio->id,
                'precio_base' => $precioBase,
                'porcentaje_comision' => $porcentaje,
                'importe_comision' => $importe,
            ]);
        });

        return redirect()
            ->route('admin.comisiones.index')
            ->with('success', 'Comisión calculada y guardada correctamente.');
    }
}
