<?php
header("Access-Control-Allow-Origin: *");

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mi_base_jose";

$conn = new mysqli($servername, $username, $password, $dbname);

// Función para autenticar usuario
function autenticarUsuario($usuario, $contrasena) {
    global $conn;
    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND contrasena = '$contrasena'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

// Función para registrar nuevo usuario
function registrarUsuario($nombre, $usuario, $contrasena, $nombreTienda, $fotoTienda) {
    global $conn;
    $sql = "INSERT INTO usuarios (nombre, usuario, contrasena, nombre_tienda, foto_tienda) VALUES ('$nombre', '$usuario', '$contrasena', '$nombreTienda', '$fotoTienda')";
    
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Funciones para el control de productos
function agregarProducto($nombre, $descripcion,$cantidad, $precioCosto, $precioVenta, $fotoProducto, $idUsuario) {
    global $conn;
    $sql = "INSERT INTO productos (nombre,descripcion,cantidad, precio_costo, precio_venta, url_foto, id_usuario) VALUES ('$nombre','$descripcion',$cantidad, $precioCosto, $precioVenta, '$fotoProducto', $idUsuario)";
    
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function editarProducto($idProducto,$nombre,$descripcion, $cantidad, $precioCosto, $precioVenta, $fotoProducto) {
    global $conn;
    $sql = "UPDATE productos SET nombre='$nombre',descripcion='$descripcion', cantidad = $cantidad, precio_costo = $precioCosto, precio_venta = $precioVenta, url_foto = '$fotoProducto' WHERE id = $idProducto";
    
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function eliminarProducto($idProducto) {
    global $conn;
    $sql = "DELETE FROM productos WHERE id = $idProducto";
    
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function obtenerProducto($idProducto) {
    global $conn;
    $sql = "SELECT * FROM productos WHERE id = $idProducto";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

function obtenerProductos($id_usuario) {
    global $conn;
    $sql = "SELECT * FROM productos where id_usuario=$id_usuario";
    $result = $conn->query($sql);
    
    $productos = array();
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    }
    
    return $productos;
}

// Manejar las solicitudes GET
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

switch ($accion) {
    case 'autenticar':
        $usuario = $_GET['usuario'];
        $contrasena = $_GET['contrasena'];
        $usuarioAutenticado = autenticarUsuario($usuario, $contrasena);
        
        if ($usuarioAutenticado) {
            echo json_encode($usuarioAutenticado);
        } else {
            echo "Usuario o contraseña incorrectos";
        }
        break;
    
    case 'registrar':
        $nombre = $_GET['nombre'];
        $usuario = $_GET['usuario'];
        $contrasena = $_GET['contrasena'];
        $nombreTienda = $_GET['nombreTienda'];
        $fotoTienda = $_GET['fotoTienda'];
        
        if (registrarUsuario($nombre, $usuario, $contrasena, $nombreTienda, $fotoTienda)) {
            echo "Usuario registrado exitosamente";
        } else {
            echo "Error al registrar el usuario";
        }
        break;
    
    case 'agregarProducto':
        $nombre = $_GET['nombre'];
        $descripcion = $_GET['descripcion'];
        $cantidad = $_GET['cantidad'];
        $precioCosto = $_GET['precioCosto'];
        $precioVenta = $_GET['precioVenta'];
        $fotoProducto = $_GET['url_foto'];
        $idUsuario = $_GET['idUsuario'];

        
        if (agregarProducto($nombre, $descripcion, $cantidad, $precioCosto, $precioVenta, $fotoProducto, $idUsuario)) {
            echo "Producto agregado exitosamente";
        } else {
            echo "Error al agregar el producto";
        }
        break;
    
    case 'editarProducto':
        $idProducto = $_GET['id'];
        $nombre = $_GET['nombre'];
        $descripcion= $_GET['descripcion'];
        $cantidad = $_GET['cantidad'];
        $precioCosto = $_GET['precioCosto'];
        $precioVenta = $_GET['precioVenta'];
        $fotoProducto = $_GET['url_foto'];
        
        if (editarProducto($idProducto,$nombre,$descripcion, $cantidad, $precioCosto, $precioVenta, $fotoProducto)) {
            echo "Producto editado exitosamente";
        } else {
            echo "Error al editar el producto";
        }
        break;
    
    case 'eliminarProducto':
        $idProducto = $_GET['idProducto'];
        
        if (eliminarProducto($idProducto)) {
            echo "Producto eliminado exitosamente";
        } else {
            echo "Error al eliminar el producto";
        }
        break;
    
    case 'obtenerProducto':
        $idProducto = $_GET['idProducto'];
        $producto = obtenerProducto($idProducto);
        
        if ($producto) {
            echo json_encode($producto);
        } else {
            echo "Producto no encontrado";
        }
        break;
    
    case 'obtenerProductos':
        $id_usuario = $_GET['id_usuario'];
        $productos = obtenerProductos($id_usuario);
        echo json_encode($productos);
        break;
    
    default:
        echo "Acción no válida";
}

$conn->close();
?>