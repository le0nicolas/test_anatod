<?php
    include_once 'class.database.php';
    $database = new class_db();
    $database->__construct();

    $clienteID = (isset($_POST["clienteID"])) ? $_POST["clienteID"] : null;
    $nombreCliente = (isset($_POST["nombreUpdate"])) ? $_POST["nombreUpdate"] : null;
    $dniCliente = (isset($_POST["dniUpdate"])) ? $_POST["dniUpdate"] : null;
    $localidadID = (isset($_POST["localidadUpdate"])) ? $_POST["localidadUpdate"] : null;

    if ($clienteID!= null && $nombreCliente != null && $dniCliente!=null && $localidadID!=null ){
        $sql = "UPDATE clientes SET cliente_nombre='$nombreCliente', cliente_dni='$dniCliente', cliente_localidad='$localidadID' WHERE cliente_id='$clienteID'"; 
        $database->conn->query($sql);
        header ('location: index.php');
    }else{
        header ('location: index.php');
    }
?>