<?php

class IncidenciaController
{
    public function misIncidencias()
    {
        requiereCliente();

        $incidencias = Incidencia::listarPorCliente(
            (int) $_SESSION['user_id']
        );

        require __DIR__ . '/../views/cliente/incidencias.php';
    }

    public function nueva()
    {
        requiereCliente();

        $especialidades = Incidencia::listarEspecialidades();
        $errores = [];
        $fechaServicio = null;

        $datos = [
            'especialidad_id' => '',
            'descripcion' => '',
            'direccion' => '',
            'telefono_contacto' => '',
            'fecha_servicio' => '',
            'franja_horaria' => '',
            'tipo_urgencia' => 'Estándar'
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            foreach ($datos as $campo => $valor) {
                $datos[$campo] = trim($_POST[$campo] ?? $valor);
            }

            $especialidadId = filter_var(
                $datos['especialidad_id'],
                FILTER_VALIDATE_INT
            );

            if (
                !$especialidadId ||
                !Incidencia::especialidadActiva($especialidadId)
            ) {
                $errores[] = 'Selecciona una especialidad válida.';
            }

            if ($datos['descripcion'] === '') {
                $errores[] = 'Escribe una descripción de la avería.';
            }

            if ($datos['direccion'] === '' || strlen($datos['direccion']) > 255) {
                $errores[] = 'Introduce una dirección válida.';
            }

            if (
                $datos['telefono_contacto'] === '' ||
                !preg_match('/^[0-9+\s()-]{9,20}$/', $datos['telefono_contacto'])
            ) {
                $errores[] = 'Introduce un teléfono válido.';
            }

            if (!in_array($datos['franja_horaria'], ['Mañana', 'Tarde'], true)) {
                $errores[] = 'Selecciona una franja horaria válida.';
            }

            if (!in_array($datos['tipo_urgencia'], ['Estándar', 'Urgente'], true)) {
                $errores[] = 'Selecciona un tipo de urgencia válido.';
            }

            $fecha = DateTime::createFromFormat(
                '!Y-m-d',
                $datos['fecha_servicio']
            );

            if (
                !$fecha ||
                $fecha->format('Y-m-d') !== $datos['fecha_servicio']
            ) {
                $errores[] = 'Selecciona una fecha válida.';
            } else {

                $hora = $datos['franja_horaria'] === 'Mañana'
                    ? '09:00:00'
                    : '15:00:00';

                $fechaServicio = $datos['fecha_servicio'] . ' ' . $hora;

                $momentoServicio = new DateTime($fechaServicio);
                $ahora = new DateTime();

                if ($momentoServicio <= $ahora) {
                    $errores[] = 'La fecha del servicio debe ser futura.';
                }

                if (
                    $datos['tipo_urgencia'] === 'Estándar' &&
                    $momentoServicio->getTimestamp() - $ahora->getTimestamp() < 48 * 3600
                ) {
                    $errores[] = 'Las reparaciones estándar deben solicitarse con al menos 48 horas de antelación.';
                }
            }

            if (empty($errores)) {
                try {
                    Incidencia::crear(
                        (int) $_SESSION['user_id'],
                        (int) $especialidadId,
                        $datos['descripcion'],
                        $datos['direccion'],
                        $datos['telefono_contacto'],
                        $fechaServicio,
                        $datos['franja_horaria'],
                        $datos['tipo_urgencia']
                    );

                    $_SESSION['mensaje'] = 'La reparación se ha solicitado correctamente.';

                    header('Location: index.php?c=incidencias&a=misIncidencias');
                    exit;

                } catch (Throwable $error) {
                    error_log('Error al crear incidencia: ' . $error->getMessage());
                    $errores[] = 'No se ha podido guardar la solicitud.';
                }
            }
        }

        require __DIR__ . '/../views/cliente/nueva-incidencia.php';
    }

    public function detalle()
    {
        requiereCliente();

        $incidenciaId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$incidenciaId || $incidenciaId <= 0) {
            http_response_code(404);
            echo 'Reparación no encontrada.';
            exit;
        }

        $incidencia = Incidencia::obtenerPorIdYCliente(
            $incidenciaId,
            (int) $_SESSION['user_id']
        );

        if (!$incidencia) {
            http_response_code(404);
            echo 'Reparación no encontrada.';
            exit;
        }

        $historial = Incidencia::obtenerHistorial($incidenciaId);

        require __DIR__ . '/../views/cliente/detalle-incidencia.php';
    }

    public function administrar()
    {
        requiereAdmin();

        $incidencias = Incidencia::listarTodas();
        $tecnicos = Incidencia::listarTecnicosDisponibles();

        require __DIR__ . '/../views/admin/incidencias.php';
    }

    public function detalleAdmin()
    {
        requiereAdmin();

        $incidenciaId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$incidenciaId || $incidenciaId <= 0) {
            http_response_code(404);
            echo 'Reparación no encontrada.';
            exit;
        }

        $incidencia = Incidencia::obtenerPorIdAdmin($incidenciaId);

        if (!$incidencia) {
            http_response_code(404);
            echo 'Reparación no encontrada.';
            exit;
        }

        $historial = Incidencia::obtenerHistorial($incidenciaId);

        require __DIR__ . '/../views/admin/detalle-incidencia.php';
    }

    public function calendario()
    {
        requiereAdmin();

        $incidencias = Incidencia::listarParaCalendario();

        $vista = $_GET['vista'] ?? 'mes';

        if (!in_array($vista, ['mes', 'semana', 'dia'], true)) {
            $vista = 'mes';
        }

        $fecha = $_GET['fecha'] ?? date('Y-m-d');

        $fechaSeleccionada = DateTime::createFromFormat('!Y-m-d', $fecha);

        if (
            !$fechaSeleccionada ||
            $fechaSeleccionada->format('Y-m-d') !== $fecha
        ) {
            $fechaSeleccionada = new DateTime('today');
        }

        require __DIR__ . '/../views/admin/calendario.php';
    }

    public function asignar()
    {
        requiereAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?c=incidencias&a=administrar');
            exit;
        }

        $incidenciaId = filter_input(INPUT_POST, 'incidencia_id', FILTER_VALIDATE_INT);
        $tecnicoId = filter_input(INPUT_POST, 'tecnico_id', FILTER_VALIDATE_INT);

        if (
            !$incidenciaId ||
            !$tecnicoId ||
            $incidenciaId <= 0 ||
            $tecnicoId <= 0
        ) {
            $_SESSION['mensaje_admin'] = 'Selecciona una reparación y un técnico válidos.';
        } else {
            try {
                $resultado = Incidencia::asignarTecnico(
                    $incidenciaId,
                    $tecnicoId,
                    (int) $_SESSION['user_id']
                );

                $_SESSION['mensaje_admin'] = $resultado
                    ? 'El técnico se ha asignado correctamente.'
                    : 'No se ha podido realizar la asignación.';

            } catch (Throwable $error) {
                error_log('Error al asignar técnico: ' . $error->getMessage());
                $_SESSION['mensaje_admin'] = 'Se ha producido un error al asignar el técnico.';
            }
        }

        header('Location: index.php?c=incidencias&a=administrar');
        exit;
    }

    // Cancelar reparación desde el panel del cliente o administrador
    public function cancelar()
    {
        requiereLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit;
        }

        $incidenciaId = filter_input(
            INPUT_POST,
            'incidencia_id',
            FILTER_VALIDATE_INT
        );

        if (!$incidenciaId || $incidenciaId <= 0) {
            $_SESSION['mensaje'] = 'Reparación no válida.';
        } else {
            try {
                $resultado = Incidencia::cancelar(
                    $incidenciaId,
                    (int) $_SESSION['user_id'],
                    $_SESSION['rol']
                );

                $_SESSION['mensaje'] = $resultado
                    ? 'La reparación se ha cancelado correctamente.'
                    : 'No se puede cancelar esta reparación. Comprueba su estado y las condiciones de cancelación.';

            } catch (Throwable $error) {
                error_log('Error al cancelar reparación: ' . $error->getMessage());
                $_SESSION['mensaje'] = 'Se ha producido un error al cancelar la reparación.';
            }
        }

        if ($_SESSION['rol'] === 'admin') {
            header('Location: index.php?c=incidencias&a=administrar');
        } else {
            header('Location: index.php?c=incidencias&a=misIncidencias');
        }

        exit;
    }
}
