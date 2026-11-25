<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión - ReparaYa</title>
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
    <h2>Iniciar sesión</h2>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>

    <p>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a></p>
</div>
</body>
</html>
