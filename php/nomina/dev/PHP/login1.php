<?php
include("../../conexion/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se enviaron ambos campos: correo y contraseña
    if (isset($_POST["id_us"]) && isset($_POST["pass"])) {
        try {
            // Escapar los valores para evitar inyección SQL    
            $id_us = $_POST["id_us"];
            $pass = $_POST["pass"];
            $pass = hash('sha512', $pass);

            // Consulta SQL para obtener el tipo de usuario y el estado del usuario
            $sql = "SELECT id_rol, id_estado FROM usuarios WHERE id_us = :id_us AND pass = :pass";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":id_us", $id_us);
            $stmt->bindParam(":pass", $pass);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Obtener el tipo de usuario y el estado
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $id_rol = $row["id_rol"];
                $id_estado = $row["id_estado"];

                // Iniciar sesión y guardar el ID de usuario y el tipo de usuario en variables de sesión
                session_start();
                $_SESSION["id_us"] = $id_us;
                $_SESSION["id_rol"] = $id_rol;

                // Verificar el estado del usuario
                if ($id_estado == 15) {
                    echo '<script>alert("No puede acceder al sistema porque fue despedido comunicate con tu jefe inmediato.");
                    window.location = "login.php";
                    </script>';
                    exit();
                } elseif ($id_estado == 5) {
                    // Redirigir a update.php si el estado es 5
                    header("Location: update.php");
                    exit();
                }

                // Redireccionar según el tipo de usuario
                switch ($id_rol) {
                    case 5:
                        header("Location: developer/desactive.php");
                        exit();
                    case 7:
                        header("Location: developer/desactive.php");
                        exit();
                    case 3:
                        header("Location: index3.php");
                        exit();
                    case 4:
                        header("Location:../index.php");
                        exit();
                    case 6:
                        header("Location: developer/desactive.php");
                        exit();
                    default:
                        // Manejar el caso en que el tipo de usuario no está definido
                        echo '<script>alert("ID o contraseña incorrectos.");
                        window.location = "login.php";
                        </script>';
                        exit();
                }
            } else {
                // Manejar el caso en que no se encontró ningún usuario
                echo '<script>alert("ID o contraseña incorrectos.");
                window.location = "login.php";
                </script>';
                exit();
            }
        } catch (PDOException $e) {
            // Manejar cualquier error de base de datos
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Manejar el caso en que no se enviaron ambos campos
        echo '<script>alert("No se puede iniciar sesión sin enviar datos.");
        window.location = "login.php";
        </script>';
        exit();
    }
}
?>
