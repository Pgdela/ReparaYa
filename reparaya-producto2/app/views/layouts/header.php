<nav>
    <ul>
        <li><a href="index.php?c=auth&a=inicio">Inicio</a></li>

        <?php if (!isset($_SESSION['rol'])): ?>
            <!-- Menú público -->
            <li><a href="index.php?c=auth&a=login">Iniciar Sesión</a></li>
            <li><a href="index.php?c=auth&a=registro">Registrarse</a></li>

        <?php elseif ($_SESSION['rol'] === 'cliente'): ?>
            <!-- Menú CLIENTE -->
            <li><a href="index.php?c=cliente&a=mis_avisos">Mis Avisos</a></li>
            <li><a href="index.php?c=cliente&a=nueva_solicitud">Nueva Solicitud</a></li>
            <li><a href="index.php?c=perfil&a=editar">Mi Perfil</a></li>
            <li><a href="index.php?c=auth&a=logout">Cerrar Sesión (<?= e($_SESSION['nombre']) ?>)</a></li>

        <?php elseif ($_SESSION['rol'] === 'tecnico'): ?>
            <!-- Menú TÉCNICO -->
            <li><a href="index.php?c=tecnico&a=agenda">Mi Agenda</a></li>
            <li><a href="index.php?c=perfil&a=editar">Mi Perfil</a></li>
            <li><a href="index.php?c=auth&a=logout">Cerrar Sesión (<?= e($_SESSION['nombre']) ?>)</a></li>

        <?php elseif ($_SESSION['rol'] === 'admin'): ?>
            <!-- Menú ADMINISTRADOR -->
            <li><a href="index.php?c=admin&a=dashboard">Panel Admin</a></li>
            <li><a href="index.php?c=admin&a=calendario">Calendario</a></li>
            <li><a href="index.php?c=admin&a=tecnicos">Técnicos</a></li>
            <li><a href="index.php?c=admin&a=servicios">Servicios</a></li>
            <li><a href="index.php?c=perfil&a=editar">Mi Perfil</a></li>
            <li><a href="index.php?c=auth&a=logout">Cerrar Sesión (<?= e($_SESSION['nombre']) ?>)</a></li>
        <?php endif; ?>
    </ul>
</nav>