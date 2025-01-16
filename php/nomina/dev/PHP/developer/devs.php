<?php
session_start();
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
$id_rol = $_SESSION['id_rol'];
if ($id_rol == '4') {

    include("../../../conexion/db.php");

    $consultaUsuarios = $conexion->prepare("SELECT usuarios.*, roles.TP_user AS TP_user FROM usuarios 
    INNER JOIN roles ON usuarios.id_rol = roles.ID WHERE usuarios.id_rol IN (4, 5)");
    $consultaUsuarios->execute();
    $usuarios = $consultaUsuarios->fetchAll(PDO::FETCH_ASSOC);
    

?>

<!doctype html>
<html lang="en">

<head>
    <title>Usuarios</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="../../../img/logo_algj.png">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<link rel="stylesheet" href="../css/devs.css">
</head>


<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main>
        <h1>Desarrolladores</h1>
        <div class="container">
            <table class="table" id="gameTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Telefono</th>
                        <th>Roll</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario) : ?>
                        <tr>
                            <td><?php echo $usuario['id_us']; ?></td>
                            <td><?php echo $usuario['nombre_us']; ?></td>
                            <td><?php echo $usuario['apellido_us']; ?></td>
                            <td><?php echo $usuario['correo_us']; ?></td>
                            <td><?php echo $usuario['tel_us']; ?></td>
                            <td><?php echo $usuario['TP_user']; ?></td>
                            <td>
                                <a href="edit_us.php?id=<?php echo $usuario['id_us']; ?>" class="btn btn-primary">Editar</a>
                                <a href="deleate_us.php?id=<?php echo $usuario['id_us']; ?>" class="btn btn-danger">Borrar</a>
                                </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a name="" id="" class="btn btn-info" href="../../index.php" role="button">salir</a>

        </div>
    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- DataTables JavaScript Libraries -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('#gameTable').DataTable();
        });
    </script>
</body>

</html>


<?php
} else {
    echo '
    <script>
        alert("Su rol no tiene acceso a esta página");
        window.location = "../login.php";
    </script>
    ';
}
?>