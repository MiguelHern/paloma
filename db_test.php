<?php

try {
    // Configuración de la base de datos
    $host = 'localhost';
    $db = 'paloma';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    // Cadena de conexión DSN
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    // Crear la conexión
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "Conexión exitosa a la base de datos.";

} catch (PDOException $e) {
    echo "Error al conectar a la base de datos: " . $e->getMessage();
}
