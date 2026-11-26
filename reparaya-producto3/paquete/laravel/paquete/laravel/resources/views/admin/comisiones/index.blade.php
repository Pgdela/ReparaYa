<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comisiones - ReparaYa</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f3f6fa;margin:0;padding:30px;color:#263445}
        main{max-width:1150px;margin:auto;background:white;padding:28px;border-radius:12px}
        h1{color:#17365d}
        .resumen{display:flex;gap:20px;flex-wrap:wrap;margin:20px 0}
        .dato{background:#eaf2fb;padding:18px;border-radius:8px;min-width:190px}
        .dato strong{display:block;font-size:24px;color:#17365d;margin-top:8px}
        table{width:100%;border-collapse:collapse;margin-top:20px}
        th,td{text-align:left;padding:12px;border-bottom:1px solid #ddd}
        th{background:#17365d;color:white}
        a{color:#1763a6}
        .aviso{color:#687789;font-size:14px}
        .paginacion{margin-top:20px}
    </style>
</head>
<body>
<main>
    <h1>Comisiones generadas</h1>
    <p>Consulta de las comisiones registradas automáticamente al finalizar incidencias.</p>

    <div class="resumen">
        <div class="dato">
            Total de registros
            <strong>{{ $comisiones->total() }}</strong>
        </div>
        <div class="dato">
            Total generado por incidencias
<strong>{{ number_format((float)($importeTotal ?? $totalGenerado ?? $totalComisiones ?? 0), 2, ',', '.') }} €</strong>
        </div>
    </div>

    <p class="aviso">
        Los registros históricos sin incidencia vinculada se conservan, pero no forman parte de las liquidaciones mensuales.
    </p>

    <table>
        <thead>
        <tr>
            <th>Gestora</th>
            <th>Incidencia</th>
            <th>Servicio</th>
            <th>Precio base</th>
            <th>Porcentaje</th>
            <th>Comisión</th>
            <th>Fecha</th>
        </tr>
        </thead>
        <tbody>
        @forelse($comisiones as $comision)
            <tr>
                <td>{{ $comision->gestora->nombre ?? 'Sin gestora' }}</td>
                <td>
                    @if($comision->incidencia)
                        {{ $comision->incidencia->localizador }}
                    @else
                        Registro histórico
                    @endif
                </td>
                <td>{{ $comision->servicio->nombre ?? 'No disponible' }}</td>
                <td>{{ number_format((float)$comision->precio_base, 2, ',', '.') }} €</td>
                <td>{{ number_format((float)$comision->porcentaje_comision, 2, ',', '.') }} %</td>
                <td>{{ number_format((float)$comision->importe_comision, 2, ',', '.') }} €</td>
                <td>
                    {{ $comision->fecha_generacion
                        ? $comision->fecha_generacion->format('d/m/Y H:i')
                        : 'Sin fecha' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">Todavía no hay comisiones registradas.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="paginacion">{{ $comisiones->links() }}</div>
    <p><a href="{{ route('admin.usuarios.index') }}">Volver al panel de administración</a></p>
</main>
</body>
</html>
