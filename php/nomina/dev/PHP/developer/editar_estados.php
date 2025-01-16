<?php
session_start();
if (!isset($_SESSION['id_us'])) {
    echo '
            <script>
                alert("Por favor inicie sesión e intente nuevamente");
                window.location = ../login.php";
            </script>
            ';
    session_destroy();
    die();
}
$id_rol = $_SESSION['id_rol'];
if ($id_rol == '5') {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Estados</title>
    <link rel="icon" type="image/png" href="../../../img/logo_algj.png">

    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/7fd910d257.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="underlay-photo"></div>
    <div class="underlay-black"></div>

    <?php
    include '../../../conexion/conexion.php';

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "SELECT * FROM estado WHERE ID_Es = $id";
        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $valor = $row['Estado'];
    ?>

    <form class="login-form" action="editar_estados.php" method="post">
        <h1>Editar Estados</h1>
        <p class="login-text">
            <span class="fa-stack">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa-solid fa-traffic-light fa-stack-1x"></i>
            </span>
            <h2>Editar Valor De Estados</h2>
            <?php
            // Mostrar mensaje si existe
            if (isset($_GET['mensaje'])) {
                echo "<h2>" . urldecode($_GET['mensaje']) . "</h2>";
            }
            ?>
        </p>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="text" class="login-username" autofocus="true" required="true" placeholder="Nuevo Estado" name="Estado" value="<?php echo $valor; ?>" />
        <input type="submit" name="update" value="Actualizar Estado" class="login-submit" />
    </form>

    <?php
        } else {
            echo "No se encontró el estado con el ID proporcionado.";
        }
    }

    // Verificar si se envió un formulario POST para actualizar el valor
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        $id = $_POST['id'];
        $valors = $_POST['Estado'];

        $sql = "UPDATE estado SET Estado='$valors' WHERE ID_Es =$id";

        if ($conexion->query($sql) === TRUE) {
            $mensaje = "El Estado ha sido actualizado correctamente.";
        } else {
            $mensaje = "Error al actualizar el Estado: " . $conexion->error;
        }

        // Redirigir a esta página con un mensaje codificado en la URL
        header("Location: editar_estados.php?id=$id&mensaje=" . urlencode($mensaje));
        exit(); // Salir del script después de la redirección
    }
    ?>

</body>

</html>
<?php
} else {
    echo '
    <script>
        alert("su rol no tiene acceso a esta pagina");
        window.location = ../login.php";
    </script>
    ';
}
?>