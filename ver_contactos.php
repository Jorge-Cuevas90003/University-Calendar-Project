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

// Ejecutar la consulta utilizando PDO
try {
    $query = "SELECT * FROM Contactos";
    $statement = $pdo->query($query);
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ocurrió un error con la consulta: " . $e->getMessage();
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
    <title>Lista de Contactos</title>
</head>
<body>
    <center>
        <h1>Lista de contactos</h1>
    <div style="margin: 150px;">
    <table  class="table" border="1">
        <thead class="thead-dark">
        <tr>
            <th>E-mail</th>
            <th>Nombre</th>
            <th>Direccion</th>
            <th>Telefono</th>
            <th>Fecha de nacimiento</th>
            <th>Editar</th>
            <th>Eliminar</th>
        </tr>
        <?php
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($row['direccion']) . "</td>";
            echo "<td>" . htmlspecialchars($row['telefono']) . "</td>";
            echo "<td>" . htmlspecialchars($row['fecha_nacimiento']) . "</td>";
            echo "<td><center><button class='btn btn-danger'><i class='bi bi-pencil'></i></button></center>" . "</td>";
            echo "<td>
                <form action='eliminarContacto.php' method='post'>
                    <input type='hidden' name='email' value='" . $row["email"] . "'>
                    <button type='submit' class='btn btn-danger' value='email'>
                    <i class='bi bi-trash'></i>
                    </button>
                </form>
              </td>";
            echo "</tr>";
        }
        ?>
        </thead>
    </table>
    <a class="btn btn-primary" href="index.html" role="button">Regresar</a>
    </div>
    </center>
</body>
</html>
