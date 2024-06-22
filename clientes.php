<?php
header("Access-Control-Allow-Origin: *");

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mi_base_jose";

$conn = new mysqli($servername, $username, $password, $dbname);

// Funciones para el control de clientes
function agregarCliente($nombre, $direccion, $telefono, $correo, $urlFoto, $idUsuario) {
    global $conn;
    $sql = "INSERT INTO clientes (nombre, direccion, telefono, correo, url_foto, id_usuario) VALUES ('$nombre', '$direccion', '$telefono', '$correo', '$urlFoto', $idUsuario)";
    
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function editarCliente($idCliente, $nombre, $direccion, $telefono, $correo, $urlFoto) {
    global $conn;
    $sql = "UPDATE clientes SET nombre='$nombre', direccion='$direccion', telefono='$telefono', correo='$correo', url_foto='$urlFoto' WHERE id = $idCliente";
    
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function eliminarCliente($idCliente) {
    global $conn;
    $sql = "DELETE FROM clientes WHERE id = $idCliente";
    
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function obtenerCliente($idCliente) {
    global $conn;
    $sql = "SELECT * FROM clientes WHERE id = $idCliente";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

function obtenerClientes($id_usuario) {
    global $conn;
    $sql = "SELECT * FROM clientes WHERE id_usuario = $id_usuario";
    $result = $conn->query($sql);
    
    $clientes = array();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $clientes[] = $row;
        }
    }
    
    return $clientes;
}

// Manejar las solicitudes GET
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

switch ($accion) {
    case 'agregarCliente':
        $nombre = $_GET['nombre'];
        $direccion = $_GET['direccion'];
        $telefono = $_GET['telefono'];
        $correo = $_GET['correo'];
        $urlFoto = $_GET['url_foto'];
        $idUsuario = $_GET['idUsuario'];
        
        if (agregarCliente($nombre, $direccion, $telefono, $correo, $urlFoto, $idUsuario)) {
            echo "Cliente agregado exitosamente";
        } else {
            echo "Error al agregar el cliente";
        }
        break;
    
    case 'editarCliente':
        $idCliente = $_GET['id'];
        $nombre = $_GET['nombre'];
        $direccion = $_GET['direccion'];
        $telefono = $_GET['telefono'];
        $correo = $_GET['correo'];
        $urlFoto = $_GET['url_foto'];
        
        if (editarCliente($idCliente, $nombre, $direccion, $telefono, $correo, $urlFoto)) {
            echo "Cliente editado exitosamente";
        } else {
            echo "Error al editar el cliente";
        }
        break;
    
    case 'eliminarCliente':
        $idCliente = $_GET['idCliente'];
        
        if (eliminarCliente($idCliente)) {
            echo "Cliente eliminado exitosamente";
        } else {
            echo "Error al eliminar el cliente";
        }
        break;
    
    case 'obtenerCliente':
        $idCliente = $_GET['idCliente'];
        $cliente = obtenerCliente($idCliente);
        
        if ($cliente) {
            echo json_encode($cliente);
        } else {
            echo "Cliente no encontrado";
        }
        break;
    
    case 'obtenerClientes':
        $id_usuario = $_GET['id_usuario'];
        $clientes = obtenerClientes($id_usuario);
        echo json_encode($clientes);
        break;
    
    default:
        echo "Acción no válida";
}

$conn->close();
?>
