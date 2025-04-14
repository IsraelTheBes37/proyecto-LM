<?php
session_start();
if ($_SESSION['rol'] !== 'Informático') {
    header("Location: ../views/listaEmpleados.php");
    exit;
}

require_once '../conn.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM empleados WHERE num_empleado=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: ../views/listaEmpleados.php");
exit;
