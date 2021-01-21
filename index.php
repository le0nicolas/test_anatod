<!DOCTYPE html>
<html lang="es">

<?php
    include_once 'class.database.php';
    $database = new class_db();
    $database->__construct();
    //armo la lista de las provincias con los datos de la bd para los select
    $lista_provincia = "";
    $sql = "SELECT * FROM provincias"; 
    $stmt = $database->conn->query($sql);
    while ($fila = $stmt->fetch_array(MYSQLI_ASSOC)) {
        $lista_provincia .= "<option value='{$fila['provincia_id']}'>{$fila['provincia_nombre']}</option>";
    }

    //query para traer los datos para la tabla clientes (y los id de provincia/localidad para luego usarlo en el update)
    $cliente ="";
    $clientes="";
    $query = 
        "   SELECT c.cliente_id, c.cliente_nombre, c.cliente_dni, l.localidad_nombre, l.localidad_id , p.provincia_nombre, p.provincia_id 
            FROM clientes c 
                    INNER JOIN localidades l ON c.cliente_localidad = l.localidad_id
                    INNER JOIN provincias p ON l.localidad_provincia = p.provincia_id
        ";

    $clientes = $database->conn->query($query);
    if($clientes && $clientes->num_rows>0){
        $clientes->fetch_all(MYSQLI_ASSOC);
    }

    //query para traer los datos para la tabla provincias
    $provincia ="";
    $provincias="";
    $query = 
        "   
            SELECT p.provincia_id, provincia_nombre, l.localidad_nombre, COUNT(DISTINCT c.cliente_dni) AS cantidad_clientes
            FROM localidades l
            INNER JOIN provincias p ON p.provincia_id=l.localidad_provincia
            INNER JOIN clientes c ON c.cliente_localidad=l.localidad_id
            GROUP BY l.localidad_id
            ORDER BY cantidad_clientes DESC
        ";

    $provincias = $database->conn->query($query);
    if($provincias && $provincias->num_rows>0){
        $provincias->fetch_all(MYSQLI_ASSOC);
    }

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Anatod Test">
    <link rel="stylesheet" href="styles/index.css">
    <!-- font awesome -->
    <script src="https://kit.fontawesome.com/1afd94d30f.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Anatod Test</title>
</head>
<body>
    <header class="headerShape">
        <div class="navbar">
            <ul>
                <li>Leandro Márquez</li>
                <li><a href="#contacto-info">Contacto</a></li>
                <li><a href="#tabla-provincias">Tabla Provincia</a></li>
                <li><a href="#tabla-clientes">Tabla Clientes</a></li>
            </ul>
        </div>     
        <h1>Evaluación de Anatod</h1>
    </header>

    <div class="main">
        <div class="formulario">
            <form class="formClientes" method="post" action="createCliente.php">

                <div class="formGroup">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="input_text" id="nombre" placeholder="Ingresar nombre" name="nombreCliente">
                </div>

                <div class="formGroup">
                    <label for="dni">DNI:</label>
                    <input type="text" class="input_text" id="dni" placeholder="DNI" name="dniCliente">
                </div>

                <div class="formGroup">
                    <label for="provincia">Provincia:</label>
                    <select name="provincia" class="custom_select" id="provincias">
                        <option value="seleccione" selected>Seleccione una provincia</option>
                        <?=$lista_provincia?> 
                    </select>
                </div>
                
                <div class="formGroup">
                    <label for="localidad">Localidad:</label>
                    <select name="localidad" class="custom_select" id="localidades">
                    </select>
                </div>

                <div class="formGroup">
                    <button class="btn" type="submit">
                        <i class="fal fa-chevron-right" style="color:#B81365;"></i>
                        Crear
                    </button>
                </div>

            </form>
        </div>

        <div class="tablas" id="tabla-clientes">
            <h1>Tabla Clientes:</h1>
            <?php if (!empty($clientes))
                { 
            ?>
                <table class="tablaInfo">
                    <thead>
                        <tr>
                            <th scope="col">ID Cliente</th>
                            <th scope="col">Nombre Cliente</th>
                            <th scope="col">DNI Cliente</th>
                            <th scope="col">Localidad</th>
                            <th scope="col">Provincia</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($clientes as $cliente){
                        ?>
                            <tr>
                                <?php
                                    echo "<td>". $cliente['cliente_id'] . "</td>";
                                    echo "<td>". $cliente['cliente_nombre'] . "</td>";
                                    echo "<td>". $cliente['cliente_dni'] . "</td>";
                                    echo "<td>". $cliente['localidad_nombre'] . "</td>";
                                    echo "<td>". $cliente['provincia_nombre'] . "</td>";
                                    echo "  <td> 
                                                <button 
                                                    class='btn btnModificar' 
                                                    data-idCliente=".$cliente['cliente_id']."
                                                    data-nombreCliente='".$cliente['cliente_nombre']."' 
                                                    data-dniCliente=".$cliente['cliente_dni']."
                                                    data-localidad_id=".$cliente['localidad_id']."
                                                    data-provincia_id=".$cliente['provincia_id'].
                                                "> 
                                                    <i class='fal fa-edit' style='color:#B81365;'></i> 
                                                </button> 
                                                <button class='btn btnBorrar' value=".$cliente['cliente_id']."> 
                                                    <i class='far fa-trash-alt' style='color:#B81365;'></i>
                                                </button> 
                                            </td>";
                                ?>
                            </tr>                    
                        <?php
                            }
                        ?>
                        
                    </tbody>
                </table>
            <?php
                }else{
                    echo "<h3> No se encontraron datos.  </h3>"; 
                    } 
            ?>
        </div>
        <div class="tablas" id="tabla-provincias">
            <h1>Tabla Provincias:</h1>
            <?php if (!empty($provincias))
                { 
            ?>
                <table class="tablaInfo">
                    <thead>
                        <tr>
                            <th scope="col">ID Provincia</th>
                            <th scope="col">Nombre Provincia</th>
                            <th scope="col">Nombre Localidad</th>
                            <th scope="col">Cantidad de Clientes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($provincias as $provincia){
                        ?>
                            <tr>
                                <?php
                                    echo "<td>". $provincia['provincia_id'] . "</td>";
                                    echo "<td>". $provincia['provincia_nombre'] . "</td>";
                                    echo "<td>". $provincia['localidad_nombre'] . "</td>";
                                    echo "<td>". $provincia['cantidad_clientes'] . "</td>";
                                    
                                ?>
                            </tr>                    
                        <?php
                            }
                        ?>
                        
                    </tbody>
                </table>
            <?php
                }else{
                    echo "<h3> No se encontraron datos. </h3>"; 
                    } 
            ?>
        </div>
        <!-- contacto -->
        <div class="grid-informacion" id="contacto-info">
            <section class="card">
                <div class="info-contacto">
                    <button class="btn" onclick="window.open('https://drive.google.com/file/d/1yrySkZ9Aj17uMj3fijT3eHbx_SNfSVLt/view?usp=sharing', '_blank')" type="button">
                        Mi CV
                    </button>
                    <button class="btn" onclick="window.open('https://www.linkedin.com/in/leandromarquez95/', '_blank')" type="button">
                        Linkedin
                    </button>
                    
                </div>
                <div class="info-personal">
                    <h2>Información personal:</h2>
                    <p>
                        Mi nombre es Leandro Márquez, tengo 25 años y este año me recibo de Técnico Superior en Computación (sólo me queda un final para recibirme). <br><br>
                        Soy una persona autodidacta, me gusta aprender por mi cuenta e investigar nuevas tecnologías. <br>
                        Me interesa la parte del desarrollo web front-end, y actualmente estoy haciendo proyectos propios y capacitandome en cursos. <br><br>
                        En este momento estoy trabajando como soporte técnico y desarrollador MySQL para la empresa Aoniken.

                    </p> 
                </div>
            </section>
        </div>
        
        <!-- Modal con form para modificar datos -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Modificar Cliente:</h2>
                
                <form method="post" action="updateCliente.php">
                    <input id="clienteID" name="clienteID" hidden/>

                    <label for="nombreUpdate">Nombre Cliente:</label>
                    <input class="inputUpdate" type="text" id="nombreUpdate" name="nombreUpdate">

                    <label for="dniUpdate">DNI:</label>
                    <input class="inputUpdate" type="text" id="dniUpdate" name="dniUpdate">
                    
                    <label for="provincia">Provincia:</label>
                    <select name="provinciaUpdate" class="selectUpdate" id="provinciaUpdate">
                        <?=$lista_provincia?> 
                    </select>
                    
                    <label for="localidad">Localidad:</label>
                    <select name="localidadUpdate" class="selectUpdate" id="localidadUpdate">
                    </select>

                    <input class="btnUpdate" type="submit" value="Modificar">
                </form>
                
            </div>
        </div>
    </div>
    <div class="footer">
            <p>Esta página es responsive!</p>
            <i class="fas fa-phone-laptop"></i>
    </div>
    <script src="index.js"></script>
</body>
</html>