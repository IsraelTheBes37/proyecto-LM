<?php
session_start();
require '../conn.php'; // tu archivo de conexión

$cargo = $_SESSION['cargo'];
$permiteEliminar = ($cargo === "Informático");
$permiteEditar = in_array($cargo, ["Informático", "DirectorVentas"]);
$permiteCrear = in_array($cargo, ["Informático", "DirectorVentas"]);

$busqueda = $_GET['busqueda'] ?? '';

$sql = "SELECT * FROM clientes WHERE nombre LIKE ? OR apellido LIKE ? OR correo LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$busqueda%";
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    th, td {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: center;
    }
    th {
        background-color: #f5f5f5;
    }
    .crud-actions button {
        margin: 0 5px;
    }
</style>

<h3>Clientes</h3>

<form method="get">
    <input type="text" name="busqueda" placeholder="Buscar cliente..." value="<?= htmlspecialchars($busqueda) ?>">
    <button type="submit">Buscar</button>
</form>

<?php if ($permiteCrear): ?>
    <a href="formCrearCliente.php"><button>Nuevo Cliente</button></a>
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
            <td class="crud-actions">
                <?php if ($permiteEditar): ?>
                    <a href="editarCliente.php?id=<?= $row['num_cliente'] ?>"><button>Editar</button></a>
                <?php endif; ?>
                <?php if ($permiteEliminar): ?>
                    <a href="eliminarCliente.php?id=<?= $row['num_cliente'] ?>" onclick="return confirm('¿Eliminar cliente?')"><button>Eliminar</button></a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
