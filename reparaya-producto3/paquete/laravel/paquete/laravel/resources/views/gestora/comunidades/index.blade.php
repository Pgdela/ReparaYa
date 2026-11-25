<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis comunidades | ReparaYa</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f1f5f4; color: #263b39; }
        header { background: #176b62; color: white; padding: 22px 7%; }
        main { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .panel { background: white; padding: 28px; border-radius: 12px; margin-bottom: 22px; box-shadow: 0 3px 12px #00000010; }
        a, button { display: inline-block; background: #176b62; color: white; padding: 11px 17px; border: 0; border-radius: 7px; text-decoration: none; cursor: pointer; }
        .editar { background: #b7791f; }
        .eliminar { background: #a83232; }
        .mensaje { padding: 12px; border-radius: 6px; background: #e1f5e9; margin-bottom: 15px; }
        .error { background: #ffe8e8; color: #9b2222; }
        article { border-bottom: 1px solid #ddd; padding: 15px 0; }
        form { display: inline; }
    </style>
</head>
<body>
<header><strong>ReparaYa · Área de gestoras</strong></header>
<main>
    <section class="panel">
        <h1>Mis comunidades</h1>

        @if (session('success'))
            <div class="mensaje">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mensaje error">{{ session('error') }}</div>
        @endif

        <p><a href="{{ route('gestora.comunidades.create') }}">+ Crear comunidad</a></p>

        @forelse ($comunidades as $comunidad)
            <article>
                <h2>{{ $comunidad->nombre }}</h2>
                <p><strong>Dirección:</strong> {{ $comunidad->direccion }}</p>
                <p><strong>Zona:</strong> {{ $comunidad->zona?->nombre ?? 'Sin zona' }}</p>
                <p><strong>Incidencias:</strong> {{ $comunidad->incidencias_count }}</p>

                <a class="editar" href="{{ route('gestora.comunidades.edit', $comunidad->id) }}">Editar</a>

                <form method="POST" action="{{ route('gestora.comunidades.destroy', $comunidad->id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="eliminar" onclick="return confirm('¿Eliminar esta comunidad?')">Eliminar</button>
                </form>
            </article>
        @empty
            <p>Tu empresa todavía no tiene comunidades asignadas.</p>
        @endforelse

        {{ $comunidades->links() }}

        <p><a href="{{ route('gestora.panel') }}">Volver al panel</a></p>
    </section>
</main>
</body>
</html>
