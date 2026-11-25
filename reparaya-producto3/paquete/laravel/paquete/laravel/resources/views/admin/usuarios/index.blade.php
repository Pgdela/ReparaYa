<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de usuarios - ReparaYa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 30px;
            color: #263238;
        }
        .contenedor {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
        }
        h1 { color: #176b62; }
        a, button {
            display: inline-block;
            padding: 10px 15px;
            border: 0;
            border-radius: 6px;
            background: #176b62;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }
        .menu {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th { background: #e8f2f0; }
        .mensaje { padding: 12px; background: #e0f2e9; margin: 15px 0; }
        .error { background: #fde2e2; }
        .acciones { display: flex; gap: 8px; }
        .eliminar { background: #b83232; }
        .cerrar { background: #555; }
    </style>
</head>
<body>
<div class="contenedor">
    <h1>Panel de administración ReparaYa</h1>
    <p>Gestión de usuarios y servicios para administradores.</p>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="cerrar">Cerrar sesión</button>
    </form>

    @if(session('success'))
        <div class="mensaje">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="mensaje error">{{ session('error') }}</div>
    @endif

    <h2>Administración</h2>

    <div class="menu">
        <a href="{{ route('admin.usuarios.index') }}">Usuarios</a>
        <a href="{{ route('admin.gestoras.index') }}">Gestoras</a>
        <a href="{{ route('admin.zonas.index') }}">Zonas</a>
        <a href="{{ route('admin.comunidades.index') }}">Comunidades</a>
        <a href="{{ route('admin.comisiones.index') }}">Comisiones</a>
    </div>

    <h2>Usuarios registrados</h2>

    <a href="{{ route('admin.usuarios.create') }}">Crear usuario</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo electrónico</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        <div class="acciones">
                            <a href="{{ route('admin.usuarios.edit', $user) }}">Editar</a>
                            <form action="{{ route('admin.usuarios.destroy', $user) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Seguro que quieres eliminar este usuario?')">
                                @csrf
                                @method('DELETE')
                                <button class="eliminar" type="submit">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay usuarios registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $users->links() }}
</div>
</body>
</html>