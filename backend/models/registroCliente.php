<?php
// Conexión a la base de datos
require_once '../conn.php';

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$representante = !empty($_POST['representante']) ? $_POST['representante'] : null;
$calle = $_POST['calle'];
$porton = $_POST['porton'];
$num_piso = $_POST['num_piso'];
$cp = $_POST['cp'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];
$clave = $_POST['clave']; 

// Preparar e insertar
$stmt = $conn->prepare("INSERT INTO clientes (nombre, apellido, representante, calle, porton, num_piso, cp, telefono, correo, clave) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssisssssss", $nombre, $apellido, $representante, $calle, $porton, $num_piso, $cp, $telefono, $correo, $clave);

if ($stmt->execute()) {
    header("Location: ../../frontend/vistaInscripcion.html?status=ok");
} else {
    header("Location: ../../frontend/vistaInscripcion.html?status=error");
}

$stmt->close();
$conn->close();
?>
