<?php
require '../conn.php';

$id = $_POST['id_modelo'];
$descripcion = $_POST['descripcion'];
$nom_imagen = $_POST['nom_imagen'];
$precio = $_POST['precio'];
$existencias = $_POST['existencias'];
$modelo = $_POST['modelo'];
$talla = $_POST['talla'];
$genero = $_POST['genero'];

$sql = "UPDATE productos SET descripcion=?, nom_imagen=?, precio=?, existencias=?, modelo=?, talla=?, genero=?
        WHERE id_modelo=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssiisssi", $descripcion, $nom_imagen, $precio, $existencias, $modelo, $talla, $genero, $id);
$stmt->execute();

echo "Producto actualizado correctamente";
?>
