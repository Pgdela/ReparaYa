<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Crear zona - ReparaYa</title></head>
<body>
    <h1>Crear zona</h1>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('admin.zonas.store') }}">
        @csrf
        <label>Nombre de la zona</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}" required maxlength="150">
        <button type="submit">Guardar zona</button>
    </form>

    <a href="{{ route('admin.zonas.index') }}">Volver</a>
</body>
</html>