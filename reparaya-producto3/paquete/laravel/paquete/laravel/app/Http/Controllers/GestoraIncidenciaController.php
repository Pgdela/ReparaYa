<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\Comunidad;
use App\Models\Gestora;
use App\Models\HistorialEstado;
use App\Models\Incidencia;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GestoraIncidenciaController extends Controller
{
    private function obtenerGestora(): Gestora
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'gestora',
            403,
            'No tienes permiso para gestionar incidencias.'
        );

        return Gestora::where('user_id', Auth::id())
            ->where('activa', true)
            ->firstOrFail();
    }

    public function index()
    {
        $gestora = $this->obtenerGestora();

        $incidencias = Incidencia::with([
            'cliente',
            'servicio',
            'comunidad',
        ])
            ->whereHas('comunidad', function ($query) use ($gestora) {
                $query->where('gestora_id', $gestora->id);
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('gestora.incidencias.index', compact(
            'gestora',
            'incidencias'
        ));
    }

    public function create()
    {
        $gestora = $this->obtenerGestora();

        $comunidades = Comunidad::where('gestora_id', $gestora->id)
            ->orderBy('nombre')
            ->get();

        $servicios = Servicio::where('activo', true)
            ->orderBy('nombre')
            ->get();

        $clientes = User::where('role', 'particular')
            ->orderBy('name')
            ->get();

        return view('gestora.incidencias.create', compact(
            'gestora',
            'comunidades',
            'servicios',
            'clientes'
        ));
    }

    public function store(Request $request)
    {
        $gestora = $this->obtenerGestora();

        $datos = $request->validate([
            'comunidad_id' => ['required', 'exists:comunidades,id'],
            'cliente_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'particular'),
            ],
            'servicio_id' => ['required', 'exists:servicios,id'],
            'descripcion' => ['required', 'string', 'max:2000'],
            'direccion' => ['required', 'string', 'max:255'],
            'telefono_contacto' => ['required', 'string', 'max:30'],
            'fecha_servicio' => ['required', 'date', 'after_or_equal:today'],
            'franja_horaria' => ['required', 'in:Mañana,Tarde,Noche'],
            'tipo_urgencia' => ['required', 'in:Estándar,Urgente'],
        ]);

        $comunidad = Comunidad::where('gestora_id', $gestora->id)
            ->findOrFail($datos['comunidad_id']);

        $servicio = Servicio::where('activo', true)
            ->findOrFail($datos['servicio_id']);

        User::where('role', 'particular')
            ->findOrFail($datos['cliente_id']);

        do {
            $localizador = 'RY-' . Str::upper(Str::random(10));
        } while (Incidencia::where('localizador', $localizador)->exists());

        $incidencia = Incidencia::create([
            'localizador' => $localizador,
            'cliente_id' => $datos['cliente_id'],
            'comunidad_id' => $comunidad->id,
            'servicio_id' => $servicio->id,
            'descripcion' => $datos['descripcion'],
            'direccion' => $datos['direccion'],
            'telefono_contacto' => $datos['telefono_contacto'],
            'fecha_servicio' => $datos['fecha_servicio'],
            'franja_horaria' => $datos['franja_horaria'],
            'tipo_urgencia' => $datos['tipo_urgencia'],
            'estado' => 'Pendiente',
            'precio_base' => $servicio->precio_base,
        ]);

        HistorialEstado::create([
            'incidencia_id' => $incidencia->id,
            'estado_anterior' => null,
            'estado_nuevo' => 'Pendiente',
            'usuario_id' => Auth::id(),
            'fecha_cambio' => now(),
            'observacion' => 'Incidencia registrada por la gestora.',
        ]);

        return redirect()
            ->route('gestora.incidencias.index')
            ->with('success', 'Incidencia creada correctamente. Localizador: ' . $localizador);
    }
}
