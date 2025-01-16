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
$sql = $conexion -> prepare ("SELECT * FROM prestamo, estado WHERE prestamo.id_prest = '".$_GET['id']."'");
$sql -> execute();
$usua = $sql -> fetch();
?>

<?php
if(isset($_POST["delete"]))
{
    $insertSQL = $conexion->prepare("DELETE FROM prestamo WHERE id_prest = '".$_GET['id']."'");
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

    <title>Actualizar datos</title>
</head>

<body onload="centrar();">

<h2><center>ACTUALIZAR DATOS</center></h2>
        <table class="center">
            <form autocomplete="off" name="form_regis" method="POST">
                <tr>
                    <td>ID del prestamo</td>
                    <td><input name ="ID_prest" value="<?php echo $usua['ID_prest']?>" readonly></td>
                </tr>

                <tr>
                    <td>ID del empleado</td>
                    <td><input name ="ID_Empleado" value="<?php echo $usua['ID_Empleado']?>" readonly ></td>
                </tr>

                <tr>
                    <td>Fecha</td>
                    <td><input name ="Fecha" value="<?php echo $usua['Fecha']?>" ></td>
                </tr>
                <tr>
                    <td>Cantidad de cuotas</td>
                    <td><input name ="Cantidad_cuotas" value="<?php echo $usua['Cantidad_cuotas']?>" ></td>
                </tr>

                <tr>
                    <td>Valor de las cuotas</td>
                    <td><input name ="Valor_Cuotas" value="<?php echo $usua['Valor_Cuotas']?>" ></td>
                </tr>

                <tr>
                    <td>Cuotas en deuda</td>
                    <td><input name ="cuotas_en_deuda" value="<?php echo $usua['cuotas_en_deuda']?>" ></td>
                </tr>

                <tr>
                    <td>Cuotas pagas</td>
                    <td><input name ="cuotas_pagas" value="<?php echo $usua['cuotas_pagas']?>" ></td>
                </tr>

                <tr>
                    <td>Valor</td>
                    <td><input name ="VALOR" value="<?php echo $usua['VALOR']?>" ></td>
                </tr>

                <tr>
                    <td>Estado de solicitud</td>
                    <td>
                <select name="ESTADO_SOLICITUD">
                    <option value="<?php echo($usua['ESTADO_SOLICITUD'])?>"><?php echo($usua['Estado'])?></option>
                <?php
                    $control = $conexion -> prepare ("SELECT * from estado WHERE ID_Es > 3 ");
                    $control -> execute();
                while ($fila = $control->fetch(PDO::FETCH_ASSOC)) 
                {
                    echo "<option value=" . $fila['ID_Es'] . ">"
                    . $fila['Estado'] . "</option>";
                }
                ?>

            </select>
                    </td>
                </tr>
            
                    </td>
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