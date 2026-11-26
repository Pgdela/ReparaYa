<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liquidaciones mensuales - ReparaYa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f6fa;
            margin: 0;
            padding: 30px;
            color: #263445;
        }
        main {
            max-width: 1150px;
            margin: auto;
            background: white;
            padding: 28px;
            border-radius: 12px;
        }
        h1, h2 { color: #17365d; }
        .resumen {
            background: #eaf2fb;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .resumen strong {
            display: block;
            font-size: 28px;
            color: #17365d;
            margin-top: 8px;
        }
        .mes {
            border: 1px solid #dce4ed;
            border-radius: 8px;
            margin: 22px 0;
            padding: 20px;
        }
        .datos {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            margin: 15px 0;
        }
        .dato {
            background: #f3f6fa;
            padding: 12px 18px;
            border-radius: 6px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }
        th, td {
            text-align: left;
            padding: 11px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #17365d;
            color: white;
        }
        a { color: #1763a6; }
        .aviso {
            color: #687789;
            padding: 18px;
            background: #f3f6fa;
            border-radius: 8px;
        }
    </style>
</head>
<body>
<main>
    <h1>Liquidaciones mensuales</h1>

    <p>
        Consulta de las comisiones generadas por las incidencias
        finalizadas y agrupadas por mes.
    </p>

    <section class="resumen">
        Total liquidado
        <strong>
            {{ number_format((float)$totalLiquidado, 2, ',', '.') }} €
        </strong>
    </section>

    @forelse($liquidaciones as $liquidacion)
        <section class="mes">
            <h2>
                {{ \Illuminate\Support\Carbon::createFromFormat('Y-m', $liquidacion['mes'])->translatedFormat('F Y') }}
            </h2>

            <div class="datos">
                <div class="dato">
                    Incidencias finalizadas:
                    <strong>{{ $liquidacion['numero'] }}</strong>
                </div>

                <div class="dato">
                    Total del mes:
                    <strong>
                        {{ number_format((float)$liquidacion['total'], 2, ',', '.') }} €
                    </strong>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Incidencia</th>
                        <th>Cliente</th>
                        <th>Comunidad</th>
                        <th>Servicio</th>
                        <th>Precio base</th>
                        <th>Comisión</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($liquidacion['comisiones'] as $comision)
                        <tr>
                            <td>
                                {{ $comision->incidencia->localizador ?? 'No disponible' }}
                            </td>
                            <td>
                                {{ $comision->incidencia->cliente->name ?? 'No disponible' }}
                            </td>
                            <td>
                                {{ $comision->incidencia->comunidad->nombre ?? 'No disponible' }}
                            </td>
                            <td>
                                {{ $comision->servicio->nombre ?? 'No disponible' }}
                            </td>
                            <td>
                                {{ number_format((float)$comision->precio_base, 2, ',', '.') }} €
                            </td>
                            <td>
                                {{ number_format((float)$comision->importe_comision, 2, ',', '.') }} €
                            </td>
                            <td>
                                {{ $comision->fecha_generacion->format('d/m/Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    @empty
        <p class="aviso">
            Todavía no hay comisiones generadas para liquidar.
        </p>
    @endforelse

    <p>
        <a href="{{ route('gestora.comisiones.index') }}">
            Volver a mis comisiones
        </a>
    </p>
</main>
</body>
</html>
