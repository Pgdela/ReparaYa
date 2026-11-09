<?php

class AuthController
{
    public function inicio()
    {
        requiereLogin();
        require __DIR__ . '/../views/home.php';
    }

    public function registro()
    {
        $errores = [];

        $datos = [
            'nombre' => '',
            'email' => '',
            'telefono' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos['nombre'] = trim($_POST['nombre'] ?? '');
            $datos['email'] = trim($_POST['email'] ?? '');
            $datos['telefono'] = trim($_POST['telefono'] ?? '');

            $password = $_POST['password'] ?? '';
            $password2 = $_POST['password2'] ?? '';

            if ($datos['nombre'] === '') {
                $errores[] = 'El nombre es obligatorio.';
            }

            if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'El correo no es válido.';
            }

            if ($password === '') {
                $errores[] = 'La contraseña es obligatoria.';
            }

            if ($password !== $password2) {
                $errores[] = 'Las contraseñas no coinciden.';
            }

            if (empty($errores)) {
                try {
                    if (Usuario::buscarPorEmail($datos['email'])) {
                        $errores[] = 'Este correo ya está registrado.';
                    } else {
                        Usuario::crear(
                            $datos['nombre'],
                            $datos['email'],
                            $password,
                            $datos['telefono'] ?: null
                        );

                        $_SESSION['mensaje'] =
                            'Registro completado. Ya puedes iniciar sesión.';

                        header('Location: index.php?c=auth&a=login');
                        exit;
                    }
                } catch (PDOException $e) {
                    $errores[] = 'No se ha podido completar el registro.';
                    error_log($e->getMessage());
                }
            }
        }

        require __DIR__ . '/../views/auth/registro.php';
    }

    public function login()
    {
        $errores = [];
        $email = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $usuario = Usuario::buscarPorEmail($email);

            if ($usuario && password_verify(
                $password,
                $usuario['password']
            )) {
                session_regenerate_id(true);

                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['rol'] = $usuario['rol'];
               echo 'ID USUARIO: ' . $_SESSION['user_id'];
echo '<br>ROL DETECTADO: ' . $_SESSION['rol'];
exit;

                if ($usuario['rol'] === 'admin') {
                    header('Location: index.php?c=auth&a=admin');
                } elseif ($usuario['rol'] === 'tecnico') {
                    header('Location: index.php?c=tecnicos&a=misIncidencias');
                } else {
                    header('Location: index.php?c=auth&a=cliente');
                }

                exit;
            }

            $errores[] = 'Correo o contraseña incorrectos.';
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    public function perfil()
    {
        requiereLogin();

        $errores = [];

        $usuario = Usuario::obtenerPorId(
            (int) $_SESSION['user_id']
        );

        if (!$usuario) {
            session_destroy();
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $password2 = $_POST['password2'] ?? '';

            if ($nombre === '') {
                $errores[] = 'El nombre es obligatorio.';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'El correo no es válido.';
            }

            if ($password !== '' && $password !== $password2) {
                $errores[] = 'Las contraseñas no coinciden.';
            }

            if (
                Usuario::emailExisteParaOtroUsuario(
                    $email,
                    (int) $_SESSION['user_id']
                )
            ) {
                $errores[] = 'Ese correo ya pertenece a otro usuario.';
            }

            if (empty($errores)) {
                try {
                    Usuario::actualizarPerfil(
                        (int) $_SESSION['user_id'],
                        $nombre,
                        $email,
                        $password
                    );

                    $_SESSION['nombre'] = $nombre;
                    $_SESSION['email'] = $email;

                    $_SESSION['mensaje'] =
                        'Tu perfil se ha actualizado correctamente.';

                    header('Location: index.php?c=auth&a=perfil');
                    exit;

                } catch (PDOException $e) {
                    error_log($e->getMessage());
                    $errores[] = 'No se ha podido actualizar el perfil.';
                }
            }

            $usuario['nombre'] = $nombre;
            $usuario['email'] = $email;
        }

        require __DIR__ . '/../views/auth/perfil.php';
    }

    public function admin()
    {
        requiereAdmin();
        require __DIR__ . '/../views/admin/inicio.php';
    }

    public function tecnico()
    {
        requiereTecnico();

        header('Location: index.php?c=tecnicos&a=misIncidencias');
        exit;
    }

    public function cliente()
    {
        requiereCliente();
        require __DIR__ . '/../views/cliente/inicio.php';
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();

        header('Location: index.php?c=auth&a=login');
        exit;
    }
}
