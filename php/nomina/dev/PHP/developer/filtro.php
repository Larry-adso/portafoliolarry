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

$id_us = $_SESSION['id_us'];

$consultaUsuarios = $conexion->prepare("SELECT usuarios.*, empresas.NIT as NIT, licencia.ID_Estado as ID_Estado FROM usuarios
INNER JOIN empresas ON usuarios.id_empresa = empresas.NIT
INNER JOIN licencia ON empresas.ID_Licencia = licencia.ID
WHERE usuarios.id_us = :id_us");
$consultaUsuarios->bindParam(":id_us", $id_us);
$consultaUsuarios->execute();

if ($consultaUsuarios->rowCount() > 0) {
    $usuario = $consultaUsuarios->fetch(PDO::FETCH_ASSOC);
    $_SESSION['id_us'] = $id_us;
    $id_rol = $usuario['id_rol'];
    $id_estado = $usuario['ID_Estado'];

    if ($id_rol == 5 && $id_estado == 5) {
        header("Location: activacion.php");
    } elseif ($id_rol == 5 && $id_estado == 2) {
        echo '
        <script>
               alert("La licencia está desactivada. Por favor, comuníquese con los desarrolladores.");
               window.location = "../login.php";
           </script>
           ';
        session_destroy();
        die();
        
    } elseif ($id_rol == 6 && $id_estado != 1) {
        echo '
        <script>
               alert("No se puede iniciar sesión porque la licencia no está activa.");
               window.location = "../login.php";
           </script>
           ';
        session_destroy();
        die();
    } elseif ($id_rol == 6 && $id_estado == 1) {
        header("Location: ../../../usuarios/prestamo.php");
    } elseif ($id_rol == 7 && $id_estado != 1) {
        echo '
        <script>
               alert("No se puede iniciar sesión porque la licencia no está activa.");
               window.location = "../login.php";
           </script>
           ';
        session_destroy();
        die();
    } elseif ($id_rol == 7 && $id_estado == 1) {
        header("Location: ../../../recursos_humanos/RH/index.php");
    } else {
        header("Location: ../../../admin/PHP/index.php");
    }
    exit();
} else {
    echo '
 <script>
        alert("Lo siento no puede acceder al sistema ya que la licencia no se encuentra activa");
        window.location = "../login.php";
    </script>
    ';
    session_destroy();
    die();
}
