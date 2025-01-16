<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Valor De Hora Extra</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin.css">
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

<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Cargar Valor De Hora Extra</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        session_start();
                        require_once '../../../conexion/db.php';

                        if (!isset($_SESSION['id_us'])) {
                            echo '
                            <script>
                                alert("Por favor inicie sesión e intente nuevamente");
                                window.location = "../../dev/PHP/login.php";
                            </script>
                            ';
                            session_destroy();
                            die();
                        }

                        $id_us = $_SESSION['id_us'];
                        $stmt = $conexion->prepare("SELECT id_empresa FROM usuarios WHERE id_us = :id_us");
                        $stmt->bindParam(':id_us', $id_us, PDO::PARAM_INT);
                        $stmt->execute();
                        $id_empresa = $stmt->fetchColumn();

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $v_h_extra = $_POST['v_h_extra'];

                            if ($conexion) {
                                // Modificar la consulta para insertar el id_empresa
                                $sql = "INSERT INTO v_h_extra (id_empresa, V_H_extra) VALUES (:id_empresa, :v_h_extra)";
                                $stmt = $conexion->prepare($sql);
                                $stmt->bindParam(':id_empresa', $id_empresa);
                                $stmt->bindParam(':v_h_extra', $v_h_extra);

                                if ($stmt->execute()) {
                                    echo '<script>alert("El valor de Hora Extra \'' . $v_h_extra . '\' ha sido insertado correctamente."); window.location.href = "../tablas/vhe.php";</script>';
                                } else {
                                    echo '<div class="alert alert-danger" role="alert">Error al insertar el valor: ' . $stmt->errorInfo()[2] . '</div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger" role="alert">Error al establecer la conexión a la base de datos.</div>';
                            }
                        }
                        ?>
                        <form id="cargarValorHoraExtraForm" action="vhe.php" method="post">
                            <div class="form-group">
                                <label for="v_h_extra">Valor de Hora Extra</label>
                                <input type="number" class="form-control" id="v_h_extra" name="v_h_extra" placeholder="Ingrese el valor de Hora Extra" required  pattern="[0-9]{1,10}>
                                <small id="v_h_extra_error" class="text-danger"></small>
                            </div>
                            <button type="submit" class="btn btn-primary" id="registrarValorBtn">Registrar Valor</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Validación de formulario (si es necesario)
    const cargarValorHoraExtraForm = document.getElementById('cargarValorHoraExtraForm');
    const v_h_extraInput = document.getElementById('v_h_extra');
    const registrarValorBtn = document.getElementById('registrarValorBtn');

    v_h_extraInput.addEventListener('input', validarValorHoraExtra);

    function validarValorHoraExtra() {
        const valor = v_h_extraInput.value.trim();
        if (valor === '') {
            v_h_extraInput.classList.add('is-invalid');
            document.getElementById('v_h_extra_error').textContent = 'Complete el campo, no puede estar vacío.';
            registrarValorBtn.disabled = true;
        } else if (!/^\d+(\.\d{1,2})?$/.test(valor)) {
            v_h_extraInput.classList.add('is-invalid');
            document.getElementById('v_h_extra_error').textContent = 'Ingrese un valor válido para la Hora Extra.';
            registrarValorBtn.disabled = true;
        } else {
            v_h_extraInput.classList.remove('is-invalid');
            document.getElementById('v_h_extra_error').textContent = '';
            registrarValorBtn.disabled = false;
        }
    }
</script>

</body>

</html>