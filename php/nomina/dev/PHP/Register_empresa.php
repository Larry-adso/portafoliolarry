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

// Incluir la librería de generación de código de barras
require '../../vendor/autoload.php';
use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorPNG;

$id_rol = $_SESSION['id_rol'];
if ($id_rol == '4') {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $NIT = isset($_POST["NIT"]) ? $_POST["NIT"] : "";
        $Nombre = isset($_POST["Nombre"]) ? $_POST["Nombre"] : "";
        $Id_licencia = isset($_POST["ID_Licencia"]) ? $_POST["ID_Licencia"] : "";
        $Correo = isset($_POST["Correo"]) ? $_POST["Correo"] : "";
        $Telefono = isset($_POST["Telefono"]) ? $_POST["Telefono"] : "";

        // Verificar si el NIT ya existe en la base de datos 
        $verificarNIT = $conexion->prepare("SELECT * FROM empresas WHERE NIT = :NIT");
        $verificarNIT->bindParam(":NIT", $NIT);
        $verificarNIT->execute();
        $resultadoNIT = $verificarNIT->fetch(PDO::FETCH_ASSOC);

        if ($resultadoNIT) {
            // Mostrar alerta de que el NIT ya está registrado
            echo '<script>
                alert("El NIT ya está registrado. No se puede guardar el registro.");
            </script>';
        } else {
            // Generar el código de barras
            $generator = new BarcodeGeneratorPNG();
            $barcode = $generator->getBarcode($NIT, $generator::TYPE_CODE_128);

            // Guardar el código de barras en la ruta img/
            $barcodePath = 'img/' . $NIT . '.png';
            file_put_contents($barcodePath, $barcode);

            // Insertar el nuevo registro en la tabla empresas
            $sentencia = $conexion->prepare("INSERT INTO empresas(NIT, Nombre, Correo, Telefono, ID_Licencia, barcode) 
                VALUES (:NIT, :Nombre, :Correo, :Telefono, :ID_Licencia, :barcode)");
            $sentencia->bindParam(":NIT", $NIT);
            $sentencia->bindParam(":Nombre", $Nombre);
            $sentencia->bindParam(":Correo", $Correo);
            $sentencia->bindParam(":Telefono", $Telefono);
            $sentencia->bindParam(":ID_Licencia", $Id_licencia);
            $sentencia->bindParam(":barcode", $barcodePath);

            if ($sentencia->execute()) {
                $mensaje = "Registro creado correctamente";
                echo '<script>
                    alert("Registro creado correctamente");
                    window.location = "../index.php";
                </script>';

                // Actualizar el estado de la licencia a 5
                $actualizarEstado = $conexion->prepare("UPDATE licencia SET ID_Estado = 5 WHERE ID = :ID_Licencia");
                $actualizarEstado->bindParam(":ID_Licencia", $Id_licencia);
                $actualizarEstado->execute();
            } else {
                $mensaje = "Error al crear el registro";
                echo '<script>
                    alert("Error al crear el registro");
                </script>';
            }
        }
    }

    $consultaLicencia = $conexion->prepare("SELECT licencia.ID, licencia.Serial, tp_licencia.Tipo FROM licencia 
        INNER JOIN tp_licencia ON licencia.TP_licencia = tp_licencia.ID WHERE licencia.ID_estado = 3");
    $consultaLicencia->execute();
    $Tp_licencia = $consultaLicencia->fetchAll(PDO::FETCH_ASSOC);
?>

    <!doctype html>
    <html lang="en">

    <head>
        <title>Title</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <!-- Bootstrap CSS v5.2.1 -->
        <link rel="icon" type="image/png" href="../../img/logo_algj.png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    </head>

    <body>

        <header>
            <!-- place navbar here -->
        </header>
        <main>
            <div class="container d-flex justify-content-center align-items-center vh-100">
                <div class="card">
                    <div class="card-body">
                        <h1 class="title text-center mb-4">Registro De Empresa</h1>

                        <form action="" class="form" method="POST" onsubmit="return validarFormulario()"  autocomplete="off">
                            <div class="inputContainer mb-3">
                                <label class="label">Licencia <a style="text-decoration: none;" href="#" onclick="abrirVentanaSerial()">Crear</a></label>
                                <select class="form-select form-select-sm input" name="ID_Licencia" id="id_licencia" required>
                                    <option value="" selected disabled>Seleccione una licencia</option>
                                    <?php foreach ($Tp_licencia as $licencia_) { ?>
                                        <option value="<?php echo $licencia_['ID']; ?>">
                                            <?php echo  "  Serial: " . $licencia_['Serial'] . " - Tiempo: " . $licencia_['Tipo']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="inputContainer mb-3">
                                <label class="label">NIT</label>
                                <input type="text" name="NIT" id="nit" class="form-control" required placeholder="Ingrese el NIT de la empresa" oninput="validarNIT(this)">
                                <small id="nitHelpBlock" class="form-text text-muted"></small>
                            </div>

                            <div class="inputContainer mb-3">
                                <label class="label">Nombre de la empresa</label>
                                <input type="text" name="Nombre" id="nombre" class="form-control" required placeholder="Ingrese el nombre de la empresa" oninput="validarNombre(this)">
                                <small id="nombreHelpBlock" class="form-text text-muted"></small>
                            </div>


                            <div class="inputContainer mb-3">
                                <label class="label">Correo</label>
                                <input type="email" name="Correo" id="correo" class="form-control" required placeholder="Ingrese el correo de la empresa" oninput="validarCorreo(this)">
                                <small id="correoHelpBlock" class="form-text text-muted"></small>
                            </div>

                            <div class="inputContainer mb-3">
                                <label class="label">Telefono</label>
                                <input type="tel" name="Telefono" id="telefono" class="form-control" required placeholder="Ingrese el teléfono de la empresa" oninput="validarTelefono(this)">
                                <small id="telefonoHelpBlock" class="form-text text-muted"></small>
                            </div>


                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </div>

                            <div class="d-grid gap-2">
                                <a class="btn btn-danger" href="../index.php" role="button">Regresar</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </main>
        <script src="js/empresa.js"></script>

        <!-- Bootstrap JavaScript Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

        <script>
            function validarNIT(input) {
                var nit = input.value.replace(/\D/g, '');
                if (nit.length > 10) {
                    nit = nit.slice(0, 10);
                }
                input.value = nit;
                var charsRemaining = 10 - nit.length;
                document.getElementById('nitHelpBlock').textContent = " Deben ser 10 números. Faltan " + charsRemaining;
            }

         function validarNombre(input) {
    var nombre = input.value.replace(/[^a-zA-Z0-9\s]/g, ''); // Permitir letras, números y espacios
    if (nombre.length > 20) {
        nombre = nombre.slice(0, 20);
    }
    input.value = nombre;
    var charsRemaining = 20 - nombre.length;
    document.getElementById('nombreHelpBlock').textContent = " Solo letras y números. Máximo 20 caracteres. Quedan " + charsRemaining;
}


            function validarCorreo(input) {
                // Verificar si el correo tiene un formato válido
                var correo = input.value;
                var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!regex.test(correo)) {
                    document.getElementById('correoHelpBlock').textContent = "Formato de correo inválido.";
                } else {
                    document.getElementById('correoHelpBlock').textContent = "Correo valido";
                }
            }

            function validarTelefono(input) {
                var telefono = input.value.replace(/\D/g, '');
                if (telefono.length > 10) {
                    telefono = telefono.slice(0, 10);
                }
                input.value = telefono;
                var charsRemaining = 10 - telefono.length;
                document.getElementById('telefonoHelpBlock').textContent = "Teléfono: Deben ser 10 números. Faltan " + charsRemaining;
            }

            function validarFormulario() {
                // Aquí puedes agregar validaciones adicionales si es necesario
                return true; // Devuelve true para permitir el envío del formulario
            }
        </script>
        <script></script>

    </body>

    </html>



<?php
} else {
    echo '
    <script>
        alert("su rol no tiene acceso a esta pagina");
        window.location = "login.php";
    </script>
    ';
}
?>