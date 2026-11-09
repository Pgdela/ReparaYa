<?php

class TecnicoController
{
    /**
     * Comprueba el rol real del usuario en la base de datos
     * y actualiza el rol guardado en la sesión.
     */
    private function comprobarRol(array $rolesPermitidos): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        $pdo = Database::conectar();

        $stmt = $pdo->prepare(
            'SELECT id, rol FROM usuarios WHERE id = :id LIMIT 1'
        );

        $stmt->execute([
            ':id' => (int) $_SESSION['user_id']
        ]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            session_destroy();
            header('Location: index.php?c=auth&a=login');
            exit;
        }

        // Actualizar el rol de la sesión con el valor real de MySQL.
        $_SESSION['rol'] = $usuario['rol'];

        if (!in_array($usuario['rol'], $rolesPermitidos, true)) {
            http_response_code(403);
            exit('No tienes permiso para acceder a esta página.');
        }
    }

    /**
     * Gestión de técnicos del administrador.
     */
    public function administrar()
    {
        $this->comprobarRol(['admin']);

        $errores = [];

        $datos = [
            'nombre' => '',
            'email' => '',
            'especialidad_id' => ''
        ];

        $tecnicos = Tecnico::listarTodos();
        $especialidades = Tecnico::listarEspecialidades();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            foreach ($datos as $campo => $valor) {
                $datos[$campo] = trim($_POST[$campo] ?? '');
            }

            $password = $_POST['password'] ?? '';

            if ($datos['nombre'] === '') {
                $errores[] = 'Introduce el nombre del técnico.';
            } elseif (strlen($datos['nombre']) > 100) {
                $errores[] = 'El nombre es demasiado largo.';
            }

            if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'Introduce un correo electrónico válido.';
            }

            if (strlen($password) < 8) {
                $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
            }

            $especialidadId = filter_var(
                $datos['especialidad_id'],
                FILTER_VALIDATE_INT
            );

            $especialidadValida = false;

            foreach ($especialidades as $especialidad) {
                if ((int) $especialidad['id'] === (int) $especialidadId) {
                    $especialidadValida = true;
                    break;
                }
            }

            if (!$especialidadId || !$especialidadValida) {
                $errores[] = 'Selecciona una especialidad válida.';
            }

            if (empty($errores)) {
                $pdo = Database::conectar();

                try {
                    $pdo->beginTransaction();

                    // Crear primero la cuenta de usuario.
                    $stmt = $pdo->prepare(
                        'INSERT INTO usuarios
                        (nombre, email, password, rol)
                        VALUES (:nombre, :email, :password, :rol)'
                    );

                    $stmt->execute([
                        ':nombre' => $datos['nombre'],
                        ':email' => $datos['email'],
                        ':password' => password_hash(
                            $password,
                            PASSWORD_DEFAULT
                        ),
                        ':rol' => 'tecnico'
                    ]);

                    $usuarioId = (int) $pdo->lastInsertId();

                    // Crear el perfil de técnico asociado a esa cuenta.
                    $resultado = Tecnico::crear(
                        $usuarioId,
                        $datos['nombre'],
                        (int) $especialidadId
                    );

                    if (!$resultado) {
                        throw new RuntimeException(
                            'No se pudo crear el perfil del técnico.'
                        );
                    }

                    $pdo->commit();

                    $_SESSION['mensaje_tecnico'] =
                        'El técnico se ha registrado correctamente.';

                    header('Location: index.php?c=tecnicos&a=administrar');
                    exit;

                } catch (Throwable $error) {

                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }

                    error_log(
                        'Error al registrar técnico: ' .
                        $error->getMessage()
                    );

                    $errores[] =
                        'No se ha podido registrar el técnico. Comprueba que el correo no esté registrado.';
                }
            }

            $tecnicos = Tecnico::listarTodos();
        }

        require __DIR__ . '/../views/admin/tecnicos.php';
    }

    /**
     * Mostrar la agenda del técnico conectado.
     */
    public function misIncidencias()
    {
        $this->comprobarRol(['tecnico']);

        $tecnico = Tecnico::obtenerPorUsuarioId(
            (int) $_SESSION['user_id']
        );

        if (!$tecnico) {
            http_response_code(403);
            exit('No existe un perfil de técnico asociado a esta cuenta.');
        }

        $incidencias = Tecnico::listarIncidenciasAsignadas(
            (int) $tecnico['id']
        );

        require __DIR__ . '/../views/tecnico/inicio.php';
    }

    /**
     * Mostrar el detalle de una reparación propia.
     */
    public function detalle()
    {
        $this->comprobarRol(['tecnico']);

        $tecnico = Tecnico::obtenerPorUsuarioId(
            (int) $_SESSION['user_id']
        );

        if (!$tecnico) {
            http_response_code(403);
            exit('No existe un perfil de técnico asociado a esta cuenta.');
        }

        $incidenciaId = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        if (!$incidenciaId || $incidenciaId < 1) {
            http_response_code(400);
            exit('Identificador de reparación no válido.');
        }

        $incidencia = Tecnico::obtenerIncidenciaAsignada(
            $incidenciaId,
            (int) $tecnico['id']
        );

        if (!$incidencia) {
            http_response_code(404);
            exit('Reparación no encontrada o no asignada a este técnico.');
        }

        $historial = Tecnico::obtenerHistorial($incidenciaId);

        require __DIR__ . '/../views/tecnico/detalle-incidencia.php';
    }

    /**
     * Actualizar el estado de una reparación propia.
     */
    public function cambiarEstado()
    {
        $this->comprobarRol(['tecnico']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método no permitido.');
        }

        $tecnico = Tecnico::obtenerPorUsuarioId(
            (int) $_SESSION['user_id']
        );

        if (!$tecnico) {
            http_response_code(403);
            exit('No existe un perfil de técnico asociado a esta cuenta.');
        }

        $incidenciaId = filter_var(
            $_POST['incidencia_id'] ?? '',
            FILTER_VALIDATE_INT
        );

        $nuevoEstado = trim($_POST['nuevo_estado'] ?? '');
        $observacion = trim($_POST['observacion'] ?? '');

        if (
            !$incidenciaId ||
            $incidenciaId < 1 ||
            !in_array($nuevoEstado, ['En curso', 'Finalizada'], true) ||
            strlen($observacion) > 255
        ) {
            $_SESSION['mensaje_tecnico_panel'] =
                'Los datos enviados no son válidos.';

            header('Location: index.php?c=tecnicos&a=misIncidencias');
            exit;
        }

        try {
            $resultado = Tecnico::cambiarEstadoIncidencia(
                (int) $incidenciaId,
                (int) $tecnico['id'],
                $nuevoEstado
            );

            $_SESSION['mensaje_tecnico_panel'] = $resultado
                ? 'El estado de la reparación se ha actualizado correctamente.'
                : 'No se puede realizar ese cambio de estado.';

        } catch (Throwable $error) {

            error_log(
                'Error al cambiar estado: ' . $error->getMessage()
            );

            $_SESSION['mensaje_tecnico_panel'] =
                'No se ha podido actualizar el estado.';
        }

        header(
            'Location: index.php?c=tecnicos&a=detalle&id=' . $incidenciaId
        );
        exit;
    }
}
