<?php

session_start();
if (!isset($_SESSION['id_us'])) {
    echo '
 <script>
        alert("Por favor inicie sesión e intente nuevamente");
        window.location = "login.php";
    </script>
    ';
    session_destroy();
    die();
}
include "../../conexion/db.php";

// Obtener el ID del usuario actual
$id_usuario = $_SESSION['id_us'];

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el código de verificación del formulario
    $pass = (isset($_POST["pass"]) ? $_POST["pass"] : "");

    // Preparar la consulta SQL para verificar el código de verificación
    $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE id_us = :id_usuario AND pass = :pass");

    // Vincular los parámetros
    $consulta->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $consulta->bindParam(':pass', $pass, PDO::PARAM_STR);

    // Ejecutar la consulta
    $consulta->execute();

    // Verificar si se encontró un registro con el código de verificación
    if ($consulta->rowCount() > 0) {
        // Si coincide, redirigir a update.php
        header("Location: update.php");
        exit();
    } else {
        echo '<script>alert("Código de verificación incorrecto. Por favor, inténtelo de nuevo.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Actualizar Contraseña</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/recuperar.css">
    <link rel="icon" type="image/png" href="../../img/logo_algj.png">

</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg ">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Validar Codigo Para El Usuario :</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="navbar-brand" href="#"><?php echo $_SESSION["id_us"]; ?></a>


                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="container">
            <div class="card-body">
                <h4 class="card-title text-center">Validación de Código de Seguridad</h4>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="mb-3">
                        <label for="password" class="form-label">Ingrese su código de verificación:</label>
                        <input type="text" class="form-control" name="pass" id="password" placeholder="Ingrese su codigo de verificacion" required />
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Validar código </button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <a class="boton_volver" href="cerrar.php" role="button">Cerrar</a>
                </div>
            </div>
    </main>
    <footer class="mt-5 text-center">
        <!-- Place footer content here -->
    </footer>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>