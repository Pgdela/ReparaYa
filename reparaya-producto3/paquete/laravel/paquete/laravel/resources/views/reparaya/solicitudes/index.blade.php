<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis reparaciones - ReparaYa</title>
    <style>
        body { font-family: Arial; background: #f1f5f4; margin: 0; color: #263b39; }
        header { background: #176b62; color: white; padding: 20px 7%; }
        main { max-width: 950px; margin: 35px auto; padding: 25px; background: white; border-radius: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        a { display: inline-block; margin-top: 20px; padding: 12px 18px; background: #176b62; color: white; border-radius: 6px; text-decoration: none; }
        .success { color: #176b62; }
    </style>
</head>
<body>
<header><strong>ReparaYa</strong></header>
<main>
    <h1>Mis reparaciones</h1>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if ($solicitudes->isEmpty())
        <p>Todavia no tienes solicitudes registradas.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th>Precio base</th>
                    <th>Fecha preferida</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($solicitudes as $solicitud)
                    <tr>
                        <td>{{ $solicitud->servicio->nombre }}</td>
                        <td>{{ number_format($solicitud->precio_base, 2, ',', '.') }} EUR</td>
                        <td>{{ $solicitud->fecha_preferida ?? 'Sin especificar' }}</td>
                        <td>{{ $solicitud->estado }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('reparaya') }}">Volver al panel</a>
    <a href="{{ route('reparaya.solicitudes.create') }}">Nueva solicitud</a>
</main>
</body>
</html>
