<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Empresas gestoras | ReparaYa</title>
    <style>
        body { font-family: Arial; margin: 40px; background: #f4f6f9; }
        .container { max-width: 1100px; margin: auto; background: white; padding: 25px; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #edf2f7; }
        a, button { padding: 8px 12px; border-radius: 5px; text-decoration: none; border: 0; cursor: pointer; }
        .primary { background: #1769aa; color: white; }
        .edit { background: #e6a23c; color: white; }
        .delete { background: #c0392b; color: white; }
        .success { background: #d4edda; padding: 12px; margin: 15px 0; }
        .error { background: #f8d7da; padding: 12px; margin: 15px 0; }
        form { display: inline; }
    </style>
</head>
<body>
<div class="container">
    <h1>Empresas gestoras</h1>
    <p>Administración de empresas gestoras de ReparaYa.</p>

    <a class="primary" href="{{ route('admin.gestoras.create') }}">+ Nueva gestora</a>
    <a href="{{ url('/reparaya') }}">Volver al inicio</a>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    @if($gestoras->count())
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>CIF</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Comisión</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gestoras as $gestora)
                    <tr>
                        <td>{{ $gestora->nombre }}</td>
                        <td>{{ $gestora->cif ?? '—' }}</td>
                        <td>{{ $gestora->email ?? '—' }}</td>
                        <td>{{ $gestora->telefono ?? '—' }}</td>
                        <td>{{ number_format((float)$gestora->porcentaje_comision, 2, ',', '.') }} %</td>
                        <td>{{ $gestora->activa ? 'Activa' : 'Inactiva' }}</td>
                        <td>
                            <a class="edit" href="{{ route('admin.gestoras.edit', $gestora) }}">Editar</a>
                            <form method="POST" action="{{ route('admin.gestoras.destroy', $gestora) }}"
                                  onsubmit="return confirm('¿Seguro que quieres eliminar esta gestora?')">
                                @csrf
                                @method('DELETE')
                                <button class="delete" type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $gestoras->links() }}
    @else
        <p>Todavía no hay empresas gestoras registradas.</p>
    @endif
</div>
</body>
</html>
