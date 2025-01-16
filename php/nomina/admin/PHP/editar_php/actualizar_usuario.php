<?php
include '../../../conexion/db.php';
include '../../../conexion/validar_sesion.php';

// Obtener datos del formulario
$id_us = $_POST['id_us'];
$id = $_POST['id'];
$nombre_us = $_POST['nombre_us'];
$apellido_us = $_POST['apellido_us'];
$correo_us = $_POST['correo_us'];
$tel_us = $_POST['tel_us'];
$pass = $_POST['pass'];
$id_puesto = $_POST['id_puesto'];
$id_rol = $_POST['id_rol'];
$Codigo = $_POST['Codigo'];
$id_empresa = $_POST['id_empresa'];
$token = $_POST['token'];
$id_estado = $_POST['id_estado'];

// Verificar si se actualiza la contraseña
if (!empty($pass)) {
    $hashed_pass = hash('sha512', $pass); // Encriptar la contraseña con SHA-512
    $query = "UPDATE usuarios SET nombre_us = :nombre_us, id_estado = :id_estado, apellido_us = :apellido_us, correo_us = :correo_us, tel_us = :tel_us, pass = :pass, id_puesto = :id_puesto, id_rol = :id_rol, Codigo = :Codigo, id_empresa = :id_empresa, token = :token WHERE id_us = :id_us";
} else {
    $query = "UPDATE usuarios SET nombre_us = :nombre_us, id_estado = :id_estado, apellido_us = :apellido_us, correo_us = :correo_us, tel_us = :tel_us, id_puesto = :id_puesto, id_rol = :id_rol, Codigo = :Codigo, id_empresa = :id_empresa, token = :token WHERE id_us = :id_us";
}

$query_update = $conexion->prepare($query);
$query_update->bindParam(':nombre_us', $nombre_us);
$query_update->bindParam(':id_estado', $id_estado);
$query_update->bindParam(':apellido_us', $apellido_us);
$query_update->bindParam(':correo_us', $correo_us);
$query_update->bindParam(':tel_us', $tel_us);
$query_update->bindParam(':id_puesto', $id_puesto);
$query_update->bindParam(':id_rol', $id_rol);
$query_update->bindParam(':Codigo', $Codigo);
$query_update->bindParam(':id_empresa', $id_empresa);
$query_update->bindParam(':token', $token);
$query_update->bindParam(':id_us', $id_us);

if (!empty($pass)) {
    $query_update->bindParam(':pass', $hashed_pass);
}

$query_update->execute();

// Manejo de la foto
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $foto_nombre = $_FILES['foto']['name'];
    
    // Generar un nombre único para la imagen
    $nuevo_nombre = uniqid() . '.' . pathinfo($foto_nombre, PATHINFO_EXTENSION);

    // Definir la ruta de destino para la foto
    $ruta_foto = "../../../uploads/fotos/" . $nuevo_nombre;

    // Mover la foto de la ubicación temporal a la permanente
    move_uploaded_file($foto_tmp, $ruta_foto);

    // Actualizar la ruta de la foto en la base de datos
    $ruta_foto_db = "../../../uploads/fotos/" . $nuevo_nombre;
    $query_foto = $conexion->prepare("UPDATE usuarios SET ruta_foto = :foto WHERE id_us = :id_us");
    $query_foto->bindParam(':foto', $ruta_foto_db);
    $query_foto->bindParam(':id_us', $id_us);
    $query_foto->execute();
}

echo "<script>alert('Usuario actualizado con éxito'); window.location.href='../index.php';</script>";
?>
