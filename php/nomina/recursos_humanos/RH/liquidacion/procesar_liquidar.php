<?php
include '../../../conexion/validar_sesion.php';
include '../../../conexion/db.php'; // Incluir el archivo de conexión

try {
    // Obtener el id_empresa del usuario con sesión activa
    $id_us = $_SESSION['id_us'];

    // Consulta SQL para obtener el valor de la hora extra de la empresa del usuario activo
    $sql_h_extra = "SELECT V_H_extra FROM v_h_extra WHERE id_empresa = (SELECT id_empresa FROM usuarios WHERE id_us = :id_us)";
    $stmt_h_extra = $conexion->prepare($sql_h_extra);
    $stmt_h_extra->bindParam(':id_us', $id_us, PDO::PARAM_INT);
    $stmt_h_extra->execute();
    $valor_hora_extra = $stmt_h_extra->fetch(PDO::FETCH_ASSOC);

    if (!$valor_hora_extra) {
        echo "<script>alert('no se pudo obtener el valor de la hora extra'); window.location.href='../index.php';</script>";
        exit();
    }
} catch (PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
    exit();
}

// Obtener datos del formulario
$id_usuario = isset($_POST['id_us']) ? $_POST['id_us'] : null;
$horas_trabajadas = isset($_POST['horas_trabajadas']) ? (int)$_POST['horas_trabajadas'] : 0;
$salario_total_a_pagar = isset($_POST['salario_total_a_pagar']) ? (int)$_POST['salario_total_a_pagar'] : 0;
$id_prestamo = isset($_POST['id_prestamo']) ? $_POST['id_prestamo'] : null;
$dias_trabajados = isset($_POST['dias_trabajados_resultado']) ? $_POST['dias_trabajados_resultado'] : null;
$cuota= isset($_POST['cuota_prestamo']) ? (int)$_POST['cuota_prestamo'] : 0;
$transporte = isset($_POST['valor_auxilio_transporte']) ? (int)$_POST['valor_auxilio_transporte'] : 0;
if ($id_prestamo) {
    // Actualizar el préstamo si se ha pasado un ID de préstamo
    try {
        // Iniciar una transacción
        $conexion->beginTransaction();

        // Actualizar las cuotas en deuda
        $sql_update_prestamo = "UPDATE prestamo SET cuotas_en_deuda = cuotas_en_deuda - 1 WHERE ID_Prest = $id_prestamo AND cuotas_en_deuda > 0";
        $stmt_update_prestamo = $conexion->query($sql_update_prestamo);

        // Verificar si se actualizó correctamente la cuota en deuda
        if ($stmt_update_prestamo->rowCount() > 0) {
            // Actualizar las cuotas pagadas
            $sql_update_cuotas_pagas = "UPDATE prestamo SET cuotas_pagas = cuotas_pagas + 1 WHERE ID_Prest = $id_prestamo";
            $stmt_update_cuotas_pagas = $conexion->query($sql_update_cuotas_pagas);

            // Verificar si las cuotas en deuda son 0 y actualizar el estado del préstamo a 9
            $sql_check_cuotas = "SELECT cuotas_en_deuda FROM prestamo WHERE ID_Prest = $id_prestamo";
            $stmt_check_cuotas = $conexion->query($sql_check_cuotas);
            $cuotas_en_deuda = $stmt_check_cuotas->fetchColumn();

            if ($cuotas_en_deuda == 0) {
                $sql_update_estado = "UPDATE prestamo SET estado = 9 WHERE ID_Prest = $id_prestamo";
                $stmt_update_estado = $conexion->query($sql_update_estado);
            }

            // Confirmar la transacción
            $conexion->commit();
        } else {
            // Si la actualización de la cuota en deuda no fue exitosa, hacer un rollback
            $conexion->rollBack();
            echo "<script>alert('no se puede actualizar la cuota'); window.location.href='../index.php';</script>";
            exit();
        }
    } catch (PDOException $e) {
        // Si ocurre algún error, hacer rollback y mostrar el mensaje de error
        $conexion->rollBack();
        echo "Error al actualizar el préstamo: " . $e->getMessage();
        exit();
    }
}

// Insertar en la tabla sumas
try {
    $fecha = date('Y-m-d');
    $sql_insert_sumas = "INSERT INTO sumas (id_usuario, transporte, fecha, valor_hora_extra, horas_trabajadas, total) VALUES ($id_usuario, $transporte, '$fecha', {$valor_hora_extra['V_H_extra']}, $horas_trabajadas, $salario_total_a_pagar)";
    $stmt_insert_sumas = $conexion->query($sql_insert_sumas);

    // Obtenemos el ID de la última inserción en la tabla sumas
    $id_suma = $conexion->lastInsertId();
} catch (PDOException $e) {
    echo "Error al insertar en la tabla sumas: " . $e->getMessage();
    exit();
}

// Insertar en la tabla de deducciones
try {
    $id_salud = 1; // Aquí debes obtener el ID de salud correspondiente
    $id_pension = 1; // Aquí debes obtener el ID de pensión correspondiente

    // Obtener id_arl según el id_puesto del usuario
    $sql_puesto = "SELECT id_arl FROM puestos WHERE ID = (SELECT id_puesto FROM usuarios WHERE id_us = $id_usuario)";
    $stmt_puesto = $conexion->query($sql_puesto);
    $id_arl = $stmt_puesto->fetchColumn();

    $fecha = date('Y-m-d');

    // Calcular el total de deducciones
    $total_deducciones = $_POST['deduccion_salud'] + $_POST['deduccion_pension'];

    $sql_insert_deducciones = "INSERT INTO deduccion (id_usuario, cuota, fecha, id_prestamo, id_salud, id_pension, parafiscales, total) VALUES ($id_usuario, $cuota, '$fecha', $id_prestamo, $id_salud, $id_pension, $total_deducciones, {$_POST['salario_total_deducciones']})";
    $stmt_insert_deducciones = $conexion->query($sql_insert_deducciones);

    // Obtenemos el ID de la última inserción en la tabla de deducción
    $id_deduccion = $conexion->lastInsertId();
} catch (PDOException $e) {
    echo "Error al insertar en la tabla deducciones: " . $e->getMessage();
    exit();
}

// Insertar en la tabla de nómina
try {
    $fecha = date('Y-m-d');
    $sql_insert_nomina = "INSERT INTO nomina (ID_user, dias_trabajados, fecha, id_deduccion, id_suma, Valor_Pagar) VALUES ($id_usuario, $dias_trabajados, '$fecha', $id_deduccion, $id_suma, {$_POST['salario_total_deducciones']})";
    $stmt_insert_nomina = $conexion->query($sql_insert_nomina);
} catch (PDOException $e) {
    echo "Error al insertar en la tabla de nómina: " . $e->getMessage();
    exit();
}

echo "<script>alert('Liquidación realizada con éxito'); window.location.href='../index.php';</script>";
?>
