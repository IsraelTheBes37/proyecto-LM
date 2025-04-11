<?php
session_start();

// Datos de conexión
require("../conn.php");

// Conexión a MySQL
$conn = new mysqli($servidor, $usuario, $contrasena, $nombreBD);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$correo = $_POST['correo'];
$clave = $_POST['clave'];

// Consulta segura
$stmt = $conn->prepare("SELECT num_empleado, nombre, clave FROM empleados WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $empleado = $result->fetch_assoc();
    
    // Comparar claves (en este ejemplo están en texto plano, pero lo ideal es usar hash)
    if ($clave === $empleado['clave']) {
        $_SESSION['empleado'] = $empleado['nombre'];
        header("Location: ../../frontend/vistaAdmin.php");
        exit();
    } else {
        echo "❌ Contraseña incorrecta";
    }
} else {
    echo "❌ Usuario no encontrado";
}

$stmt->close();
$conn->close();
?>
