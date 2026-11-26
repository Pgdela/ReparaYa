<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liquidaciones mensuales - ReparaYa</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f3f6fa;margin:0;padding:30px;color:#263445}
        main{max-width:1100px;margin:auto;background:white;padding:28px;border-radius:12px}
        h1,h2{color:#17365d}
        .total{background:#eaf2fb;padding:20px;border-radius:8px;margin:20px 0}
        .total strong{display:block;font-size:28px;color:#17365d;margin-top:8px}
        .mes{border:1px solid #dce3eb;border-radius:8px;padding:18px;margin:20px 0}
        table{width:100%;border-collapse:collapse;margin-top:12px}
        th,td{text-align:left;padding:11px;border-bottom:1px solid #ddd}
        th{background:#17365d;color:white}
        a{color:#1763a6}
        .importe{font-weight:bold}
    </style>
</head>
<body>
<main>
    <h1>Liquidaciones mensuales</h1>
    <p>Gestora: {{ $gestora->nombre }}</p>

    <div class="total">
        Total liquidado
        <strong>{{ number_format((float)$totalLiquidado, 2, ',', '.') }} €</strong>
    </div>

    <p>
        <a href="{{ route('gestora.comisiones.index') }}">Volver a mis comisiones</a>
        &nbsp; | &nbsp;
        <a href="{{ route('gestora.panel') }}">Volver al panel</a>
    </p>

    @forelse($liquidaciones as $liquidacion)
        <section class="mes">
            <h2>
                {{ \Illuminate\Support\Carbon::createFromFormat('Y-m', $liquidacion['mes'])->translatedFormat('F Y') }}
            </h2>

            <p>Número de servicios finalizados: {{ $liquidacion['numero'] }}</p>
            <p class="importe">
                Total del mes:
                {{ number_format((float)$liquidacion['total'], 2, ',', '.') }} €
            </p>

            <table>
                <thead>
                <tr>
                    <th>Incidencia</th>
                    <th>Servicio</th>
                    <th>Precio base</th>
                    <th>Porcentaje</th>
                    <th>Comisión</th>
                    <th>Fecha</th>
                </tr>
                </thead>
                <tbody>
                @foreach($liquidacion['comisiones'] as $comision)
                    <tr>
                        <td>{{ $comision->incidencia->localizador ?? 'No disponible' }}</td>
                        <td>{{ $comision->servicio->nombre ?? 'No disponible' }}</td>
                        <td>{{ number_format((float)$comision->precio_base, 2, ',', '.') }} €</td>
                        <td>{{ number_format((float)$comision->porcentaje_comision, 2, ',', '.') }} %</td>
                        <td>{{ number_format((float)$comision->importe_comision, 2, ',', '.') }} €</td>
                        <td>{{ $comision->fecha_generacion?->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </section>
    @empty
        <p>Aún no hay liquidaciones mensuales. Se mostrarán cuando existan incidencias finalizadas con comisión generada.</p>
    @endforelse
</main>
</body>
</html>