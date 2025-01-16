<?php
// Incluir archivo de configuración de la base de datos y otros archivos necesarios
include '../../../conexion/db.php';

// Iniciar sesión (debe ser la primera línea ejecutada antes de cualquier salida)
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TABLA VALOR HORAS EXTRA</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../../img/logo_algj.png">
    <style>
        :root {
    --primary-color: #c7a17a !important;
    --background-color: #f9f5f0 !important;
    --dark-color: #151515 !important;
    --hover-button-color: #9b7752 !important;
    --button-login-color: #6DC5D1 !important;
    --button-login-hover: #59a2ac !important;
    --button-decline-term: #e88162 !important;
}

body {
    background-color: #F9F5F0 !important; /* Beige claro */
    color: #0B0B0B !important; /* Negro oscuro */
}

h1 {
    color: #0B0B0B !important; /* Negro oscuro */
}

.card-body {
    background-color: #FFFFFF !important; /* Blanco */
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1) !important;
}

.form-control {
    border: 1px solid #DDDDDD !important; /* Gris claro */
}

input.btn.btn-primary, a.btn.btn-primary {
    background-color: var(--button-login-color) !important;
    color: #FFFFFF !important; /* Blanco */
    border: none !important; /* Quitar borde para consistencia */
}

input.btn.btn-primary:hover {
    background-color: var(--button-login-hover) !important; /* Un tono más oscuro para el hover */
    color: #FFFFFF !important;
}
a.btn.btn-primary:hover {
    background-color: var(--button-login-hover) !important; /* Un tono más oscuro para el hover */
    color: #FFFFFF !important;  
}
a.btn.btn-warning {
    background-color: var(--button-decline-term) !important; /* Rojo */
    color: #FFFFFF !important; /* Blanco */
    --bs-btn-border-color: none !important;

}

.table-dark {
    background-color: #2E2E2E !important; /* Gris oscuro */
    color: #FFFFFF !important; /* Blanco */
}

.table-light {
    background-color: #FFFFFF !important; /* Blanco */
    color: #0B0B0B !important; /* Negro oscuro */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1) !important;

}

.thead-dark {
    background-color: var(--hover-button-color) !important; /* Negro más claro */
    color: #FFFFFF !important; /* Blanco */
}
a.btn.btn-success {
    background-color: var(--primary-color) !important;
    --bs-btn-border-color: none !important;
    border: none;
}
a.btn.btn-success:hover {
    background-color: var(--hover-button-color) !important;
    --bs-btn-border-color: none !important;
}


.table-responsive {
    max-width: 600px !important; /* Establece el ancho máximo deseado */
    margin: auto !important; /* Centrar el div */
}

    </style>
</head>
<body style="background-color: #f9f5f0;">
    <a class="btn btn-success" href="../index.php">INICIO</a>
    <div class="content">
        <div class="container">
            <center>
                <h2 class="mb-5">TABLA VALOR HORAS EXTRA</h2>
            </center>
            <div class="table-responsive">
                <a href="../crear_php/vhe.php" class="btn btn-success">Cargar valor</a>
                <table class="table table-striped custom-table">
                    <thead>
                        <tr>
                           
                            <th scope="col">VALOR</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        <?php
                        // Obtener id_empresa del usuario de sesión
                        if (!isset($_SESSION['id_us'])) {
                            echo '<tr><td colspan="3">Por favor inicie sesión.</td></tr>';
                        } else {
                            $id_us = $_SESSION['id_us'];
                            $stmt = $conexion->prepare("SELECT id_empresa FROM usuarios WHERE id_us = :id_us");
                            $stmt->bindParam(':id_us', $id_us, PDO::PARAM_INT);
                            $stmt->execute();
                            $id_empresa = $stmt->fetchColumn();

                            // Realizar la conexión a la base de datos utilizando mysqli
                            $conn = new mysqli($servername, $username, $password, $dbname);

                            // Verificar si hay errores en la conexión
                            if ($conn->connect_error) {
                                die("Error de conexión a la base de datos: " . $conn->connect_error);
                            }

                            // Consulta SQL para obtener los valores de horas extra del id_empresa del usuario
                            $sql = "SELECT * FROM v_h_extra WHERE id_empresa = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $id_empresa);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            // Verificar si hay valores de horas extra registrados
                            if ($result->num_rows > 0) {
                                // Iterar sobre los resultados y generar las filas de la tabla
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                  
                                    echo "<td>" . $row['V_H_extra'] . "</td>";
                                    echo "<td><a class='btn btn-primary' href='../editar_php/editar_valor_extra.php?id=" . $row['ID'] . "'>Editar</a> | <a class='btn btn-warning' href='../eliminar_php/eliminar_v_hora_extra.php?id=" . $row['ID'] . "'>Eliminar</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                // Mostrar mensaje si no hay valores de horas extra registrados para ese id_empresa
                                echo "<tr><td colspan='3'>No hay valores de hora extra registrados para su empresa.</td></tr>";
                            }

                            // Cerrar la conexión a la base de datos
                            $conn->close();
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>
