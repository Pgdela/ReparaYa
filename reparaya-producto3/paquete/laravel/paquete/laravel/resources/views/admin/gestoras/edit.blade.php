<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar gestora | ReparaYa</title>
    <style>
        body { font-family: Arial; background: #f4f6f9; margin: 40px; }
        .container { max-width: 650px; margin: auto; background: white; padding: 30px; border-radius: 10px; }
        input, select { width: 100%; padding: 10px; margin: 7px 0 15px; box-sizing: border-box; }
        button, a { padding: 10px 15px; border: 0; border-radius: 5px; text-decoration: none; cursor: pointer; }
        button { background: #1769aa; color: white; }
        a { background: #ddd; color: #222; }
    </style>
</head>
<body>
<div class="container">
    <h1>Editar empresa gestora</h1>

    @if($errors->any())
        <div style="color:red">
            <p>Revisa los siguientes errores:</p>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.gestoras.update', $gestora) }}">
        @csrf
        @method('PUT')
        @include('admin.gestoras.form')

        <button type="submit">Guardar cambios</button>
        <a href="{{ route('admin.gestoras.index') }}">Cancelar</a>
    </form>
</div>
</body>
</html>
