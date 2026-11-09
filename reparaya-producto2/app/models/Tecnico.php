<?php

class Tecnico
{
    public static function listarTodos(): array
    {
        $sql = "
            SELECT
                t.*,
                u.nombre,
                u.email,
                e.nombre AS especialidad
            FROM tecnicos t
            INNER JOIN usuarios u ON u.id = t.usuario_id
            LEFT JOIN especialidades e ON e.id = t.especialidad_id
            ORDER BY t.id DESC
        ";

        return Database::conectar()
            ->query($sql)
            ->fetchAll();
    }

    public static function crear(
        int $usuarioId,
        string $nombreCompleto,
        ?int $especialidadId = null
    ): bool {
        $sql = "
            INSERT INTO tecnicos
                (usuario_id, nombre_completo, especialidad_id, disponible)
            VALUES
                (:usuario_id, :nombre_completo, :especialidad_id, 1)
        ";

        $stmt = Database::conectar()->prepare($sql);

        return $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':nombre_completo' => $nombreCompleto,
            ':especialidad_id' => $especialidadId
        ]);
    }

    public static function listarEspecialidades(): array
    {
        $sql = "SELECT * FROM especialidades ORDER BY nombre";

        return Database::conectar()
            ->query($sql)
            ->fetchAll();
    }

    public static function obtenerPorUsuarioId(int $usuarioId): ?array
    {
        $sql = "
            SELECT
                t.*,
                e.nombre AS especialidad,
                e.precio_hora
            FROM tecnicos t
            LEFT JOIN especialidades e
                ON e.id = t.especialidad_id
            WHERE t.usuario_id = :usuario_id
            LIMIT 1
        ";

        $stmt = Database::conectar()->prepare($sql);

        $stmt->execute([
            ':usuario_id' => $usuarioId
        ]);

        $tecnico = $stmt->fetch();

        return $tecnico ?: null;
    }

    public static function listarIncidenciasAsignadas(int $tecnicoId): array
    {
        $sql = "
            SELECT *
            FROM incidencias
            WHERE tecnico_id = :tecnico_id
            ORDER BY fecha_creacion DESC
        ";

        $stmt = Database::conectar()->prepare($sql);

        $stmt->execute([
            ':tecnico_id' => $tecnicoId
        ]);

        return $stmt->fetchAll();
    }

    public static function obtenerIncidenciaAsignada(
        int $incidenciaId,
        int $tecnicoId
    ): ?array {
        $sql = "
            SELECT *
            FROM incidencias
            WHERE id = :incidencia_id
            AND tecnico_id = :tecnico_id
            LIMIT 1
        ";

        $stmt = Database::conectar()->prepare($sql);

        $stmt->execute([
            ':incidencia_id' => $incidenciaId,
            ':tecnico_id' => $tecnicoId
        ]);

        $incidencia = $stmt->fetch();

        return $incidencia ?: null;
    }

    public static function cambiarEstadoIncidencia(
        int $incidenciaId,
        int $tecnicoId,
        string $estado
    ): bool {
        $sql = "
            UPDATE incidencias
            SET estado = :estado
            WHERE id = :incidencia_id
            AND tecnico_id = :tecnico_id
        ";

        $stmt = Database::conectar()->prepare($sql);

        return $stmt->execute([
            ':estado' => $estado,
            ':incidencia_id' => $incidenciaId,
            ':tecnico_id' => $tecnicoId
        ]);
    }

    public static function obtenerHistorial(int $incidenciaId): array
    {
        $sql = "
            SELECT *
            FROM historial_incidencias
            WHERE incidencia_id = :incidencia_id
            ORDER BY fecha DESC
        ";

        $stmt = Database::conectar()->prepare($sql);

        $stmt->execute([
            ':incidencia_id' => $incidenciaId
        ]);

        return $stmt->fetchAll();
    }
}