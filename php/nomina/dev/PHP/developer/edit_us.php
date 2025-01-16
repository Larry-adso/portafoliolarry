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

include("../../../conexion/db.php");

$id_us = isset($_GET['id']) ? $_GET['id'] : null;
$userData = null;

if ($id_us) {
    try {
        $sql = "SELECT * FROM usuarios WHERE id_us = :id_us";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_us', $id_us, PDO::PARAM_INT);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_us = $_POST['id_us'];
    $nombre_us = $_POST['nombre_us'];
    $apellido_us = $_POST['apellido_us'];
    $correo_us = $_POST['correo_us'];
    $tel_us = $_POST['tel_us'];

    try {
        $sql = "UPDATE usuarios SET nombre_us = :nombre_us, apellido_us = :apellido_us, correo_us = :correo_us, tel_us = :tel_us WHERE id_us = :id_us";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id_us', $id_us, PDO::PARAM_INT);
        $stmt->bindParam(':nombre_us', $nombre_us, PDO::PARAM_STR);
        $stmt->bindParam(':apellido_us', $apellido_us, PDO::PARAM_STR);
        $stmt->bindParam(':correo_us', $correo_us, PDO::PARAM_STR);
        $stmt->bindParam(':tel_us', $tel_us, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo '
            <script>
                alert("Registro editado correctamente");
                window.location = "devs.php";
            </script>
            ';
        } else {
            echo '
                <script>
                    alert("Registro no editado correctamente");
                    window.location = "devs.php";
                </script>
                ';
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conexion = null;
}

?>

<!doctype html>
<html lang="en">

<head>
    <title>Editar usuarios</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="icon" type="image/png" href="../../../img/logo_algj.png">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <br>
    <hr>
    <h2 style="text-align: center;">Edición de administradores y desarrolladores</h2>
    <hr>

    <main class="d-flex align-items-center justify-content-center vh-100">
        <?php if ($userData) : ?>
            <div class="w-50 p-4 border rounded shadow-sm bg-light">
                <h4>Usuario: <?php echo htmlspecialchars($userData['id_us']) . ' - ' . htmlspecialchars($userData['nombre_us']); ?></h4>
                <form action="edit_us.php?id=<?php echo $userData['id_us']; ?>" method="post">
                    <input type="hidden" name="id_us" value="<?php echo htmlspecialchars($userData['id_us']); ?>">
                    <div class="mb-3">
                        <label for="nombre_us" class="form-label">Nombre</label>
                        <input type="text" name="nombre_us" class="form-control" id="nombre_us" value="<?php echo htmlspecialchars($userData['nombre_us']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido_us" class="form-label">Apellido</label>
                        <input type="text" name="apellido_us" class="form-control" id="apellido_us" value="<?php echo htmlspecialchars($userData['apellido_us']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="correo_us" class="form-label">Correo</label>
                        <input type="email" name="correo_us" class="form-control" id="correo_us" value="<?php echo htmlspecialchars($userData['correo_us']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="tel_us" class="form-label">Teléfono</label>
                        <input type="text" name="tel_us" class="form-control" id="tel_us" value="<?php echo htmlspecialchars($userData['tel_us']); ?>" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a name="" id="" class="btn btn-danger" href="devs.php" role="button">Atrás</a>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        <?php else : ?>
            <p>Usuario no encontrado.</p>
        <?php endif; ?>
    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>
