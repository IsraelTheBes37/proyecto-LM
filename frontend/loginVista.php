<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <link rel="stylesheet" href="css/login.css">
</head>
<body>
  <!-- menu -->
<header>
    <div class="container">
        <div class="logo">Huellas Zapatería</div>
        <nav>
            <a class="active" href="index.html">Inicio</a>
            <a href="vistaInscripcion.html">Registrarse</a>
            <a href="loginVista.php">Ingresar</a>
            <a href="Contacto.html">Formulario de contacto</a>
        </nav>
    </div>
</header>        

  <form action="../backend/models/login.php" method="post">
    <h2>Login</h2>

    <!-- Mostrar mensaje de error si viene con ?error=1 -->
    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
      <div class="error">Correo o clave incorrectos!!!</div>
    <?php endif; ?>

    <label for="correo">Correo:</label>
    <input type="email" id="correo" name="correo" required><br><br>

    <label for="clave">Clave:</label>
    <input type="password" id="clave" name="clave" required><br><br>

    <input type="submit" value="Iniciar Sesión">
  </form>

</body>
</html>
