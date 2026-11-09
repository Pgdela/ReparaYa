<?php

class Usuario
{
    public static function buscarPorEmail(string $email): ?array
    {
        $st = Database::conectar()->prepare(
            'SELECT * FROM usuarios WHERE email = :email'
        );

        $st->execute([':email' => $email]);

        return $st->fetch() ?: null;
    }

    public static function obtenerPorId(int $id): ?array
    {
        $st = Database::conectar()->prepare(
            'SELECT id, nombre, email, telefono, rol
             FROM usuarios
             WHERE id = ?'
        );

        $st->execute([$id]);

        return $st->fetch() ?: null;
    }

    public static function crear(
        string $nombre,
        string $email,
        string $password,
        ?string $telefono
    ): int {
        $pdo = Database::conectar();

        $st = $pdo->prepare(
            "INSERT INTO usuarios
                (nombre, email, password, rol, telefono)
             VALUES
                (:nombre, :email, :password, 'particular', :telefono)"
        );

        $st->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':telefono' => $telefono,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function emailExisteParaOtroUsuario(
        string $email,
        int $usuarioId
    ): bool {
        $st = Database::conectar()->prepare(
            'SELECT id
             FROM usuarios
             WHERE email = ?
             AND id != ?
             LIMIT 1'
        );

        $st->execute([$email, $usuarioId]);

        return (bool) $st->fetch();
    }

    public static function actualizarPerfil(
        int $id,
        string $nombre,
        string $email,
        string $password = ''
    ): bool {
        $pdo = Database::conectar();

        if ($password !== '') {
            $st = $pdo->prepare(
                'UPDATE usuarios
                 SET nombre = ?, email = ?, password = ?
                 WHERE id = ?'
            );

            return $st->execute([
                $nombre,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                $id
            ]);
        }

        $st = $pdo->prepare(
            'UPDATE usuarios
             SET nombre = ?, email = ?
             WHERE id = ?'
        );

        return $st->execute([
            $nombre,
            $email,
            $id
        ]);
    }
}
