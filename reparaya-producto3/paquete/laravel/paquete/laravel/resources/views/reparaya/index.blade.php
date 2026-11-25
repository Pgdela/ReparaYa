<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ReparaYa - Panel principal</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f1f5f4; color: #263b39; }
        header { background: #176b62; color: white; padding: 22px 7%; display: flex; justify-content: space-between; align-items: center; }
        main { max-width: 950px; margin: 45px auto; padding: 0 20px; }
        .panel { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 3px 12px #00000010; }
        .acciones { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 25px; }
        a, button { display: inline-block; background: #176b62; color: white; padding: 13px 20px; border: 0; border-radius: 7px; text-decoration: none; cursor: pointer; font-size: 15px; }
        .salir { background: white; color: #176b62; }
        h1 { margin-top: 0; }
    </style>
</head>
<body>
    <header>
        <strong>ReparaYa</strong>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="salir" type="submit">Cerrar sesi&oacute;n</button>
        </form>
    </header>
    <main>
        <section class="panel">
            <h1>Bienvenido/a, {{ Auth::user()->name }}</h1>
            <p>Este es tu panel principal de ReparaYa.</p>
            <p>Desde aqu&iacute; podr&aacute;s acceder a los servicios de reparaci&oacute;n y consultar tus solicitudes.</p>
            <div class="acciones">
                <a href="{{ route('reparaya.solicitudes.create') }}">Solicitar una reparaci&oacute;n</a>
                <a href="{{ route('reparaya.solicitudes.index') }}">Mis reparaciones</a>
                <a href="{{ route('reparaya.perfil') }}">Mi perfil</a>
            </div>
        </section>
    </main>
</body>
</html>
