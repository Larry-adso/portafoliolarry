<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_us'])) {
    echo '
        <script>
            alert("Por favor inicie sesión e intente nuevamente");
            window.location = "../../dev/PHP/login.php";
        </script>
    ';
    die();
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
    <style>
        :root {
    --primary-color: #c7a17a !important;
    --background-color: #f9f5f0 !important;
    --dark-color: #151515 !important;
    --hover-button-color: #9b7752 !important;
    --button-login-color: #6DC5D1 !important;
    --button-login-hover: #59a2ac !important;
    --button-decline-term: #e88162 !important;
}

body {
    background-color: #F9F5F0 !important; /* Beige claro */
    color: #0B0B0B !important; /* Negro oscuro */
}

h1 {
    color: #0B0B0B !important; /* Negro oscuro */
}

.card-body {
    background-color: #FFFFFF !important; /* Blanco */
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1) !important;
}

.form-control {
    border: 1px solid #DDDDDD !important; /* Gris claro */
}

input.btn.btn-primary, a.btn.btn-primary {
    background-color: var(--button-login-color) !important;
    color: #FFFFFF !important; /* Blanco */
    border: none !important; /* Quitar borde para consistencia */
}

input.btn.btn-primary:hover {
    background-color: var(--button-login-hover) !important; /* Un tono más oscuro para el hover */
    color: #FFFFFF !important;
}
a.btn.btn-primary:hover {
    background-color: var(--button-login-hover) !important; /* Un tono más oscuro para el hover */
    color: #FFFFFF !important;  
}
a.btn.btn-warning {
    background-color: var(--button-decline-term) !important; /* Rojo */
    color: #FFFFFF !important; /* Blanco */
    --bs-btn-border-color: none !important;

}

.table-dark {
    background-color: #2E2E2E !important; /* Gris oscuro */
    color: #FFFFFF !important; /* Blanco */
}

.table-light {
    background-color: #FFFFFF !important; /* Blanco */
    color: #0B0B0B !important; /* Negro oscuro */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1) !important;

}

.thead-dark {
    background-color: var(--hover-button-color) !important; /* Negro más claro */
    color: #FFFFFF !important; /* Blanco */
}
a.btn.btn-success {
    background-color: var(--primary-color) !important;
    --bs-btn-border-color: none !important;
    border: none;
}
a.btn.btn-success:hover {
    background-color: var(--hover-button-color) !important;
    --bs-btn-border-color: none !important;
}


.table-responsive {
    max-width: 600px !important; /* Establece el ancho máximo deseado */
    margin: auto !important; /* Centrar el div */
}

    </style>


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