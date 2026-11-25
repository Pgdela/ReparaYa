<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\HistorialEstado;
use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class IncidenciaController extends Controller
{
    public function actualizarEstado(Request $request, Incidencia $incidencia)
    {
        $usuario = auth()->user();

        abort_unless(
            $usuario && in_array($usuario->role, ['admin', 'tecnico']),
            403,
            'No tienes permiso para modificar esta incidencia.'
        );

        if ($usuario->role === 'tecnico') {
            $incidencia->load('tecnico');

            abort_unless(
                $incidencia->tecnico &&
                (int) $incidencia->tecnico->user_id === (int) $usuario->id,
                403,
                'Solo puedes modificar las incidencias que tienes asignadas.'
            );
        }

        $datos = $request->validate([
            'estado' => [
                'required',
                'in:Pendiente,Asignada,En curso,Finalizada,Cancelada'
            ],
            'observacion' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($incidencia, $datos, $usuario) {

            $incidencia = Incidencia::whereKey($incidencia->id)
                ->lockForUpdate()
                ->firstOrFail();

            $estadoAnterior = $incidencia->estado;
            $estadoNuevo = $datos['estado'];

            if ($estadoAnterior === $estadoNuevo) {
                return;
            }

            $transiciones = [
                'Pendiente' => ['Asignada', 'Cancelada'],
                'Asignada' => ['En curso', 'Cancelada'],
                'En curso' => ['Finalizada', 'Cancelada'],
                'Finalizada' => [],
                'Cancelada' => [],
            ];

            if (!in_array($estadoNuevo, $transiciones[$estadoAnterior] ?? [])) {
                throw ValidationException::withMessages([
                    'estado' => 'No está permitido realizar este cambio de estado.'
                ]);
            }

            $incidencia->estado = $estadoNuevo;
            $incidencia->save();

            HistorialEstado::create([
                'incidencia_id' => $incidencia->id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $estadoNuevo,
                'usuario_id' => $usuario->id,
                'fecha_cambio' => now(),
                'observacion' => $datos['observacion'] ?? null,
            ]);

            if ($estadoNuevo === 'Finalizada' && $incidencia->comunidad_id !== null) {

                $incidencia->load(['comunidad.gestora', 'servicio']);

                $gestora = $incidencia->comunidad?->gestora;

                if ($gestora && $gestora->activa) {

                    $precioBase = (float) $incidencia->precio_base;
                    $porcentaje = (float) $gestora->porcentaje_comision;
                    $importe = round($precioBase * $porcentaje / 100, 2);

                    Comision::firstOrCreate(
                        ['incidencia_id' => $incidencia->id],
                        [
                            'gestora_id' => $gestora->id,
                            'servicio_id' => $incidencia->servicio_id,
                            'precio_base' => $precioBase,
                            'porcentaje_comision' => $porcentaje,
                            'importe_comision' => $importe,
                            'fecha_generacion' => now(),
                        ]
                    );
                }
            }
        });

        return back()->with(
            'success',
            'Estado de la incidencia actualizado correctamente.'
        );
    }
}
