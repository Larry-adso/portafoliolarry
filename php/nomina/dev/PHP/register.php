<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

session_start();

if (!isset($_SESSION['id_us'])) {
    echo '
        <script>
            alert("Por favor inicie sesión e intente nuevamente");
            window.location = "../login.php";
        </script>
    ';
    die();
}

include("../../conexion/db.php");

$id_rol = $_SESSION['id_rol'];
if ($id_rol == '4') {
    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "regm")) {
        $id_us = $_POST['id_us'];

        // Verificar si ya existe un usuario con el mismo id_us
        $checkSQL = $conexion->prepare("SELECT COUNT(*) AS count FROM usuarios WHERE id_us = :id_us");
        $checkSQL->bindParam(':id_us', $id_us);
        $checkSQL->execute();
        $result = $checkSQL->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            echo '
                <script>
                    alert("El ID de usuario ya está en uso. Por favor, elija otro ID.");
                    window.location = document.referrer;  // Redireccionar a la página anterior
                </script>
            ';
            die();
        }

        // Resto del código para la inserción si el ID de usuario no está en uso
        $id_us = $_POST['id_us'];
        $nombre_us = $_POST['nombre_us'];
        $apellido_us = $_POST['apellido_us'];
        $correo_us = $_POST['correo_us'];
        $tel_us = $_POST['tel_us'];
        $pass = $_POST['pass'];  // Contraseña sin encriptar
        $Codigo = $_POST['Codigo'];
        $id_rol = $_POST['id_rol'];
        $id_empresa = $_POST['id_empresa'];

        // Encriptar la contraseña antes de guardarla en la base de datos
        $encrypted_pass = hash('sha512', $pass);

        // Preparamos la consulta SQL con el campo id_estado
        $insertSQL = $conexion->prepare("INSERT INTO usuarios (id_us, nombre_us, apellido_us, correo_us, tel_us, pass, id_rol, Codigo, id_empresa, id_estado) 
                                 VALUES (:id_us, :nombre_us, :apellido_us, :correo_us, :tel_us, :pass, :id_rol, :Codigo, :id_empresa, 5)");

        // Vinculamos los parámetros
        $insertSQL->bindParam(':id_us', $id_us);
        $insertSQL->bindParam(':nombre_us', $nombre_us);
        $insertSQL->bindParam(':apellido_us', $apellido_us);
        $insertSQL->bindParam(':correo_us', $correo_us);
        $insertSQL->bindParam(':tel_us', $tel_us);
        $insertSQL->bindParam(':pass', $encrypted_pass);  // Contraseña encriptada
        $insertSQL->bindParam(':id_rol', $id_rol);
        $insertSQL->bindParam(':Codigo', $Codigo);
        $insertSQL->bindParam(':id_empresa', $id_empresa);

        // Ejecutamos la consulta
        $insertSQL->execute();

        // Obtener la URL completa de la imagen del código de barras desde la tabla empresas
        $empresaSQL = $conexion->prepare("SELECT barcode FROM empresas WHERE NIT = :id_empresa");
        $empresaSQL->bindParam(':id_empresa', $id_empresa);
        $empresaSQL->execute();
        $empresa = $empresaSQL->fetch(PDO::FETCH_ASSOC);

        // Construir la ruta de la imagen
        $barcodeURL = __DIR__ . '/' . $empresa['barcode'];

        // Crear una instancia de PHPMailer
        $mail = new PHPMailer(true);
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();


        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Especifica el servidor SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];  // Tu dirección de correo
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Remitente y destinatario
            $mail->setFrom('windonpc125@gmail.com', 'Soporte');
            $mail->addAddress($correo_us, $nombre_us . ' ' . $apellido_us);

            // Adjuntar la imagen si existe
            if (file_exists($barcodeURL)) {
                $mail->addAttachment($barcodeURL, 'barcode.png');
            }

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
                    <p>contraseña : $pass</p>  <!-- Contraseña sin encriptar -->
                    <p>Se adjunta una imagen con un código de barras. Para mayor facilidad, puedes imprimirlo y
                    escanearlo en la sección de activación para activar tu licencia. </p>
                    <p>RECUERDE QUE DEBE IMPRIMIR EL CODIGO CON UNA IMPRESORA A LASER</p>
                    <p> Inicia sesión aquí: <a href='http://localhost/nomina_algj/dev/PHP/login.php'>Iniciar sesión</a></p>
                    <p>Saludos,<br>Equipo de Soporte</p>
                </div>
            </body>
            </html>
            ";

            // Enviar el correo
            $mail->send();
            echo '<script>alert("Registro exitoso");</script>';
        } catch (Exception $e) {
            echo '<script>alert("No se pudo enviar el correo. Error: ' . $mail->ErrorInfo . '");</script>';
        }
    }

    $conx = $conexion->prepare("SELECT * FROM roles WHERE ID IN (4, 5)");
    $conx->execute();
    $conz = $conx->fetchAll(PDO::FETCH_ASSOC);

    $cons = $conexion->prepare("SELECT * FROM empresas");
    $cons->execute();
    $empresas = $cons->fetchAll(PDO::FETCH_ASSOC);

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://uniconnect.uniconnectscout.com/release/v2.1.9/css/uniconnect.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <title>Document</title>
        <link rel="icon" type="image/png" href="../../img/logo_algj.png">
        <style>
            body {
                background-color: white;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h1 class="mb-4 pb-3 text-center">REGISTRO DE PERSONAS</h1>
                    <form action="#" name="form" method="post" onsubmit="return validarFormulario()" autocomplete="off">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="id_us" class="form-label">* Cedula</label>
                                <input type="text" class="form-control" title="Solo se permiten números con un máximo de 10 dígitos" name="id_us" id="id_us" placeholder="Cedula del usuario" required oninput="validarCedula(this)">
                                <small id="idUsHelpBlock" class="form-text"></small>
                            </div>
                            <div class="col-md-4">
                                <label for="nombre_us" class="form-label">Nombre</label>
                                <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo se permiten letras" name="nombre_us" id="nombre_us" placeholder="Nombre Completo" oninput="validarNombre(this)">
                                <small id="nombreUsHelpBlock" class="form-text"></small>
                            </div>
                            <div class="col-md-4">
                                <label for="apellido_us" class="form-label">Apellido</label>
                                <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo se permiten letras" name="apellido_us" id="apellido_us" placeholder="Apellido completo" oninput="validarApellido(this)">
                                <small id="apellidoUsHelpBlock" class="form-text"></small>
                            </div>
                            <div class="col-md-4">
                                <label for="correo_us" class="form-label">Correo</label>
                                <input type="email" class="form-control" name="correo_us" id="correo_us" placeholder="Correo electronico" required>
                            </div>
                            <div class="col-md-4">
                                <label for="pass" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" title="Debe ser alfanumérico de al menos 8 caracteres con una letra en mayúscula" name="pass" id="pass" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">Mostrar</button>
                                </div>
                                <small id="passHelp" class="form-text"></small>
                            </div>

                            <script>
                                // Obtener referencia al campo de contraseña y al botón de alternar visibilidad
                                var passInput = document.getElementById('pass');
                                var toggleButton = document.getElementById('togglePassword');

                                // Agregar evento al botón para alternar entre tipo password y text
                                toggleButton.addEventListener('click', function() {
                                    if (passInput.type === 'password') {
                                        passInput.type = 'text';
                                        toggleButton.textContent = 'Ocultar';
                                    } else {
                                        passInput.type = 'password';
                                        toggleButton.textContent = 'Mostrar';
                                    }
                                });

                                // Agregar evento input al campo de contraseña
                                passInput.addEventListener('input', function(event) {
                                    var password = event.target.value;
                                    var remainingCharacters = Math.max(8 - password.length, 0);
                                    var uppercaseRegex = /[A-Z]/;
                                    var hasUppercase = uppercaseRegex.test(password);

                                    var passHelp = document.getElementById('passHelp');

                                    if (remainingCharacters > 0) {
                                        passHelp.textContent = 'Ingrese al menos ' + remainingCharacters + ' caracteres más.';
                                        passHelp.classList.remove('text-success');
                                        passHelp.classList.add('text-danger');
                                    } else if (!hasUppercase) {
                                        passHelp.textContent = 'La contraseña debe contener al menos una letra en mayúscula.';
                                        passHelp.classList.remove('text-success');
                                        passHelp.classList.add('text-danger');
                                    } else {
                                        passHelp.textContent = 'Correcto';
                                        passHelp.classList.remove('text-danger');
                                        passHelp.classList.add('text-success');
                                    }

                                    if (password.length > 8) {
                                        event.target.value = password.slice(0, 8);
                                    }
                                });
                            </script>

                            <div class="col-md-4">
                                <label for="id_empresa" class="form-label">NIT_empresa <a style="text-decoration: none;" href="#" onclick="abrirVentanaEmpresa()"> Crear</a></label>
                                <select class="form-select form-select-sm input" name="id_empresa" id="id_empresa" required>
                                    <option value="" selected disabled>Seleccione una empresa</option>
                                    <?php foreach ($empresas as $empresas_m) { ?>
                                        <option value="<?php echo $empresas_m['NIT']; ?>">
                                            <?php echo  "  NIT: " . $empresas_m['NIT'] . " - Nombre: " . $empresas_m['Nombre']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="tel_us" class="form-label">Telefono</label>
                                <input type="text" class="form-control" title="Debe ser un número de 10 dígitos" name="tel_us" id="tel_us" placeholder="" required oninput="validarTelefono(this)">
                                <small id="telUsHelpBlock" class="form-text"></small>
                            </div>
                            <div class="col-md-4">
                                <label for="Codigo" class="form-label">Codigo de seguridad</label>
                                <input type="password" class="form-control" title="Debe ser un número de 4 dígitos" name="Codigo" id="Codigo" placeholder="" maxlength="4" required>
                                <small id="codigoHelp" class="form-text"></small>
                            </div>

                            <script>
                                // Obtener referencia al campo de entrada
                                var codigoInput = document.getElementById('Codigo');
                                // Agregar evento input
                                codigoInput.addEventListener('input', function(event) {
                                    // Obtener el valor actual del campo de entrada
                                    var codigo = event.target.value;
                                    // Validar si el valor ingresado contiene solo números
                                    if (!/^\d*$/.test(codigo)) {
                                        // Si no cumple con la condición, eliminar el último carácter ingresado
                                        codigoInput.value = codigo.slice(0, -1);
                                    }
                                    // Validar si el valor tiene exactamente 4 dígitos
                                    var codigoHelp = document.getElementById('codigoHelp');
                                    if (codigo.length === 4 && !/^\d{4}$/.test(codigo)) {
                                        codigoHelp.textContent = 'El código debe contener solo 4 números.';
                                        codigoHelp.classList.remove('text-success');
                                        codigoHelp.classList.add('text-danger');
                                    } else {
                                        codigoHelp.textContent = '';
                                        codigoHelp.classList.remove('text-danger');
                                        codigoHelp.classList.add('text-success');
                                    }
                                });
                            </script>

                            <div class="col-md-4">
                                <label for="id_rol" class="form-label">Usuarios</label>
                                <select class="form-select" name="id_rol" id="id_rol" required>
                                    <option value="" selected disabled>Seleccione un tipo de usuario</option>
                                    <?php foreach ($conz as $pregunta) { ?>
                                        <option value="<?php echo $pregunta['ID']; ?>">
                                            <?php echo $pregunta['Tp_user']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-primary me-2" type="submit" name="validar">Registrar</button>
                            <a class="btn btn-danger" href="../index.php">Inicio</a>
                        </div>
                        <input type="hidden" name="MM_insert" value="regm">
                    </form>
                </div>
            </div>
        </div>

        <script>
            function validarCedula(input) {
                var cedula = input.value.replace(/\D/g, '');
                if (cedula.length > 10) {
                    cedula = cedula.slice(0, 10);
                }
                input.value = cedula;
                var charsRemaining = 10 - cedula.length;
                var idUsHelpBlock = document.getElementById('idUsHelpBlock');
                if (cedula.length === 10) {
                    idUsHelpBlock.textContent = "Cedula: Correcto";
                    idUsHelpBlock.classList.remove('text-danger');
                    idUsHelpBlock.classList.add('text-success');
                } else {
                    idUsHelpBlock.textContent = "Cedula: Deben ser 10 números. Faltan " + charsRemaining;
                    idUsHelpBlock.classList.remove('text-success');
                    idUsHelpBlock.classList.add('text-danger');
                }
            }

            function validarNombre(input) {
                var nombre = input.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
                if (nombre.length > 20) {
                    nombre = nombre.slice(0, 20);
                }
                input.value = nombre;
                var charsRemaining = 20 - nombre.length;
                var nombreUsHelpBlock = document.getElementById('nombreUsHelpBlock');
                if (nombre.length === 20) {
                    nombreUsHelpBlock.textContent = "Nombre: Correcto";
                    nombreUsHelpBlock.classList.remove('text-danger');
                    nombreUsHelpBlock.classList.add('text-success');
                } else {
                    nombreUsHelpBlock.textContent = "Nombre: Solo letras. Máximo 20 caracteres. Quedan " + charsRemaining;
                    nombreUsHelpBlock.classList.remove('text-success');
                    nombreUsHelpBlock.classList.add('text-secondary');
                }
            }


            function validarApellido(input) {
                var apellido = input.value.replace(/[^A-Za-zñÑáéíóúÁÉÍÓÚ\s]/g, '');
                if (apellido.length > 20) {
                    apellido = apellido.slice(0, 20);
                }
                input.value = apellido;
                var charsRemaining = 20 - apellido.length;
                var apellidoUsHelpBlock = document.getElementById('apellidoUsHelpBlock');
                if (apellido.length === 20) {
                    apellidoUsHelpBlock.textContent = "Apellido: Correcto";
                    apellidoUsHelpBlock.classList.remove('text-danger');
                    apellidoUsHelpBlock.classList.add('text-success');
                } else {
                    apellidoUsHelpBlock.textContent = "Apellido: Solo letras. Máximo 20 caracteres. Quedan " + charsRemaining;
                    apellidoUsHelpBlock.classList.remove('text-success');
                    apellidoUsHelpBlock.classList.add('text-secondary');
                }
            }

            function validarTelefono(input) {
                var telefono = input.value.replace(/\D/g, '');
                if (telefono.length > 10) {
                    telefono = telefono.slice(0, 10);
                }
                input.value = telefono;
                var charsRemaining = 10 - telefono.length;
                var telUsHelpBlock = document.getElementById('telUsHelpBlock');
                if (telefono.length === 10) {
                    telUsHelpBlock.textContent = "Teléfono: Correcto";
                    telUsHelpBlock.classList.remove('text-danger');
                    telUsHelpBlock.classList.add('text-success');
                } else {
                    telUsHelpBlock.textContent = "Teléfono: Deben ser 10 números. Faltan " + charsRemaining;
                    telUsHelpBlock.classList.remove('text-success');
                    telUsHelpBlock.classList.add('text-danger');
                }
            }

            function validarFormulario() {
                // Aquí puedes agregar validaciones adicionales si es necesario
                return true; // Devuelve true para permitir el envío del formulario
            }

            function abrirVentanaEmpresa() {
                // Abrir una ventana emergente
                var ventanaSerial = window.open("", "VentanaSerial", "width=600,height=600");

                // Hacer una solicitud GET al archivo serial.php
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Cuando se complete la solicitud con éxito, insertar el contenido en la ventana emergente
                        ventanaSerial.document.write(this.responseText);
                    }
                };
                xhttp.open("GET", "modal/modal_empresa.php", true);
                xhttp.send();
            }
        </script>
        <script src=""></script>
    </body>

    </html>

<?php

} else {
    echo '
    <script>
        alert("Su rol no tiene acceso a esta pagina");
        window.location = "../login.php";
    </script>
    ';
}
?>