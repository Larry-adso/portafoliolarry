<?php
include '../../../conexion/db.php';
include '../../../conexion/validar_sesion.php';

// Obtener el id_us del usuario a editar desde la URL
$id_us_editar = isset($_GET['id_us']) ? $_GET['id_us'] : null;

// Obtener la información del usuario activo
$id_us = $_SESSION['id_us'];
$query_usuario = $conexion->prepare("SELECT id_empresa, id_rol FROM usuarios WHERE id_us = :id_us");
$query_usuario->bindParam(':id_us', $id_us);
$query_usuario->execute();
$usuario_activo = $query_usuario->fetch(PDO::FETCH_ASSOC);

$id_empresa = $usuario_activo['id_empresa'];
$rol_usuario_activo = $usuario_activo['id_rol'];

// Obtener datos del usuario a editar
$query_usuario_editar = $conexion->prepare("SELECT * FROM usuarios WHERE id_us = :id_us_editar");
$query_usuario_editar->bindParam(':id_us_editar', $id_us_editar);
$query_usuario_editar->execute();
$usuario_editar = $query_usuario_editar->fetch(PDO::FETCH_ASSOC);

// Obtener puestos de la empresa del usuario activo
$query_puestos = $conexion->prepare("SELECT ID, cargo FROM puestos WHERE id_empresa = :id_empresa");
$query_puestos->bindParam(':id_empresa', $id_empresa);
$query_puestos->execute();
$puestos = $query_puestos->fetchAll(PDO::FETCH_ASSOC);

// Obtener roles, excluyendo el rol del usuario activo
$query_roles = $conexion->prepare("SELECT ID, Tp_user FROM roles WHERE ID != :rol_usuario_activo");
$query_roles->bindParam(':rol_usuario_activo', $rol_usuario_activo);
$query_roles->execute();
$roles = $query_roles->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
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
<a class="btn btn-success" href="../index.php" style="border:none;" >INICIO</a>
    <div class="container">
        <h2>Editar Usuario</h2>
        <form id="editarUsuarioForm" action="actualizar_usuario.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="id">N° De Identificación</label>
                <input type="text" class="form-control" id="id" name="id" value="<?= $usuario_editar['id_us'] ?>" required readonly>
            </div>
            <div class="form-group">
                <label for="nombre_us">Nombre</label>
                <input type="text" class="form-control" id="nombre_us" name="nombre_us" value="<?= $usuario_editar['nombre_us'] ?>" required>
                <small id="nombre_us_error" class="text-danger"></small> <!-- Mensaje de error -->
            </div>
            <div class="form-group">
                <label for="apellido_us">Apellido</label>
                <input type="text" class="form-control" id="apellido_us" name="apellido_us" value="<?= $usuario_editar['apellido_us'] ?>" required>
                <small id="apellido_us_error" class="text-danger"></small> <!-- Mensaje de error -->
            </div>
            <div class="form-group">
                <label for="correo_us">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo_us" name="correo_us" value="<?= $usuario_editar['correo_us'] ?>" required>
                <small id="correo_us_error" class="text-danger"></small> <!-- Mensaje de error -->
            </div>
            <div class="form-group">
                <label for="tel_us">Teléfono</label>
                <input type="text" class="form-control" id="telefono_us" name="tel_us" value="<?= $usuario_editar['tel_us'] ?>" required>
                <div id="telefono_us_error" class="text-danger"></div>
            </div>
            <div class="form-group">
                <label for="pass">Contraseña</label>
                <input type="password" class="form-control" id="pass" name="pass">
                <small class="form-text text-muted">Dejar en blanco si no desea cambiar la contraseña</small>
            </div>
            <div class="form-group">
                <label for="foto">Foto</label>
                <input type="file" class="form-control-file" id="foto" name="foto">
                <small class="form-text text-muted">Dejar en blanco si no desea cambiar la foto</small>
            </div>
            <div class="form-group">
                <label for="id_puesto">Puesto</label>
                <select class="form-control" id="id_puesto" name="id_puesto">
                    <option value="">Seleccione un puesto</option>
                    <?php foreach ($puestos as $puesto) : ?>
                        <option value="<?= $puesto['ID'] ?>" <?= $puesto['ID'] == $usuario_editar['id_puesto'] ? 'selected' : '' ?>><?= $puesto['cargo'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_rol">Rol</label>
                <select class="form-control" id="id_rol" name="id_rol" required>
                    <option value="">Seleccione un rol</option>
                    <?php foreach ($roles as $rol) : ?>
                        <option value="<?= $rol['ID'] ?>" <?= $rol['ID'] == $usuario_editar['id_rol'] ? 'selected' : '' ?>><?= $rol['Tp_user'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="Codigo">Código</label>
                <input type="number" class="form-control" id="Codigo" name="Codigo" value="<?= $usuario_editar['Codigo'] ?>" required>
                <small id="Codigo_error" class="text-danger"></small> <!-- Mensaje de error -->
            </div>
            <input type="hidden" id="id_empresa" name="id_empresa" value="<?= $id_empresa ?>">
            <div class="form-group">
                <label for="token"></label>
                <input type="hidden" class="form-control" id="token" name="token">
                <small id="token_error" class="text-danger"></small>
            </div>
            <input type="hidden" name="id_us" value="<?= $id_us_editar ?>">
            <button type="submit" class="btn btn-primary" id="guardarCambiosBtn" disabled style="background-color:#c7a17a; border:none;">Guardar Cambios</button> <!-- Botón deshabilitado por defecto -->
        </form>
    </div>

    <script>
    const nombreInput = document.getElementById('nombre_us');
    const apellidoInput = document.getElementById('apellido_us');
    const correoInput = document.getElementById('correo_us');
    const codigoInput = document.getElementById('Codigo');
    const telefonoInput = document.getElementById('telefono_us'); // Nuevo input para teléfono
    const guardarCambiosBtn = document.getElementById('guardarCambiosBtn');

    // Event listeners para validar en tiempo real
    nombreInput.addEventListener('input', validarNombre);
    apellidoInput.addEventListener('input', validarApellido);
    correoInput.addEventListener('input', validarCorreo);
    codigoInput.addEventListener('input', validarCodigo);
    telefonoInput.addEventListener('input', validarTelefono); // Nuevo event listener para teléfono

    function validarNombre() {
        const nombre = nombreInput.value.trim();
        if (/\d/.test(nombre)) {
            nombreInput.classList.add('border', 'border-danger');
            document.getElementById('nombre_us_error').textContent = 'Ingrese un nombre válido sin números.';
            guardarCambiosBtn.disabled = true;
        } else {
            nombreInput.classList.remove('border', 'border-danger');
            document.getElementById('nombre_us_error').textContent = '';
            habilitarBoton();
        }
    }

    function validarApellido() {
        const apellido = apellidoInput.value.trim();
        if (/\d/.test(apellido)) {
            apellidoInput.classList.add('border', 'border-danger');
            document.getElementById('apellido_us_error').textContent = 'Ingrese un apellido válido sin números.';
            guardarCambiosBtn.disabled = true;
        } else {
            apellidoInput.classList.remove('border', 'border-danger');
            document.getElementById('apellido_us_error').textContent = '';
            habilitarBoton();
        }
    }

    function validarCorreo() {
        const correo = correoInput.value.trim();
        if (!isValidEmail(correo)) {
            correoInput.classList.add('border', 'border-danger');
            document.getElementById('correo_us_error').textContent = 'Ingrese un correo electrónico válido.';
            guardarCambiosBtn.disabled = true;
        } else {
            correoInput.classList.remove('border', 'border-danger');
            document.getElementById('correo_us_error').textContent = '';
            habilitarBoton();
        }
    }

    function validarCodigo() {
        const codigo = codigoInput.value.trim();
        if (isNaN(codigo)) {
            codigoInput.classList.add('border', 'border-danger');
            document.getElementById('Codigo_error').textContent = 'Ingrese un código válido (solo números).';
            guardarCambiosBtn.disabled = true;
        } else {
            codigoInput.classList.remove('border', 'border-danger');
            document.getElementById('Codigo_error').textContent = '';
            habilitarBoton();
        }
    }

    // Nueva función para validar el teléfono
    function validarTelefono() {
        const telefono = telefonoInput.value.trim();
        const soloNumeros = /^[0-9]*$/;
        if (!soloNumeros.test(telefono) || telefono.length !== 10) {
            telefonoInput.classList.add('border', 'border-danger');
            document.getElementById('telefono_us_error').textContent = 'Ingrese un teléfono válido de 10 dígitos.';
            guardarCambiosBtn.disabled = true;
        } else {
            telefonoInput.classList.remove('border', 'border-danger');
            document.getElementById('telefono_us_error').textContent = '';
            habilitarBoton();
        }
    }

    // Función para habilitar el botón de guardar cambios si no hay errores
    function habilitarBoton() {
        if (
            !nombreInput.classList.contains('border-danger') &&
            !apellidoInput.classList.contains('border-danger') &&
            !correoInput.classList.contains('border-danger') &&
            !codigoInput.classList.contains('border-danger') &&
            !telefonoInput.classList.contains('border-danger') // Verificar también el campo de teléfono
        ) {
            guardarCambiosBtn.disabled = false;
        }
    }

    // Función para validar correo electrónico
    function isValidEmail(email) {
        const re = /\S+@\S+\.\S+/;
        return re.test(email);
    }
</script>
</body>


</html>