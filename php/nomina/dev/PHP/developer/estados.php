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
include '../../../conexion/conexion.php';

$id_rol = $_SESSION['id_rol'];
if ($id_rol == '4') {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Estados</title>
        <link rel="icon" type="image/png" href="../../../img/logo_algj.png">

        <!-- Agregar referencia al archivo CSS de Bootstrap 5 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Estilos personalizados -->
        <style>
            .button {
                text-align: center;
                margin-bottom: 20px;
            }

            .button a {
                margin-right: 10px;
            }

            .editar-btn {
                background-color: green;
                color: white;
            }

            .borrar-btn {
                background-color: red;
                color: white;
            }

            .custom-table {
                margin: 0 auto;
                /* Centra la tabla horizontalmente */
                width: 80%;
                /* Define un ancho máximo para la tabla */
            }
        </style>
    </head>

    <body>
        <div class="content">
            <div class="container">
                <h2 class="mb-5">TABLA ESTADOS</h2>
                <div class="table-responsive">

                    <table class="table table-striped custom-table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">ESTADO</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            <?php
                            $sql = "SELECT * FROM estado";
                            $result = $conexion->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['ID_Es'] . "</td>";
                                    echo "<td>" . $row['Estado'] . "</td>";
                                    echo "<td>
                                          <a href='editar_estados.php?id=" . $row['ID_Es'] . "' class='btn editar-btn'>Editar</a> 
                                          <a href='eliminar_estado.php?id=" . $row['ID_Es'] . "' class='btn borrar-btn'>Eliminar</a>
                                      </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No hay estados registrados.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="button">
            <a name="" id="" class="btn btn-primary" href="estados.php" role="button">Crear</a>
            <a name="" id="" class="btn btn-danger" href="../../index.php">Salir</a>


        </div>

    </body>

    </html>

<?php
} else {
    echo '
    <script>
        alert("su rol no tiene acceso a esta pagina");
        window.location = "../login.php";
    </script>
    ';
}
?>