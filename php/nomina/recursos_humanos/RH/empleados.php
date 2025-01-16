<?php
include '../conexion/validar_sesion.php';

include '../conexion/db.php';
// Obtener la información del usuario activo
$id_us = $_SESSION['id_us'];
$query_usuario = $conexion->prepare("SELECT id_empresa, id_rol FROM usuarios WHERE id_us = :id_us");
$query_usuario->bindParam(':id_us', $id_us);
$query_usuario->execute();
$usuario = $query_usuario->fetch(PDO::FETCH_ASSOC);

$id_empresa = $usuario['id_empresa'];
$rol_usuario_activo = $usuario['id_rol'];

// Obtener puestos
$query_puestos = $conexion->prepare("SELECT ID, cargo FROM puestos");
$query_puestos->execute();
$puestos = $query_puestos->fetchAll(PDO::FETCH_ASSOC);

// Obtener roles, excluyendo el rol del usuario activo
$query_roles = $conexion->prepare("SELECT ID, Tp_user FROM roles WHERE ID = 6");
$query_roles->execute();
$roles = $query_roles->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Usuarios</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../img/logo_algj.png">
</head>

<body>
    <div class="container">
        <h2>Agregar Usuario</h2>
        <form action="insertar_usuario.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre_us">N° De Identificación</label>
                <input type="text" class="form-control" id="id" name="id" required>
            </div>
            <div class="form-group">
                <label for="nombre_us">Nombre</label>
                <input type="text" class="form-control" id="nombre_us" name="nombre_us" required>
            </div>
            <div class="form-group">
                <label for="apellido_us">Apellido</label>
                <input type="text" class="form-control" id="apellido_us" name="apellido_us" required>
            </div>
            <div class="form-group">
                <label for="correo_us">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo_us" name="correo_us" required>
            </div>
            <div class="form-group">
                <label for="tel_us">Teléfono</label>
                <input type="text" class="form-control" id="tel_us" name="tel_us" required>
            </div>
            <div class="form-group">
                <label for="pass">Contraseña</label>
                <input type="password" class="form-control" id="pass" name="pass" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto</label>
                <input type="file" class="form-control-file" id="foto" name="foto">
            </div>
            <div class="form-group">
                <label for="id_puesto">Puesto</label>
                <select class="form-control" id="id_puesto" name="id_puesto">
                    <option value="">Seleccione un puesto</option>
                    <?php foreach ($puestos as $puesto) : ?>
                        <option value="<?= $puesto['ID'] ?>"><?= $puesto['cargo'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_rol">Rol</label>
                <select class="form-control" id="id_rol" name="id_rol" required>
                    <option value="">Seleccione un rol</option>
                    <?php foreach ($roles as $rol) : ?>
                        <option value="<?= $rol['ID'] ?>"><?= $rol['Tp_user'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="Codigo">Código</label>
                <input type="number" class="form-control" id="Codigo" name="Codigo" required>
            </div>
            <input type="hidden" id="id_empresa" name="id_empresa" value="<?= $id_empresa ?>">
            <div class="form-group">
                <label for="token"></label>
                <input type="hidden" class="form-control" id="token" name="token" >
                <small id="token_error" class="text-danger"></small>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</body>

</html>