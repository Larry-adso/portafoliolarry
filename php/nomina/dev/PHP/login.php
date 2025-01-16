<!DOCTYPE html>
<html>

<head>
	<title>Inició De Sesión</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="../../img/logo_algj.png">

</head>

<body>
	<img class="wave" src="img/wave.png">
	<div class="container">
		<div class="img">
			<img src="img/bg.png">
		</div>
		<div class="login-content">
			<form action="login1.php" method="post" autocomplete="off">
				<img src="img/avatar.svg">
				<h2 class="title">Bienvenido</h2>
				<div class="input-div one">
					<div class="i">
						<i class="fas fa-user"></i>
					</div>
					<div class="div">
						<h5>N° De Identidad</h5>
						<input type="text" class="input" id="username" name="id_us" maxlength="10">
						<span class="error" id="usernameError" style="font-size: 15px; text-align: right;">Ingrese solo números</span>
						<span class="error" id="usernameEmptyError" style="font-size: 15px; text-align: right;">Complete el campo</span>
					</div>

					<script>
						document.getElementById('username').addEventListener('input', function(e) {
							const username = e.target.value;
							const usernameError = document.getElementById('usernameError');
							const usernameEmptyError = document.getElementById('usernameEmptyError');

							// Remove non-numeric characters
							e.target.value = username.replace(/[^0-9]/g, '');

							// Validate input
							if (username.length > 0 && username.length <= 10) {
								usernameError.style.display = 'none';
								usernameEmptyError.style.display = 'none';
							} else if (username.length === 0) {
								usernameEmptyError.style.display = 'block';
								usernameError.style.display = 'none';
							} else {
								usernameError.style.display = 'block';
								usernameEmptyError.style.display = 'none';
							}
						});

						document.getElementById('username').addEventListener('blur', function(e) {
							const usernameEmptyError = document.getElementById('usernameEmptyError');

							if (e.target.value.length === 0) {
								usernameEmptyError.style.display = 'block';
							} else {
								usernameEmptyError.style.display = 'none';
							}
						});
					</script>
				</div>
				<div class="input-div pass">
					<div class="i">
						<i class="fas fa-lock"></i>
					</div>
					<div class="div">
						<h5>Contraseña</h5>
						<input type="password" class="input" id="password" name="pass">
						<span id="togglePassword" class="toggle-password">
							<i class="fa fa-eye" id="toggleIcon"></i>
						</span>
						<span class="error" id="passwordEmptyError" style="font-size: 15px;  text-align: right;">Complete el campo</span>
					</div>
					<script>
						document.getElementById('togglePassword').addEventListener('click', function() {
							const passwordField = document.getElementById('password');
							const toggleIcon = document.getElementById('toggleIcon');

							if (passwordField.type === 'password') {
								passwordField.type = 'text';
								toggleIcon.classList.remove('fa-eye');
								toggleIcon.classList.add('fa-eye-slash');
							} else {
								passwordField.type = 'password';
								toggleIcon.classList.remove('fa-eye-slash');
								toggleIcon.classList.add('fa-eye');
							}
						});
					</script>


				</div>

				<input type="checkbox" id="termsCheckbox">
				<label for="termsCheckbox">Acepto los términos y condiciones</label>
				<span class="error" id="termsError" style="color: red;">Acepta los términos y condiciones</span>

				<a href="metodos.php">Olvido la contraseña?</a>
				<input type="submit" class="btn" value="Iniciar Sesión" id="loginBtn" disabled>

			</form>
			<div id="termsModal" class="modal">
				<div class="modal-content">
					<span class="close">&times;</span>
					<h2>Términos de Uso</h2>
					<p>Al utilizar este software, usted acepta estar sujeto a estos términos de uso. Si no está de
						acuerdo con alguno de estos términos, por favor, no utilice el software.</p>
					<h3>Aceptación de los Términos de Uso</h3>
					<p>Al utilizar este software, usted acepta estar sujeto a estos términos de uso. Si no está de
						acuerdo con alguno de estos términos, por favor, no utilice el software.</p>
					<h3>Licencia de Uso</h3>
					<p>El software está protegido por derechos de autor y se le otorga una licencia limitada, no
						exclusiva, intransferible y revocable para utilizar el software de acuerdo con estos
						términos.</p>
					<h3>Uso Aceptable</h3>
					<p>Usted acepta utilizar el software de manera legal y ética, y se compromete a no utilizarlo
						para actividades ilegales, fraudulentas o que infrinjan los derechos de terceros.</p>
					<h3>Propiedad Intelectual</h3>
					<p>Todos los derechos de propiedad intelectual sobre el software y su contenido pertenecen a
						ALGJ S.A.S. o a sus licenciantes. Usted acepta no copiar, modificar, distribuir, vender ni
						realizar ingeniería inversa sobre el software.</p>
					<h3>Actualizaciones y Mantenimiento</h3>
					<p>ALGJ S.A.S. puede proporcionar actualizaciones periódicas del software para mejorar su
						funcionamiento. Usted acepta recibir estas actualizaciones automáticamente.</p>
					<h3>Privacidad</h3>
					<p>Al utilizar el software, usted acepta nuestra Política de Privacidad, que describe cómo
						recopilamos, utilizamos y compartimos su información personal.</p>
					<h3>Limitación de Responsabilidad</h3>
					<p>El software se proporciona "tal cual" y ALGJ S.A.S. no ofrece garantías de ningún tipo sobre
						su funcionamiento. En ningún caso ALGJ S.A.S. será responsable por daños indirectos,
						incidentales o consecuentes derivados del uso del software.</p>
					<h3>Modificaciones de los Términos de Uso</h3>
					<p>ALGJ S.A.S. se reserva el derecho de modificar estos términos en cualquier momento. Las
						modificaciones entrarán en vigencia al ser publicadas en este documento.</p>
					<h3>Rescisión</h3>
					<p>ALGJ S.A.S. puede rescindir su licencia para utilizar el software si viola estos términos de
						uso. En caso de rescisión, usted deberá dejar de utilizar el software y destruir todas las
						copias que haya realizado.</p>
					<h3>Ley Aplicable</h3>
					<p>Estos términos de uso se regirán e interpretarán de acuerdo con las leyes de Colombia sin
						tener en cuenta sus conflictos de principios legales.</p>
					<br>
					<p>Al utilizar este software, usted reconoce haber leído, comprendido y aceptado estos términos
						de uso. Si tiene alguna pregunta o inquietud, por favor contáctenos a SyscPay@gmail.com.</p>
					<div class="modal-footer">
						<button id="acceptBtn" class="b_estilo">Aceptar</button>
						<button id="declineBtn" class="b_estilo">Declinar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="js/main.js"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const username = document.getElementById('username');
			const password = document.getElementById('password');
			const termsCheckbox = document.getElementById('termsCheckbox');
			const usernameError = document.getElementById('usernameError');
			const usernameEmptyError = document.getElementById('usernameEmptyError');
			const passwordEmptyError = document.getElementById('passwordEmptyError');
			const termsError = document.getElementById('termsError');
			const loginBtn = document.getElementById('loginBtn');
			const termsModal = document.getElementById('termsModal');
			const acceptBtn = document.getElementById('acceptBtn');
			const declineBtn = document.getElementById('declineBtn');
			const closeBtn = document.querySelector('.close');

			let usernameValid = false;
			let passwordValid = false;
			let termsAccepted = false;

			// Validación del nombre de usuario
			username.addEventListener('input', function() {
				const usernameValue = username.value.trim();
				if (usernameValue === '') {
					usernameError.style.display = 'none';
					usernameEmptyError.style.display = 'block';
					usernameValid = false;
				} else if (!/^\d+$/.test(usernameValue)) {
					usernameEmptyError.style.display = 'none';
					usernameError.style.display = 'block';
					usernameValid = false;
				} else {
					usernameEmptyError.style.display = 'none';
					usernameError.style.display = 'none';
					usernameValid = true;
				}
				toggleButtonState();
			});

			// Validación de la contraseña
			password.addEventListener('input', function() {
				const passwordValue = password.value.trim();
				if (passwordValue === '') {
					passwordEmptyError.style.display = 'block';
					passwordValid = false;
				} else {
					passwordEmptyError.style.display = 'none';
					passwordValid = true;
				}
				toggleButtonState();
			});

			// Validación de aceptación de términos
			termsCheckbox.addEventListener('change', function() {
				termsError.style.display = termsCheckbox.checked ? 'none' : 'block';
				termsAccepted = termsCheckbox.checked;
				toggleButtonState();
			});

			// Función para habilitar/deshabilitar el botón de login
			function toggleButtonState() {
				if (usernameValid && passwordValid && termsAccepted) {
					loginBtn.disabled = false;
					loginBtn.classList.remove('disabled');
				} else {
					loginBtn.disabled = true;
					loginBtn.classList.add('disabled');
				}
			}

			// Ventana emergente de términos y condiciones
			termsModal.style.display = 'none'; // Ocultar inicialmente

			// Mostrar la ventana modal al hacer clic en el enlace de términos y condiciones
			termsCheckbox.addEventListener('click', function() {
				termsModal.style.display = 'block';
			});

			// Botón de aceptar términos
			acceptBtn.addEventListener('click', function() {
				termsModal.style.display = 'none';
				termsCheckbox.checked = true;
				termsError.style.display = 'none';
				termsAccepted = true;
				toggleButtonState();
			});

			// Botón de declinar términos
			declineBtn.addEventListener('click', function() {
				termsModal.style.display = 'none';
				termsCheckbox.checked = false;
				termsError.style.display = 'block';
				termsAccepted = false;
				toggleButtonState();
			});

			// Botón de cerrar modal
			closeBtn.addEventListener('click', function() {
				termsModal.style.display = 'none';
			});
		});
	</script>
</body>

</html>