<?php

require_once __DIR__ . '/config/database.php';

try {

    $resultado = $pdo->query("SHOW TABLES");

    echo "<h1>Conexión correcta con MySQL</h1>";

    echo "<h2>Tablas de ReparaYa:</h2>";

    while ($tabla = $resultado->fetch(PDO::FETCH_NUM)) {
        echo "<p>" . htmlspecialchars($tabla[0]) . "</p>";
    }

} catch (PDOException $e) {

    echo "Error al consultar la base de datos.";

}