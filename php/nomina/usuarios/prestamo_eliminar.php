<?php

include("../conexion/db.php");

    // Verificar si se proporcionó un ID válido
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        // Preparar la consulta para actualizar el estado del registro con el ID proporcionado
        $sql = "UPDATE prestamo SET estado = 8 WHERE ID_prest = :id";
        $stmt = $conexion->prepare($sql);

        // Ejecutar la consulta
        $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Estado actualizado exitosamente.'); window.location.href = 'prestamo.php';</script>";
        } else {
            echo "<script>alert('No se encontró ningún registro con el ID proporcionado.'); window.location.href = 'prestamo.php';</script>";
        }
    } else {
        echo "<script>alert('ID no válido proporcionado para actualizar el registro.'); window.location.href = 'prestamo.php';</script>";
    }


?>
