<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitar reparacion - ReparaYa</title>
    <style>
        body { font-family: Arial; background: #f1f5f4; margin: 0; color: #263b39; }
        header { background: #176b62; color: white; padding: 20px 7%; }
        main { max-width: 650px; margin: 35px auto; padding: 25px; background: white; border-radius: 12px; }
        label { display: block; margin-top: 18px; font-weight: bold; }
        input, select, textarea { width: 100%; box-sizing: border-box; padding: 12px; margin-top: 7px; border: 1px solid #ccc; border-radius: 6px; }
        textarea { min-height: 110px; }
        button, a { display: inline-block; margin-top: 20px; padding: 12px 18px; background: #176b62; color: white; border: 0; border-radius: 6px; text-decoration: none; cursor: pointer; }
        .error { color: #b83232; }
    </style>
</head>
<body>
<header><strong>ReparaYa</strong></header>
<main>
    <h1>Solicitar una reparacion</h1>
    <p>Completa los datos para registrar tu solicitud.</p>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('reparaya.solicitudes.store') }}">
        @csrf

        <label for="servicio_id">Tipo de reparacion</label>
        <select name="servicio_id" id="servicio_id" required>
            <option value="">Selecciona un servicio</option>
            @foreach ($servicios as $servicio)
                <option value="{{ $servicio->id }}" {{ old('servicio_id') == $servicio->id ? 'selected' : '' }}>
                    {{ $servicio->nombre }} - {{ number_format($servicio->precio_base, 2, ',', '.') }} EUR
                </option>
            @endforeach
        </select>

        <label for="descripcion">Descripcion del problema</label>
        <textarea name="descripcion" id="descripcion" required>{{ old('descripcion') }}</textarea>

        <label for="direccion">Direccion del servicio</label>
        <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}" required>

        <label for="telefono">Telefono de contacto</label>
        <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" required>

        <label for="fecha_preferida">Fecha preferida (opcional)</label>
        <input type="date" name="fecha_preferida" id="fecha_preferida" min="{{ date('Y-m-d') }}" value="{{ old('fecha_preferida') }}">

        <button type="submit">Enviar solicitud</button>
        <a href="{{ route('reparaya') }}">Volver al panel</a>
    </form>
</main>
</body>
</html>
