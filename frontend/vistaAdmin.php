<?php
session_start();

if (!isset($_SESSION['empleado'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Bienvenido</title>
</head>
<body>
  <h2>¡Bienvenido, <?php echo htmlspecialchars($_SESSION['empleado']); ?>!</h2>
  <p>Has iniciado sesión correctamente.</p>
  <a href="logout.php">Cerrar sesión</a>
</body>
</html>
