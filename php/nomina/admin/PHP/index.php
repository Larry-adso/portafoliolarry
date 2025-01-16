<?php
session_start();
if (!isset($_SESSION['id_us']) || ($_SESSION['id_rol'] != 5 && $_SESSION['id_rol'] != 7)) {
    echo '
            <script>
                alert("Por favor inicie sesión e intente nuevamente");
                window.location = "../../dev/PHP/login.php";
            </script>
            ';
    session_destroy();
    die();
}

// Incluir el archivo de conexión
include '../../conexion/db.php';

// Inicializar las variables de búsqueda
$search_term = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Asignar valor de búsqueda si está disponible
    $search_term = isset($_POST["search_term"]) ? $_POST["search_term"] : "";
}

try {
    // Obtener id_empresa del usuario de la sesión activa
    $id_us = $_SESSION['id_us'];
    $stmt = $conexion->prepare("SELECT id_empresa FROM usuarios WHERE id_us = :id_us");
    $stmt->bindParam(':id_us', $id_us, PDO::PARAM_INT);
    $stmt->execute();
    $id_empresa = $stmt->fetchColumn();

    // Consulta SQL con filtro de búsqueda y restricción de id_empresa y id_rol
    $sql = "SELECT 
    usuarios.*,
    usuarios.id_us AS id_estado, 
    usuarios.nombre_us, 
    usuarios.apellido_us, 
    usuarios.correo_us, 
    usuarios.tel_us, 
    roles.tp_user, 
    puestos.cargo, 
    puestos.salario, 
    usuarios.ruta_foto,
    estado.Estado
FROM usuarios 
LEFT JOIN roles ON usuarios.id_rol = roles.id 
LEFT JOIN puestos ON usuarios.id_puesto = puestos.id 
LEFT JOIN estado ON usuarios.id_estado = estado.ID_Es
WHERE usuarios.id_rol >= 6 
  AND usuarios.id_empresa = :id_empresa";



    // Aplicar filtro si se proporciona un término de búsqueda
    if (!empty($search_term)) {
        $sql .= " AND (usuarios.nombre_us LIKE :search_term OR usuarios.apellido_us LIKE :search_term OR usuarios.tel_us LIKE :search_term OR usuarios.id_us LIKE :search_term OR roles.tp_user LIKE :search_term OR puestos.cargo LIKE :search_term)";
    }

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
    if (!empty($search_term)) {
        $search_term = "%" . $search_term . "%";
        $stmt->bindParam(':search_term', $search_term, PDO::PARAM_STR);
    }
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
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

        .card {
            margin-bottom: 20px;
            background-color: #c7a17a;
            transition: all .4s;
        }

        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card:hover {
            transition: all .4s;
            margin-top: -10px;
            border: 2px solid #c7a17a;
            box-shadow: 0px 0px 5px 5px #c7a17a;
        }

        .botones {
            display: flex;
            width: 100%;
            height: auto;
            justify-content: space-evenly;
            align-items: center;
        }

        #btn {
            background-color: #c7a17a;
            border: none;
        }
    </style>


</head>

<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <div class="container mt-5">
            <h2 class="mb-4">Empleados</h2>
            <div class="row mb-4">
                <div class="col">
                    <form method="post" class="form-inline">
                        <div class="form-group mr-2">
                            <input type="text" class="form-control" name="search_term" placeholder="Buscar...">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2" id="btn"><i class="fas fa-search"></i> Buscar</button>
                        <?php if (!empty($search_term)) : ?>
                            <a href="." class="btn btn-secondary" id="btn"><i class="fas fa-times"></i> Limpiar</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <div class="row">
                <?php if (isset($usuarios) && !empty($usuarios)) : ?>
                    <?php foreach ($usuarios as $usuario) : ?>
                        <div class="col-md-4">
                            <div class="card">
                                <?php if (!empty($usuario['ruta_foto'])) : ?>
                                    <?php
                                    // Obteniendo la ruta de la foto
                                    $ruta_foto = $usuario['ruta_foto'];

                                    // Eliminando dos niveles del directorio de la ruta actual
                                    $ruta_foto_ajustada = implode("/", array_slice(explode("/", $ruta_foto), 2));
                                    ?>
                                    <img class="card-img-top" src="../<?php echo $ruta_foto_ajustada; ?>" alt="Foto">
                                <?php endif; ?>

                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $usuario['nombre_us'] . ' ' . $usuario['apellido_us']; ?></h5>
                                    <p class="card-text"><strong>Cédula:</strong> <?php echo $usuario['id_us']; ?></p>
                                    <p class="card-text"><strong>Rol:</strong> <?php echo $usuario['tp_user']; ?></p>
                                    <p class="card-text"><strong>Cargo:</strong> <?php echo $usuario['cargo']; ?></p>
                                    <p class="card-text"><strong>Salario:</strong> $ <?= number_format($usuario['salario'], 0, ',', '.') ?> COP</p>
                                    <p class="card-text"><strong>Estado:</strong> <?php echo $usuario['Estado']; ?></p>
                                    <!-- Formulario oculto para enviar el id_us -->
                                    <div class="botones">
                                        <form action="liquidacion/editar.php" method="POST" style="display: none;" id="liquidar_form_<?php echo $usuario['id_us']; ?>">
                                            <input type="hidden" name="id_us" value="<?php echo $usuario['id_us']; ?>" id="btn">
                                        </form>
                                        <!-- Botón para liquidar -->
                                        <button class="btn btn-success btn-sm" onclick="window.location.href='editar_php/editar_empleados.php?id_us=<?php echo $usuario['id_us']; ?>'" id="btn">Editar</button>
                                        <form action="despedir.php" method="POST">
                                            <input type="hidden" name="id_us" value="<?php echo $usuario['id_us']; ?>">
                                            <input type="hidden" name="despedir" value="15">
                                            <button type="submit" class="btn btn-success btn-sm" id="btn">Despedir</button>
                                        </form>
                                    </div>



                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="col">
                        <div class="alert alert-warning" role="alert" style="background-color: transparent; border:none;">
                            No se encontraron trabajadores.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>


</body>

</html>