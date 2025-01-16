<?php
include '../../conexion/db.php';
include '../../conexion/validar_sesion.php';

// Obtener id_us de la sesión activa
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

    // Consultar los datos de la tabla nomina para el id_empresa específico
    $sql = "SELECT n.ID, n.ID_user, CONCAT(u.nombre_us, ' ', u.apellido_us) AS nombre_completo, DATE_FORMAT(n.fecha, '%d de %M de %Y') AS fecha, n.id_deduccion, n.id_suma, FORMAT(n.Valor_Pagar, 0) AS Valor_Pagar
            FROM nomina n
            INNER JOIN usuarios u ON n.ID_user = u.id_us
            WHERE u.id_empresa = :id_empresa";

    // Verificar si hay una búsqueda específica
    if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
        $buscar = '%' . $_GET['buscar'] . '%';
        $sql .= " AND (u.nombre_us LIKE :buscar OR u.apellido_us LIKE :buscar OR n.ID_user LIKE :buscar)";
    }

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_empresa', $id_empresa_session, PDO::PARAM_INT);

    if (isset($buscar)) {
        $stmt->bindParam(':buscar', $buscar, PDO::PARAM_STR);
    }

    $stmt->execute();
    $nomina = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Array para transformar nombres de meses de inglés a español
    $meses = array(
        'January' => 'Enero',
        'February' => 'Febrero',
        'March' => 'Marzo',
        'April' => 'Abril',
        'May' => 'Mayo',
        'June' => 'Junio',
        'July' => 'Julio',
        'August' => 'Agosto',
        'September' => 'Septiembre',
        'October' => 'Octubre',
        'November' => 'Noviembre',
        'December' => 'Diciembre'
    );
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    // Puedes redirigir a una página de error o manejar la situación de otra forma
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nóminas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="icon" type="image/png" href="../../img/logo_algj.png">
</head>
<body>
<a class="btn btn-success" href="index.php" style="border: none;">INICIO</a>

    <div class="container mt-5">
        <h2 class="mb-4">Nóminas</h2>
        <form action="" method="GET" class="mb-3">
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Buscar por nombre o N° de Documento" name="buscar">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
        </form>
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
                    <td><?= $fila['ID_user'] ?></td>
                    <td><?= $fila['nombre_completo'] ?></td>
                    <td>
                        <?php
                            // Transformar el nombre del mes de inglés a español
                            $fecha = $fila['fecha'];
                            foreach ($meses as $mes_en => $mes_es) {
                                $fecha = str_replace($mes_en, $mes_es, $fecha);
                            }
                            echo $fecha;
                        ?>
                    </td>
                    
                    <td> $<?= $fila['Valor_Pagar'] ?> COP</td>
                    <td><a href="detalles.php?id=<?= $fila['ID'] ?>" class="btn btn-primary">Detalles</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
