<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\Zona;
use Illuminate\Http\JsonResponse;

class ServicioZonaController extends Controller
{
    public function index(): JsonResponse
    {
        $servicios = Servicio::where('activo', true)
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'precio_base']);

        $zonas = Zona::withCount('comunidades')
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        return response()->json([
            'success' => true,
            'data' => [
                'servicios' => $servicios,
                'zonas' => $zonas,
            ],
            'total_servicios' => $servicios->count(),
            'total_zonas' => $zonas->count(),
        ], 200);
    }
}
