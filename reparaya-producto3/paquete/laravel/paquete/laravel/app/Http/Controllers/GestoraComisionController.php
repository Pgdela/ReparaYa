<?php

namespace App\Http\Controllers;

use App\Models\Comision;
use App\Models\Gestora;
use Illuminate\Support\Facades\Auth;

class GestoraComisionController extends Controller
{
    private function obtenerGestora(): Gestora
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'gestora',
            403,
            'No tienes permiso para consultar estas comisiones.'
        );

        return Gestora::where('user_id', Auth::id())
            ->where('activa', true)
            ->firstOrFail();
    }

    public function index()
    {
        $gestora = $this->obtenerGestora();

        $comisiones = Comision::with([
            'servicio',
            'incidencia.cliente',
            'incidencia.comunidad',
        ])
            ->where('gestora_id', $gestora->id)
            ->orderByDesc('fecha_generacion')
            ->orderByDesc('created_at')
            ->paginate(15);

        $totalAcumulado = Comision::where('gestora_id', $gestora->id)
            ->sum('importe_comision');

        $totalRegistros = Comision::where('gestora_id', $gestora->id)
            ->count();

        return view('gestora.comisiones.index', compact(
            'gestora',
            'comisiones',
            'totalAcumulado',
            'totalRegistros'
        ));
    }

    public function liquidaciones()
    {
        $gestora = $this->obtenerGestora();

        $comisiones = Comision::with(['servicio', 'incidencia'])
            ->where('gestora_id', $gestora->id)
            ->whereNotNull('fecha_generacion')
            ->orderByDesc('fecha_generacion')
            ->get();

        $liquidaciones = $comisiones
            ->groupBy(function ($comision) {
                return $comision->fecha_generacion->format('Y-m');
            })
            ->map(function ($grupo, $mes) {
                return [
                    'mes' => $mes,
                    'numero' => $grupo->count(),
                    'total' => $grupo->sum(
                        fn ($comision) => (float) $comision->importe_comision
                    ),
                    'comisiones' => $grupo,
                ];
            })
            ->sortKeysDesc()
            ->values();

        $totalLiquidado = $comisiones->sum(
            fn ($comision) => (float) $comision->importe_comision
        );

        return view('gestora.liquidaciones.index', compact(
            'gestora',
            'liquidaciones',
            'totalLiquidado'
        ));
    }
}
