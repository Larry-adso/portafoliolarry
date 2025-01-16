<?php
include "../../conexion/db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_us = $_POST["id_us"];
    $pass = $_POST["pass"];

    // 1. Use prepared statements to prevent SQL injection
    $consulta = $conexion->prepare("SELECT * FROM triggers JOIN usuarios ON usuarios.id_us = triggers.id_us WHERE triggers.id_us = :id_us AND triggers.pass = :pass");
    $consulta->bindParam(":id_us", $id_us);
    $consulta->bindParam(":pass", $pass);
    $pass = hash('sha512', $pass);

    $consulta->execute();

    // 2. Check if a user was found
    if ($consulta->rowCount() == 1) {
        // Inicio de sesión exitoso
        $_SESSION["id_us"] = $id_us;
        header("Location: update.php"); // Redirect to the successful login page
        exit; // Stop further execution
    } elseif ($consulta->rowCount() == 0) {
        // Las credenciales son incorrectas
        echo '<script>
                alert("Credenciales incorrectas. Por favor, inténtelo nuevamente.");
              </script>';
    } else {
        // No es posible iniciar sesión por este medio
        echo '<script>
                alert("No es posible iniciar sesión por este medio. Por favor, inténtelo nuevamente.");
                window.location = "metodos.php";
              </script>';
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="css/recuperar.css">
    <link rel="icon" type="image/png" href="../../img/logo_algj.png">


</head>

<body>
<section class="banner">
		<div class="content-banner">
			<h2> Recuperar con contraseña anterior</h2>
		</div>
</section>
<main class="main-content">
    <form action="" method="post">
        <h2>Recuperar Contraseña</h2>
        <label for="ID">Documento:</label>
        <input type="text" name="id_us" pattern="[0-9]{10}" maxlength="10" required>

        <label for="password">password:</label>
        <input type="password" name="pass" required>

        <button type="submit" class="btn-success">Enviar</button>

    </form>
    <a name="" id="" class="boton_volver" href="login.php" role="button">Volver</a>
    </main>

</body>

</html>