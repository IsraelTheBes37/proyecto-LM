<?php
session_start();

// Datos de conexión
include "../conn.php";

// Obtener datos del formulario
$correo = $_POST['correo'];
$clave = $_POST['clave'];

// Verificar si es empleado
$sqlEmpleado = "SELECT * FROM empleados WHERE correo = ? AND clave = ?";
$stmtEmpleado = $conn->prepare($sqlEmpleado);
$stmtEmpleado->bind_param("ss", $correo, $clave);
$stmtEmpleado->execute();
$resultEmpleado = $stmtEmpleado->get_result();

if ($resultEmpleado->num_rows > 0) {
    $empleado = $resultEmpleado->fetch_assoc();
    $_SESSION['nombre'] = $empleado['nombre'];
    $_SESSION['cargo'] = $empleado['cargo'];

    if ($empleado['cargo'] === 'Informatico') {
        header("Location: ../../frontend/vistaAdmin.php");
    } elseif ($empleado['cargo'] === 'DirectorVentas') {
        header("Location: ../../frontend/vistaAdmin.php");
    } elseif ($empleado['cargo'] === 'Vendedor') {
        header("Location: ../../frontend/vistaAdmin.php");
    } else {
        echo "Cargo no autorizado.";
        echo '<br><button onclick="window.location.href=\'../../frontend/loginVista.php\';">Regresar</button>';
    }
    exit;
}

// Verificar si es cliente
$sqlCliente = "SELECT * FROM clientes WHERE correo = ? AND clave = ?";
$stmtCliente = $conn->prepare($sqlCliente);
$stmtCliente->bind_param("ss", $correo, $clave);
$stmtCliente->execute();
$resultCliente = $stmtCliente->get_result();

if ($resultCliente->num_rows > 0) {
    $cliente = $resultCliente->fetch_assoc();
    $_SESSION['nombre'] = $cliente['nombre'];
    $_SESSION['cliente'] = [
        'num_cliente' => $cliente['num_cliente'],
        'nombre' => $cliente['nombre'],
        'apellido' => $cliente['apellido'],
        'correo' => $cliente['correo']
    ];
    header("Location: ../../frontend/vistaCliente.php");
    exit;
}

// Si no coincide
header("Location: ../../frontend/loginVista.php?error=1");
exit;
//echo "Correo o clave incorrectos.";
?>