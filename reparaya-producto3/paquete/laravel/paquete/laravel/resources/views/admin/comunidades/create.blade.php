<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear comunidad - ReparaYa</title>
</head>
<body>
    <h1>Crear comunidad</h1>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.comunidades.store') }}">
        @csrf

        <label>Nombre de la comunidad</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}" required maxlength="150">

        <br><br>

        <label>Dirección</label>
        <input type="text" name="direccion" value="{{ old('direccion') }}" required maxlength="255">

        <br><br>

        <label>Zona</label>
        <select name="zona_id" required>
            <option value="">Selecciona una zona</option>
            @foreach($zonas as $zona)
                <option value="{{ $zona->id }}" @selected(old('zona_id') == $zona->id)>
                    {{ $zona->nombre }}
                </option>
            @endforeach
        </select>

        <br><br>

        <label>Gestora</label>
        <select name="gestora_id" required>
            <option value="">Selecciona una gestora</option>
            @foreach($gestoras as $gestora)
                <option value="{{ $gestora->id }}" @selected(old('gestora_id') == $gestora->id)>
                    {{ $gestora->nombre }}
                </option>
            @endforeach
        </select>

        <br><br>

        <button type="submit">Guardar comunidad</button>
    </form>

    <a href="{{ route('admin.comunidades.index') }}">Volver</a>
</body>
</html>