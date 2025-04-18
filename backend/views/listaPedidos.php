<?php
include '../conn.php';

// Insertar nuevo pedido
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['insertar'])) {
    $fecha = $_POST['fecha_pedido'];
    $cliente = $_POST['fk_cliente'];
    $vendedor = $_POST['fk_vendedor'];
    $modelo = $_POST['fk_modelo'];
    $cantidad = $_POST['cantidad'];

    $sql = "INSERT INTO pedidos (fecha_pedido, fk_cliente, fk_vendedor, fk_modelo, cantidad) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiii", $fecha, $cliente, $vendedor, $modelo, $cantidad);
    $stmt->execute();
    header("Location: listaPedidos.php");
    exit();
}

// Eliminar pedido
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM pedidos WHERE num_pedido = $id");
    header("Location: listaPedidos.php");
    exit();
}

// Obtener datos para edición
$editarPedido = null;
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $res = $conn->query("SELECT * FROM pedidos WHERE num_pedido = $id");
    $editarPedido = $res->fetch_assoc();
}

// Actualizar pedido
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['actualizar'])) {
    $id = $_POST['num_pedido'];
    $fecha = $_POST['fecha_pedido'];
    $cliente = $_POST['fk_cliente'];
    $vendedor = $_POST['fk_vendedor'];
    $modelo = $_POST['fk_modelo'];
    $cantidad = $_POST['cantidad'];

    $sql = "UPDATE pedidos SET fecha_pedido=?, fk_cliente=?, fk_vendedor=?, fk_modelo=?, cantidad=? WHERE num_pedido=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiiii", $fecha, $cliente, $vendedor, $modelo, $cantidad, $id);
    $stmt->execute();
    header("Location: listaPedidos.php");
    exit();
}

// Obtener todos los pedidos
$pedidos = $conn->query("
    SELECT p.*, c.nombre AS cliente_nombre, c.apellido AS cliente_apellido,
           e.nombre AS vendedor_nombre, e.apellido AS vendedor_apellido,
           pr.modelo AS modelo_nombre
    FROM pedidos p
    LEFT JOIN clientes c ON p.fk_cliente = c.num_cliente
    LEFT JOIN empleados e ON p.fk_vendedor = e.num_empleado
    LEFT JOIN productos pr ON p.fk_modelo = pr.id_modelo
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Pedidos</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        input, select { padding: 5px; margin: 5px; }
        form { margin-top: 20px; }
    </style>
    <link rel="stylesheet" href="../../frontend/css/estiloTablas.css">
</head>
<body>
    <h1>Gestión de Pedidos</h1>
    <button onclick="window.location.href='../../frontend/vistaAdmin.php';">Regresar</button>
    <h2><?php echo $editarPedido ? "Editar Pedido" : "Nuevo Pedido"; ?></h2>
    <form method="POST">
        <?php if ($editarPedido): ?>
            <input type="hidden" name="num_pedido" value="<?= $editarPedido['num_pedido'] ?>">
        <?php endif; ?>
        <input type="date" name="fecha_pedido" value="<?= $editarPedido['fecha_pedido'] ?? '' ?>" required>

        <!-- Cliente -->
        <select name="fk_cliente" required>
            <option value="">Seleccione Cliente</option>
            <?php
            $clientes = $conn->query("SELECT num_cliente, nombre, apellido FROM clientes");
            while ($row = $clientes->fetch_assoc()):
                $selected = ($editarPedido && $editarPedido['fk_cliente'] == $row['num_cliente']) ? "selected" : "";
                echo "<option value='{$row['num_cliente']}' $selected>{$row['nombre']} {$row['apellido']}</option>";
            endwhile;
            ?>
        </select>

        <!-- Vendedor -->
        <select name="fk_vendedor" required>
            <option value="">Seleccione Vendedor</option>
            <?php
            $empleados = $conn->query("SELECT num_empleado, nombre, apellido FROM empleados WHERE cargo = 'Vendedor'");
            while ($row = $empleados->fetch_assoc()):
                $selected = ($editarPedido && $editarPedido['fk_vendedor'] == $row['num_empleado']) ? "selected" : "";
                echo "<option value='{$row['num_empleado']}' $selected>{$row['nombre']} {$row['apellido']}</option>";
            endwhile;
            ?>
        </select>

        <!-- Producto -->
        <select name="fk_modelo" required>
            <option value="">Seleccione Modelo</option>
            <?php
            $productos = $conn->query("SELECT id_modelo, modelo FROM productos");
            while ($row = $productos->fetch_assoc()):
                $selected = ($editarPedido && $editarPedido['fk_modelo'] == $row['id_modelo']) ? "selected" : "";
                echo "<option value='{$row['id_modelo']}' $selected>{$row['modelo']}</option>";
            endwhile;
            ?>
        </select>

        <input type="number" name="cantidad" placeholder="Cantidad" value="<?= $editarPedido['cantidad'] ?? '' ?>" required>

        <?php if ($editarPedido): ?>
            <button type="submit" name="actualizar">Actualizar</button>
        <?php else: ?>
            <button type="submit" name="insertar">Insertar</button>
        <?php endif; ?>
    </form>

    <h2>Lista de Pedidos</h2>
    <table>
        <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Vendedor</th>
            <th>Modelo</th>
            <th>Cantidad</th>
            <th>Acciones</th>
        </tr>
        <?php while ($p = $pedidos->fetch_assoc()): ?>
            <tr>
                <td><?= $p['num_pedido'] ?></td>
                <td><?= $p['fecha_pedido'] ?></td>
                <td><?= $p['cliente_nombre'] . ' ' . $p['cliente_apellido'] ?></td>
                <td><?= $p['vendedor_nombre'] . ' ' . $p['vendedor_apellido'] ?></td>
                <td><?= $p['modelo_nombre'] ?></td>
                <td><?= $p['cantidad'] ?></td>
                <td class="acciones">
                    <form action="" method="get" style="display: inline;">
                        <input type="hidden" name="editar" value="<?= $p['num_pedido'] ?>">
                        <button type="submit" id="boton-editar">Editar</button>
                    </form>
                    <form action="" method="get" style="display: inline;" onsubmit="return confirm('¿Eliminar este pedido?')">
                        <input type="hidden" name="eliminar" value="<?= $p['num_pedido'] ?>">
                        <button type="submit" class="boton boton-eliminar">Eliminar</button>
                    </form>
                </td>

            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
