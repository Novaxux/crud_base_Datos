<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mi_base_jose";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    $response = array(
        'status' => 'error',
        'message' => 'Error de conexión: ' . $conn->connect_error
    );
    echo json_encode($response);
    exit;
}

// Obtener los datos del método GET
$nombre = $_GET['nombre'];
$usuario = $_GET['usuario'];
$contrasena = $_GET['contrasena'];

// Preparar la consulta SQL
$sql = "INSERT INTO usuarios (nombre, usuario, contrasena) VALUES ('$nombre', '$usuario', '$contrasena')";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    $response = array(
        'status' => 'ok',
        'message' => 'Usuario agregado correctamente'
    );
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Error: ' . $sql . '<br>' . $conn->error
    );
}

// Devolver la respuesta en formato JSON
echo json_encode($response);

// Cerrar la conexión
$conn->close();
?>