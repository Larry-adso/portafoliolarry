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

include "../../conexion/conexion.php";

$id_rol = $_SESSION['id_rol'];
if ($id_rol == '4') {

    function generarClave($longitud)
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $clave = '';
        for ($i = 0; $i < $longitud; $i++) {
            $clave .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        return $clave;
    }

    $claveGenerada = '';
    $mensaje = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['generar'])) {
            $claveGenerada = generarClave(25);
        } elseif (isset($_POST['guardar'])) {
            $nuevaClave = $_POST['guardar'];
            $tipoLicencia = $_POST['TP_licencia']; // Obtener el ID del tipo de licencia seleccionado
            // Verificar unicidad de la clave antes de guardarla
            $consulta = "SELECT COUNT(*) as total FROM licencia WHERE Serial = '$nuevaClave'";
            $resultado = $conexion->query($consulta);
            $fila = $resultado->fetch_assoc();
            if ($fila['total'] == 0) {
                // Insertar la clave y el tipo de licencia en la tabla
                $sql = "INSERT INTO licencia (Serial, ID_Estado, TP_licencia) VALUES ('$nuevaClave', 3, $tipoLicencia)";

                if ($conexion->query($sql) === TRUE) {
                    $mensaje = "Clave guardada correctamente en la base de datos.";
                } else {
                    $mensaje = "Error al guardar la clave: " . $conexion->error;
                }
            } else {
                $mensaje = "La clave ya existe en la base de datos.";
            }
        } elseif (isset($_POST['noGuardar'])) {
            $mensaje = "Clave no guardada.";
        }
    }

    $consulta = "SELECT * FROM tp_licencia";
    $resultado = $conexion->query($consulta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Claves</title>
    <link rel="icon" type="image/png" href="../../img/logo_algj.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }
        .card {
            width: 400px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin-bottom: 20px;
            text-align: center;
        }
        input[type="submit"] {
            margin-top: 10px;
        }
        select {
            margin-top: 20px;
            width: 100%;
        }
        button[type="submit"] {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Generador de Claves</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="submit" name="generar" value="Generar Clave" class="btn btn-primary d-block mx-auto">
            <br>
            <?php if (!empty($claveGenerada)) : ?>
                <p>Clave generada: <?php echo $claveGenerada; ?></p>
                <label for="TP_licencia" class="form-label">Selecciona un tipo de licencia:</label>
                <select id="TP_licencia" name="TP_licencia" required class="form-select">
                    <option value="" disabled selected>Selecciona un tipo de licencia</option>
                    <?php while ($licencia = $resultado->fetch_assoc()) { ?>
                        <option value="<?php echo $licencia['ID']; ?>"><?php echo $licencia['Tipo']; ?></option>
                    <?php } ?>
                </select>
                <br>
                <button type="submit" name="guardar" value="<?php echo $claveGenerada; ?>" class="btn btn-success d-block mx-auto">Guardar Clave</button>
                <button type="submit" name="noGuardar" value="" class="btn btn-danger d-block mx-auto">No Guardar Clave</button>
            <?php endif; ?>
        </form>
        <?php if (!empty($mensaje)) : ?>
            <p><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <a name="" id="" class="btn btn-danger" href="../index.php" role="button">Atras</a>
    </div>
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
