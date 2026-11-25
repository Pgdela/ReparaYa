<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calcular comisión - ReparaYa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f6f9; }
        h1 { color: #263849; }
        form { background: white; padding: 25px; max-width: 550px; border-radius: 8px; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        select { width: 100%; padding: 10px; margin-top: 6px; }
        button, a { display: inline-block; margin-top: 20px; padding: 10px 15px; border: 0; border-radius: 5px; text-decoration: none; cursor: pointer; }
        button { background: #087f5b; color: white; }
        a { background: #ddd; color: #222; }
        .error { color: #c00; }
    </style>
</head>
<body>

<h1>Calcular nueva comisión</h1>

<form method="POST" action="{{ route('admin.comisiones.store') }}">
    @csrf

    <label for="gestora_id">Gestora</label>
    <select name="gestora_id" id="gestora_id" required>
        <option value="">Selecciona una gestora</option>
        @foreach($gestoras as $gestora)
            <option value="{{ $gestora->id }}" @selected(old('gestora_id') == $gestora->id)>
                {{ $gestora->nombre }} ({{ $gestora->porcentaje_comision }} %)
            </option>
        @endforeach
    </select>
    @error('gestora_id')
        <p class="error">{{ $message }}</p>
    @enderror

    <label for="servicio_id">Servicio</label>
    <select name="servicio_id" id="servicio_id" required>
        <option value="">Selecciona un servicio</option>
        @foreach($servicios as $servicio)
            <option value="{{ $servicio->id }}" @selected(old('servicio_id') == $servicio->id)>
                {{ $servicio->nombre }} - {{ number_format($servicio->precio_base, 2, ',', '.') }} €
            </option>
        @endforeach
    </select>
    @error('servicio_id')
        <p class="error">{{ $message }}</p>
    @enderror

    <button type="submit">Calcular y guardar comisión</button>
    <a href="{{ route('admin.comisiones.index') }}">Volver</a>
</form>

</body>
</html>
