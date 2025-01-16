<?php
include '../../../conexion/validar_sesion.php';
include '../../../conexion/db.php'; // Incluir el archivo de conexión

try {
    // Obtener el id_empresa del usuario con sesión activa
    $id_us = isset($_POST['id_us']) ? $_POST['id_us'] : null;
    // Consulta SQL para obtener el valor de la hora extra desde la tabla v_h_extra para la empresa del usuario
    $sql_h_extra = "SELECT * FROM v_h_extra WHERE id_empresa = (SELECT id_empresa FROM usuarios WHERE id_us = :id_us)";
    $stmt_h_extra = $conexion->prepare($sql_h_extra);
    $stmt_h_extra->bindParam(':id_us', $id_us, PDO::PARAM_INT);
    $stmt_h_extra->execute();
    $valor_hora_extra = $stmt_h_extra->fetch(PDO::FETCH_ASSOC);

    if (!$valor_hora_extra) {
        echo "<script>alert('No se pudo obtener el valor de hora extra, registre uno he intentelo nuevamente'); window.location.href='../index.php';</script>";
        exit();
    }
} catch (PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
    exit();
}

try {
    // Consulta SQL para obtener el valor de salud para la empresa del usuario
    $sql_salud = "SELECT * FROM salud WHERE id_empresa = (SELECT id_empresa FROM usuarios WHERE id_us = :id_us)";
    $stmt_salud = $conexion->prepare($sql_salud);
    $stmt_salud->bindParam(':id_us', $id_us, PDO::PARAM_INT);
    $stmt_salud->execute();
    $salud = $stmt_salud->fetch(PDO::FETCH_ASSOC);

    if (!$salud) {
        echo "<script>alert('No se pudo obtener el valor de salud, registre uno he intentelo nuevamente'); window.location.href='../index.php';</script>";
        exit();
    }

    // Consulta SQL para obtener el valor de pensión para la empresa del usuario
    $sql_pension = "SELECT * FROM pension WHERE id_empresa = (SELECT id_empresa FROM usuarios WHERE id_us = :id_us)";
    $stmt_pension = $conexion->prepare($sql_pension);
    $stmt_pension->bindParam(':id_us', $id_us, PDO::PARAM_INT);
    $stmt_pension->execute();
    $pension = $stmt_pension->fetch(PDO::FETCH_ASSOC);

    if (!$pension) {
        echo "<script>alert('No se pudo obtener el valor de pension, registre uno he intentelo nuevamente'); window.location.href='../index.php';</script>";
        exit();
    }

    // Resto del código para calcular el salario y las deducciones ...

    // Consultar la información del usuario y otros datos necesarios
    $sql_usuario = "SELECT usuarios.id_us, usuarios.nombre_us, usuarios.apellido_us, usuarios.correo_us, usuarios.tel_us, usuarios.ruta_foto, roles.tp_user, puestos.cargo, puestos.salario, puestos.id_arl
            FROM usuarios
            LEFT JOIN roles ON usuarios.id_rol = roles.id
            LEFT JOIN puestos ON usuarios.id_puesto = puestos.id
            WHERE usuarios.id_us = :id_us";
    $stmt_usuario = $conexion->prepare($sql_usuario);
    $stmt_usuario->bindParam(':id_us', $id_us, PDO::PARAM_INT);
    $stmt_usuario->execute();
    $usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "<script>alert('Usuario no encontrado registre uno he intentelo nuevamente'); window.location.href='../index.php';</script>";
        exit();
    }

    // Obtener las horas extras trabajadas y los días trabajados desde el método POST
    $horas_trabajadas = isset($_POST['horas_trabajadas']) ? (int)$_POST['horas_trabajadas'] : 0;
    $dias_trabajados = isset($_POST['dias_trabajados']) ? (int)$_POST['dias_trabajados'] : 0;

    // Calcular el salario total
    $salario_base_por_dia = $usuario['salario'] / 31;
    $dias_trabajados = min($dias_trabajados, 31); // Limita los días trabajados a 31
    $salario_dias_trabajados = $salario_base_por_dia * $dias_trabajados;
    $salario_total_horas_extras = $horas_trabajadas * $valor_hora_extra['V_H_extra'];
    $salario_total_a_pagar = $salario_dias_trabajados + $salario_total_horas_extras;

    // Consultas para obtener los valores de las deducciones de salud y pensión
    $sql_salud = "SELECT * FROM salud WHERE id_empresa = (SELECT id_empresa FROM usuarios WHERE id_us = :id_us)";
    $stmt_salud = $conexion->prepare($sql_salud);
    $stmt_salud->bindParam(':id_us', $id_us, PDO::PARAM_INT);
    $stmt_salud->execute();
    $salud = $stmt_salud->fetch(PDO::FETCH_ASSOC);

    $sql_pension = "SELECT * FROM pension WHERE id_empresa = (SELECT id_empresa FROM usuarios WHERE id_us = :id_us)";
    $stmt_pension = $conexion->prepare($sql_pension);
    $stmt_pension->bindParam(':id_us', $id_us, PDO::PARAM_INT);
    $stmt_pension->execute();
    $pension = $stmt_pension->fetch(PDO::FETCH_ASSOC);

    if (!$salud || !$pension) {
        echo "<script>alert('No se pudo obtener el valor de la pension, inserte uno he intentelo nuevamente'); window.location.href='../index.php';</script>";
        exit();
    }

    // Consultar el valor de la ARL
    $id_arl = $usuario['id_arl'];
    $sql_arl = "SELECT * FROM arl WHERE id_arl = :id_arl";
    $stmt_arl = $conexion->prepare($sql_arl);
    $stmt_arl->bindParam(':id_arl', $id_arl, PDO::PARAM_INT);
    $stmt_arl->execute();
    $arl = $stmt_arl->fetch(PDO::FETCH_ASSOC);

    if (!$arl) {
        echo "<script>alert('No se pudo obtener el valor de la ARL verifique su registro de el puesto e intentelo nuevamente'); window.location.href='../index.php';</script>";
        exit();
    }

    // Consultar el valor de la cuota del préstamo solo si el estado es 6
    $sql_prestamo = "SELECT * FROM prestamo WHERE ID_Empleado = :id_us AND estado = 6";
    $stmt_prestamo = $conexion->prepare($sql_prestamo);
    $stmt_prestamo->bindParam(':id_us', $id_us, PDO::PARAM_INT);
    $stmt_prestamo->execute();
    $prestamo = $stmt_prestamo->fetch(PDO::FETCH_ASSOC);

    if (!$prestamo) {
        $id_prestamo = 0; // Si no hay préstamo, el ID es 0
        $cuota_prestamo = 0; // Si no hay préstamo, la cuota es 0
    } else {
        $id_prestamo = $prestamo['ID_prest'];
        $cuota_prestamo = $prestamo['Valor_Cuotas'];
    }

    // Calcular las deducciones
    $deduccion_salud = $salario_total_a_pagar * ($salud['Valor'] / 100);
    $deduccion_pension = $salario_total_a_pagar * ($pension['Valor'] / 100);
    $deduccion_arl = $salario_total_a_pagar * ($arl['valor'] / 100);
    $salario_total_deducciones = $salario_total_a_pagar - ($deduccion_salud + $deduccion_pension + $deduccion_arl + $cuota_prestamo);
} catch (PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
    exit();
}
?>





<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liquidar Salario</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/png" href="../../../img/logo_algj.png">

</head>

<body>
    <div class="container">
        <div class="container mt-5">
            <h2 class="mb-4">Liquidar Salario</h2>
            <div class="flex-container">
                <div class="flex-item">
                    <div class="card">
                        <?php if (!empty($usuario['ruta_foto'])) : ?>
                            <?php
                            $ruta_foto = $usuario['ruta_foto'];
                            $ruta_foto_ajustada = implode("/", array_slice(explode("/", $ruta_foto), 2));
                            ?>
                            <img class="card-img-top" src="../../<?php echo $ruta_foto_ajustada; ?>" alt="Foto">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $usuario['nombre_us'] . ' ' . $usuario['apellido_us']; ?></h5>
                            <p class="card-text"><strong>Cédula:</strong> <?php echo $usuario['id_us']; ?></p>
                            <p class="card-text"><strong>Rol:</strong> <?php echo $usuario['tp_user']; ?></p>
                            <p class="card-text"><strong>Cargo:</strong> <?php echo $usuario['cargo']; ?></p>
                            <p class="card-text"><strong>Salario:</strong> <?php echo '$ COP ' . number_format($usuario['salario'], 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="flex-item">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Formulario de Liquidación</h5>
                            <form id="liquidacionForm" method="POST" action="procesar_liquidar.php" onsubmit="return validateForm()">
                                <input type="hidden" name="id_us" value="<?php echo $usuario['id_us']; ?>">
                                <div class="form-group">
                                    <label for="dias_trabajados">Días trabajados:</label>
                                    <input type="number" class="form-control" id="dias_trabajados" name="dias_trabajados" min="1" max="31" value="0" required oninput="calcularLiquidacion()" required oninput="noNegativeInput()">
                                </div>
                                <div class="form-group">
                                    <label for="horas_trabajadas">Horas extras trabajadas:</label>
                                    <input type="number" class="form-control" id="horas_trabajadas" name="horas_trabajadas" min="0" max="30" value="0" required oninput="calcularLiquidacion()" required oninput="noNegativeInput()">
                                </div>
                                <button type="submit" class="btn btn-primary">Liquidar</button>

                                <div id="resultado">
                                    <h5>Resultado de la Liquidación:</h5>
                                    <div class="form-group">
                                        <label for="dias_trabajados_resultado">Días trabajados:</label>
                                        <input type="text" class="form-control" id="dias_trabajados_resultado" name="dias_trabajados_resultado" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="salario_dias_trabajados">Salario base por días trabajados:</label>
                                        <input type="text" class="form-control" id="salario_dias_trabajados" name="salario_dias_trabajados" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="horas_extras_trabajadas_resultado">Horas extras trabajadas:</label>
                                        <input type="text" class="form-control" id="horas_extras_trabajadas_resultado" name="horas_extras_trabajadas_resultado" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="salario_horas_extras">Valor de  horas extras:</label>
                                        <input type="text" class="form-control" id="salario_horas_extras" name="salario_horas_extras" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="auxilio_transporte_aplica">¿Aplica para Auxilio de Transporte?</label>
                                        <input type="text" class="form-control" id="auxilio_transporte_aplica" name="auxilio_transporte_aplica" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="valor_auxilio_transporte">Valor Auxilio de Transporte:</label>
                                        <input type="text" class="form-control" id="valor_auxilio_transporte" name="valor_auxilio_transporte" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="salario_total_a_pagar">Salario total a pagar:</label>
                                        <input type="text" class="form-control" id="salario_total_a_pagar" name="salario_total_a_pagar" readonly>
                                    </div>
                                    <h5>Deducciones:</h5>
                                    <div class="form-group">
                                        <label for="deduccion_salud">Salud (<?php echo $salud['Valor']; ?>%):</label>
                                        <input type="text" class="form-control" id="deduccion_salud" name="deduccion_salud" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="deduccion_pension">Pensión (<?php echo $pension['Valor']; ?>%):</label>
                                        <input type="text" class="form-control" id="deduccion_pension" name="deduccion_pension" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="deduccion_arl">ARL (<?php echo $arl['valor']; ?>%):</label>
                                        <input type="text" class="form-control" id="deduccion_arl" name="deduccion_arl" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="cuota_prestamo">Cuota de préstamo:</label>
                                        <input type="text" class="form-control" id="cuota_prestamo" name="cuota_prestamo" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="total_deducciones">Total deducciones:</label>
                                        <input type="text" class="form-control" id="total_deducciones" name="total_deducciones" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="salario_total_deducciones">Salario total después de deducciones:</label>
                                        <input type="text" class="form-control" id="salario_total_deducciones" name="salario_total_deducciones" readonly>
                                    </div>
                                </div>
                                <input type="hidden" name="id_prestamo" value="<?php echo $id_prestamo; ?>">
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <script>
            function noNegativeInput(event) {
                const key = event.key;
                if (key === '-' || key === '+' || key.toLowerCase() === 'e') {
                    event.preventDefault();
                    return false;
                }
                return true;
            }

            const salarioBaseInicial = <?php echo $usuario['salario']; ?>;
            const valorHoraExtra = '<?php echo number_format($valor_hora_extra['V_H_extra'], 0, ',', '.'); ?>';
            const saludValor = 'COP <?php echo number_format($salud['Valor'], 0, ',', '.'); ?>';
            const pensionValor = 'COP <?php echo number_format($pension['Valor'], 0, ',', '.'); ?>';
            const arlValor = 'COP <?php echo number_format($arl['valor'], 0, ',', '.'); ?>';
            const cuotaPrestamo = 'COP <?php echo number_format($cuota_prestamo, 0, ',', '.'); ?>';

            function formatNumber(number) {
                return new Intl.NumberFormat('es-CO').format(number);
            }

            function cleanNumberFormat(input) {
                return input.replace(/COP|\./g, '').trim();
            }

            function calcularLiquidacion() {
                const diasTrabajados = parseInt(cleanNumberFormat(document.getElementById('dias_trabajados').value)) || 0;
                const horasTrabajadas = parseInt(cleanNumberFormat(document.getElementById('horas_trabajadas').value)) || 0;
                const liquidarButton = document.querySelector('.btn.btn-primary');

                if (diasTrabajados < 1 || diasTrabajados > 31) {
                    document.getElementById('salario_dias_trabajados').value = 'Por favor ingrese los valores permitidos entre 1 y 31 dias trabajados';
                    document.getElementById('salario_horas_extras').value = 'Por favor ingrese los valores permitidos entre 1 y 31 dias trabajados';
                    document.getElementById('salario_total_a_pagar').value = 'Por favor ingrese los valores permitidos entre 1 y 31 dias trabajados';
                    document.getElementById('deduccion_salud').value = 'Por favor ingrese los valores permitidos entre 1 y 31 dias trabajados';
                    document.getElementById('deduccion_pension').value = 'Por favor ingrese los valores permitidos entre 1 y 31 dias trabajados';
                    document.getElementById('deduccion_arl').value = 'Por favor ingrese los valores permitidos entre 1 y 31 dias trabajados';
                    document.getElementById('cuota_prestamo').value = 'Por favor ingrese los valores permitidos entre 1 y 31 dias trabajados';
                    document.getElementById('total_deducciones').value = 'Por favor ingrese los valores permitidos entre 1 y 31 dias trabajados';
                    document.getElementById('salario_total_deducciones').value = 'Por favor ingrese los valores permitidos entre 1 y 31 dias trabajados';
                    document.getElementById('auxilio_transporte_aplica').value = 'Por favor ingrese los valores permitidos entre 1 y 31 dias trabajados';
                    document.getElementById('valor_auxilio_transporte').value = 'Por favor ingrese los valores permitidos entre 1 y 31 dias trabajados';
                    liquidarButton.disabled = true;
                    return;
                } else {
                    liquidarButton.disabled = false;
                }

                const salarioBase = salarioBaseInicial / 31 * diasTrabajados;
                const salarioDiasTrabajados = Math.floor(salarioBase);
                const salarioTotalHorasExtras = Math.floor(horasTrabajadas * parseInt(valorHoraExtra.replace(/\D/g, '')));
                let salarioTotalAPagar = salarioDiasTrabajados + salarioTotalHorasExtras;

                // Auxilio de transporte
                let auxilioTransporteAplica = 'No';
                let valorAuxilioTransporte = 0;
                if (salarioBaseInicial <= 2500000) {
                    auxilioTransporteAplica = 'Sí';
                    valorAuxilioTransporte = 170000;
                    salarioTotalAPagar += valorAuxilioTransporte; // Agregar el auxilio al salario total a pagar
                }

                const deduccionSalud = Math.floor(salarioTotalAPagar * (parseInt(saludValor.replace(/\D/g, '')) / 100));
                const deduccionPension = Math.floor(salarioTotalAPagar * (parseInt(pensionValor.replace(/\D/g, '')) / 100));
                const deduccionArl = Math.floor(salarioTotalAPagar * (parseInt(arlValor.replace(/\D/g, '')) / 100));
                const totalDeducciones = deduccionSalud + deduccionPension + deduccionArl + parseInt(cuotaPrestamo.replace(/\D/g, ''));
                const salarioTotalDeducciones = salarioTotalAPagar - totalDeducciones;

                document.getElementById('dias_trabajados_resultado').value = formatNumber(diasTrabajados);
                document.getElementById('salario_dias_trabajados').value = 'COP ' + formatNumber(salarioDiasTrabajados);
                document.getElementById('horas_extras_trabajadas_resultado').value = formatNumber(horasTrabajadas);
                document.getElementById('salario_horas_extras').value = valorHoraExtra;
                document.getElementById('salario_total_a_pagar').value = 'COP ' + formatNumber(salarioTotalAPagar);
                document.getElementById('deduccion_salud').value = 'COP ' + formatNumber(deduccionSalud);
                document.getElementById('deduccion_pension').value = 'COP ' + formatNumber(deduccionPension);
                document.getElementById('deduccion_arl').value = 'COP ' + formatNumber(deduccionArl);
                document.getElementById('cuota_prestamo').value = cuotaPrestamo;
                document.getElementById('total_deducciones').value = 'COP ' + formatNumber(totalDeducciones);
                document.getElementById('salario_total_deducciones').value = 'COP ' + formatNumber(salarioTotalDeducciones);
                document.getElementById('auxilio_transporte_aplica').value = auxilioTransporteAplica;
                document.getElementById('valor_auxilio_transporte').value = 'COP ' + formatNumber(valorAuxilioTransporte);
            }

            function validateForm() {
                const diasTrabajados = document.getElementById('dias_trabajados').value;
                const horasTrabajadas = document.getElementById('horas_trabajadas').value;

                if (!diasTrabajados || diasTrabajados <= 0) {
                    alert('Por favor, ingrese un número válido de días trabajados.');
                    return false;
                }

                if (!horasTrabajadas || horasTrabajadas < 0) {
                    alert('Por favor, ingrese un número válido de horas extras trabajadas.');
                    return false;
                }

                return true;
            }

            document.getElementById('liquidacionForm').addEventListener('submit', function() {
                const formElements = this.elements;

                for (let i = 0; i < formElements.length; i++) {
                    const element = formElements[i];
                    if (element.tagName === 'INPUT' && element.type === 'text') {
                        element.value = cleanNumberFormat(element.value);
                    }
                }
            });

            window.onload = calcularLiquidacion;
        </script>






</body>


</html>