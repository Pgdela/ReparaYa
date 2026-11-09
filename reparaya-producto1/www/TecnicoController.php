<?php

class TecnicoController
{
    // Gestión de técnicos del administrador
    public function administrar()
    {
        requiereAdmin();

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
                try {
                    $resultado = Tecnico::crear(
                        $datos['nombre'],
                        $datos['email'],
                        $password,
                        (int) $especialidadId
                    );

                    if ($resultado) {
                        $_SESSION['mensaje_tecnico'] =
                            'El técnico se ha registrado correctamente.';

                        header('Location: index.php?c=tecnicos&a=administrar');
                        exit;
                    }

                    $errores[] =
                        'Ya existe una cuenta con ese correo electrónico.';

                } catch (Throwable $error) {
                    error_log(
                        'Error al registrar técnico: ' . $error->getMessage()
                    );

                    $errores[] = 'No se ha podido registrar el técnico.';
                }
            }

            $tecnicos = Tecnico::listarTodos();
        }

        require __DIR__ . '/../views/admin/tecnicos.php';
    }

    // Mostrar la agenda del técnico conectado
    public function misIncidencias()
    {
        requiereTecnico();

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

    // Mostrar el detalle de una reparación propia
    public function detalle()
    {
        requiereTecnico();

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

    // Actualizar el estado de una reparación propia
    public function cambiarEstado()
    {
        requiereTecnico();

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
            $resultado = Tecnico::cambiarEstado(
                $incidenciaId,
                (int) $tecnico['id'],
                (int) $_SESSION['user_id'],
                $nuevoEstado,
                $observacion
            );

            $_SESSION['mensaje_tecnico_panel'] = $resultado
                ? 'El estado de la reparación se ha actualizado correctamente.'
                : 'No se puede realizar ese cambio de estado.';

        } catch (Throwable $error) {
            error_log(
                'Error al cambiar estado de reparación: ' . $error->getMessage()
            );

            $_SESSION['mensaje_tecnico_panel'] =
                'No se ha podido actualizar el estado.';
        }

        header('Location: index.php?c=tecnicos&a=detalle&id=' . $incidenciaId);
        exit;
    }
}
