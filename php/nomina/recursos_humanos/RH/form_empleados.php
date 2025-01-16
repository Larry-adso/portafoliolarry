<?php
session_start();


if (!isset($_SESSION['id_us'])) {
    echo '
        <script>
            alert("Por favor inicie sesión e intente nuevamente");
            window.location = "../../dev/PHP/login.php";
        </script>
    ';
    die();
}
include("../conexion/db.php");

// Obtener id_empresa del usuario con sesión activa
$id_us_sesion = $_SESSION['id_us'];
try {
    $query_empresa = $conexion->prepare("SELECT id_empresa FROM usuarios WHERE id_us = ?");
    $query_empresa->bindParam(1, $id_us_sesion);
    $query_empresa->execute();
    $result_empresa = $query_empresa->fetch(PDO::FETCH_ASSOC);
    $id_empresa_sesion = $result_empresa['id_empresa'];
} catch (PDOException $e) {
    echo 'Error en la consulta de la empresa: ' . $e->getMessage();
    die();
}

$id_rol = $_SESSION['id_rol'];
if ($id_rol == '4' || $id_rol == '5') {

    if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "regm")) {

        $id_us = $_POST['id_us'];
        $nombre_us = $_POST['nombre_us'];
        $apellido_us = $_POST['apellido_us'];
        $correo_us = $_POST['correo_us'];
        $tel_us = $_POST['tel_us'];
        $pass = $_POST['pass'];
        $Codigo = $_POST['Codigo'];
        $id_rol = $_POST['id_rol'];
        $cargo = $_POST['cargo'];
        $pass = hash('sha512', $pass);

        // Manejar la carga de la imagen
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
            $foto = file_get_contents($_FILES['foto']['tmp_name']);
        } else {
            $foto = null;
        }

        try {
            $insertSQL = $conexion->prepare("INSERT INTO usuarios (id_us, nombre_us, apellido_us, correo_us, tel_us, pass, foto, id_rol, Codigo, id_empresa, id_puesto) 
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insertSQL->bindParam(1, $id_us);
            $insertSQL->bindParam(2, $nombre_us);
            $insertSQL->bindParam(3, $apellido_us);
            $insertSQL->bindParam(4, $correo_us);
            $insertSQL->bindParam(5, $tel_us);
            $insertSQL->bindParam(6, $pass);
            $insertSQL->bindParam(7, $foto, PDO::PARAM_LOB);
            $insertSQL->bindParam(8, $id_rol);
            $insertSQL->bindParam(9, $Codigo);
            $insertSQL->bindParam(10, $id_empresa_sesion);
            $insertSQL->bindParam(11, $cargo); // Aquí se insertará el ID del puesto seleccionado
            $insertSQL->execute();
            echo '<script>alert("Registro exitoso");</script>';
        } catch (PDOException $e) {
            echo 'Error en la inserción: ' . $e->getMessage();
            die();
        }
    }

    try {
        $query_roles = $conexion->prepare("SELECT * FROM roles WHERE ID >= 5");
        $query_roles->execute();
        $cons = $query_roles->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error en la consulta de roles: ' . $e->getMessage();
        die();
    }

    // Obtener cargos desde la tabla puestos
    try {
        $query_puestos = $conexion->prepare("SELECT ID, cargo, salario FROM puestos"); // Seleccionamos el ID también
        $query_puestos->execute();
        $puestos = $query_puestos->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error en la consulta de puestos: ' . $e->getMessage();
        die();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://uniconnect.uniconnectscout.com/release/v2.1.9/css/uniconnect.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="../../img/logo_algj.png">
    <title>Document</title>
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
                <h1 class="mb-4 pb-3 text-center">REGISTRO DE ADMINISTRADOR</h1>
                <form action="#" name="form" method="post" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="id_us" class="form-label">* Cedula</label>
                            <input type="number" class="form-control" title="Solo se permiten números con un máximo de 10 dígitos" name="id_us" id="id_us" placeholder="Cedula del usuario" required>
                        </div>
                        <div class="col-md-4">
                            <label for="nombre_us" class="form-label">Nombre</label>
                            <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo se permiten letras" name="nombre_us" id="nombre_us" placeholder="Nombre Completo">
                        </div>
                        <div class="col-md-4">
                            <label for="apellido_us" class="form-label">Apellido</label>
                            <input type="text" class="form-control" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ\s]+" title="Solo se permiten letras" name="apellido_us" id="apellido_us" placeholder="Apellido completo">
                        </div>
                        <div class="col-md-4">
                            <label for="correo_us" class="form-label">Correo</label>
                            <input type="email" class="form-control" name="correo_us" id="correo_us" placeholder="Correo electronico" required>
                        </div>
                        <div class="col-md-4">
                            <label for="pass" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" title="Debe ser alfanumérico de al menos 10 caracteres" name="pass" id="pass" required>
                        </div>
                        <div class="col-md-4">
                            <label for="tel_us" class="form-label">Telefono</label>
                            <input type="tel" class="form-control" pattern="[0-9]{10}" title="Debe ser un número de 10 dígitos" name="tel_us" id="tel_us" placeholder="" required>
                        </div>
                        <div class="col-md-4">
                            <label for="Codigo" class="form-label">Codigo de seguridad</label>
                            <input type="number" class="form-control" title="Debe ser un número de 10 dígitos" name="Codigo" id="Codigo" placeholder="" required>
                        </div>
                        <div class="col-md-4">
                            <label for="id_rol" class="form-label">Usuarios</label>
                            <select class="form-select" name="id_rol" id="id_rol" required>
                                <option value="" selected disabled>Seleccione un tipo de usuario</option>
                                <?php foreach ($cons as $rol) : ?>
                                    <option value="<?php echo $rol['ID']; ?>"><?php echo $rol['Tp_user']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="cargo" class="form-label">Cargo</label>
                            <select class="form-select" name="cargo" id="cargo" required>
                                <option value="" selected disabled>Seleccione un cargo</option>
                                <?php foreach ($puestos as $puesto) : ?>
                                    <option value="<?php echo $puesto['ID']; ?>"><?php echo $puesto['cargo']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="col-md-4">
                            <label for="foto" class="form-label">Foto</label>
                            <input type="file" class="form-control" name="foto" id="foto" accept="image/*">
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-block" name="MM_insert" value="regm">Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
<?php
} else {
    echo '
            <script>
                alert("No tiene permiso para acceder a esta página");
                window.location = "../../login.php";
            </script>
            ';
}
?>

