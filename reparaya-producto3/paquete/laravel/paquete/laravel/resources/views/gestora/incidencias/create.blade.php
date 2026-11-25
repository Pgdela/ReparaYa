<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear incidencia - ReparaYa</title>
    <style>
        body { font-family:Arial,sans-serif; background:#f1f5f4; margin:0; color:#263238; }
        header { background:#176b62; color:white; padding:20px 5%; }
        main { max-width:750px; margin:30px auto; padding:0 20px; }
        .panel { background:white; padding:25px; border-radius:10px; }
        label { display:block; margin-top:15px; font-weight:bold; }
        input, select, textarea { width:100%; box-sizing:border-box; padding:11px; margin-top:6px; border:1px solid #ccc; border-radius:6px; }
        textarea { min-height:100px; }
        button, .volver { display:inline-block; margin-top:20px; padding:12px 18px; background:#176b62; color:white; border:0; border-radius:6px; text-decoration:none; cursor:pointer; }
        .volver { background:#607d8b; margin-left:8px; }
        .error { color:#b42318; font-size:14px; }
        .errores { background:#fde8e7; padding:12px; border-radius:6px; }
    </style>
</head>
<body>
<header>
    <h2>ReparaYa - Nueva incidencia</h2>
</header>

<main>
    <section class="panel">
        <h1>Registrar una incidencia</h1>
        <p>Completa los datos de la reparación solicitada por un vecino.</p>

        @if($errors->any())
            <div class="errores">
                <strong>Revisa los siguientes campos:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($comunidades->isEmpty())
            <p>Tu gestora todavía no tiene comunidades asignadas.</p>
        @elseif($servicios->isEmpty())
            <p>No hay servicios activos disponibles.</p>
        @elseif($clientes->isEmpty())
            <p>No hay vecinos registrados como particulares. Es necesario registrar primero una cuenta de vecino.</p>
        @else
            <form method="POST" action="{{ route('gestora.incidencias.store') }}">
                @csrf

                <label>Comunidad</label>
                <select name="comunidad_id" required>
                    <option value="">Selecciona una comunidad</option>
                    @foreach($comunidades as $comunidad)
                        <option value="{{ $comunidad->id }}" @selected(old('comunidad_id') == $comunidad->id)>
                            {{ $comunidad->nombre }} - {{ $comunidad->direccion }}
                        </option>
                    @endforeach
                </select>

                <label>Vecino</label>
                <select name="cliente_id" required>
                    <option value="">Selecciona un vecino</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" @selected(old('cliente_id') == $cliente->id)>
                            {{ $cliente->name }} ({{ $cliente->email }})
                        </option>
                    @endforeach
                </select>

                <label>Servicio</label>
                <select name="servicio_id" required>
                    <option value="">Selecciona un servicio</option>
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id }}" @selected(old('servicio_id') == $servicio->id)>
                            {{ $servicio->nombre }} - {{ number_format((float)$servicio->precio_base, 2, ',', '.') }} €
                        </option>
                    @endforeach
                </select>

                <label>Descripción de la incidencia</label>
                <textarea name="descripcion" required>{{ old('descripcion') }}</textarea>

                <label>Dirección de la reparación</label>
                <input type="text" name="direccion" value="{{ old('direccion') }}" required>

                <label>Teléfono de contacto</label>
                <input type="text" name="telefono_contacto" value="{{ old('telefono_contacto') }}" required>

                <label>Fecha preferida</label>
                <input type="date" name="fecha_servicio" min="{{ date('Y-m-d') }}" value="{{ old('fecha_servicio') }}" required>

                <label>Franja horaria</label>
                <select name="franja_horaria" required>
                    <option value="Mañana" @selected(old('franja_horaria') == 'Mañana')>Mañana</option>
                    <option value="Tarde" @selected(old('franja_horaria') == 'Tarde')>Tarde</option>
                    <option value="Noche" @selected(old('franja_horaria') == 'Noche')>Noche</option>
                </select>

                <label>Urgencia</label>
                <select name="tipo_urgencia" required>
                    <option value="Estándar" @selected(old('tipo_urgencia') == 'Estándar')>Estándar</option>
                    <option value="Urgente" @selected(old('tipo_urgencia') == 'Urgente')>Urgente</option>
                </select>

                <button type="submit">Guardar incidencia</button>
                <a class="volver" href="{{ route('gestora.incidencias.index') }}">Cancelar</a>
            </form>
        @endif
    </section>
</main>
</body>
</html>
