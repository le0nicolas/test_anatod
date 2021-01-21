<?php
    $clienteID=$_POST['clienteID'];

    if(!is_numeric($clienteID)){
        echo "Data Error";
        exit;
    }
    include_once 'class.database.php';
    $database = new class_db();
    $database->__construct();
    
    $sql="DELETE FROM clientes WHERE clientes.cliente_id ='$clienteID'";
    $stmt = $database->conn->query($sql);
?>