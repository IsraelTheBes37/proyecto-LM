<?php
        $servidor = "localhost:3307";
        $usuario = "root";
        $contrasena = "root";
        $nombreBD = "bdzapateria";

        /*$servidor = "localhost:3306";
        $usuario = "root";
        $contrasena = "";
        $nombreBD = "bdzapateria";*/

        $conn = new mysqli($servidor, $usuario, $contrasena, $nombreBD);
        if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
        }
?>