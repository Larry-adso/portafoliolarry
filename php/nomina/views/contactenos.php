<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Contactenos</title>
	<link rel="stylesheet" href="../css/styles.css" />
	<link rel="icon" type="image/png" href="../img/logo_algj.png">

	<script src="script.js"></script>


</head>

<body>
	<header>
		<div class="container-hero">
			<div class="container hero">
				<div class="customer-support">
					<i class="fa-solid fa-headset"></i>
					<div class="content-customer-support">
						<span class="text">Soporte al cliente</span>
						<span class="number">+58 318 773 86 47</span>
					</div>
				</div>

				<div class="container-logo">
					<h1 class="logo"><a href="/">NOMINAS SYNCPAY</a></h1>
				</div>

				<div class="container-user">
					<a href="../dev/PHP/login.php"><i class="fa-solid fa-user"></i></a>

				</div>
			</div>
		</div>
		</div>

		<div class="container-navbar">
        <nav class="navbar container">
            <i class="fa-solid fa-bars" id="burger"></i>
            <ul class="menu" id="menu">
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="sobre_n.php">Sobre nosotros</a></li>
                <li><a href="contactenos.php">Contactenos</a></li>
            </ul>
            <ul class="menu">
                <li>
                    <a href="https://docs.google.com/document/d/1kdiEfqIHL1a879InM30_-BwDaQEdN3L8/edit?usp=sharing&ouid=100333253493459131869&rtpof=true&sd=true">Manual</a><i class="fa-solid fa-circle-question"></i>
                </li>
            </ul>
        </nav>
    </div>
	</header>

	<main class="main-content">
		<section class="container container-features">
			<div class="card-feature">
				<i class="fa-solid fa-phone"></i>
				<div class="feature-content">
					<span>+57 318 773 86 47</span>
					<p>Llama cuando quieras</p>
					<h1></h1>
				</div>
			</div>
			<div class="card-feature">
				<i class="fa-solid fa-map"></i>
				<div class="feature-content">
					<span> SENA </span>
					<p>centro de industria y construcción</p>
				</div>
			</div>
			<div class="card-feature">
				<i class="fa-solid fa-envelope"></i>
				<div class="feature-content">
					<span>SyscPay@gmail.com</span>
					<p>Atencion Personalizada</p>
				</div>
			</div>

		</section>

		<section class="container top-categories">
			<h1 class="heading-1">¡Ubicanos!</h1>
			<div class="container-map">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3978.048260414706!2d-75.15231862521023!3d4.402072895572057!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e38daac36ef33ef%3A0xc4167c4b60b14a15!2sSENA%20Centro%20de%20Industria%20y%20de%20la%20Construcci%C3%B3n!5e0!3m2!1ses!2sco!4v1715348500820!5m2!1ses!2sco" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
		</section>

		<section class="container top-categories">
			<h1 class="heading-1">Consulta con nosotros!</h1>
			<div class="container-com">
				<form action="contacto.php" method="POST">
					<label for="nombres">
						<h2>Nombres:</h2>
					</label>
					<input type="text" id="nombres" name="nombres" required>
					<label for="correo">
						<h2>Correo:</h2>
					</label>
					<input type="text" id="correo" name="correo" required>
					<label for="telefono">
						<h2>Telefono:</h2>
					</label>
					<input type="text" id="telefono" name="telefono" required>
					<label for="comentario">
						<h2>Comentario:</h2>
					</label>
					<textarea id="comentario" name="comentario" rows="4" required></textarea>
					<button type="submit">Enviar</button>
				</form>

			</div>
		</section>
	</main>

	<footer class="footer">
		<div class="container container-footer">
			<div class="menu-footer">
				<div class="contact-info">
					<a href="contactenos.php" class="title-footer">Información de Contacto</a>
					<ul>
						<li>
							Dirección: SENA Centro de industria y construcción <br> Ibagué - Tolima
						<li>Teléfono: +57 318 773 86 47</li>
						<li>EmaiL: senatrabajos2022@gmail.com</li>
					</ul>

				</div>

				<div class="information">
					<a href="sobre_n.php" class="title-footer">Información desarrolladores</a>
					<ul>
						<li><a href="sobre_n.php">Acerca de Nosotros</a></li>
						<li><a href="contactenos.php">Contactános</a></li>
					</ul>
				</div>

				<div class="my-account">
					<a href="../dev/PHP/login.php" class="title-footer">Mi cuenta</a>

					<ul>
						<li><a href="../dev/PHP/login.php">Iniciar sesión</a></li>
						<li><a href="https://docs.google.com/document/d/1kdiEfqIHL1a879InM30_-BwDaQEdN3L8/edit?usp=sharing&ouid=100333253493459131869&rtpof=true&sd=true">Manual de uso</a></li>
					</ul>
				</div>


			</div>

			<div class="copyright">
				<p>
					ALGJ S.A.S. &copy; 2024
				</p>

			</div>
		</div>
	</footer>


	<script src="https://kit.fontawesome.com/81581fb069.js" crossorigin="anonymous"></script>
</body>

</html>