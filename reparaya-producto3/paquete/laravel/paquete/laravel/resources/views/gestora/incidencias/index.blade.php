<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Incidencias de mis comunidades - ReparaYa</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f1f5f4; margin:0; color:#263238; }
        header { background:#176b62; color:white; padding:20px 5%; }
        main { max-width:1100px; margin:30px auto; padding:0 20px; }
        .panel { background:white; padding:25px; border-radius:10px; margin-bottom:20px; }
        a, button { background:#176b62; color:white; padding:10px 15px; border-radius:6px; text-decoration:none; border:0; cursor:pointer; display:inline-block; }
        .secundario { background:#607d8b; }
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        th, td { padding:12px; text-align:left; border-bottom:1px solid #ddd; }
        th { background:#edf4f2; }
        .mensaje { background:#d9f3e5; padding:12px; border-radius:6px; margin-bottom:15px; }
        .acciones { display:flex; gap:10px; flex-wrap:wrap; }
    </style>
</head>
<body>
<header>
    <h2>ReparaYa - Gestión de incidencias</h2>
</header>

<main>
    <section class="panel">
        <h1>Incidencias de mis comunidades</h1>
        <p>Consulta las reparaciones registradas en las comunidades de tu gestora.</p>

        <div class="acciones">
            <a href="{{ route('gestora.incidencias.create') }}">Crear incidencia</a>
            <a class="secundario" href="{{ route('gestora.panel') }}">Volver al panel</a>
        </div>
    </section>

    <section class="panel">
        @if(session('success'))
            <div class="mensaje">{{ session('success') }}</div>
        @endif

        @if($incidencias->count())
            <table>
                <thead>
                    <tr>
                        <th>Localizador</th>
                        <th>Comunidad</th>
                        <th>Vecino</th>
                        <th>Servicio</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($incidencias as $incidencia)
                        <tr>
                            <td>{{ $incidencia->localizador }}</td>
                            <td>{{ $incidencia->comunidad->nombre }}</td>
                            <td>{{ $incidencia->cliente->name }}</td>
                            <td>{{ $incidencia->servicio->nombre }}</td>
                            <td>{{ $incidencia->fecha_servicio->format('d/m/Y') }}</td>
                            <td>{{ $incidencia->estado }}</td>
                            <td>{{ number_format((float)$incidencia->precio_base, 2, ',', '.') }} €</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top:20px;">
                {{ $incidencias->links() }}
            </div>
        @else
            <p>Todavía no hay incidencias registradas en tus comunidades.</p>
        @endif
    </section>
</main>
</body>
</html>
