<?php
// deleteProducto.php
require '../conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM productos WHERE id_modelo = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../views/listaProductos.php");
    } else {
        echo "Error al eliminar producto: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>