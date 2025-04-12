<?php
require '../conn.php';

$descripcion = $_POST['descripcion'];
$nom_imagen = $_POST['nom_imagen'];
$precio = $_POST['precio'];
$existencias = $_POST['existencias'];
$modelo = $_POST['modelo'];
$talla = $_POST['talla'];
$genero = $_POST['genero'];

$sql = "INSERT INTO productos (descripcion, nom_imagen, precio, existencias, modelo, talla, genero)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssiisss", $descripcion, $nom_imagen, $precio, $existencias, $modelo, $talla, $genero);
$stmt->execute();

header("Location: ../vistas/listaProductos.php");
?>
