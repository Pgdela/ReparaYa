<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar comunidad - ReparaYa</title>
</head>
<body>
    <h1>Editar comunidad</h1>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.comunidades.update', $comunidad) }}">
        @csrf
        @method('PUT')

        <label>Nombre de la comunidad</label>
        <input type="text" name="nombre" value="{{ old('nombre', $comunidad->nombre) }}" required maxlength="150">

        <br><br>

        <label>Dirección</label>
        <input type="text" name="direccion" value="{{ old('direccion', $comunidad->direccion) }}" required maxlength="255">

        <br><br>

        <label>Zona</label>
        <select name="zona_id" required>
            @foreach($zonas as $zona)
                <option value="{{ $zona->id }}" @selected(old('zona_id', $comunidad->zona_id) == $zona->id)>
                    {{ $zona->nombre }}
                </option>
            @endforeach
        </select>

        <br><br>

        <label>Gestora</label>
        <select name="gestora_id" required>
            @foreach($gestoras as $gestora)
                <option value="{{ $gestora->id }}" @selected(old('gestora_id', $comunidad->gestora_id) == $gestora->id)>
                    {{ $gestora->nombre }}
                </option>
            @endforeach
        </select>

        <br><br>

        <button type="submit">Guardar cambios</button>
    </form>

    <a href="{{ route('admin.comunidades.index') }}">Volver</a>
</body>
</html>