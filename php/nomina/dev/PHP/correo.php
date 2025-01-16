<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

include("../../conexion/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_us = $_POST["id_us"];

    $consulta = $conexion->prepare("SELECT correo_us, pass FROM usuarios WHERE id_us = :id_us");
    $consulta->bindParam(":id_us", $id_us);
    $consulta->execute();

    // Verificamos si se encontró un usuario
    if ($consulta->rowCount() > 0) {
        // Si se encontró, obtenemos el correo asociado al usuario
        $correo_resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $correo_us = $correo_resultado['correo_us'];
        $pass = $correo_resultado['pass'];

        // Crear sesión con id_us
        $_SESSION['id_us'] = $id_us;

        $titulo = "Recuperación de contraseña";
        $msj = "Su contraseña actual es: $pass. <br>  Por su seguridad, es recomendable cambiar su contraseña.";

        // Crear una instancia de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Especifica el servidor SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'senatrabajos2022@gmail.com';  // Tu dirección de correo
            $mail->Password = 'ifan ewbg exlf hjck';  // Tu contraseña de correo
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Remitente y destinatario
            $mail->setFrom('senatrabajos2022@gmail.com', 'Soporte');
            $mail->addAddress($correo_us);

            // Contenido del correo
            $mail->isHTML(false);
            $mail->Subject = $titulo;
            $mail->Body = $msj;

            // Enviar el correo
            $mail->send();
            echo '<script>
                alert("Su código de verificación fue enviado a: ' . $correo_us . '. Gracias por usar el sistema de recuperación.");
                window.location = "code.php";
                </script>';
        } catch (Exception $e) {
            echo '<script>alert("No se pudo enviar el correo. Error: ' . $mail->ErrorInfo . '");</script>';
        }
    } else {
        echo "No se encontró ningún usuario con esa información.";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Recuperacion por correp</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdnjsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/correo.css">
    <link rel="icon" type="image/png" href="../../img/logo_algj.png">
</head>

<body>
    <section class="banner">
        <div class="content-banner">
            <h2> Recuperación por correo electrónico</h2>
        </div>
    </section>
    <main class="main-content">

        <div id="central-card">
            <h2>Recuperar Contraseña</h2>
            <form action="" method="post">
                <label for="ID">Documento:</label>
                <input type="text" name="id_us" pattern="[0-9]{10}" maxlength="10" required>
                <button type="submit" class="btn-success">Enviar</button>
            </form>
        </div>
        <a name="" id="" class="boton_volver" href="login.php" role="button">Volver</a>

    </main>

    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>