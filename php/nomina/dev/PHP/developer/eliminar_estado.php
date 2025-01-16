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

$id_rol = $_SESSION['id_rol'];
if ($id_rol == '5') {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar estados</title>
    <link rel="icon" type="image/png" href="../../../img/logo_algj.png">

    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/7fd910d257.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="underlay-photo"></div>
    <div class="underlay-black"></div>

    <?php
    include '../../conexion/db.php';

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
        $id = $_GET['id'];

        if (isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
            $sql = "DELETE FROM estado WHERE ID_Es = $id";

            if ($conn->query($sql) === TRUE) {
                $mensaje = "estado eliminado correctamente.";
                header("Location:../../index.php?mensaje=" . urlencode($mensaje));
            } else {
                $mensaje = "Error al eliminar el estado: " . $conn->error;
                echo "<h2>$mensaje</h2>";
            }
            exit();
        }

        $sql = "SELECT * FROM estado WHERE ID_Es = $id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $valor = $row['Estado'];
    ?>

            <div class="login-form">
                <h1 style="color:white;">Eliminar Estado</h1>
                <p class="login-text">
                    <span class="fa-stack">
                        <i class="fa fa-circle fa-stack-2x"></i>
                        <i class="fa-solid fa-sack-dollar fa-stack-1x"></i>
                    </span>
                    <h2>Eliminar Estado</h2>
                </p>
                <?php
                // Mostrar mensaje si existe
                if (isset($_GET['mensaje'])) {
                    echo "<h2>" . urldecode($_GET['mensaje']) . "</h2>";
                }
                ?>
                <p style="color:white;">¿Estás seguro de que deseas eliminar este Estado?</p>
                <form id="delete-form" action="" method="get">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="confirm" value="true">
                    <input type="submit" name="delete" value="Eliminar" class="login-submit" onclick="confirmDelete(event)" />
                </form>
            </div>
            <h2>--</h2>

            <script>
                function confirmDelete(event) {
                    event.preventDefault();
                    var confirmDelete = confirm("¿Estás seguro de que deseas eliminar este estado?");
                    if (confirmDelete) {
                        document.getElementById('delete-form').submit();
                    }
                }
            </script>

    <?php
        } else {
            echo "No se encontró el estado con el ID proporcionado.";
        }
    }
    ?>

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