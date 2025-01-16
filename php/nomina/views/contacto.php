<?php
include("../conexion/db.php");

$nombres = $_POST['nombres'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$comentario = $_POST['comentario'];

// Validar los datos antes de insertarlos (opcional pero recomendado)
if (empty($nombres) || empty($correo) || empty($telefono) || empty($comentario)) {
    echo "Todos los campos son obligatorios.";
    exit();
}

// Insertar los datos en la base de datos
$sql = "INSERT INTO contactanos (nombres, correo, telefono, comentario, id_estado) VALUES (:nombres, :correo, :telefono, :comentario, 13)";
$stmt = $conexion->prepare($sql);

$stmt->bindParam(':nombres', $nombres);
$stmt->bindParam(':correo', $correo);
$stmt->bindParam(':telefono', $telefono);
$stmt->bindParam(':comentario', $comentario);

if ($stmt->execute()) {
    echo '
    <script>
        alert("Felicidades, se ha enviado correctamente la información. Por favor, espere una respuesta pacientemente.");
        window.location = "contactenos.php";
    </script>
    ';
} else {
    echo "Error al guardar la información.";
}

// Cerrar la conexión (opcional, dependiendo de tu configuración de conexión a la base de datos)
$conexion = null;
?>
