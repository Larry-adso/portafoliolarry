<?php
session_start();
if (!isset($_SESSION['id_us'])) {
    echo '
    <script>
        alert("Por favor inicie sesión e intente nuevamente");
        window.location = "../login.php";
    </script>
    ';
    session_destroy();
    die();
}
include("../../../conexion/db.php");

$id_rol = $_SESSION['id_rol'];
if ($id_rol == '5') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $NIT = $_POST["NIT"];
        $id_usuario = $_SESSION['id_us'];

        try {
            // Obtener el serial asignado a la empresa según el NIT
            $consultaSerial = $conexion->prepare("SELECT e.NIT, l.Serial, l.TP_licencia FROM empresas e 
            INNER JOIN licencia l ON e.ID_Licencia = l.ID WHERE e.NIT = :NIT AND l.ID_Estado = 5;");
            $consultaSerial->bindParam(":NIT", $NIT);
            $consultaSerial->execute();

            $row = $consultaSerial->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $Serial = $row['Serial'];
                $tipoLicencia = $row['TP_licencia'];
                $empresa_id = $row['NIT'];

                // Verificar si el usuario está asociado a esta empresa
                $validacionUsuario = $conexion->prepare("SELECT COUNT(*) as count FROM usuarios WHERE id_us = :id_usuario AND id_empresa = :empresa_id");
                $validacionUsuario->bindParam(":id_usuario", $id_usuario);
                $validacionUsuario->bindParam(":empresa_id", $empresa_id);
                $validacionUsuario->execute();
                $usuarioAsociado = $validacionUsuario->fetch(PDO::FETCH_ASSOC);

                if ($usuarioAsociado['count'] == 1) {
                    // Actualizar el estado de la licencia a activo (ID_Estado = 1)
                    $updateLicencia = $conexion->prepare("UPDATE licencia SET ID_Estado = 1 WHERE Serial = :Serial");
                    $updateLicencia->bindParam(":Serial", $Serial);
                    $updateLicencia->execute();

                    // Calcular la fecha de fin de la licencia según el tipo de licencia
                    $fechaInicio = date('Y-m-d H:i:s'); // Fecha actual
                    $fechaFin = date('Y-m-d H:i:s', strtotime('+1 year', strtotime($fechaInicio))); // Por defecto 1 año
                    if ($tipoLicencia == 1213) {
                        // Si es de tipo 1213 (6 meses)
                        $fechaFin = date('Y-m-d H:i:s', strtotime('+6 months', strtotime($fechaInicio)));
                    } elseif ($tipoLicencia == 1214) {
                        // Si es de tipo 1214 (1 año)
                        $fechaFin = date('Y-m-d H:i:s', strtotime('+1 year', strtotime($fechaInicio)));
                    }

                    // Actualizar el campo F_inicio y F_fin en la tabla licencia
                    $updateFechas = $conexion->prepare("UPDATE licencia SET F_inicio = :fechaInicio, F_fin = :fechaFin WHERE Serial = :Serial");
                    $updateFechas->bindParam(":Serial", $Serial);
                    $updateFechas->bindParam(":fechaInicio", $fechaInicio);
                    $updateFechas->bindParam(":fechaFin", $fechaFin);
                    $updateFechas->execute();

                    echo '<script>alert("El estado de la licencia se ha actualizado correctamente, para confirmar vuelve e inicia sesión");
                    window.location = "../login.php";
                    </script>';
                } else {
                    echo '<script>alert("Lo siento, no puedes activar una licencia que no está asociada a tu empresa.");</script>';
                }
            } else {
                echo '<script>alert("No se encontró ninguna empresa con el NIT proporcionado o la licencia ya está activa.");</script>';
            }
        } catch (PDOException $ex) {
            echo "Error de consulta: " . $ex->getMessage();
        }
    }

?>


    <!doctype html>
    <html lang="en">

    <head>
        <title>Activación Licencia de Empresa</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <link rel="icon" type="image/png" href="../../../img/logo_algj.png">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <!-- Bootstrap CSS v5.2.1 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
        <script>
            function validarNIT(event) {
                const input = event.target;
                const value = input.value;

                // Permitir solo números
                input.value = value.replace(/\D/g, '');

                // Verificar si la longitud es 10
                if (input.value.length > 10) {
                    input.value = input.value.slice(0, 10);
                }

                // Actualizar el mensaje
                const message = document.getElementById('message');
                if (input.value.length < 10) {
                    message.textContent = `Llevas ${input.value.length} caracteres. Deben ser 10.`;
                } else {
                    message.textContent = '';
                }
            }

            function validarFormulario(event) {
                const input = document.querySelector('input[name="NIT"]');
                if (input.value.length !== 10) {
                    alert('El NIT debe contener exactamente 10 números.');
                    event.preventDefault();
                }
            }

            window.addEventListener('DOMContentLoaded', (event) => {
                const inputNIT = document.querySelector('input[name="NIT"]');
                inputNIT.addEventListener('input', validarNIT);

                const form = document.querySelector('form');
                form.addEventListener('submit', validarFormulario);
            });
        </script>
        <style>
            /* Estilos personalizados para la tarjeta central */
            #central-card {
                max-width: 500px;
                margin: 0 auto;
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                background-color: #f9f9f9;
            }

            .message {
                color: blue;
                font-size: 0.9em;
            }

            #central-card h2 {
                margin-bottom: 20px;
                color: #333;
                text-align: center;
            }

            #central-card label {
                font-weight: bold;
                color: #555;
            }

            #central-card input[type="text"],
            #central-card input[type="email"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 20px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
            }

            #central-card button[type="submit"] {
                display: block;
                width: 100%;
                padding: 10px;
                border: none;
                border-radius: 5px;
                background-color: #007bff;
                color: #fff;
                cursor: pointer;
            }

            #central-card button[type="submit"]:hover {
                background-color: #0056b3;
            }
        </style>
    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <br>
        <br>
        <br>
        <br>
       
        
        <main>
            <div class="container mt-5">
                <hr>
                <h2 style="text-align: center;" >Modulo de activacion de seriales solo para admin</h2>
                <hr>
                <br>
                <br>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <form action="" method="post" class="border p-4">
                            <label for="NIT" class="form-label">Por su seguridad digite manualmente el NIT de su empresa:</label>
                            <input type="text" name="NIT" pattern="[0-9]{10}" maxlength="13" required class="form-control mb-3">
                            <div id="message" class="message"></div>

                            <button type="submit" style="text-align: center;" class="btn btn-success">Activar</button>
                            <a name="" id="" class="btn btn-danger" href="../login.php" role="button">Cancelar proceso</a>

                        </form>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Qb2e0lkoqg4qbslQU5gUy" crossorigin="anonymous"></script>
    </body>

    </html>
<?php
} else {
    echo '
    <script>
        alert("Su rol no tiene acceso a esta página");
        window.location = "../login.php";
    </script>
    ';
}
?>