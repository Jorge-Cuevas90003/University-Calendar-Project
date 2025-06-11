<?php
// 1. Incluir el archivo de configuración
require_once 'config.php';

// 2. Usar las constantes para conectar
try {
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error al conectar con la base de datos: ' . $e->getMessage();
    exit;
}
// Insertar un nuevo tipo de evento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $frecuencia = $_POST['frecuencia'];

    $stmt = $pdo->prepare('INSERT INTO T_eventos (nombre, frecuencia) VALUES (:nombre, :frecuencia)');
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':frecuencia', $frecuencia);

    if ($stmt->execute()) {
        // Redirigir al usuario al índice
        header('Location: index.html');
        exit;
    } else {
        echo 'Error al registrar el tipo de evento.';
    }
}
?>
