<?php
session_start();
require '../conn.php';

$cargo = $_SESSION['cargo'];
$permiteEliminar = ($cargo === "Informatico");
$permiteEditar = in_array($cargo, ["Informatico", "DirectorVentas"]);
$permiteCrear = in_array($cargo, ["Informatico", "DirectorVentas"]);

$busqueda = $_GET['busqueda'] ?? '';
$editarID = $_GET['editar'] ?? null;

// ELIMINAR CLIENTE
if (isset($_GET['eliminar']) && $permiteEliminar) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM clientes WHERE num_cliente = $id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// ACTUALIZAR CLIENTE
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar']) && $permiteEditar) {
    $id = $_POST['num_cliente'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $sql = "UPDATE clientes SET nombre=?, apellido=?, correo=?, telefono=? WHERE num_cliente=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nombre, $apellido, $correo, $telefono, $id);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// BUSCAR CLIENTES
$sql = "SELECT * FROM clientes WHERE nombre LIKE ? OR apellido LIKE ? OR correo LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$busqueda%";
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$resultado = $stmt->get_result();

// OBTENER DATOS DE CLIENTE A EDITAR
$clienteEditar = null;
if ($editarID && $permiteEditar) {
    $res = $conn->query("SELECT * FROM clientes WHERE num_cliente = $editarID");
    $clienteEditar = $res->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Clientes</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #f5f5f5; }
        .crud-actions button { margin: 0 5px; }
        form.inline { display: inline; }
    </style>
    <link rel="stylesheet" href="../../frontend/css/estiloTablas.css">
</head>
<body>

<h1>Gestión Clientes</h1>
<button onclick="window.location.href='../../frontend/vistaAdmin.php';">Regresar</button>

<form method="get">
    <input type="text" name="busqueda" placeholder="Buscar cliente..." value="<?= htmlspecialchars($busqueda) ?>">
    <button type="submit">Buscar</button>
</form>

<?php if ($clienteEditar): ?>
    <h2>Editar Cliente</h2>
    <form method="post">
        <input type="hidden" name="num_cliente" value="<?= $clienteEditar['num_cliente'] ?>">
        <input type="text" name="nombre" placeholder="Nombre" value="<?= htmlspecialchars($clienteEditar['nombre']) ?>" required>
        <input type="text" name="apellido" placeholder="Apellido" value="<?= htmlspecialchars($clienteEditar['apellido']) ?>" required>
        <input type="email" name="correo" placeholder="Correo" value="<?= htmlspecialchars($clienteEditar['correo']) ?>" required>
        <input type="text" name="telefono" placeholder="Teléfono" value="<?= htmlspecialchars($clienteEditar['telefono']) ?>" required>
        <button type="submit" name="actualizar">Actualizar</button>
        <a href="<?= $_SERVER['PHP_SELF'] ?>"><button type="button">Cancelar</button></a>
    </form>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>#</th><th>Nombre</th><th>Apellido</th><th>Correo</th><th>Teléfono</th><th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= $row['num_cliente'] ?></td>
            <td><?= htmlspecialchars($row['nombre']) ?></td>
            <td><?= htmlspecialchars($row['apellido']) ?></td>
            <td><?= htmlspecialchars($row['correo']) ?></td>
            <td><?= htmlspecialchars($row['telefono']) ?></td>
            <td class="acciones">
                <?php if ($permiteEditar): ?>
                    <form method="get" class="inline">
                        <input type="hidden" name="editar" value="<?= $row['num_cliente'] ?>">
                        <button type="submit" id="boton-editar">Editar</button>
                    </form>
                <?php endif; ?>
                <?php if ($permiteEliminar): ?>
                    <form method="get" class="inline" onsubmit="return confirm('¿Eliminar cliente?');">
                        <input type="hidden" name="eliminar" value="<?= $row['num_cliente'] ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
