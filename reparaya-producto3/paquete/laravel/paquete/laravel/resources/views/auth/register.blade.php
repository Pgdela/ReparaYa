<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - ReparaYa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial; background: #f1f5f4; display: grid; place-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 35px; width: 340px; border-radius: 12px; }
        h1 { color: #176b62; }
        input { box-sizing: border-box; width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 6px; }
        button { width: 100%; padding: 12px; background: #176b62; color: white; border: 0; border-radius: 6px; cursor: pointer; }
        a { color: #176b62; }
        .error { color: #b83232; }
    </style>
</head>
<body>
<div class="card">
    <h1>ReparaYa</h1>
    <h2>Crear cuenta</h2>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <input type="text" name="name" placeholder="Nombre completo" value="{{ old('name') }}" required>
        <input type="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" required>
        <input type="password" name="password" placeholder="Contraseña (mínimo 8 caracteres)" required>
        <input type="password" name="password_confirmation" placeholder="Repetir contraseña" required>
        <button type="submit">Registrarme</button>
    </form>

    <p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
</div>
</body>
</html>
