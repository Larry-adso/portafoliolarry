<?php
include '../../../conexion/db.php';
include '../../../conexion/validar_sesion.php';
require '../../../vendor/autoload.php'; // Ajusta la ruta si es necesario

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_us = $_POST['id'];
    $nombre_us = $_POST['nombre_us'];
    $apellido_us = $_POST['apellido_us'];
    $correo_us = $_POST['correo_us'];
    $tel_us = $_POST['tel_us'];
    $pass_original = $_POST['pass']; // Obtener la contraseña sin cifrar
    $pass = hash('sha512', $pass_original); // Calcular el hash de la contraseña
    $id_puesto = $_POST['id_puesto'];
    $id_rol = $_POST['id_rol'];
    $Codigo = $_POST['Codigo'];
    $id_empresa = $_POST['id_empresa'];
    $token = $_POST['token'];
    $id_estado = 5; // Aquí deberías establecer el valor correcto para id_estado

    // Manejo de la foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_nombre = $_FILES['foto']['name'];
        
        // Obtener la extensión de la imagen
        $extension = pathinfo($foto_nombre, PATHINFO_EXTENSION);
        
        // Generar un nombre único y al azar para la imagen con su extensión original
        $nuevo_nombre = uniqid(rand(), true) . '.' . $extension;
        
        // Definir la ruta de destino para la foto
        $ruta_foto = "../../../uploads/fotos/" . $nuevo_nombre;
        
        // Mover la foto de la ubicación temporal a la permanente con el nuevo nombre
        move_uploaded_file($foto_tmp, $ruta_foto);
    } else {
        // Si no se selecciona ninguna foto, asignar la ruta por defecto
        $ruta_foto = "../../../uploads/fotos/user.jpg";
    }

    // Validar si el id_us ya existe en la base de datos
    $sql_id = "SELECT COUNT(*) AS count FROM usuarios WHERE id_us = :id_us";
    $stmt_id = $conexion->prepare($sql_id);
    $stmt_id->bindParam(':id_us', $id_us);
    $stmt_id->execute();
    $result_id = $stmt_id->fetch(PDO::FETCH_ASSOC);

    // Validar si el correo_us ya existe en la base de datos
    $sql_correo = "SELECT COUNT(*) AS count FROM usuarios WHERE correo_us = :correo_us";
    $stmt_correo = $conexion->prepare($sql_correo);
    $stmt_correo->bindParam(':correo_us', $correo_us);
    $stmt_correo->execute();
    $result_correo = $stmt_correo->fetch(PDO::FETCH_ASSOC);

    // Si el id_us o el correo_us ya existen, mostrar mensaje de error
    if ($result_id['count'] > 0) {
        echo "<script>alert('El ID de usuario ya existe en la base de datos'); window.location.href='../index.php';</script>";
        exit; // Detener la ejecución si hay error
    }

    if ($result_correo['count'] > 0) {
        echo "<script>alert('El correo electrónico ya está registrado en la base de datos'); window.location.href='../index.php';</script>";
        exit; // Detener la ejecución si hay error
    }

    // Insertar datos en la base de datos
    $sql = "INSERT INTO usuarios (id_us, id_estado, nombre_us, apellido_us, correo_us, tel_us, pass, ruta_foto, id_puesto, id_rol, Codigo, id_empresa, token) 
            VALUES (:id_us, :id_estado, :nombre_us, :apellido_us, :correo_us, :tel_us, :pass, :ruta_foto, :id_puesto, :id_rol, :Codigo, :id_empresa, :token)";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_us', $id_us);
    $stmt->bindParam(':id_estado', $id_estado); // Aquí corregimos el binding para id_estado
    $stmt->bindParam(':nombre_us', $nombre_us);
    $stmt->bindParam(':apellido_us', $apellido_us);
    $stmt->bindParam(':correo_us', $correo_us);
    $stmt->bindParam(':tel_us', $tel_us);
    $stmt->bindParam(':pass', $pass);
    $stmt->bindParam(':ruta_foto', $ruta_foto); // Se guarda el nuevo nombre en la base de datos
    $stmt->bindParam(':id_puesto', $id_puesto);
    $stmt->bindParam(':id_rol', $id_rol);
    $stmt->bindParam(':Codigo', $Codigo);
    $stmt->bindParam(':id_empresa', $id_empresa);
    $stmt->bindParam(':token', $token);
    
    if ($stmt->execute()) {
        echo "<script>alert('Usuario insertado correctamente');</script>";

        // Enviar correo electrónico con PHPMailer
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
            $mail->addAddress($correo_us, $nombre_us . ' ' . $apellido_us);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Registro exitoso';
            $mail->Body = "
            <html>
            <head>
                <title>Registro exitoso</title>
                <style>
                    .card {
                        margin: 20px;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 10px;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                        font-family: Arial, sans-serif;
                    }
                    .card p {
                        margin: 0 0 10px;
                    }
                    .card img {
                        display: block;
                        margin: 10px 0;
                        max-width: 100%;
                        height: auto;
                    }
                </style>
            </head>
            <body>
                <div class='card'>
                    <p>Hola $nombre_us $apellido_us,</p>
                    <p>Has sido registrado exitosamente en nuestro sistema y asociado a la empresa con NIT: $id_empresa.</p>
                    
                    <p>Usuario : $id_us </p>
                    <p>Contraseña : $pass_original</p>  <!-- Contraseña sin encriptar -->
                    <p> Inicia sesión aquí: <a href='https://nominaalgj.000webhostapp.com/dev/PHP/login.php'>Iniciar sesión</a></p>
                    <p>Saludos,<br>Equipo de Soporte</p>
                </div>
            </body>
            </html>
            ";

            // Enviar el correo
            $mail->send();
            echo '<script>alert("Correo enviado exitosamente");</script>';
        } catch (Exception $e) {
            echo '<script>alert("No se pudo enviar el correo. Error: ' . $mail->ErrorInfo . '");</script>';
        }

        echo "<script>window.location.href='../index.php';</script>";
    } else {
        echo "<script>alert('Error al insertar el usuario'); window.location.href='../index.php';</script>";
    }
}
?>
