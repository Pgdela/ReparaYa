<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitudController extends Controller
{
    public function index()
    {
        $solicitudes = Solicitud::with('servicio')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('reparaya.solicitudes.index', compact('solicitudes'));
    }

    public function create()
    {
        $servicios = Servicio::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('reparaya.solicitudes.create', compact('servicios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'servicio_id' => ['required', 'integer', 'exists:servicios,id'],
            'descripcion' => ['required', 'string', 'min:10'],
            'direccion' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:30'],
            'fecha_preferida' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $servicio = Servicio::where('activo', true)
            ->findOrFail($validated['servicio_id']);

        Solicitud::create([
            'user_id' => Auth::id(),
            'servicio_id' => $servicio->id,
            'descripcion' => $validated['descripcion'],
            'direccion' => $validated['direccion'],
            'telefono' => $validated['telefono'],
            'fecha_preferida' => $validated['fecha_preferida'] ?? null,
            'precio_base' => $servicio->precio_base,
            'estado' => 'Pendiente',
        ]);

        return redirect()
            ->route('reparaya.solicitudes.index')
            ->with('success', 'Tu solicitud se ha registrado correctamente.');
    }
}
