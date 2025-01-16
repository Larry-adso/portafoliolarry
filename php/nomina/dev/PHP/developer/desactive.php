<?php
session_start();

// Verificar si el usuario ha iniciado sesión
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

// Obtener el ID del usuario de la sesión
$id_us = $_SESSION['id_us'];

try {
    // Preparar y ejecutar la consulta para obtener el ID de la empresa, la licencia y su estado
    $consulta = $conexion->prepare("SELECT e.NIT AS id_empresa, l.ID AS id_licencia, l.F_fin, l.ID_Estado
        FROM usuarios u
        INNER JOIN empresas e ON u.id_empresa = e.NIT
        INNER JOIN licencia l ON e.ID_Licencia = l.ID
        WHERE u.id_us = :id_us
    ");
    $consulta->bindParam(":id_us", $id_us, PDO::PARAM_INT);
    $consulta->execute();

    // Obtener los resultados
    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        $id_empresa = $resultado['id_empresa'];
        $id_licencia = $resultado['id_licencia'];
        $fecha_fin = $resultado['F_fin'];
        $id_estado = $resultado['ID_Estado'];

        if ($id_estado == 1) {
            // Obtener la fecha actual del sistema
            $fecha_actual = date('Y-m-d H:i:s');

            // Comparar la fecha actual con la fecha de fin de la licencia
            if ($fecha_actual > $fecha_fin) {
                // Actualizar el estado de la licencia si la fecha actual supera la fecha de fin
                $update = $conexion->prepare("UPDATE licencia SET ID_estado = 2 WHERE ID = :id_licencia");
                $update->bindParam(":id_licencia", $id_licencia, PDO::PARAM_INT);
                $update->execute();

                echo '
                <script>
                    alert("Lo siento, su licencia ha vencido. Comuníquese con los proveedores del software.");
                    window.location = "../login.php";
                </script>
                ';
            } else {
                header('Location: filtro.php');
            }
        } else {
            header('Location: filtro.php');
        }
    } else {
        echo 'No se encontraron datos para el usuario especificado.';
    }
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
