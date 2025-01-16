<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Salud</title>
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
                        <h3 class="text-center">Cargar Porcentaje de Salud (Solo numeros enteros)</h3>
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
                            $valor = $_POST['valor'];

                            if ($conexion) {
                                // Modificar la consulta para insertar el id_empresa
                                $sql = "INSERT INTO salud (id_empresa, valor) VALUES (:id_empresa, :valor)";
                                $stmt = $conexion->prepare($sql);
                                $stmt->bindParam(':id_empresa', $id_empresa);
                                $stmt->bindParam(':valor', $valor);
                                
                                if ($stmt->execute()) {
                                    echo '<script>alert("El valor de Salud \'' . $valor . '%\' ha sido insertado correctamente."); window.location.href = "../index.php";</script>';
                                } else {
                                    echo '<script>alert("Error al insertar el valor: ' . $stmt->errorInfo()[2] . '"); window.location.href = "../index.php";</script>';
                                }
                            } else {
                                echo '<script>alert("Error al establecer la conexión a la base de datos."); window.location.href = "salud.php";</script>';
                            }
                        }
                        ?>
                        <form id="cargarSaludForm" action="salud.php" method="post">
                            <div class="form-group">
                                <label for="valor">Valor de Salud</label>
                                <input type="number" class="form-control" id="valor" name="valor" placeholder="Ingrese el valor de Salud" required >
                                <small id="valor_error" class="text-danger"></small>
                            </div>
                            <button type="submit" class="btn btn-primary" id="registrarValorBtn">Registrar Valor</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const valorInput = document.getElementById('valor');
    const registrarValorBtn = document.getElementById('registrarValorBtn');

    valorInput.addEventListener('input', validarValorSalud);

    function validarValorSalud() {
        let valor = parseFloat(valorInput.value.trim());  // Convertimos el valor a punto flotante

        // Verificamos si el valor es un número y está dentro del rango 1 a 10
        if (!isNaN(valor) && valor >= 0 && valor <= 10) {
            // Si es válido, quitamos el borde rojo y el mensaje de error
            valorInput.classList.remove('border', 'border-danger');
            document.getElementById('valor_error').textContent = '';
            registrarValorBtn.disabled = false;
        } else {
            // Si no es válido, agregamos el borde rojo, mostramos el mensaje de error y deshabilitamos el botón
            valorInput.classList.add('border', 'border-danger');
            document.getElementById('valor_error').textContent = 'Ingrese un valor de salud válido (entre 1 y 10).';
            registrarValorBtn.disabled = true;

            // Ajustamos el valor automáticamente si es menor que 1 o mayor que 10
            if (isNaN(valor) || valor < 1) {
                valor = 0;
            } else if (valor > 10) {
                valor = 10;
            }
        }

        // Actualizamos el input con el valor ajustado
        valorInput.value = Math.floor(valor); // Utilizamos Math.floor() para redondear hacia abajo y quitar los decimales
    }
</script>



</body>

</html>
