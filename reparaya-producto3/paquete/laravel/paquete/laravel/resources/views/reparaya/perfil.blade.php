<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi perfil - ReparaYa</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f1f5f4; margin: 0; color: #263b39; }
        header { background: #176b62; color: white; padding: 20px 7%; }
        main { max-width: 650px; margin: 40px auto; padding: 30px; background: white; border-radius: 12px; }
        label { display: block; margin-top: 18px; font-weight: bold; }
        input { width: 100%; padding: 12px; margin-top: 7px; border: 1px solid #ccc; border-radius: 6px; }
        button, a { display: inline-block; margin-top: 22px; padding: 12px 18px; background: #176b62; color: white; border: 0; border-radius: 6px; text-decoration: none; cursor: pointer; }
        .success { color: #176b62; }
        .error { color: #b83232; }
    </style>
</head>
<body>
<header><strong>ReparaYa - Mi perfil</strong></header>
<main>
    <h1>Mis datos personales</h1>
    <p>Consulta y modifica tus datos de usuario.</p>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('reparaya.perfil.update') }}">
        @csrf
        @method('PUT')

        <label for="name">Nombre</label>
        <input type="text" id="name" name="name" value="{{ old('name', $usuario->name) }}" required>

        <label for="email">Correo electr?nico</label>
        <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}" required>

        <button type="submit">Guardar cambios</button>
    </form>

    <a href="{{ route('reparaya') }}">Volver al panel</a>
</main>
</body>
</html>
