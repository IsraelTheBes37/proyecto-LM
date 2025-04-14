<?php
session_start();
if (!in_array($_SESSION['rol'], ['Informatico', 'DirectorVentas'])) {
    header("Location: ../views/listaEmpleados.php");
    exit;
}

require_once '../conn.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $cargo = $_POST['cargo'];
    $correo = $_POST['correo'];
    $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO empleados (nombre, cargo, correo, clave) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $cargo, $correo, $clave);
    $stmt->execute();

    header("Location: ../views/listaEmpleados.php");
    exit;
}
?>

<h2>Agregar Empleado</h2>
<form method="POST">
    Nombre: <input type="text" name="nombre" required><br>
    Cargo: 
    <select name="cargo" required>
        <option value="Informático">Informático</option>
        <option value="DirectorVentas">DirectorVentas</option>
        <option value="Vendedor">Vendedor</option>
        <option value="AdministradorContable">Administrador Contable</option>
        <option value="Marketing">Marketing</option>
    </select><br>
    Correo: <input type="email" name="correo" required><br>
    Clave: <input type="password" name="clave" required><br>
    <button type="submit">Guardar</button>
</form>
