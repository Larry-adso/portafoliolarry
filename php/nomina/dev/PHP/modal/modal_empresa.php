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
include "../../../conexion/db.php";

$id_rol = $_SESSION['id_rol'];
if ($id_rol == '4') {


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $NIT = isset($_POST["NIT"]) ? $_POST["NIT"] : "";
        $Nombre = isset($_POST["Nombre"]) ? $_POST["Nombre"] : "";
        $Id_licencia = isset($_POST["ID_Licencia"]) ? $_POST["ID_Licencia"] : "";
        $password = hash('sha512', $password);
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
            // Insertar el nuevo registro en la tabla empresas
            $sentencia = $conexion->prepare("INSERT INTO empresas(NIT, Nombre, Correo, Telefono, ID_Licencia) VALUES (:NIT, :Nombre, :Correo, :Telefono, :ID_Licencia)");
            $sentencia->bindParam(":NIT", $NIT);
            $sentencia->bindParam(":Nombre", $Nombre);
            $sentencia->bindParam(":Correo", $Correo);
            $sentencia->bindParam(":Telefono", $Telefono);
            $sentencia->bindParam(":ID_Licencia", $Id_licencia);

            if ($sentencia->execute()) {
                $mensaje = "Registro creado correctamente";
                echo '<script>
        alert("Registro creado correctamente");
    </script>';

                // Actualizar el estado de la licencia a 2
                $actualizarEstado = $conexion->prepare("UPDATE licencia SET ID_Estado = 2 WHERE ID = :ID_Licencia");
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

    $consultaLicencia = $conexion->prepare("SELECT licencia.ID, licencia.Serial, tp_licencia.Tipo FROM licencia INNER JOIN tp_licencia ON licencia.TP_licencia = tp_licencia.ID WHERE licencia.ID_estado = 3");
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

                        <form action="" class="form" method="POST" autocomplete="off" >
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
                                <input type="text" name="NIT" pattern="[0-9]{10}" maxlength="10" class="form-control" required placeholder="Ingrese el NIT de la empresa">
                            </div>

                            <div class="inputContainer mb-3">
                            <label class="label">Nombre</label>
                                <input type="text" name="Nombre" class="form-control" required placeholder="Ingrese el nombre de la empresa">
                            </div>


                            <div class="inputContainer mb-3">
                            <label class="label">Correo</label>

                                <input type="email" name="Correo" class="form-control" required placeholder="Ingrese el correo de la empresa">
                            </div>

                            <div class="inputContainer mb-3">
                            <label class="label">Telefono</label>

                                <input type="tel" name="Telefono" pattern="[0-9]{10}" maxlength="10" class="form-control" required placeholder="Ingrese el teléfono de la empresa">
                            </div>


                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </div>

                            <div class="d-grid gap-2">
                                <a class="btn btn-danger" href="../../index.php" role="button">Regresar</a>
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