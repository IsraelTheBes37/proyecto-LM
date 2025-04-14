<?php
session_start();
if (!in_array($_SESSION['rol'], ['Informático', 'DirectorVentas'])) {
    header("Location: ../views/listaEmpleados.php");
    exit;
}

require_once '../conn.php';

$id = $_GET['id'] ?? null;

if (!$id) exit("ID no válido");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $cargo = $_POST['cargo'];
    $correo = $_POST['correo'];

    $stmt = $conn->prepare("UPDATE empleados SET nombre=?, cargo=?, correo=? WHERE num_empleado=?");
    $stmt->bind_param("sssi", $nombre, $cargo, $correo, $id);
    $stmt->execute();

    header("Location: ../views/listaEmpleados.php");
    exit;
} else {
    $stmt = $conn->prepare("SELECT * FROM empleados WHERE num_empleado=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $empleado = $stmt->get_result()->fetch_assoc();
}
?>

<h2>Editar Empleado</h2>
<form method="POST">
    Nombre: <input type="text" name="nombre" value="<?= $empleado['nombre'] ?>" required><br>
    Cargo: 
    <select name="cargo" required>
        <option <?= $empleado['cargo']=='Informático'?'selected':'' ?>>Informático</option>
        <option <?= $empleado['cargo']=='DirectorVentas'?'selected':'' ?>>DirectorVentas</option>
        <option <?= $empleado['cargo']=='Vendedor'?'selected':'' ?>>Vendedor</option>
    </select><br>
    Correo: <input type="email" name="correo" value="<?= $empleado['correo'] ?>" required><br>
    <button type="submit">Actualizar</button>
</form>
