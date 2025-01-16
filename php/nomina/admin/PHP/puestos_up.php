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
require_once("../../conexion/db.php");

$id_rol = $_SESSION['id_rol'];
if ($id_rol == '5') {
    $sql = $conexion->prepare("SELECT * FROM puestos WHERE puestos.ID = '" . $_GET['id'] . "'");
    $sql->execute();
    $usua = $sql->fetch();
?>

    <?php
    if (isset($_POST["update"])) {
        $cargo = $_POST['cargo'];
        $salario = $_POST['salario'];

        $insertSQL = $conexion->prepare("UPDATE puestos SET cargo ='$cargo', salario = '$salario' WHERE ID = '" . $_GET['id'] . "'");
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
            iz = (screen.width - document.body.clientwidth) / 2;
            de = (screen.height - document.body.clientHeight) / 2;
            moveTo(iz, de);
        }
    </script>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <title>Actualizar datos</title>
    </head>

    <body onload="centrar();">

        <table class="center">
            <form autocomplete="off" name="form_regis" method="POST">

                <tr>
                    <div class="form-group col-md-4">
                        <td>ID Puesto</td>
                        <td><input class="form-control border border-dark mb-3" name="ID" value="<?php echo $usua['ID'] ?>" readonly></td>
                    </div>

                </tr>

                <tr>
                    <td>Cargo</td>
                    <td><input class="form-control border border-gray mb-3" type="text" name="cargo" value="<?php echo $usua['cargo'] ?>"></td>
                </tr>

                <tr>
                    <td>Salario</td>
                    <td><input id="salario" class="form-control border border-gray mb-3" type="text" name="salario" value="<?php echo $usua['salario'] ?>"></td>
                </tr>
                <script>
                    document.getElementById('salario').addEventListener('input', function(e) {
                        let value = e.target.value.replace(/[^0-9]/g, ''); // Elimina cualquier caracter que no sea un número
                        if (value.length > 10) { // Limita la entrada a 10 dígitos
                            value = value.slice(0, 7);
                        }
                        e.target.value = value;
                    });
                </script>







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