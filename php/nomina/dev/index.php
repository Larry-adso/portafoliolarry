<?php
session_start(); // Inicia la sesión

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['id_us'])) {
    // Si no ha iniciado sesión, muestra una alerta y redirige al usuario a la página de inicio de sesión
    echo '
    <script>
        alert("Por favor inicie sesión e intente nuevamente");
        window.location = "PHP/login.php";
    </script>
    ';
    session_destroy(); // Destruye la sesión
    die(); // Termina la ejecución del script
}

include "../conexion/db.php"; // Incluye el archivo de conexión a la base de datos

$id_rol = $_SESSION['id_rol']; // Obtiene el rol del usuario de la sesión
if ($id_rol == '4') { // Verifica si el rol del usuario es '4'

    // Configuración de la paginación
    $results_per_page = 5; // Número de resultados por página
    if (isset($_GET['page'])) { // Verifica si se ha establecido la página actual en la URL
        $page = $_GET['page']; // Establece la página actual
    } else {
        $page = 1; // Si no se ha establecido, por defecto es la página 1
    }
    $start_from = ($page - 1) * $results_per_page; // Calcula el punto de inicio para la consulta SQL

    // Inicializa el filtro
    $filter = "";
    if (isset($_POST['filter'])) { // Verifica si se ha enviado un filtro en el formulario
        $filter = $_POST['filter']; // Establece el filtro
    }

    // Inicializa la búsqueda
    $search = "";
    if (isset($_POST['search'])) { // Verifica si se ha enviado una búsqueda en el formulario
        $search = $_POST['search']; // Establece el término de búsqueda
    }

    // Prepara la consulta SQL para obtener datos de las empresas
    $consulta = $conexion->prepare("SELECT empresas.NIT, empresas.Nombre, empresas.ID_Licencia, empresas.Correo, empresas.barcode, licencia.Serial, licencia.F_inicio, licencia.F_fin, tp_licencia.Tipo AS Tipo_Licencia, estado.Estado
        FROM empresas
        INNER JOIN licencia ON empresas.ID_Licencia = licencia.ID
        INNER JOIN tp_licencia ON licencia.TP_licencia = tp_licencia.ID
        INNER JOIN estado ON licencia.ID_Estado = estado.ID_Es
        WHERE empresas.Nombre LIKE '%$search%' -- Aplica el término de búsqueda
        ORDER BY empresas.Nombre
        LIMIT $start_from, $results_per_page"); // Aplica la paginación
    $consulta->execute(); // Ejecuta la consulta
    $consulta_ = $consulta->fetchAll(PDO::FETCH_ASSOC); // Obtiene todos los resultados

    // Prepara la consulta SQL para obtener el nombre del usuario autenticado
    $consultaUsuario = $conexion->prepare("SELECT nombre_us FROM usuarios WHERE id_us = :id_us");
    $consultaUsuario->bindParam(':id_us', $_SESSION['id_us']); // Vincula el parámetro de la consulta con el ID de usuario de la sesión
    $consultaUsuario->execute(); // Ejecuta la consulta
    $usuario = $consultaUsuario->fetch(PDO::FETCH_ASSOC); // Obtiene el resultado
    $nombreUsuario = $usuario['nombre_us']; // Obtiene el nombre de usuario

    // Prepara la consulta SQL para obtener datos de licencias en un estado específico
    $consultaLicencia = $conexion->prepare("SELECT licencia.ID, licencia.Serial, tp_licencia.Tipo FROM licencia 
    INNER JOIN tp_licencia ON licencia.TP_licencia = tp_licencia.ID WHERE licencia.ID_estado = 3");
    $consultaLicencia->execute(); // Ejecuta la consulta
    $Tp_licencia = $consultaLicencia->fetchAll(PDO::FETCH_ASSOC); // Obtiene todos los resultados
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Menu desarrollador</title>
        <link rel="stylesheet" href="PHP/css/dev.css">
        <link rel="icon" type="image/png" href="../img/logo_algj.png">
        <script src="https://kit.fontawesome.com/41bcea2ae3.js" crossorigin="anonymous"></script>
        </style>
    </head>

    <body id="body">
        <header>
            <div class="icon__menu">
                <i class="fas fa-bars" id="menu_toggle"></i>
            </div>
        </header>
        <div class="menu__side" id="menu_side">
            <div class="name__page">
                <i class="far fa-user"></i>
                <h4>DEV</h4>
                <p>: <?php echo $nombreUsuario; ?></p>
            </div>
            <div class="options__menu">
                <a href="../index.php" class="selected">
                    <div class="option">
                        <i class="fas fa-home" title="Inicio"></i>
                        <h4>Inicio</h4>
                    </div>
                </a>
                <a href="PHP/Register_empresa.php">
                    <div class="option">
                        <i class="far fa-file" title="Crear empresa"></i>
                        <h4>Crear Empresa</h4>
                    </div>
                </a>
                <a href="PHP/serial.php">
                    <div class="option">
                        <i class="fas fa-key" title="Seriales"></i>
                        <h4>Seriales</h4>
                    </div>
                </a>
                <a href="PHP/register.php">
                    <div class="option">
                        <i class="far fa-user" title="Login"></i>
                        <h4>Registrar Personas</h4>
                    </div>
                </a>
                <a href="PHP/developer/devs.php">
                    <div class="option">
                        <i class="fas fa-children"></i>
                        <h4>Ver Personas</h4>
                    </div>
                </a>
                <a href="PHP/developer/estados.php">
                    <div class="option">
                        <i class="far fa-address-card" title="Opciones de estados"></i>
                        <h4>Opciones de estados</h4>
                    </div>
                </a>
                <a href="PHP/developer/info.php">
                    <div class="option">
                        <i class="far fa-address-card" title="Contactos"></i>
                        <h4>Contactos</h4>
                    </div>
                </a>
                <a href="PHP/cerrar.php">
                    <div class="option">
                        <i class="fas fa-share-from-square" title="Cerrar sesión"></i>
                        <h4>Cerrar sesión</h4>
                    </div>
                </a>
            </div>
            <div class="icon__menu icon__close">
                <i class="fas fa-times" id="menu_close"></i>
            </div>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <main>
            <h4>Empresas que han adquirido el software</h4>


            <div class="form-container">

                <form method="POST">
                    <div class="search-container">
                        <input type="text" name="search" placeholder="Buscar por nombre...">
                        <input type="submit" value="Buscar">
                    </div>

                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-primary table-bordered text-nowrap" id="datatable_users">
                    <thead>
                        <tr>
                            <th scope="col">NIT</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Seriales</th>
                            <th scope="col">Estado Licencia</th>
                            <th scope="col">Fecha Inicio</th>
                            <th scope="col">Fecha Fin</th>
                            <th scope="col">Barcode</th>
                            <th scope="col">Tipo Licencia</th>
                            <th scope="col">Renovar</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($consulta_ as $info) { ?>
                            <tr>
                                <td scope="row"><?php echo htmlspecialchars($info['NIT']); ?></td>
                                <td><?php echo htmlspecialchars($info['Nombre']); ?></td>
                                <td><?php echo htmlspecialchars($info['Correo']); ?></td>
                                <td><?php echo htmlspecialchars($info['Serial']); ?></td>
                                <td><?php echo htmlspecialchars($info['Estado']); ?></td>
                                <td><?php echo htmlspecialchars($info['F_inicio']); ?></td>
                                <td><?php echo htmlspecialchars($info['F_fin']); ?></td>
                                <td><img src="PHP/<?php echo htmlspecialchars($info['barcode']); ?>" alt="Barcode"></td>
                                <td><?php echo htmlspecialchars($info['Tipo_Licencia']); ?></td>
                                <td>
                                    <?php
                                    if ($info['Estado'] == 'Activa') {
                                        echo '<a name="" id="" class="btn-custom" href="#" role="button" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; border-radius: 4px; border: 1px solid #398439;">Vigente</a>';
                                    } elseif ($info['Estado'] == 'primera vez') {
                                        echo '<a name="" id="" class="btn-custom" href="#" role="button" style="background-color: #408df1; color: blue; padding: 10px 20px; text-decoration: none; display: inline-block; border-radius: 4px; border: 1px solid #398439;">Por activar</a>';
                                    } else {
                                        echo '<a name="" id="" class="btn-custom" href="PHP/renovar.php?NIT=' . $info['NIT'] . '" role="button" style="background-color: #f50c0c; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; border-radius: 4px; border: 1px solid #398439;">Renovar</a>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>



                    </tbody>
                </table>
            </div>


            <!-- Paginación -->
            <?php
            $sql = "SELECT COUNT(*) AS total FROM empresas";
            $result = $conexion->query($sql);
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $total_pages = ceil($row["total"] / $results_per_page);
            ?>
            <div class="pagination">
                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<a href='?page=" . $i . "'>" . $i . "</a>";
                }
                ?>
            </div>
        </main>
        <script>

        </script>

        <!-- JavaScript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <script src="PHP/js/data.js"></script>

    </body>

    </html>

<?php
} else {
    echo '
    <script>
        alert("su rol no tiene acceso a esta pagina");
        window.location = "PHP/login.php";
    </script>
    ';
}
?>