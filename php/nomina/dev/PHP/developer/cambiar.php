<?php
include("../../../conexion/conexion.php");

if (isset($_GET['id'])) {
$id = $_GET['id'];  
$update = $conexion->prepare("UPDATE contactanos SET id_estado = 14 WHERE id = '$id'");

$update->execute();

echo '
<script>
    alert("Se ha marcado como vista la peticion");
    window.location = "info.php";
</script>
';
}else{
    echo '
<script>
    alert("nel wey no se hice nada");
    window.location = "info.php";
</script>
';
}