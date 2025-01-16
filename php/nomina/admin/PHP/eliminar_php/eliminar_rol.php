<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar rol</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/7fd910d257.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="../../../img/logo_algj.png">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <?php
                include '../../../conexion/db.php';

                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
                    $id = $_GET['id'];

                    if (isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
                        $sql = "DELETE FROM roles WHERE ID = :id";
                        $stmt = $conexion->prepare($sql);
                        $stmt->bindParam(':id', $id);

                        try {
                            $stmt->execute();
                            $mensaje = "Rol eliminado correctamente.";
                            header("Location:../index.php?mensaje=" . urlencode($mensaje));
                        } catch (PDOException $e) {
                            $mensaje = "Error al eliminar el rol: " . $e->getMessage();
                            echo "<h2>$mensaje</h2>";
                        }
                        exit();
                    }

                    $sql = "SELECT * FROM roles WHERE ID = :id";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    $rol = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($rol) {
                ?>

                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h3 class="card-title">Eliminar Rol</h3>
                            </div>
                            <div class="card-body">
                                <p>¿Estás seguro de que deseas eliminar este Rol?</p>
                                <form id="delete-form" action="" method="get">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="hidden" name="confirm" value="true">
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </div>

                        <script>
                            document.getElementById('delete-form').addEventListener('submit', function(event) {
                                event.preventDefault();
                                var confirmDelete = confirm("¿Estás seguro de que deseas eliminar este Rol?");
                                if (confirmDelete) {
                                    this.submit();
                                }
                            });
                        </script>

                <?php
                    } else {
                        echo "<p class='text-danger'>No se encontró el Rol con el ID proporcionado.</p>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>
