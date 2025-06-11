<center>
<?php

// 1. Incluir el archivo de configuración
require_once 'config.php';

// 2. Usar las constantes para conectar
$conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASSWORD;
$conn = pg_connect($conn_string);

if (!$conn) {
    echo "Ocurrió un error con la conexión.\n";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Eliminar contacto</title>
</head>
<?php
if(isset($_POST["email"])) {
    $email = $_POST["email"];

    $eliminar = "DELETE FROM Contactos WHERE email = $1";
    $result = pg_query_params($conn, $eliminar, array($email));

    if ($result) {
        echo "<center>";
        echo "<div style='margin: 250px;'>";
        echo "<h1>Contacto eliminado exitosamente.</h1>";
        echo "<a class='btn btn-primary' href='index.html' role='button'>Regresar</a>";
        echo "</div>";
        echo "</center";
    } else {
        echo "Error al eliminar contacto: ";
    }
}

// Cerrar la conexión
pg_close($conn);
?>
</html>

</center>