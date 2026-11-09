<?php

class Incidencia
{
    public static function listarPorCliente(int $clienteId): array
    {
        $pdo = Database::conectar();

        $sql = "SELECT *
                FROM incidencias
                WHERE cliente_id = ?
                ORDER BY fecha_servicio DESC";

        $consulta = $pdo->prepare($sql);
        $consulta->execute([$clienteId]);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarEspecialidades(): array
    {
        $pdo = Database::conectar();

        $sql = "SELECT id, nombre_especialidad, precio_base
                FROM especialidades
                WHERE activa = 1
                ORDER BY nombre_especialidad";

        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function especialidadActiva(int $especialidadId): bool
    {
        $pdo = Database::conectar();

        $sql = "SELECT id
                FROM especialidades
                WHERE id = ? AND activa = 1";

        $consulta = $pdo->prepare($sql);
        $consulta->execute([$especialidadId]);

        return (bool) $consulta->fetch();
    }

    public static function generarLocalizador(): string
    {
        return 'RY-' . strtoupper(bin2hex(random_bytes(5)));
    }

    public static function crear(
        int $clienteId,
        int $especialidadId,
        string $descripcion,
        string $direccion,
        string $telefono,
        string $fechaServicio,
        string $franjaHoraria,
        string $tipoUrgencia
    ): bool {
        $pdo = Database::conectar();

        try {
            $pdo->beginTransaction();

            $sql = "INSERT INTO incidencias (
                        localizador,
                        cliente_id,
                        especialidad_id,
                        descripcion,
                        direccion,
                        telefono_contacto,
                        fecha_servicio,
                        franja_horaria,
                        tipo_urgencia,
                        estado
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $consulta = $pdo->prepare($sql);

            $consulta->execute([
                self::generarLocalizador(),
                $clienteId,
                $especialidadId,
                $descripcion,
                $direccion,
                $telefono,
                $fechaServicio,
                $franjaHoraria,
                $tipoUrgencia,
                'Pendiente'
            ]);

            $incidenciaId = (int) $pdo->lastInsertId();

            $sqlHistorial = "INSERT INTO historial_estados (
                                incidencia_id,
                                estado_anterior,
                                estado_nuevo,
                                usuario_id,
                                observacion
                            ) VALUES (?, NULL, ?, ?, ?)";

            $consultaHistorial = $pdo->prepare($sqlHistorial);

            $consultaHistorial->execute([
                $incidenciaId,
                'Pendiente',
                $clienteId,
                'Solicitud creada por el cliente'
            ]);

            $pdo->commit();

            return true;

        } catch (Throwable $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $error;
        }
    }

    public static function obtenerPorIdYCliente(
        int $incidenciaId,
        int $clienteId
    ): ?array {
        $pdo = Database::conectar();

        $sql = "SELECT
                    i.*,
                    e.nombre_especialidad,
                    e.precio_base
                FROM incidencias i
                INNER JOIN especialidades e
                    ON i.especialidad_id = e.id
                WHERE i.id = ?
                  AND i.cliente_id = ?";

        $consulta = $pdo->prepare($sql);
        $consulta->execute([$incidenciaId, $clienteId]);

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    public static function obtenerPorIdAdmin(int $incidenciaId): ?array
    {
        $pdo = Database::conectar();

        $sql = "SELECT
                    i.*,
                    u.nombre AS nombre_cliente,
                    u.email AS email_cliente,
                    e.nombre_especialidad,
                    e.precio_base,
                    t.nombre_completo AS nombre_tecnico
                FROM incidencias i
                INNER JOIN usuarios u
                    ON i.cliente_id = u.id
                INNER JOIN especialidades e
                    ON i.especialidad_id = e.id
                LEFT JOIN tecnicos t
                    ON i.tecnico_id = t.id
                WHERE i.id = ?";

        $consulta = $pdo->prepare($sql);
        $consulta->execute([$incidenciaId]);

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    public static function obtenerHistorial(int $incidenciaId): array
    {
        $pdo = Database::conectar();

        $sql = "SELECT
                    h.estado_anterior,
                    h.estado_nuevo,
                    h.fecha_cambio,
                    h.observacion,
                    u.nombre AS responsable
                FROM historial_estados h
                INNER JOIN usuarios u
                    ON h.usuario_id = u.id
                WHERE h.incidencia_id = ?
                ORDER BY h.fecha_cambio ASC";

        $consulta = $pdo->prepare($sql);
        $consulta->execute([$incidenciaId]);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarTodas(): array
    {
        $pdo = Database::conectar();

        $sql = "SELECT
                    i.*,
                    u.nombre AS nombre_cliente,
                    e.nombre_especialidad
                FROM incidencias i
                INNER JOIN usuarios u
                    ON i.cliente_id = u.id
                INNER JOIN especialidades e
                    ON i.especialidad_id = e.id
                ORDER BY i.fecha_servicio DESC";

        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarTecnicosDisponibles(): array
    {
        $pdo = Database::conectar();

        $sql = "SELECT
                    id,
                    nombre_completo,
                    especialidad_id
                FROM tecnicos
                WHERE disponible = 1
                ORDER BY nombre_completo";

        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function asignarTecnico(
        int $incidenciaId,
        int $tecnicoId,
        int $adminId
    ): bool {
        $pdo = Database::conectar();

        try {
            $pdo->beginTransaction();

            $sql = "SELECT estado
                    FROM incidencias
                    WHERE id = ?
                    FOR UPDATE";

            $consulta = $pdo->prepare($sql);
            $consulta->execute([$incidenciaId]);

            $incidencia = $consulta->fetch();

            if (
                !$incidencia ||
                !in_array($incidencia['estado'], ['Pendiente', 'Asignada'], true)
            ) {
                $pdo->rollBack();
                return false;
            }

            $sql = "SELECT id
                    FROM tecnicos
                    WHERE id = ? AND disponible = 1";

            $consulta = $pdo->prepare($sql);
            $consulta->execute([$tecnicoId]);

            if (!$consulta->fetch()) {
                $pdo->rollBack();
                return false;
            }

            $estadoAnterior = $incidencia['estado'];

            $sql = "UPDATE incidencias
                    SET tecnico_id = ?, estado = 'Asignada'
                    WHERE id = ?";

            $consulta = $pdo->prepare($sql);
            $consulta->execute([$tecnicoId, $incidenciaId]);

            $sql = "INSERT INTO historial_estados (
                        incidencia_id,
                        estado_anterior,
                        estado_nuevo,
                        usuario_id,
                        observacion
                    ) VALUES (?, ?, 'Asignada', ?, ?)";

            $consulta = $pdo->prepare($sql);
            $consulta->execute([
                $incidenciaId,
                $estadoAnterior,
                $adminId,
                'Técnico asignado por el administrador'
            ]);

            $pdo->commit();

            return true;

        } catch (Throwable $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $error;
        }
    }

    public static function listarParaCalendario(): array
    {
        $pdo = Database::conectar();

        $sql = "SELECT
                    i.id,
                    i.localizador,
                    i.fecha_servicio,
                    i.franja_horaria,
                    i.tipo_urgencia,
                    i.estado,
                    u.nombre AS nombre_cliente,
                    e.nombre_especialidad,
                    t.nombre_completo AS nombre_tecnico
                FROM incidencias i
                INNER JOIN usuarios u
                    ON i.cliente_id = u.id
                INNER JOIN especialidades e
                    ON i.especialidad_id = e.id
                LEFT JOIN tecnicos t
                    ON i.tecnico_id = t.id
                ORDER BY i.fecha_servicio ASC";

        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cancelar una reparación y registrar el cambio en el historial
    public static function cancelar(
        int $incidenciaId,
        int $usuarioId,
        string $rol
    ): bool {
        $pdo = Database::conectar();

        try {
            $pdo->beginTransaction();

            $sql = "SELECT id, cliente_id, estado, fecha_servicio
                    FROM incidencias
                    WHERE id = ?
                    FOR UPDATE";

            $consulta = $pdo->prepare($sql);
            $consulta->execute([$incidenciaId]);

            $incidencia = $consulta->fetch(PDO::FETCH_ASSOC);

            if (!$incidencia) {
                $pdo->rollBack();
                return false;
            }

            if (
                !in_array($incidencia['estado'], ['Pendiente', 'Asignada'], true)
            ) {
                $pdo->rollBack();
                return false;
            }

            if ($rol === 'particular') {
                if ((int) $incidencia['cliente_id'] !== $usuarioId) {
                    $pdo->rollBack();
                    return false;
                }

                $fechaServicio = strtotime($incidencia['fecha_servicio']);

                if ($fechaServicio - time() <= 48 * 3600) {
                    $pdo->rollBack();
                    return false;
                }

            } elseif ($rol !== 'admin') {
                $pdo->rollBack();
                return false;
            }

            $estadoAnterior = $incidencia['estado'];

            $sql = "UPDATE incidencias
                    SET estado = 'Cancelada'
                    WHERE id = ?";

            $consulta = $pdo->prepare($sql);
            $consulta->execute([$incidenciaId]);

            $sql = "INSERT INTO historial_estados (
                        incidencia_id,
                        estado_anterior,
                        estado_nuevo,
                        usuario_id,
                        observacion
                    ) VALUES (?, ?, 'Cancelada', ?, ?)";

            $consulta = $pdo->prepare($sql);
            $consulta->execute([
                $incidenciaId,
                $estadoAnterior,
                $usuarioId,
                'Reparación cancelada por ' .
                    ($rol === 'admin' ? 'el administrador' : 'el cliente')
            ]);

            $pdo->commit();

            return true;

        } catch (Throwable $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $error;
        }
    }
}
