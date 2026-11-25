<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comisiones | ReparaYa</title>
    <style>
        body { font-family: Arial; margin: 40px; background: #f4f6f9; }
        .container { max-width: 1100px; margin: auto; background: white; padding: 25px; border-radius: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #edf2f7; }
        a { display: inline-block; padding: 10px 15px; background: #1769aa; color: white; border-radius: 5px; text-decoration: none; }
        .success { background: #d4edda; padding: 12px; margin: 15px 0; }
    </style>
</head>
<body>
<div class="container">
    <h1>Gestión de comisiones</h1>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <p><a href="{{ route('admin.comisiones.create') }}">Crear nueva comisión</a></p>

    <table>
        <thead>
            <tr>
                <th>Gestora</th>
                <th>Servicio</th>
                <th>Precio base</th>
                <th>Porcentaje</th>
                <th>Importe comisión</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($comisiones as $comision)
                <tr>
                    <td>{{ $comision->gestora->nombre }}</td>
                    <td>{{ $comision->servicio->nombre }}</td>
                    <td>{{ number_format($comision->precio_base, 2, ',', '.') }} €</td>
                    <td>{{ $comision->porcentaje_comision }}%</td>
                    <td>{{ number_format($comision->importe_comision, 2, ',', '.') }} €</td>
                    <td>{{ $comision->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="6">Todavía no hay comisiones registradas.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $comisiones->links() }}

    <p><a href="{{ url('/admin') }}">Volver al panel</a></p>
</div>
</body>
</html>
