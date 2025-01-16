<?php
if (!isset($_SESSION['id_us'])) {
  echo '
            <script>
                alert("Por favor inicie sesión e intente nuevamente");
                window.location = "../../modulo_larry/PHP/login.php";
            </script>
            ';
  session_destroy();
  die();
}
require_once("../conexion/db.php");

$nombre = isset($_SESSION['nombre_us']) ? $_SESSION['nombre_us'] : 'Usuario';
$apellido = isset($_SESSION['apellido_us']) ? $_SESSION['apellido_us'] : 'Usuario';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MENÚ</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .btn-toggle {
            background-color: #c7a17a;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 999;
        }

        .sidebar {
            width: 0;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #c7a17a;
            overflow-x: hidden;
            transition: width 0.5s;
            padding-top: 60px;
            z-index: 1;
            color: #fff;
        }

        .sidebar.active {
            width: 250px;
            margin-right: 120px;
        }

        .sidebar h3, h4 {
            padding: 10px;
            text-align: center;
        }

        .sidebar ul li {
            padding: 35px 20px;
        }

        .sidebar a {
            text-decoration: none;
            color: #fff;
        }

        .sidebar a:hover {
            color: #ccc;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            color: #fff;
            cursor: pointer;
        }


        .btn-sm {
            background-color: #111;
        }

        .active-sidebar {
            margin-left: 250px;
        }
    </style>
</head>

<body>
    <button class="btn btn-toggle" id="toggleNav">
        <i class="fas fa-bars"></i>
    </button>

    <div class="sidebar" id="sidebar">
        <h3>Bienvenido</h3>
        <h4><?php echo htmlspecialchars($nombre_us . ' ' . $apellido_us); ?></h4> 
            <ul class="nav flex-column">
            <li class="nav-item"><a href="prestamo.php" class="nav-link"><i class="fas fa-user-tag"></i> Solicitar prestamos</a></li>
            <li class="nav-item"><a href="permisos.php" class="nav-link"><i class="fas fa-user-tag"></i> Solicitar permisos</a></li>
            <li class="nav-item"><a href="nomina_Tab.php" class="nav-link"><i class="fas fa-user-tag"></i> Nomina</a></li>
            <li class="nav-item">

                <a type="submit" name="logout" class="nav-link fas fa-sign-out-alt" style="background: none; border: none; padding: 0; cursor: pointer; color:#fff;" href="../dev/PHP/cerrar.php" role="button">Cerrar sesion</a>
            </li>
        </ul>

    </div>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#toggleNav").click(function() {
                $("#sidebar").toggleClass('active');
                $("#main-content").toggleClass('active-sidebar');
            });
        });
    </script>
</body>

</html>