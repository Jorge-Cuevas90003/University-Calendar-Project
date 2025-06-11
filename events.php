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
// Obtener eventos
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['action'] == 'fetch_events') {
    $sql = "SELECT id_evento, titulo_evento AS title, descripcion, fecha AS start, hora, email AS contacto, id_tipo FROM Eventos";
    $result = pg_query($conn, $sql);

    if (!$result) {
        echo json_encode(["error" => "Error en la consulta: " . pg_last_error()]);
        exit;
    }

    $events = [];
    while ($row = pg_fetch_assoc($result)) {
        $row['start'] = $row['start'] . 'T' . $row['hora'];
        $events[] = $row;
    }

    pg_free_result($result);
    echo json_encode($events);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['action'] == 'fetch_tipos_eventos') {
    $sql = "SELECT id_tipo AS value, nombre AS text FROM T_eventos";
    $result = pg_query($conn, $sql);

    if (!$result) {
        echo json_encode(["error" => "Error en la consulta: " . pg_last_error()]);
        exit;
    }

    $tipos = [];
    while ($row = pg_fetch_assoc($result)) {
        $tipos[] = $row;
    }

    pg_free_result($result);
    echo json_encode($tipos);
    exit;
}


// Obtener contactos
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['action'] == 'fetch_contactos') {
    $sql = "SELECT email AS value, nombre AS text FROM Contactos";
    $result = pg_query($conn, $sql);

    if (!$result) {
        echo json_encode(["error" => "Error en la consulta: " . pg_last_error()]);
        exit;
    }

    $contactos = [];
    while ($row = pg_fetch_assoc($result)) {
        $contactos[] = $row;
    }

    pg_free_result($result);
    echo json_encode($contactos);
    exit;
}

// Guardar eventos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['action'] == 'save_event') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id_evento = isset($data['id_evento']) ? pg_escape_string($conn, $data['id_evento']) : null;
    $title = pg_escape_string($conn, $data['title']);
    $id_tipo = pg_escape_string($conn, $data['id_tipo']);
    $descripcion = pg_escape_string($conn, $data['descripcion']);
    $start = pg_escape_string($conn, $data['start']);
    $hora = pg_escape_string($conn, $data['hora']);
    $contacto = pg_escape_string($conn, $data['contacto']);

    if ($id_evento) {
        $sql = "UPDATE Eventos SET titulo_evento = '$title', id_tipo = '$id_tipo', descripcion = '$descripcion', fecha = '$start', hora = '$hora', email = '$contacto' WHERE id_evento = $id_evento";
    } else {
        $sql = "INSERT INTO Eventos (titulo_evento, id_tipo, descripcion, fecha, hora, email) VALUES ('$title', '$id_tipo', '$descripcion', '$start', '$hora', '$contacto')";
    }

    $result = pg_query($conn, $sql);

    if (!$result) {
        echo json_encode(["error" => "Error en la consulta: " . pg_last_error()]);
    } else {
        echo json_encode(["success" => true]);
    }

    pg_close($conn);
    exit;
}

// Eliminar eventos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']) && $_GET['action'] == 'delete_event') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id_evento = null;

    // Buscar el ID del evento usando la descripción y el título recibidos
    $descripcion = pg_escape_string($conn, $data['descripcion']);
    $title = pg_escape_string($conn, $data['title']);
    $sql = "SELECT id_evento FROM Eventos WHERE descripcion = '$descripcion' AND titulo_evento = '$title'";
    $result = pg_query($conn, $sql);

    if ($row = pg_fetch_assoc($result)) {
        $id_evento = $row['id_evento'];
    }

    // Si se encontró el ID del evento, eliminarlo
    if ($id_evento !== null) {
        $sql = "DELETE FROM Eventos WHERE id_evento = $id_evento";
        $result = pg_query($conn, $sql);

        if (!$result) {
            echo json_encode(["error" => "Error en la consulta: " . pg_last_error()]);
        } else {
            echo json_encode(["success" => true]);
        }
    } else {
        echo json_encode(["error" => "No se encontró el evento correspondiente a la descripción y el título proporcionados"]);
    }

    pg_close($conn);
    exit;
}
?>
