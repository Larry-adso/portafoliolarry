<?php

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_us']) || !isset($_SESSION['id_rol']) || ($_SESSION['id_rol'] != 5 && $_SESSION['id_rol'] != 7)) {
    echo '
        <script>
            alert("Por favor inicie sesión con un usuario autorizado e intente nuevamente");
            window.location = "../../dev/PHP/login.php";
        </script>
    ';
    exit; // Terminar el script para evitar que se ejecute más código
}


// Incluir el archivo de conexión a la base de datos
require_once "../../conexion/db.php"; // Reemplazar con el nombre correcto de tu archivo de conexión

// Obtener el id_empresa del usuario en sesión
$id_us_session = $_SESSION['id_us'];

try {
    // Obtener id_empresa del usuario en sesión
    $query_empresa = "SELECT id_empresa FROM usuarios WHERE id_us = :id_us_session";
    $statement_empresa = $conexion->prepare($query_empresa);
    $statement_empresa->bindParam(':id_us_session', $id_us_session, PDO::PARAM_INT);
    $statement_empresa->execute();
    $id_empresa_session = $statement_empresa->fetchColumn();

    if ($id_empresa_session === false) {
        throw new Exception("No se encontró el id_empresa para el usuario en sesión.");
    }

    // Consultar todos los datos de la tabla de préstamos
    $query = "SELECT p.ID_prest, p.Fecha, p.Cantidad_cuotas, p.Valor_Cuotas, p.cuotas_en_deuda, p.cuotas_pagas, p.VALOR, p.estado AS estado_prestamo, e.estado AS estado_descripcion, CONCAT(u.nombre_us, ' ', u.apellido_us) AS nombre_empleado
              FROM prestamo p
              INNER JOIN estado e ON p.estado = e.ID_Es
              INNER JOIN usuarios u ON p.ID_Empleado = u.id_us
              WHERE u.id_empresa = :id_empresa_session";
    $statement = $conexion->prepare($query);
    $statement->bindParam(':id_empresa_session', $id_empresa_session, PDO::PARAM_INT);
    $statement->execute();
    $prestamos = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejar errores de conexión a la base de datos
    echo "Error de conexión a la base de datos: " . $e->getMessage();
    exit();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

// Procesar el formulario cuando se presiona uno de los botones
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['aprobar']) || isset($_POST['rechazar'])) {
        $id_prestamo = $_POST['id_prestamo'];
        $estado = isset($_POST['aprobar']) ? '6' : '7'; // Si se presiona el botón "Aprobar", establece el estado en '6', de lo contrario, en '7'

        try {
            // Actualizar el estado del préstamo
            $query_update_estado = "UPDATE prestamo SET estado = :estado WHERE ID_prest = :id_prestamo";
            $statement_update = $conexion->prepare($query_update_estado);
            $statement_update->bindParam(':estado', $estado, PDO::PARAM_STR);
            $statement_update->bindParam(':id_prestamo', $id_prestamo, PDO::PARAM_INT);
            $statement_update->execute();

            // Preparar mensaje de estado
            $mensaje_estado = ($estado == '6') ? 'Préstamo aprobado' : 'Préstamo rechazado';
            $mensaje_color = ($estado == '6') ? '#28a745' : '#e88162';

            // Redirigir para evitar envío del formulario al actualizar la página
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } catch (PDOException $e) {
            echo "Error al actualizar el estado del préstamo: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Préstamos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="icon" type="image/png" href="../../img/logo_algj.png">
    
</head>

<body>
    <a class="btn btn-success" href="index.php">INICIO</a>

    <div class="container mt-5">
        <h2>Lista de Préstamos</h2>
        <table class="table table-striped custom-table">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Fecha</th>
                    <th>Cantidad de Cuotas</th>
                    <th>Valor de Cuotas</th>
                    <th>Cuotas en Deuda</th>
                    <th>Cuotas Pagas</th>
                    <th>Valor</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prestamos as $prestamo) : ?>
                    <tr>
                        <td><?php echo $prestamo['nombre_empleado']; ?></td>
                        <td><?php echo $prestamo['Fecha']; ?></td>
                        <td><?php echo $prestamo['Cantidad_cuotas']; ?></td>
                        <td><?php echo $prestamo['Valor_Cuotas']; ?></td>
                        <td><?php echo $prestamo['cuotas_en_deuda']; ?></td>
                        <td><?php echo $prestamo['cuotas_pagas']; ?></td>
                        <td><?php echo $prestamo['VALOR']; ?></td>
                        <td><?php echo $prestamo['estado_descripcion']; ?></td>
                        <td>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" onsubmit="return confirmSubmit(this);">
                                <input type="hidden" name="id_prestamo" value="<?php echo $prestamo['ID_prest']; ?>">
                                <?php if ($prestamo['estado_prestamo'] == 6 || $prestamo['estado_prestamo'] == 7) : ?>
                                    <span class="text-success"><?php echo $prestamo['estado_prestamo'] == 6 ? 'Préstamo aprobado' : 'Préstamo rechazado'; ?></span>
                                <?php else : ?>
                                    <button type="submit" name="aprobar" class="btn btn-success">Aprobar</button>
                                    <button type="submit" name="rechazar" class="btn btn-warning">Rechazar</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Script para deshabilitar los botones después de enviar el formulario -->
    <script>
        function confirmSubmit(form) {
            const approveButton = form.querySelector('button[name="aprobar"]');
            const rejectButton = form.querySelector('button[name="rechazar"]');

            let message = "";
            if (approveButton && approveButton === document.activeElement) {
                message = "¿Estás seguro de que deseas aprobar este préstamo?";
            } else if (rejectButton && rejectButton === document.activeElement) {
                message = "¿Estás seguro de que deseas rechazar este préstamo?";
            }

            if (confirm(message)) {
                return true; // Permitir el envío del formulario
            } else {
                return false; // Evitar el envío del formulario
            }
        }

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($mensaje_estado)) : ?>
            document.addEventListener("DOMContentLoaded", function() {
                // Mostrar mensaje de estado
                const message = document.createElement('span');
                message.textContent = "<?php echo $mensaje_estado; ?>";
                message.style.color = "<?php echo $mensaje_color; ?>";
                const forms = document.querySelectorAll('form');
                forms.forEach(function(form) {
                    const id_prestamo = form.querySelector('input[name="id_prestamo"]').value;
                    const prestamoEstado = <?php echo json_encode($prestamos); ?>;
                    // Seleccionar el mensaje de estado correcto para el préstamo actual
                    const este = prestamoEstado.find(prestamo => prestamo.ID_prest == id_prestamo);
                    const message = document.createElement('span');
                    message.textContent = este.estado_prestamo == '6' ? "Préstamo aprobado." : "Préstamo rechazado.";
                    message.style.color = este.estado_prestamo == '6' ? "#28a745" : "#e88162";
                    form.appendChild(message);
                    disableButtons(form, este.estado_prestamo == '6' ? "aprobado" : "rechazado");
                });
            });
        <?php endif; ?>
    </script>
</body>

</html>

