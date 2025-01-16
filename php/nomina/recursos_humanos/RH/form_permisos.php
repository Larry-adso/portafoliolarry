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
require_once "../../conexion/db.php"; // Asegúrate de cambiar esto al nombre correcto de tu archivo de conexión

// Obtener el ID del usuario en sesión
$id_us_session = $_SESSION['id_us'];

$mensaje_estado = '';
$mensaje_color = '';

// Procesar el formulario cuando se presiona uno de los botones
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['aprobar']) || isset($_POST['rechazar'])) {
        $id_permiso = $_POST['id_permiso'];
        $estado = isset($_POST['aprobar']) ? 6 : 7; // 6 para aprobar, 7 para rechazar

        try {
            // Actualizar el estado del permiso en la base de datos
            $query_update_estado = "UPDATE permisos SET estado = :estado WHERE id_permiso = :id_permiso";
            $statement_update = $conexion->prepare($query_update_estado);
            $statement_update->bindParam(':estado', $estado, PDO::PARAM_INT);
            $statement_update->bindParam(':id_permiso', $id_permiso, PDO::PARAM_INT);
            $statement_update->execute();

            // Preparar mensaje de estado
            $mensaje_estado = isset($_POST['aprobar']) ? 'Permiso aprobado.' : 'Permiso rechazado.';
            $mensaje_color = isset($_POST['aprobar']) ? '#28a745' : '#e88162';

            // Redirigir para evitar envío del formulario al actualizar la página
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } catch (PDOException $e) {
            echo "Error al actualizar el estado del permiso: " . $e->getMessage();
        }
    }
}

try {
    // Consultar todos los permisos de la tabla permisos
    $query = "SELECT p.*, p.id_permiso, p.fecha, p.fecha_reingreso, p.estado, e.estado AS estado_descripcion, CONCAT(u.nombre_us, ' ', u.apellido_us) AS nombre_usuario
              FROM permisos p
              INNER JOIN estado e ON p.estado = e.ID_Es
              INNER JOIN usuarios u ON p.id_us = u.id_us
              WHERE u.id_empresa = (SELECT id_empresa FROM usuarios WHERE id_us = :id_us_session)";
    $statement = $conexion->prepare($query);
    $statement->bindParam(':id_us_session', $id_us_session, PDO::PARAM_INT);
    $statement->execute();
    $permisos = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Permisos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="icon" type="image/png" href="../../img/logo_algj.png">
</head>

<body>
    <a class="btn btn-success" href="index.php">INICIO</a>

    <div class="container mt-5">
        <h2>Lista de Permisos</h2>
        <table class="table table-striped custom-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Fecha de Reingreso</th>
                    <th>Obsevación</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($permisos as $permiso) : ?>
                    <tr>
                        <td><?php echo $permiso['nombre_usuario']; ?></td>
                        <td><?php echo $permiso['fecha']; ?></td>
                        <td><?php echo $permiso['fecha_reingreso']; ?></td>
                        <td><?php echo $permiso['observacion']; ?></td>
                        <td><?php echo $permiso['estado_descripcion']; ?></td>
                        <td>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" onsubmit="return confirmSubmit(this);">
                                <input type="hidden" name="id_permiso" value="<?php echo $permiso['id_permiso']; ?>">
                                <?php if ($permiso['estado'] != 6 && $permiso['estado'] != 7) : ?>
                                    <button type="submit" name="aprobar" class="btn btn-success">Aprobar</button>
                                    <button type="submit" name="rechazar" class="btn btn-warning">Rechazar</button>
                                <?php endif; ?>
                            </form>
                            <?php if ($permiso['estado'] == 6 || $permiso['estado'] == 7) : ?>
                                <span style="color: <?php echo $permiso['estado'] == 6 ? '#28a745' : '#e88162'; ?>">
                                    <?php echo $permiso['estado'] == 6 ? 'Permiso aprobado' : 'Permiso rechazado'; ?>
                                </span>
                            <?php endif; ?>
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
                message = "¿Estás seguro de que deseas aprobar este permiso?";
            } else if (rejectButton && rejectButton === document.activeElement) {
                message = "¿Estás seguro de que deseas rechazar este permiso?";
            }

            if (confirm(message)) {
                return true; // Permitir el envío del formulario
            } else {
                return false; // Evitar el envío del formulario
            }
        }
    </script>
</body>

</html>