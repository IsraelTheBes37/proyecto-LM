<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>
  <style>
    body { font-family: Arial; padding: 20px; background-color: #f2f2f2; }
    form { max-width: 300px; margin: auto; background: white; padding: 20px; border-radius: 6px; }
    .error { color: red; margin-bottom: 10px; font-weight: bold; text-align: center; }
  </style>
</head>
<body>

  <form action="../backend/models/login.php" method="post">
    <h2>Login</h2>

    <!-- Mostrar mensaje de error si viene con ?error=1 -->
    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
      <div class="error">Correo o clave incorrectos.</div>
    <?php endif; ?>

    <label for="correo">Correo:</label>
    <input type="email" id="correo" name="correo" required><br><br>

    <label for="clave">Clave:</label>
    <input type="password" id="clave" name="clave" required><br><br>

    <input type="submit" value="Iniciar Sesión">
  </form>

</body>
</html>
