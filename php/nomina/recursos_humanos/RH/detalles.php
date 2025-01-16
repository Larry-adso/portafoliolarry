<?php
include '../../conexion/db.php';
include '../../conexion/validar_sesion.php';


// Verificar si se ha pasado un ID de nómina
if (isset($_GET['id'])) {
    $id_nomina = $_GET['id'];

    // Consultar los datos de la tabla nomina
    $sql_nomina = "SELECT n.*, 
    s.total AS total_s, 
    s.*,
    d.*, 
    u.nombre_us, 
    u.apellido_us 
FROM nomina n
LEFT JOIN sumas s ON n.id_suma = s.ID_INDUCCION
LEFT JOIN deduccion d ON n.id_deduccion = d.ID_DEDUCCION
LEFT JOIN usuarios u ON n.ID_user = u.id_us
WHERE n.ID = :id_nomina";
    $stmt_nomina = $conexion->prepare($sql_nomina);
    $stmt_nomina->bindParam(':id_nomina', $id_nomina, PDO::PARAM_INT);
    $stmt_nomina->execute();
    $detalle_nomina = $stmt_nomina->fetch(PDO::FETCH_ASSOC);

    // Convertir la fecha al nombre del mes en español
    $meses = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
    $fecha = new DateTime($detalle_nomina['fecha']);
    $nombre_mes = $meses[$fecha->format('n') - 1];
} else {
    // Si no se proporciona un ID de nómina, redirigir al archivo nominas.php
    header("Location: nominas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Nómina</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            background-color: #F9F5F0 !important;
            /* Beige claro */
            color: #0B0B0B !important;
            /* Negro oscuro */
        }

        h1 {
            color: #0B0B0B !important;
            /* Negro oscuro */
        }

        .card-body {
            background-color: #FFFFFF !important;
            /* Blanco */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1) !important;
        }

        .form-control {
            border: 1px solid #DDDDDD !important;
            /* Gris claro */
        }

        input.btn.btn-primary,
        a.btn.btn-primary {
            background-color: var(--button-login-color) !important;
            color: #FFFFFF !important;
            /* Blanco */
            border: none !important;
            /* Quitar borde para consistencia */
        }

        input.btn.btn-primary:hover {
            background-color: var(--button-login-hover) !important;
            /* Un tono más oscuro para el hover */
            color: #FFFFFF !important;
        }

        a.btn.btn-primary:hover {
            background-color: var(--button-login-hover) !important;
            /* Un tono más oscuro para el hover */
            color: #FFFFFF !important;
        }

        a.btn.btn-warning {
            background-color: var(--button-decline-term) !important;
            /* Rojo */
            color: #FFFFFF !important;
            /* Blanco */
            --bs-btn-border-color: none !important;

        }

        .table-dark {
            background-color: #2E2E2E !important;
            /* Gris oscuro */
            color: #FFFFFF !important;
            /* Blanco */
        }

        .table-light {
            background-color: #FFFFFF !important;
            /* Blanco */
            color: #0B0B0B !important;
            /* Negro oscuro */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1) !important;

        }

        .thead-dark {
            background-color: var(--hover-button-color) !important;
            /* Negro más claro */
            color: #FFFFFF !important;
            /* Blanco */
        }

        a.btn.btn-success {
            background-color: var(--primary-color) !important;
            --bs-btn-border-color: none !important;
        }

        a.btn.btn-success:hover {
            background-color: var(--hover-button-color) !important;
            --bs-btn-border-color: none !important;
        }


        .table-responsive {
            max-width: 600px !important;
            /* Establece el ancho máximo deseado */
            margin: auto !important;
            /* Centrar el div */
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <a class="btn btn-success" href="nomina.php" style="border:none;">INICIO</a>
        <h2 class="text-center mb-4">Detalles de Nómina</h2>
        <div class="col-md-6">
            <h4>Información de la Nómina</h4>
            <table class="table table-bordered">
                <tr>
                    <th>Numero de Nomina</th>
                    <td><?= $detalle_nomina['ID'] ?></td>
                </tr>
                <tr>
                    <th> Usuario</th>
                    <td><?= $detalle_nomina['ID_user'] . ' - ' . $detalle_nomina['nombre_us'] . ' ' . $detalle_nomina['apellido_us'] ?></td>
                </tr>
                <tr>
                    <th>Fecha</th>
                    <td><?= $fecha->format('d') . ' de ' . $nombre_mes . ' de ' . $fecha->format('Y') ?></td>
                </tr>
                <tr>
                    <th>Valor a Pagar (COP)</th>
                    <td>$ <?= number_format($detalle_nomina['Valor_Pagar'], 0, ',', '.') ?> COP</td>
                </tr>
            </table>
        </div>
        <div class="row">

            <div class="col-md-6">
                <h4>Información de Sumas </h4>
                <table class="table table-bordered">

                    <tr>
                        <th>Horas Trabajadas</th>
                        <td><?= $detalle_nomina['horas_trabajadas'] ?> HORAS</td>
                    </tr>
                    <tr>
                        <th>Dias Trabajados</th>
                        <td><?= $detalle_nomina['dias_trabajados'] ?> DIAS</td>
                    </tr>
                    <tr>
                        <th>Valor Hora Extra</th>
                        <td>$ <?= number_format($detalle_nomina['valor_hora_extra'], 0, ',', '.') ?> COP</td>
                    </tr>
                    <tr>
                        <th>Valor Auxilo de transporte</th>
                        <td>$ <?= number_format($detalle_nomina['transporte'], 0, ',', '.') ?> COP</td>
                    </tr>
                    <tr>
                        <th>Total Sumas</th>
                        <td>$ <?= number_format($detalle_nomina['total_s'], 0, ',', '.') ?> COP</td>
                    </tr>

                </table>
            </div>
            <div class="col-md-6">
                <h4>Información Deducciones</h4>
                <table class="table table-bordered">

                    <tr>
                        <th>Valor deuccion de cuota</th>
                        <td>$ <?= number_format($detalle_nomina['cuota'], 0, ',', '.') ?> COP</td>
                    </tr>
                    <tr>
                        <th>Total Deducción de parafiscales</th>
                        <td>$ <?= number_format($detalle_nomina['parafiscales'], 0, ',', '.') ?> COP</td>
                    </tr>
                    <tr>
                        <th>Salario Total a Pagar</th>
                        <td>$ <?= number_format($detalle_nomina['total'], 0, ',', '.') ?> COP</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="text-center">
            <a href="nomina.php" class="btn btn-primary">Regresar a Nóminas</a>
        </div>
    </div>
</body>

</html>