<?php
session_start(); // Asegúrate de iniciar la sesión
if (!isset($_SESSION['id_us'])) {
    echo '
              <script>
                  alert("Por favor inicie sesión e intente nuevamente");
                  window.location = "../dev/PHP/login.php";
              </script>
              ';
    session_destroy();
    die();
}

include '../conexion/db.php';
$conexion->exec("SET lc_time_names = 'es_ES'");

// Obtener el ID del usuario que inició sesión
$id_us = $_SESSION['id_us'];
$id_rol = $_SESSION['id_rol'];

if ($id_rol == '6') {

    $sql_usuario = $conexion->prepare("SELECT u.id_puesto, u.nombre_us, u.apellido_us, p.salario FROM usuarios u JOIN puestos p ON u.id_puesto = p.ID WHERE u.id_us = ?");
    $sql_usuario->execute([$id_us]);
    $row_usuario = $sql_usuario->fetch();
  
    $id_puesto = $row_usuario['id_puesto'];
    $nombre_us = $row_usuario['nombre_us'];
    $apellido_us = $row_usuario['apellido_us'];
    $salario = $row_usuario['salario'];
    // Consultar los datos de la tabla nomina para el usuario en sesión
    $sql = "SELECT n.ID, n.ID_user, CONCAT(u.nombre_us, ' ', u.apellido_us) AS nombre_completo, DATE_FORMAT(n.fecha, '%d de %M de %Y') AS fecha, n.id_deduccion, n.id_suma, FORMAT(n.Valor_Pagar, 0) AS Valor_Pagar
            FROM nomina n
            INNER JOIN usuarios u ON n.ID_user = u.id_us
            WHERE n.ID_user = :id_usuario_sesion";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_usuario_sesion', $id_us, PDO::PARAM_INT);
    $stmt->execute();
    $nomina = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nóminas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/prestamo.css">
    <link rel="icon" type="image/png" href="../img/logo_algj.png">
</head>
<body style="background-color: #f9f5f0;">
<?php include 'nav.php'; ?>
    <div class="container mt-5">
        <h2 class="mb-4">Nóminas</h2>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Cedula Usuario</th>
                    <th>Nombre Usuario</th>
                    <th>Fecha</th>
                    <th>Valor a Pagar</th>
                    <th>Detalles Nómina</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($nomina as $fila): ?>
                <tr>
                    <td><?= htmlspecialchars($fila['ID_user']) ?></td>
                    <td><?= htmlspecialchars($fila['nombre_completo']) ?></td>
                    <td><?= htmlspecialchars($fila['fecha']) ?></td>
                    <td><?= htmlspecialchars($fila['Valor_Pagar']) ?></td>
                    <td><a href="detalles.php?id=<?= htmlspecialchars($fila['ID']) ?>" class="btn btn-primary">Detalles</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
} else {
  echo '
    <script>
        alert("su rol no tiene acceso a esta pagina");
        window.location = "../dev/PHP/login.php";
    </script>
    ';
}
?>
