<?php
include '../conn.php';

// Insertar nuevo empleado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insertar'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $cargo = $_POST['cargo'];
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];
    $administrador = $_POST['administrador'] !== '' ? $_POST['administrador'] : null;

    $sql = "INSERT INTO empleados (nombre, apellido, cargo, correo, clave, administrador)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $apellido, $cargo, $correo, $clave, $administrador);
    $stmt->execute();
    header("Location: listaEmpleados.php");
    exit();
}

// Eliminar empleado
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM empleados WHERE num_empleado = $id");
    header("Location: listaEmpleados.php");
    exit();
}

// Obtener datos para edición
$editarEmpleado = null;
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $result = $conn->query("SELECT * FROM empleados WHERE num_empleado = $id");
    $editarEmpleado = $result->fetch_assoc();
}

// Actualizar empleado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) {
    $id = $_POST['num_empleado'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $cargo = $_POST['cargo'];
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];
    $administrador = $_POST['administrador'] !== '' ? $_POST['administrador'] : null;

    if ($id == $administrador) $administrador = null; // No se puede ser su propio admin

    $sql = "UPDATE empleados SET nombre=?, apellido=?, cargo=?, correo=?, clave=?, administrador=? WHERE num_empleado=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssii", $nombre, $apellido, $cargo, $correo, $clave, $administrador, $id);
    $stmt->execute();
    header("Location: listaEmpleados.php");
    exit();
}

// Obtener empleados para mostrar
$empleados = $conn->query("
    SELECT e.*, a.nombre AS admin_nombre, a.apellido AS admin_apellido
    FROM empleados e
    LEFT JOIN empleados a ON e.administrador = a.num_empleado
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Empleados</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        form { margin-top: 20px; }
        input, select { padding: 5px; margin: 5px; }
    </style>
    <link rel="stylesheet" href="../../frontend/css/estiloTablas.css">
</head>
<body>
    <h1>Gestión de Empleados</h1>
    <button onclick="window.location.href='../../frontend/vistaAdmin.php';">Regresar</button>

    <h2><?php echo $editarEmpleado ? "Editar Empleado" : "Nuevo Empleado"; ?></h2>
    <form method="POST">
        <?php if ($editarEmpleado): ?>
            <input type="hidden" name="num_empleado" value="<?= $editarEmpleado['num_empleado'] ?>">
        <?php endif; ?>
        <input type="text" name="nombre" placeholder="Nombre" value="<?= $editarEmpleado['nombre'] ?? '' ?>" required>
        <input type="text" name="apellido" placeholder="Apellido" value="<?= $editarEmpleado['apellido'] ?? '' ?>" required>
        
        <select name="cargo" placeholder="Cargo" value="<?= $editarEmpleado['cargo'] ?? '' ?>" required>
            <option value="">Cargo</option>
            <option value="Informático">Informático</option>
            <option value="DirectorVentas">DirectorVentas</option>
            <option value="Vendedor">Vendedor</option>
            <option value="AdministradorContable">Administrador Contable</option>
            <option value="Marketing">Marketing</option>
        </select>
        <input type="email" name="correo" placeholder="Correo" value="<?= $editarEmpleado['correo'] ?? '' ?>" required>
        <input type="text" name="clave" placeholder="Clave" value="<?= $editarEmpleado['clave'] ?? '' ?>" required>
        <select name="administrador">
            <option value="">Sin administrador</option>
            <?php
            $opciones = $conn->query("SELECT num_empleado, nombre, apellido FROM empleados");
            while ($row = $opciones->fetch_assoc()):
                if ($editarEmpleado && $row['num_empleado'] == $editarEmpleado['num_empleado']) continue;
                $selected = ($editarEmpleado && $editarEmpleado['administrador'] == $row['num_empleado']) ? "selected" : "";
                echo "<option value='{$row['num_empleado']}' $selected>{$row['nombre']} {$row['apellido']}</option>";
            endwhile;
            ?>
        </select>
        <?php if ($editarEmpleado): ?>
            <button type="submit" name="actualizar">Actualizar</button>
        <?php else: ?>
            <button type="submit" name="insertar">Insertar</button>
        <?php endif; ?>
    </form>

    <h2>Lista de Empleados</h2>
    <table>
        <tr>
            <th>ID</th><th>Nombre</th><th>Apellido</th><th>Cargo</th><th>Correo</th><th>Administrador</th><th>Acciones</th>
        </tr>
        <?php while ($emp = $empleados->fetch_assoc()): ?>
            <tr>
                <td><?= $emp['num_empleado'] ?></td>
                <td><?= $emp['nombre'] ?></td>
                <td><?= $emp['apellido'] ?></td>
                <td><?= $emp['cargo'] ?></td>
                <td><?= $emp['correo'] ?></td>
                <td><?= $emp['admin_nombre'] ? $emp['admin_nombre'] . ' ' . $emp['admin_apellido'] : '—' ?></td>
                <td class="acciones">
                    <form action="" method="get" style="display: inline;">
                        <input type="hidden" name="editar" value="<?= $emp['num_empleado'] ?>">
                        <button type="submit" id="boton-editar">Editar</button>
                    </form>
                    <form action="" method="get" style="display: inline;" onsubmit="return confirm('¿Eliminar este empleado?')">
                        <input type="hidden" name="eliminar" value="<?= $emp['num_empleado'] ?>">
                        <button type="submit" class="boton boton-eliminar">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
