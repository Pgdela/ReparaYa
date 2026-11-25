<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar usuario - ReparaYa</title>
    <style>
        body { font-family: Arial; background: #f4f6f8; padding: 30px; }
        .contenedor { max-width: 600px; margin: auto; background: white; padding: 25px; border-radius: 12px; }
        h1 { color: #176b62; }
        label { display: block; margin-top: 15px; }
        input, select { width: 100%; padding: 10px; margin-top: 6px; box-sizing: border-box; }
        button, a { display: inline-block; margin-top: 20px; padding: 10px 15px; background: #176b62; color: white; border: 0; border-radius: 6px; text-decoration: none; cursor: pointer; }
        .error { color: #b83232; }
    </style>
</head>
<body>
<div class="contenedor">
    <h1>Editar usuario</h1>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.usuarios.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nombre completo</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>

        <label>Correo electrónico</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>

        <label>Nueva contraseña (dejar vacía para mantener la actual)</label>
        <input type="password" name="password" minlength="8">

        <label>Rol</label>
        <select name="role" required>
            <option value="particular" {{ $user->role === 'particular' ? 'selected' : '' }}>Particular</option>
            <option value="tecnico" {{ $user->role === 'tecnico' ? 'selected' : '' }}>Técnico</option>
            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrador</option>
        </select>

        <button type="submit">Guardar cambios</button>
        <a href="{{ route('admin.usuarios.index') }}">Volver</a>
    </form>
</div>
</body>
</html>
