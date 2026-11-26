<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis comisiones - ReparaYa</title>
    <style>
        body { font-family:Arial,sans-serif; background:#f1f5f4; margin:0; color:#263238; }
        header { background:#176b62; color:white; padding:20px 5%; }
        main { max-width:1150px; margin:30px auto; padding:0 20px; }
        .panel { background:white; padding:25px; border-radius:10px; margin-bottom:20px; }
        .resumen { display:flex; gap:20px; flex-wrap:wrap; }
        .dato { background:#edf5f2; padding:20px; border-radius:8px; flex:1; min-width:180px; }
        .dato strong { display:block; font-size:25px; margin-top:8px; color:#176b62; }
        a { display:inline-block; background:#176b62; color:white; padding:10px 15px; border-radius:6px; text-decoration:none; margin-right:8px; }
        .secundario { background:#607d8b; }
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        th,td { padding:12px; text-align:left; border-bottom:1px solid #ddd; }
        th { background:#edf4f2; }
        .vacio { padding:20px; background:#f5f5f5; border-radius:6px; }
        .paginacion { margin-top:20px; }
    </style>
</head>
<body>
<header>
    <h2>ReparaYa - Área de gestora</h2>
</header>

<main>
    <section class="panel">
        <h1>Mis comisiones</h1>
        <p>Consulta las comisiones generadas por las reparaciones de tus comunidades.</p>
        <a href="{{ route('gestora.panel') }}">Volver al panel</a>
        <a href="{{ route('gestora.liquidaciones') }}">Ver liquidaciones mensuales</a>
    </section>

    <section class="panel">
        <div class="resumen">
            <div class="dato">
                Total acumulado
                <strong>{{ number_format((float)$totalAcumulado, 2, ',', '.') }} €</strong>
            </div>
            <div class="dato">
                Número de comisiones
                <strong>{{ $totalRegistros }}</strong>
            </div>
            <div class="dato">
                Porcentaje acordado
                <strong>{{ number_format((float)$gestora->porcentaje_comision, 2, ',', '.') }} %</strong>
            </div>
        </div>
    </section>

    <section class="panel">
        <h2>Detalle de comisiones</h2>

        @if($comisiones->isEmpty())
            <div class="vacio">
                Todavía no se han generado comisiones para esta gestora.
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Incidencia</th>
                        <th>Servicio</th>
                        <th>Precio base</th>
                        <th>Porcentaje</th>
                        <th>Comisión</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comisiones as $comision)
                        <tr>
                            <td>
                                {{ $comision->fecha_generacion
                                    ? $comision->fecha_generacion->format('d/m/Y')
                                    : 'Sin fecha registrada' }}
                            </td>
                            <td>
                                {{ $comision->incidencia?->localizador ?? 'Registro anterior' }}
                            </td>
                            <td>{{ $comision->servicio?->nombre ?? 'No disponible' }}</td>
                            <td>{{ number_format((float)$comision->precio_base, 2, ',', '.') }} €</td>
                            <td>{{ number_format((float)$comision->porcentaje_comision, 2, ',', '.') }} %</td>
                            <td><strong>{{ number_format((float)$comision->importe_comision, 2, ',', '.') }} €</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="paginacion">
                {{ $comisiones->links() }}
            </div>
        @endif
    </section>
</main>
</body>
</html>
