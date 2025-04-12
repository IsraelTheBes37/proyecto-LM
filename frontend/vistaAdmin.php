<?php
session_start();

if (!isset($_SESSION['nombre']) || !isset($_SESSION['cargo'])) {
    header("Location: login.html");
    exit;
}

$cargo = $_SESSION['cargo'];
$nombre = $_SESSION['nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f3;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        .panel {
            margin-top: 30px;
        }
        .enlace {
            display: block;
            margin: 10px 0;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            width: 300px;
            border-radius: 6px;
            text-align: center;
        }
        .enlace:hover {
            background-color: #45a049;
        }
        .logout {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <h2>Bienvenido, <?= htmlspecialchars($nombre) ?> (<?= htmlspecialchars($cargo) ?>)</h2>

    <div class="panel">
        <?php if ($cargo === "Informático"): ?>
            <a href="../backend/views/listaClientes.php" class="enlace">Gestionar Clientes</a>
            <a href="../backend/views/listaEmpleados.php" class="enlace">Gestionar Empleados</a>
            <a href="../backend/views/listaPedidos.php" class="enlace">Gestionar Pedidos</a>
            <a href="../backend/views/listaProductos.php" class="enlace">Gestionar Productos</a>

        <?php elseif ($cargo === "DirectorVentas"): ?>
            <a href="../backend/views/listaClientes.php" class="enlace">Gestionar Clientes</a>
            <a href="../backend/views/listaProductos.php" class="enlace">Gestionar Productos</a>

        <?php elseif ($cargo === "Vendedor"): ?>
            <a href="../backend/views/listaPedidos.php" class="enlace">Gestionar Pedidos</a>

        <?php else: ?>
            <p>No tienes acceso a esta sección.</p>
        <?php endif; ?>
    </div>

    <div class="logout">
        <a href="../backend/models/logout.php" class="enlace" style="background-color:#f44336;">Cerrar sesión</a>
    </div>

</body>
</html>
