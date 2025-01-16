<?php
include("../../../conexion/db.php");

$contacto = $conexion->prepare("SELECT * FROM contactanos INNER JOIN estado WHERE ID_Es = id_estado");
$contacto->execute();
$contactorows = $contacto->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="../../../img/logo_algj.png">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>
    <style>
        a.btn.btn-warning {
            background-color: #c7a17a; 
            border: none;
        }
        a.btn.btn-warning:hover {
            background-color: #9b7752; 
            border: none;
        }

    </style>
<body>
    <header>
        <br>
        <br>
        
        <a name="" id="" class="btn btn-warning" href="../../index.php" role="button">Atras</a>
    </header>
    <main>  
        <br>
        <h1 style="text-align: center;">Peticiones para contactar con posibles clientes</h1>
        <hr>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-borderless table-primary align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombres</th>
                        <th>Correo</th>
                        <th>Telefono</th>
                        <th>Comentario</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach ($contactorows as $row) { ?>
                        <tr class="table-primary">
                            <td scope="row"><?php echo $row["id"]; ?></td>
                            <td><?php echo $row["nombres"]; ?></td>
                            <td><?php echo $row["correo"]; ?></td>
                            <td><?php echo $row["telefono"]; ?></td>
                            <td><?php echo $row["comentario"]; ?></td>
                            <td><?php echo $row["Estado"]; ?></td>
                            <td>
                                <?php if ($row['id_estado'] == 14) { ?>
                                    <a name="" id="" class="btn btn-secondary" href="#" role="button">Contactado</a>
                                <?php } elseif ($row['id_estado'] == 13) { ?>
                                    <a name="" id="" class="btn btn-primary" href="cambiar.php?id=<?php echo $row['id']; ?>" role="button">Se debe contactar el cliente</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <!-- Opcionalmente, puedes agregar aquí filas de pie de tabla -->
                </tfoot>
            </table>
        </div>
    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>