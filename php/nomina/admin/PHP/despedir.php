<?php
// Incluir el archivo de conexión
include '../../conexion/db.php';

// Verificar si se recibió el id_us por POST
if (isset($_POST['id_us'])) {
    $id_us = $_POST['id_us'];

    // Consulta para obtener los datos del usuario
    $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE id_us = ?");
    $consulta->execute([$id_us]);
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró el usuario
    if ($usuario) {
        // Mostrar alerta de confirmación
        echo "<script>";
        echo "if (confirm('¿Deseas despedir a este usuario?')) {";
        // Actualizar el estado del usuario a 15
        $actualizar = $conexion->prepare("UPDATE usuarios SET id_Estado = 15 WHERE id_us = ?");
        $actualizar->execute([$id_us]);
        echo "alert('Usuario despedido correctamente.');";
        echo "} else {";
        echo "alert('No se realizó ningún cambio.');";
        echo "}";
        echo "window.location.href = 'index.php';";
        echo "</script>";
    } else {
        // Si no se encontró el usuario
        echo "<script>";
        echo "alert('El usuario no existe.');";
        echo "window.location.href = 'index.php';";
        echo "</script>";
    }
} else {
    // Si no se recibió el id_us por POST
    echo "<script>";
    echo "alert('No se recibió información válida.');";
    echo "window.location.href = 'index.php';";
    echo "</script>";
}
?>
