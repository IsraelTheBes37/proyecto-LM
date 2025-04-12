<?php
// listaProductos.php
session_start();

if (!isset($_SESSION['cargo'])) {
    header('Location: ../../frontend/loginVista.php');
    exit;
}

$cargo = $_SESSION['cargo'];
$nombre = $_SESSION['nombre'];

require '../conn.php'; // Archivo que contiene la conexión a la BDD

$puedeEliminar = ($cargo === 'Informático');
$puedeAgregar = in_array($cargo, ['Informático', 'DirectorVentas']);
$puedeEditar = in_array($cargo, ['Informático', 'DirectorVentas']);

// Obtener productos
$sql = "SELECT * FROM productos ORDER BY id_modelo DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD Productos</title>
    <link rel="stylesheet" href="../../css/estilos.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../frontend/js/funcionesProductos.js"></script>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .acciones button { margin: 0 5px; }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 400px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>CRUD de Productos</h2>
    <button onclick="window.history.back();">Regresar</button>
    <?php if ($puedeAgregar): ?>
    <form method="post" action="../models/addProducto.php">
        <br><input type="text" name="descripcion" placeholder="Descripción" required>
        <input type="text" name="nom_imagen" placeholder="Link de la imagen" required>
        <input type="number" name="precio" placeholder="Precio" required>
        <input type="number" name="existencias" placeholder="Existencias" required>
        <select name="modelo" required>
            <option value="">Selecciona Modelo</option>
            <option value="Deportivos">Zapatos deportivos</option>
            <option value="Casuales">Zapatos casuales</option>
            <option value="Formales">Zapatos formales</option>
            <option value="Botas">Botas</option>
            <option value="Sandalias">Sandalias</option>
            <option value="Comodos">Zapatos cómodos</option>
            <option value="Infantiles">Zapatos infantiles</option>
            <option value="Especiales">Zapatos especiales</option>
        </select>
        <select name="talla" required>
            <option value="">Seleccione Talla</option>
            <option value="XS">XS</option>
            <option value="S">S</option>
            <option value="M">M</option>
            <option value="L">L</option>
            <option value="XL">XL</option>
            <option value="XXL">XXL</option>
            <option value="XXXL">XXXL</option>
        </select>
        <select name="genero" required>
            <option value="">Seleccione Género</option>
            <option value="Hombre">Hombre</option>
            <option value="Mujer">Mujer</option>
            <option value="Niño">Niño</option>
            <option value="Niña">Niña</option>
        </select>
        <input type="submit" value="Agregar Producto">
    </form>
    <?php endif; ?>

    <br><input type="text" id="busqueda" onkeyup="buscarProducto()" placeholder="Buscar por descripción o modelo">

    <table id="tablaProductos">
        <thead>
            <tr>
                <th>ID</th><th>Descripción</th><th>Imagen</th><th>Precio</th><th>Existencias</th><th>Modelo</th><th>Talla</th><th>Género</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while($fila = $resultado->fetch_assoc()): ?>
            <tr data-id="<?= $fila['id_modelo'] ?>">
                <td><?= $fila['id_modelo'] ?></td>
                <td><?= $fila['descripcion'] ?></td>
                <td><img src="<?= $fila['nom_imagen'] ?>" alt="Imagen" style="width:1.5cm; height:1.5cm; object-fit: cover;"></td>
                <td><?= $fila['precio'] ?></td>
                <td><?= $fila['existencias'] ?></td>
                <td><?= $fila['modelo'] ?></td>
                <td><?= $fila['talla'] ?></td>
                <td><?= $fila['genero'] ?></td>
                <td class="acciones">
                    <?php if ($puedeEditar): ?>
                    <button onclick="editarProducto(<?= htmlspecialchars(json_encode($fila), ENT_QUOTES, 'UTF-8') ?>)">Editar</button>
                    <?php endif; ?>
                    <?php if ($puedeEliminar): ?>
                    <button onclick="eliminarProducto(<?= $fila['id_modelo'] ?>)">Eliminar</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <h3>Editar Producto</h3>
            <form id="formEditar">
                <input type="hidden" name="id_modelo" id="edit_id_modelo">
                <input type="text" name="descripcion" id="edit_descripcion" required>
                <input type="text" name="nom_imagen" id="edit_nom_imagen" required>
                <input type="number" name="precio" id="edit_precio" required>
                <input type="number" name="existencias" id="edit_existencias" required>
                <label>Modelo:
                <select id="edit_modelo" name="modelo" required>
                    <option value="Deportivos">Zapatos deportivos</option>
                    <option value="Casuales">Zapatos casuales</option>
                    <option value="Formales">Zapatos formales</option>
                    <option value="Botas">Botas</option>
                    <option value="Sandalias">Sandalias</option>
                    <option value="Comodos">Zapatos cómodos</option>
                    <option value="Infantiles">Zapatos infantiles</option>
                    <option value="Especiales">Zapatos especiales</option>
                </select>
                </label><br>
                
                <label>Talla:
                    <select id="edit_talla" name="talla" required>
                        <option value="XS">XS</option><option value="S">S</option><option value="M">M</option>
                        <option value="L">L</option><option value="XL">XL</option><option value="XXL">XXL</option>
                        <option value="XXXL">XXXL</option>
                    </select>
                </label><br>

                <label>Género:
                    <select id="edit_genero" name="genero" required>
                        <option value="Hombre">Hombre</option>
                        <option value="Mujer">Mujer</option>
                        <option value="Niño">Niño</option>
                        <option value="Niña">Niña</option>
                    </select>
                </label><br>
                <button type="submit">Guardar Cambios</button>
                <button type="button" onclick="cerrarModal()">Cancelar</button>
            </form>
        </div>
    </div>

    <script>
        function editarProducto(producto) {
            document.getElementById('edit_id_modelo').value = producto.id_modelo;
            document.getElementById('edit_descripcion').value = producto.descripcion;
            document.getElementById('edit_nom_imagen').value = producto.nom_imagen;
            document.getElementById('edit_precio').value = producto.precio;
            document.getElementById('edit_existencias').value = producto.existencias;
            document.getElementById('edit_modelo').value = producto.modelo;
            document.getElementById('edit_talla').value = producto.talla;
            document.getElementById('edit_genero').value = producto.genero;
            document.getElementById('modalEditar').style.display = 'block';
        }

        function cerrarModal() {
            document.getElementById('modalEditar').style.display = 'none';
        }

        document.getElementById('formEditar').addEventListener('submit', function(e) {
            e.preventDefault();
            const datos = new FormData(this);
            fetch('../models/editProducto.php', {
                method: 'POST',
                body: datos
            })
            .then(response => response.text())
            .then(data => {
                alert('Producto actualizado correctamente');
                location.reload();
            })
            .catch(error => {
                alert('Error al actualizar producto');
            });
        });

        function eliminarProducto(id) {
            if (confirm('¿Eliminar producto?')) {
                fetch(`../models/deleteProducto.php?id=${id}`)
                    .then(res => res.text())
                    .then(data => location.reload())
                    .catch(err => alert('Error al eliminar producto'));
            }
        }
    </script>
</body>
</html>
