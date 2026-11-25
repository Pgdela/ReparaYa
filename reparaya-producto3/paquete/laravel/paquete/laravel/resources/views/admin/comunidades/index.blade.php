<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de comunidades - ReparaYa</title>
</head>
<body>
    <h1>Gestión de comunidades</h1>

    @if(session('success')) <p>{{ session('success') }}</p> @endif
    @if(session('error')) <p>{{ session('error') }}</p> @endif

    <a href="{{ route('admin.comunidades.create') }}">Crear comunidad</a>
    <a href="{{ route('admin.usuarios.index') }}">Volver al panel</a>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Comunidad</th>
                <th>Dirección</th>
                <th>Zona</th>
                <th>Gestora</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($comunidades as $comunidad)
                <tr>
                    <td>{{ $comunidad->nombre }}</td>
                    <td>{{ $comunidad->direccion }}</td>
                    <td>{{ $comunidad->zona->nombre ?? 'Sin zona' }}</td>
                    <td>{{ $comunidad->gestora->nombre ?? 'Sin gestora' }}</td>
                    <td>
                        <a href="{{ route('admin.comunidades.edit', $comunidad) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.comunidades.destroy', $comunidad) }}" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Eliminar esta comunidad?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Todavía no hay comunidades registradas.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $comunidades->links() }}
</body>
</html>