<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de zonas - ReparaYa</title>
</head>
<body>
    <h1>Gestión de zonas</h1>

    @if(session('success')) <p>{{ session('success') }}</p> @endif
    @if(session('error')) <p>{{ session('error') }}</p> @endif

    <a href="{{ route('admin.zonas.create') }}">Crear zona</a>
    <a href="{{ route('admin.usuarios.index') }}">Volver al panel</a>

    <table border="1" cellpadding="8">
        <thead>
            <tr><th>Zona</th><th>Comunidades</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            @forelse($zonas as $zona)
                <tr>
                    <td>{{ $zona->nombre }}</td>
                    <td>{{ $zona->comunidades_count }}</td>
                    <td>
                        <a href="{{ route('admin.zonas.edit', $zona) }}">Editar</a>
                        <form method="POST" action="{{ route('admin.zonas.destroy', $zona) }}" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Eliminar esta zona?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">Todavía no hay zonas registradas.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $zonas->links() }}
</body>
</html>