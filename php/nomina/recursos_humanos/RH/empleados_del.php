<?php

session_start();
if (!isset($_SESSION['id_us']) || !isset($_SESSION['id_rol']) || ($_SESSION['id_rol'] != 5 && $_SESSION['id_rol'] != 7)) {
    echo '
        <script>
            alert("Por favor inicie sesión con un usuario autorizado e intente nuevamente");
            window.location = "../../dev/PHP/login.php";
        </script>
    ';
    exit; // Terminar el script para evitar que se ejecute más código
  }
  
require_once("../conexion/db.php");


$id_rol = $_SESSION['id_rol'];
if ($id_rol == '5') {
$sql = $conexion -> prepare ("SELECT * FROM usuarios, puestos, roles WHERE usuarios.id_us = '".$_GET['id']."'");
$sql -> execute();
$usua = $sql -> fetch();
?>

<?php
if(isset($_POST["delete"]))
{
    $insertSQL = $conexion->prepare("DELETE FROM usuarios WHERE id_us = '".$_GET['id']."'");
    $insertSQL->execute();
    echo '<script>alert ("Registro Eliminado Exitosamente");
    window.close("empleados_del.php");
    </script>'; 
}

?>

<!DOCTYPE html>
<html lang="en">
    <script>
        function centrar() {
            iz=(screen.width-document.body.clientwidth) / 2;
            de=(screen.height-document.body.clientHeight) / 2;
            moveTo(iz,de);
        }
    </script>    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/estilo.css">
    <link rel="icon" type="image/png" href="../../img/logo_algj.png">

    <title>Actualizar datos</title>
</head>

<body onload="centrar();">

        <table class="center">
            <form autocomplete="off" name="form_regis" method="POST">

            <tr>
                    <td>ID usuario</td>
                    <td><input name ="id_us" value="<?php echo $usua['id_us']?>" readonly></td>
                </tr>

                <tr>
                    <td>Nombres</td>
                    <td><input name ="nombre_us" value="<?php echo $usua['nombre_us']?>"></td>
                </tr>

                <tr>
                    <td>Apellidos</td>
                    <td><input name ="apellido_us" value="<?php echo $usua['apellido_us']?>" ></td>
                </tr>

                <tr>
                    <td>Correo</td>
                    <td><input name ="correo_us" value="<?php echo $usua['correo_us']?>" ></td>
                </tr>

                <tr>
                    <td>Contraseña</td>
                    <td><input name ="pass" value="<?php echo $usua['pass']?>"></td>
                </tr>

                <tr>
                    <td>Telefono</td>
                    <td><input name ="tel_us" value="<?php echo $usua['tel_us']?>"></td>
                </tr>
                <tr>
                <tr>
                    <td>Puesto</td>   
                    <td>                 
                <select name="id_puesto">
                    <option value="<?php echo($usua['id_puesto'])?>"><?php echo($usua['cargo'])?></option>
                <?php
                    $control = $conexion->prepare("SELECT * from estado where ID_Es > 3");
                    $control->execute();
                    while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                     echo "<option value=" . $fila['id_puesto'] . ">" . $fila['id_puesto'] . "</option>";
                    }
                ?>

            </select>
                    </td>
                </tr>

                <tr>
                    <td>Rol</td>   
                    <td>                 
                <select name="id_rol">
                    <option value="<?php echo($usua['id_rol'])?>"><?php echo($usua['TP_user'])?></option>
                <?php
                    $control = $conexion->prepare("SELECT * from roles where ID > 0");
                    $control->execute();
                    while ($fila = $control->fetch(PDO::FETCH_ASSOC)) {
                     echo "<option value=" . $fila['ID'] . ">" . $fila['TP_user'] . "</option>";
                    }
                ?>

            </select>
                    </td>
                </tr>
        
                </tr>
            
        
                </tr>

                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr> 
                <tr>
                    <td><input type="submit" name="delete" value="Eliminar"></td>
            </tr>
            </form>
            </table>

            </body>
            </html>
            <?php
} else {
    echo '
    <script>
        alert("su rol no tiene acceso a esta pagina");
        window.location = "../../modulo_larry/PHP/login.php";
    </script>
    ';
}
?>