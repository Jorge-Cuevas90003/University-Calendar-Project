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

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $email = $_POST['email'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];

    // Preparar la consulta SQL para insertar un nuevo contacto
    $sql = 'INSERT INTO Contactos (email, nombre, direccion, telefono, fecha_nacimiento) VALUES (:email, :nombre, :direccion, :telefono, :fecha_nacimiento)';
    $stmt = $pdo->prepare($sql);

    // Vincular parámetros
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':direccion', $direccion);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);

    // Ejecutar la consulta
    try {
        $stmt->execute();
         // Redirigir al usuario al índice
        header('Location: index.html');
        exit;
    } catch (PDOException $e) {
        echo 'Error al registrar el contacto: ' . $e->getMessage();
    }
}
?>
