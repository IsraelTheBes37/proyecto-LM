<?php
session_start();
if (!isset($_SESSION['cliente'])) {
    header('Location: loginVista.php');
    exit();
}

include '../backend/conn.php';
$clienteID = $_SESSION['cliente']['num_cliente'];

// Para cerrar la sesión
if (isset($_POST['cerrar_sesion'])) {
    session_unset();
    session_destroy();
    header("Location: index.html");
    exit();
}

// AÑADIR AL CARRITO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['anadir_carrito'])) {
    $id_modelo = (int) $_POST['id_modelo'];
    $cantidad = (int) $_POST['cantidad'];

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    if (isset($_SESSION['carrito'][$id_modelo])) {
        $_SESSION['carrito'][$id_modelo] += $cantidad;
    } else {
        $_SESSION['carrito'][$id_modelo] = $cantidad;
    }
}

// ACTUALIZAR CLIENTE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_datos'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $clave = !empty($_POST['clave']) ? password_hash($_POST['clave'], PASSWORD_DEFAULT) : null;

    if ($clave) {
        $stmt = $conn->prepare("UPDATE clientes SET nombre = ?, apellido = ?, correo = ?, clave = ? WHERE num_cliente = ?");
        $stmt->bind_param("ssssi", $nombre, $apellido, $correo, $clave, $clienteID);
    } else {
        $stmt = $conn->prepare("UPDATE clientes SET nombre = ?, apellido = ?, correo = ? WHERE num_cliente = ?");
        $stmt->bind_param("sssi", $nombre, $apellido, $correo, $clienteID);
    }
    $stmt->execute();

    $_SESSION['cliente']['nombre'] = $nombre;
    $_SESSION['cliente']['apellido'] = $apellido;
    $_SESSION['cliente']['correo'] = $correo;
}

// ELIMINAR CLIENTE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_cuenta'])) {
    $stmt = $conn->prepare("DELETE FROM clientes WHERE num_cliente = ?");
    $stmt->bind_param("i", $clienteID);
    $stmt->execute();
    session_destroy();
    header("Location: loginVista.php");
    exit();
}

// DEVOLVER PEDIDO
if (isset($_GET['devolver']) && is_numeric($_GET['devolver'])) {
    $idPedido = (int) $_GET['devolver'];
    $stmt = $conn->prepare("DELETE FROM pedidos WHERE num_pedido = ? AND fk_cliente = ?");
    $stmt->bind_param("ii", $idPedido, $clienteID);
    $stmt->execute();
}

// GENERAR PEDIDO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar_pedido']) && !empty($_SESSION['carrito'])) {
    
    $fk_vendedor = 1;

    foreach ($_SESSION['carrito'] as $id_modelo => $cantidad) {
        $stmt = $conn->prepare("INSERT INTO pedidos (fecha_pedido, fk_cliente, fk_vendedor, fk_modelo, cantidad) VALUES (?, ?, ?, ?, ?)");
        $fecha = date('Y-m-d');
        $stmt->bind_param("siiii", $fecha, $clienteID, $fk_vendedor, $id_modelo, $cantidad);
        $stmt->execute();
    }

    unset($_SESSION['carrito']); // Vacía el carrito
    echo "<script>alert('Pedido realizado con éxito');</script>";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vista Cliente</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ccc; text-align: left; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-contenido { background: #fff; margin: 10% auto; padding: 20px; width: 300px; border-radius: 10px; }
        .cerrar { float: right; cursor: pointer; }
    </style>
</head>
<body>
<h1>Bienvenido, <?php echo $_SESSION['cliente']['nombre']; ?></h1>
<h2>Mi perfil</h2>
<button onclick="mostrarModalEditarDatos()">Actualizar mis datos</button>
<button onclick="confirmarEliminarCuenta()">Darme de baja</button>
<form method="post" style="display:inline;">
    <button type="submit" name="cerrar_sesion" style="background-color:red; color:white; border:none; padding:8px 16px; cursor:pointer;">
        Cerrar Sesión
    </button>
</form>


<h2>Productos</h2>
<?php
$res = $conn->query("SELECT * FROM productos LIMIT 10");
while ($producto = $res->fetch_assoc()):
?>
    <div>
        <img src="<?= $producto['nom_imagen'] ?>" alt="<?php echo htmlspecialchars($producto['modelo']); ?>" style="width: 100%; max-width: 300px; height: auto; object-fit: cover; display: block; margin: 0 auto;">
        <strong><?php echo $producto['modelo']; ?></strong><br>
        Precio: <?php echo $producto['precio']; ?> €<br>
        <form method="post">
            <input type="hidden" name="anadir_carrito" value="1">
            <input type="hidden" name="id_modelo" value="<?php echo $producto['id_modelo']; ?>">
            <input type="number" name="cantidad" min="1" max="<?php echo $producto['existencias']; ?>" required>
            <button type="submit">Añadir al carrito</button>
        </form>
    </div>
    <hr>
<?php endwhile; ?>

<h2>Carrito</h2>
<?php if (!empty($_SESSION['carrito'])): ?>
<form method="post">
    <table>
        <tr>
            <th>Modelo</th>
            <th>Nombre</th>
            <th>Precio Unitario</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
        </tr>
        <?php
        $total = 0;
        foreach ($_SESSION['carrito'] as $id_modelo => $cantidad):
            $stmt = $conn->prepare("SELECT modelo, descripcion, precio FROM productos WHERE id_modelo = ?");
            $stmt->bind_param("i", $id_modelo);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            $subtotal = $res['precio'] * $cantidad;
            $total += $subtotal;
            echo "<tr>
                    <td>" . htmlspecialchars($res['modelo']) . "</td>
                    <td>" . htmlspecialchars($res['descripcion']) . "</td>
                    <td>" . number_format($res['precio'], 2) . " €</td>
                    <td>" . $cantidad . "</td>
                    <td>" . number_format($subtotal, 2) . " €</td>
                  </tr>";
        endforeach;
        ?>
        <tr>
            <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
            <td><strong><?php echo number_format($total, 2); ?> €</strong></td>
        </tr>
    </table>
    <br>
    <input type="hidden" name="finalizar_pedido" value="1">
    <button type="submit">Finalizar compra</button>
</form>
<?php else: ?>
<p>El carrito está vacío.</p>
<?php endif; ?>


<h2>Mis pedidos</h2>
<?php
$stmt = $conn->prepare("SELECT * FROM pedidos WHERE fk_cliente = ?");
$stmt->bind_param("i", $clienteID);
$stmt->execute();
$res = $stmt->get_result();
?>
<table>
    <tr><th>Nº Pedido</th><th>Fecha</th><th>Acción</th></tr>
    <?php while ($pedido = $res->fetch_assoc()): ?>
        <tr>
            <td><?php echo $pedido['num_pedido']; ?></td>
            <td><?php echo $pedido['fecha_pedido']; ?></td>
            <td><a href="?devolver=<?php echo $pedido['num_pedido']; ?>" onclick="return confirm('¿Estás seguro?')">Devolver</a></td>
        </tr>
    <?php endwhile; ?>
</table>

<!--Modal para editar  -->
<div id="modalEditar" class="modal">
    <div class="modal-contenido">
        <span class="cerrar" onclick="cerrarModalEditarDatos()">&times;</span>
        <form method="post">
            <input type="hidden" name="actualizar_datos" value="1">
            <label>Nombre:</label><input type="text" name="nombre" value="<?php echo $_SESSION['cliente']['nombre']; ?>"><br>
            <label>Apellido:</label><input type="text" name="apellido" value="<?php echo $_SESSION['cliente']['apellido']; ?>"><br>
            <label>Correo:</label><input type="email" name="correo" value="<?php echo $_SESSION['cliente']['correo']; ?>"><br>
            <label>Contraseña:</label><input type="password" name="clave" placeholder="Nueva contraseña (opcional)"><br>
            <button type="submit">Actualizar</button>
        </form>
    </div>
</div>

<form id="formEliminar" method="post" style="display: none;">
    <input type="hidden" name="eliminar_cuenta" value="1">
</form>

<script>
function mostrarModalEditarDatos() {
    document.getElementById('modalEditar').style.display = 'block';
}
function cerrarModalEditarDatos() {
    document.getElementById('modalEditar').style.display = 'none';
}
function confirmarEliminarCuenta() {
    if (confirm('¿Seguro que deseas darte de baja? Esta acción no se puede deshacer.')) {
        document.getElementById('formEliminar').submit();
    }
}
</script>
</body>
</html>
