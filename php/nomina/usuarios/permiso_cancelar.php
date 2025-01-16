<?php
include("../conexion/db.php");
    
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $sql = "UPDATE permisos SET estado = 12 WHERE id_permiso = :id";
        $stmt = $conexion->prepare($sql);

        $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Estado actualizado exitosamente.'); window.location.href = 'permisos.php';</script>";
        } else {
            echo "<script>alert('No se encontró ningún registro con el ID proporcionado.'); window.location.href = 'permisos.php';</script>";
        }
    } else {
        echo "<script>alert('ID no válido proporcionado para actualizar el registro.'); window.location.href = 'permisos.php';</script>";
    }


?>
