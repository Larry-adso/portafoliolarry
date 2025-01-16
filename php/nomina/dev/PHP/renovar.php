<?php
session_start();

// Verificar si la sesión está iniciada
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

// Incluir archivo de conexión a la base de datos
include '../../conexion/db.php'; // Este archivo ya contiene la conexión PDO

// Variables para almacenar los datos de la empresa
$empresa_info = array();

// Verificar si se envió el formulario para actualizar la licencia
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_empresa'])) {
    $nuevo_id_licencia = $_POST['ID_Licencia'];
    $empresa = $_GET['NIT'];

    try {
        // Calcular la fecha de fin de la licencia según el tipo de licencia
        $fechaInicio = date('Y-m-d H:i:s'); // Fecha actual

        // Obtener el tipo de licencia
        $consultaTipoLicencia = $conexion->prepare("SELECT TP_licencia FROM licencia WHERE ID = :id_licencia");
        $consultaTipoLicencia->bindParam(":id_licencia", $nuevo_id_licencia);
        $consultaTipoLicencia->execute();
        $tipoLicencia = $consultaTipoLicencia->fetchColumn();

        if ($tipoLicencia == 1213) {
            // Si es de tipo 1213 (6 meses)
            $fechaFin = date('Y-m-d H:i:s', strtotime('+6 months', strtotime($fechaInicio)));
        } elseif ($tipoLicencia == 1214) {
            // Si es de tipo 1214 (1 año)
            $fechaFin = date('Y-m-d H:i:s', strtotime('+1 year', strtotime($fechaInicio)));
        } else {
            // Por defecto 1 año
            $fechaFin = date('Y-m-d H:i:s', strtotime('+1 year', strtotime($fechaInicio)));
        }

        // Actualizar el campo F_inicio y F_fin en la tabla licencia
        $updateFechas = $conexion->prepare("UPDATE licencia SET F_inicio = :fechaInicio, F_fin = :fechaFin WHERE ID = :id_licencia");
        $updateFechas->bindParam(":fechaInicio", $fechaInicio);
        $updateFechas->bindParam(":fechaFin", $fechaFin);
        $updateFechas->bindParam(":id_licencia", $nuevo_id_licencia);
        $updateFechas->execute();

        // Consulta SQL para actualizar solo el ID_Licencia de la empresa
        $consulta_update = "UPDATE empresas SET 
                            ID_Licencia = :id_licencia
                            WHERE NIT = :nit";

        // Preparar la consulta
        $stmt = $conexion->prepare($consulta_update);

        // Bind de parámetros
        $stmt->bindParam(':id_licencia', $nuevo_id_licencia);
        $stmt->bindParam(':nit', $empresa);

        // Ejecutar la consulta de actualización
        if ($stmt->execute()) {
            // Actualizar estado de la licencia a 1
            $consulta_update_estado = "UPDATE licencia SET 
                                      ID_estado = 1
                                      WHERE ID = :id_licencia";

            // Preparar la consulta
            $stmt_estado = $conexion->prepare($consulta_update_estado);
            $stmt_estado->bindParam(':id_licencia', $nuevo_id_licencia);

            // Ejecutar la consulta de actualización del estado de la licencia
            if ($stmt_estado->execute()) {
                echo '<script>
                    alert("Licencia renovada correctamente");
                    window.location = "../index.php";
                </script>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Error al actualizar el estado de la licencia.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Error al actualizar la licencia.</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger" role="alert">Error al actualizar la licencia: ' . $e->getMessage() . '</div>';
    }
}

// Consulta SQL para obtener los datos actuales de la empresa
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['NIT'])) {
    $empresa = $_GET['NIT'];

    try {
        // Consulta SQL para obtener los datos básicos de la empresa (nombre, correo, teléfono)
        $consulta = "SELECT Nombre, NIT, Correo, Telefono FROM empresas WHERE NIT = :nit";

        // Preparar la consulta
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':nit', $empresa);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si se encontraron resultados
        if ($stmt->rowCount() > 0) {
            // Obtener datos de la empresa
            $empresa_info = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // No se encontró ninguna empresa con el NIT dado
            echo '<div class="alert alert-danger" role="alert">No se encontró ninguna empresa con el NIT proporcionado.</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger" role="alert">Error al obtener los datos de la empresa: ' . $e->getMessage() . '</div>';
    }
}

// Consulta SQL para obtener las licencias
try {
    $consultaLicencia = $conexion->prepare("SELECT licencia.ID, licencia.Serial, tp_licencia.Tipo FROM licencia 
                                            INNER JOIN tp_licencia ON licencia.TP_licencia = tp_licencia.ID WHERE licencia.ID_estado = 3");
    $consultaLicencia->execute();
    $Tp_licencia = $consultaLicencia->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo '<div class="alert alert-danger" role="alert">Error al obtener las licencias: ' . $e->getMessage() . '</div>';
}

// Cerrar conexión
$conexion = null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renovar licencia de Empresa</title>
    <link rel="icon" type="image/png" href="../../img/logo_algj.png">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Renovar Licencia de Empresa</div>
                    <div class="card-body">
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?NIT=' . $_GET['NIT']; ?>">
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($empresa_info['Nombre']); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nit">NIT:</label>
                                <input type="text" class="form-control" id="nit" name="nit" value="<?php echo htmlspecialchars($empresa_info['NIT']); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="correo">Correo:</label>
                                <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($empresa_info['Correo']); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono:</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($empresa_info['Telefono']); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="ID_Licencia">Licencia</label>
                                <select class="form-select form-select-sm" name="ID_Licencia" id="id_licencia" required>
                                    <option value="" selected disabled>Seleccione una licencia</option>
                                    <?php foreach ($Tp_licencia as $licencia_) { ?>
                                        <option value="<?php echo $licencia_['ID']; ?>">
                                            <?php echo "Serial: " . $licencia_['Serial'] . " - Tipo: " . $licencia_['Tipo']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" name="update_empresa">Actualizar Licencia</button>
                            <a href="../index.php" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS y dependencias opcionales (jQuery) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
