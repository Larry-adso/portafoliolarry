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

if (isset($_GET['id'])) {
    $id_us = $_GET['id'];

    try {
        $sql = "DELETE FROM usuarios WHERE id_us = :id_us";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_us', $id_us, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo '
            <script>
                alert("Registro eliminado correctamente");
                window.location = "devs.php";
            </script>
            ';
        } else {
            echo '
                <script>
                    alert("No se pudo eliminar el registro");
                    window.location = "devs.php";
                </script>
                ';
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conexion = null;
}
