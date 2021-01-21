<?php
    $prov_id=$_GET['prov_id'];
    $lista_localidades="";

    if(!is_numeric($prov_id)){
        echo "Data Error";
        exit;
    }

    include_once 'class.database.php';
    $database = new class_db();
    $database->__construct();

    $sql="SELECT localidad_nombre,localidad_id FROM localidades WHERE localidad_provincia='$prov_id'";
    $stmt = $database->conn->query($sql);

    $data = $stmt->fetch_all(MYSQLI_ASSOC);
    
    $main = array('data'=>$data);
    echo json_encode($data);
?>