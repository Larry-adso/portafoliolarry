<?php
session_start();

// Verificar si no hay sesión activa
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

// Incluir la conexión a la base de datos
include("../../../conexion/db.php");

// Verificar si se envió un código de barras
if (isset($_POST['barcode'])) {
    // Obtener el código de barras enviado
    $barcode = $_POST['barcode'];

    // Obtener el id de usuario logeado
    $id_usuario = $_SESSION['id_us'];

    try {
        // Obtener la empresa asociada al NIT proporcionado
        $consultaEmpresa = $conexion->prepare("SELECT e.NIT, e.ID_Licencia, l.Serial, l.TP_licencia FROM empresas e 
            INNER JOIN licencia l ON e.ID_Licencia = l.ID WHERE e.NIT = :NIT AND l.ID_Estado = 5");
        $consultaEmpresa->bindParam(":NIT", $barcode);
        $consultaEmpresa->execute();
        $empresa = $consultaEmpresa->fetch(PDO::FETCH_ASSOC);

        if ($empresa) {
            // Verificar si el usuario está asociado a esta empresa
            $validacionUsuario = $conexion->prepare("SELECT COUNT(*) as count FROM usuarios WHERE id_us = :id_usuario AND id_empresa = :empresa_id");
            $validacionUsuario->bindParam(":id_usuario", $id_usuario);
            $validacionUsuario->bindParam(":empresa_id", $empresa['NIT']);
            $validacionUsuario->execute();
            $usuarioAsociado = $validacionUsuario->fetch(PDO::FETCH_ASSOC);

            if ($usuarioAsociado['count'] == 1) {
                // Activar la licencia
                $updateLicencia = $conexion->prepare("UPDATE licencia SET ID_Estado = 1, F_inicio = NOW(), F_fin = DATE_ADD(NOW(), INTERVAL 1 YEAR) WHERE Serial = :Serial");
                $updateLicencia->bindParam(":Serial", $empresa['Serial']);
                $updateLicencia->execute();

                echo 'success'; // Indicar éxito al cliente
            } else {
                echo 'No tienes permiso para activar la licencia de esta empresa.';
            }
        } else {
            echo 'El código de barras no corresponde a ninguna empresa registrada.';
        }
    } catch (PDOException $ex) {
        echo "Error de consulta: " . $ex->getMessage();
    }

    exit(); // Terminar la ejecución después de procesar el código de barras
}

// Obtener el código de barras de la empresa asociada al usuario logeado
$id_usuario = $_SESSION['id_us'];

$query = "SELECT e.barcode 
          FROM usuarios u 
          INNER JOIN empresas e ON u.id_empresa = e.NIT 
          WHERE u.id_us = :id_usuario";

$stmt = $conexion->prepare($query);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->execute();
$barcode = $stmt->fetchColumn();

// Verificar si se encontró el código de barras
if (!$barcode) {
    echo "No se encontró el código de barras para el usuario logeado.";
    // Puedes redirigir o mostrar un mensaje de error aquí
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lectura Automática de Código de Barras</title>
</head>
<body>
    <h1>Lectura Automática de Código de Barras</h1>
    <img id="barcode-img" src="../<?php echo $barcode; ?>" alt="<?php echo $barcode; ?>">
    <script>
        // Función para leer el código de barras del atributo 'alt' de la imagen
        function leerCodigoBarras() {
            var barcodeValue = document.getElementById('barcode-img').alt;
            return barcodeValue;
        }

        // Función para enviar el código de barras al servidor PHP
        function enviarCodigoBarras() {
            var barcode = leerCodigoBarras();
            
            // Verificar si el código de barras es leído correctamente antes de enviar
            if (barcode) {
                // Enviar el código de barras al servidor PHP
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "", true); // Enviar a la misma página PHP
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Respuesta del servidor
                        var response = xhr.responseText.trim();
                        if (response === 'success') {
                            alert("La licencia se ha activado correctamente.");
                            window.location.reload(); // Recargar la página después de activar la licencia
                        } else {
                            alert(response);
                        }
                    }
                };
                xhr.send("barcode=" + barcode);
            } else {
                alert("No se ha leído ningún código de barras.");
            }
        }

        // Llamar automáticamente a la función al cargar la página
        enviarCodigoBarras();
    </script>
</body>
</html>
