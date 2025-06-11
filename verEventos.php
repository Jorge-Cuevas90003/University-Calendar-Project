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

// Ejecutar la consulta
$result = pg_query($conn, "SELECT * FROM T_eventos");

if (!$result) {
    echo "Ocurrió un error con la consulta.\n";
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
    <title>Lista de tipos de eventos</title>
</head>
<body>
    <center>
        <h1>Lista de tipos de eventos</h1>
    <div style="margin: 150px;">
    <table  class="table" border="1">
        <thead class="thead-dark">
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Frecuencia</th>
            <th>Editar</th>
            <th>Eliminar</th>
        </tr>
        <?php
        while ($row = pg_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id_tipo']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($row['frecuencia']) . "</td>";
            echo "<td><center><button class='btn btn-danger'><i class='bi bi-pencil'></i></button></center>" . "</td>";
            echo "<td>
                <form action='eliminarEvento.php' method='post'>
                    <input type='hidden' name='id_tipo' value='" . $row["id_tipo"] . "'>
                    <center><button type='submit' class='btn btn-danger' value='id_tipo'>
                    <i class='bi bi-trash'></i>
                    </button></center>
                </form>
              </td>";
            echo "</tr>";
        }
        pg_close($conn);
        ?>
        </thead>
    </table>
    <a class="btn btn-primary" href="RegistroE.html" role="button">Regresar</a>
    </div>
    </center>
</body>
</html>
