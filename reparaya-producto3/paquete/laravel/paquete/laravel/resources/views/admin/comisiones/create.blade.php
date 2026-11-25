<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear comisión | ReparaYa</title>
    <style>
        body { font-family: Arial; margin: 40px; background: #f4f6f9; }
        .container { max-width: 800px; margin: auto; background: white; padding: 25px; border-radius: 10px; }
        label { display: block; margin-top: 20px; font-weight: bold; }
        select { width: 100%; padding: 12px; margin-top: 8px; }
        button, a { display: inline-block; padding: 10px 15px; margin-top: 20px; border: 0; border-radius: 5px; cursor: pointer; text-decoration: none; }
        button { background: #1769aa; color: white; }
        a { background: #777; color: white; }
        .error { background: #f8d7da; padding: 12px; margin: 15px 0; }
    </style>
</head>
<body>
<div class="container">
    <h1>Crear comisión</h1>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.comisiones.store') }}">
        @csrf

        <label>Empresa gestora</label>
        <select name="gestora_id" required>
            <option value="">Selecciona una gestora</option>
            @foreach ($gestoras as $gestora)
                <option value="{{ $gestora->id }}">
                    {{ $gestora->nombre }} - {{ $gestora->porcentaje_comision }}%
                </option>
            @endforeach
        </select>

        <label>Servicio</label>
        <select name="servicio_id" required>
            <option value="">Selecciona un servicio</option>
            @foreach ($servicios as $servicio)
                <option value="{{ $servicio->id }}">
                    {{ $servicio->nombre }} - {{ number_format($servicio->precio_base, 2, ',', '.') }} €
                </option>
            @endforeach
        </select>

        <button type="submit">Calcular y guardar comisión</button>
    </form>

    <p><a href="{{ route('admin.comisiones.index') }}">Volver al listado</a></p>
</div>
</body>
</html>
