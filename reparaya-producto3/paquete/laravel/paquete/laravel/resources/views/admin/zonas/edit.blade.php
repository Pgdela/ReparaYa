<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Editar zona - ReparaYa</title></head>
<body>
    <h1>Editar zona</h1>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.zonas.update', $zona) }}">
        @csrf
        @method('PUT')
        <label>Nombre de la zona</label>
        <input type="text" name="nombre" value="{{ old('nombre', $zona->nombre) }}" required maxlength="150">
        <button type="submit">Guardar cambios</button>
    </form>

    <a href="{{ route('admin.zonas.index') }}">Volver</a>
</body>
</html>