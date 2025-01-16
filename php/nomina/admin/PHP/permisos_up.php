<?php
session_start();
if (!isset($_SESSION['id_us'])) {
    echo '
            <script>
                alert("Por favor inicie sesión e intente nuevamente");
                window.location = "../../modulo_larry/PHP/login.php";
            </script>
            ';
    session_destroy();
    die();
}
require_once("../conexion/db.php");

$id_rol = $_SESSION['id_rol'];
if ($id_rol == '5') {
$sql = $conexion -> prepare ("SELECT * FROM permisos WHERE permisos.id_permiso = '".$_GET['id']."'");
$sql -> execute();
$usua = $sql -> fetch();
?>

<?php
if(isset($_POST["update"]))
{
    $fecha = $_POST['fecha'];
    $fecha_reingreso = $_POST['fecha_reingreso'];

    $insertSQL = $conexion->prepare ("UPDATE permisos SET fecha ='$fecha', fecha_reingreso = '$fecha_reingreso' WHERE id_permiso = '".$_GET['id']."'");
    $insertSQL->execute();
    echo '<script>alert ("Actualización Exitosa");
    window.close("permisos_up.php");
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
    
    <title>Actualizar datos</title>
</head>

<body onload="centrar();">

        <table class="center">
            <form autocomplete="off" name="form_regis" method="POST">

            <tr>
                    <td>ID usuario</td>
                    <td><input  name ="id_us" value="<?php echo $usua['id_us']?>" readonly></td>
                </tr>

                <tr>
                    <td>Fecha de salida</td>
                    <td><input type="datetime-local" name ="fecha" value="<?php echo $usua['fecha']?>"></td>
                </tr>

                <tr>
                    <td>Fecha de reingreso</td>
                    <td><input type="datetime-local" name ="fecha_reingreso" value="<?php echo $usua['fecha_reingreso']?>" ></td>
                </tr>



                </tr>
            
        
                </tr>

                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr> 
                <tr>
                    <td><input type="submit" name="update" value="Actualizar"></td>
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