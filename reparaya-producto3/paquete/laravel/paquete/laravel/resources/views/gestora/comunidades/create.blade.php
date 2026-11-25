<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear comunidad | ReparaYa</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f1f5f4; color: #263b39; }
        header { background: #176b62; color: white; padding: 22px 7%; }
        main { max-width: 800px; margin: 40px auto; padding: 0 20px; }
        .panel { background: white; padding: 28px; border-radius: 12px; box-shadow: 0 3px 12px #00000010; }
        label { display: block; margin: 18px 0 7px; font-weight: bold; }
        input, select { width: 100%; padding: 12px; border: 1px solid #ccd7d4; border-radius: 6px; font-size: 16px; }
        button, .btn { display: inline-block; background: #176b62; color: white; padding: 12px 18px; border: 0; border-radius: 7px; text-decoration: none; cursor: pointer; margin-top: 20px; }
        .secundario { background: #647572; }
        .error { background: #ffe8e8; color: #9b2222; padding: 12px; border-radius: 6px; }
    </style>
</head>
<body>
<header><strong>ReparaYa · Área de gestoras</strong></header>
<main>
    <section class="panel">
        <h1>Crear comunidad</h1>
        <p>Registra una nueva comunidad en tu empresa gestora.</p>

        @if ($errors->any())
            <div class="error">
                <strong>Revisa los siguientes errores:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('gestora.comunidades.store') }}">
            @csrf

            <label for="nombre">Nombre de la comunidad</label>
            <input id="nombre" name="nombre" value="{{ old('nombre') }}" required>

            <label for="direccion">Dirección</label>
            <input id="direccion" name="direccion" value="{{ old('direccion') }}" required>

            <label for="zona_id">Zona</label>
            <select id="zona_id" name="zona_id" required>
                <option value="">Selecciona una zona</option>
                @foreach ($zonas as $zona)
                    <option value="{{ $zona->id }}" {{ old('zona_id') == $zona->id ? 'selected' : '' }}>
                        {{ $zona->nombre }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Guardar comunidad</button>
            <a class="btn secundario" href="{{ route('gestora.comunidades.index') }}">Cancelar</a>
        </form>
    </section>
</main>
</body>
</html>
