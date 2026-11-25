<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de gestora | ReparaYa</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f1f5f4; color: #263b39; }
        header { background: #176b62; color: white; padding: 22px 7%; display: flex; justify-content: space-between; align-items: center; }
        main { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .panel { background: white; padding: 28px; border-radius: 12px; margin-bottom: 22px; box-shadow: 0 3px 12px #00000010; }
        a, button { display: inline-block; background: #176b62; color: white; padding: 11px 17px; border: 0; border-radius: 7px; text-decoration: none; cursor: pointer; }
        .salir { background: white; color: #176b62; }
        li { margin: 12px 0; }
    </style>
</head>
<body>
<header>
    <strong>ReparaYa · Área de gestoras</strong>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="salir" type="submit">Cerrar sesión</button>
    </form>
</header>

<main>
    <section class="panel">
        <p>
    <a href="{{ route('gestora.comunidades.index') }}">
        Gestionar mis comunidades
    </a>
</p>
        <h1>Bienvenida, {{ $gestora->nombre }}</h1>
        <p>Este es el espacio privado de tu empresa gestora.</p>
        <p><strong>Comisión acordada:</strong> {{ number_format((float)$gestora->porcentaje_comision, 2, ',', '.') }} %</p>
        <p><strong>Estado:</strong> {{ $gestora->activa ? 'Activa' : 'Inactiva' }}</p>
    </section>
<a href="{{ route('gestora.incidencias.index') }}"
   style="display:inline-block;background:#176b62;color:white;padding:12px 18px;border-radius:6px;text-decoration:none;margin-bottom:15px;">
    Gestionar incidencias
</a>
    <section class="panel">
        <h2>Mis comunidades</h2>

        @forelse($gestora->comunidades as $comunidad)
            <article>
                <h3>{{ $comunidad->nombre }}</h3>
                <p><strong>Dirección:</strong> {{ $comunidad->direccion }}</p>
                <p><strong>Zona:</strong> {{ $comunidad->zona?->nombre ?? 'Sin zona' }}</p>
            </article>
        @empty
            <p>Tu empresa todavía no tiene comunidades asignadas.</p>
        @endforelse
    </section>
</main>
</body>
</html>