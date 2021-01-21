<?php
    include_once 'class.database.php';
    $database = new class_db();
    $database->__construct();

    $nombreCliente = (isset($_POST["nombreCliente"])) ? $_POST["nombreCliente"] : null;
    $dniCliente = (isset($_POST["dniCliente"])) ? $_POST["dniCliente"] : null;
    $localidadID = (isset($_POST["localidad"])) ? $_POST["localidad"] : null;

    //insert con los datos obtenidos
    if ($nombreCliente != null && $dniCliente!=null && $localidadID!=null ){
        $sql = "INSERT INTO clientes (cliente_nombre, cliente_dni, cliente_localidad) VALUES ('$nombreCliente', '$dniCliente', '$localidadID')"; 
        $database->conn->query($sql);
        header ('location: index.php');
    }else{
        header ('location: index.php');
    }
?>