<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Larry Garcia</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Portafolio Larry Garcia" name="keywords">
    <meta content="Portafolio Larry Garcia" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    
    <link rel="icon" href="img/ime2.jpg" type="image/png">

</head>

<body data-spy="scroll" data-target=".navbar" data-offset="51">
    <!-- Navbar Start -->
    <nav class="navbar fixed-top shadow-sm navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-lg-5">
        <a href="index.php" class="navbar-brand ml-lg-3">
            <!-- <h1 class="m-0 display-5"><span class="text-primary">Larry</span>Dev</h1> -->
            <img src="img/logo.png" alt="">
        </a>
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse px-lg-3" id="navbarCollapse">
            <div class="navbar-nav m-auto py-0">
                <a href="#home" class="nav-item nav-link active">Home</a>
                <a href="#about" class="nav-item nav-link">Acerca de mi</a>
                <a href="#qualification" class="nav-item nav-link">Cualidades</a>
                <a href="#skill" class="nav-item nav-link">Habilidades</a>
                <a href="#service" class="nav-item nav-link">Servicios</a>
                <a href="#portfolio" class="nav-item nav-link">Portafolio</a>
               
               <!-- <a href="#testimonial" class="nav-item nav-link">Review</a>
                <a href="#blog" class="nav-item nav-link">Blog</a>
                <a href="#contact" class="nav-item nav-link">Contact</a>
                 -->
            </div>
            <a href="" class="btn btn-outline-primary d-none d-lg-block">Inicio</a>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Video Modal Start -->
    <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <!-- 16:9 aspect ratio -->
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="" id="video" allowscriptaccess="always"
                            allow="autoplay"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Video Modal End -->


    <!-- Header Start -->
    <div class="container-fluid bg-primary d-flex align-items-center mb-5 py-5" id="home" style="min-height: 100vh;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 px-5 pl-lg-0 pb-5 pb-lg-0">
                    <!-- <img class="img-fluid w-100 rounded-circle shadow-sm" src="img/larry.png" alt=""> -->
                    <video class="img-fluid w-100 rounded-circle shadow-sm" src="img/Vide1.mp4" autoplay muted loop></video>
                    </div>
                <div class="col-lg-7 text-center text-lg-left">
                    <h3 class="text-white font-weight-normal mb-3">Hola! Soy</h3>
                    <h1 class="display-3 text-uppercase text-primary mb-2" style="-webkit-text-stroke: 2px #ffffff;">
                        Larry Garcia</h1>
                    <h1 class="typed-text-output d-inline font-weight-lighter text-white"></h1>
                    <div class="typed-text d-none"> Desarrollador Junior con conocimientos en, Backend Development, Diseño y Desarrollo Web, Así como Diseño y Desarrollo de Aplicaciones.</div>
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start pt-5">
                        <a href="lib/cv/HDVlarrygarciamorales_573173328716.pdf" class="btn btn-outline-light mr-5 " download>Descargar CV</a>
                        <!-- <button type="button" class="btn-play" data-toggle="modal"
                            data-src="https://www.youtube.com/embed/DWRcNpR6Kdc" data-target="#videoModal">
                            <span></span>
                        </button>
                        <h5 class="font-weight-normal text-white m-0 ml-4 d-none d-sm-block">Play Video</h5> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->


    <!-- About Start -->
    <div class="container-fluid py-5" id="about">
        <div class="container">
            <div class="position-relative d-flex align-items-center justify-content-center">
                <h1 class="display-1 text-uppercase text-white" style="-webkit-text-stroke: 1px #dee2e6;"></h1>
                <h1 class="position-absolute text-uppercase text-primary">acerca de mí</h1>
            </div>
            <br><br><br>
            <div class="row align-items-center">
                <div class="col-lg-5 pb-4 pb-lg-0">
                    <img class="img-fluid rounded w-100" src="img/ime2.jpg" alt="">
                </div>
                <div class="col-lg-7">
                    <h3 class="mb-4"> Desarrolador Web</h3>
                    <p>Tecnólogo en Análisis y Desarrollo de Software egresado del SENA, apasionado por la
                        creación de soluciones tecnológicas innovadoras. Poseo sólidos conocimientos en PHP,
                        SQL, HTML, CSS, y experiencia en frameworks como Node.js, Laravel y Bootstrap.
                        Además, cuento con habilidades en web scraping y la implementación de flujos de
                        automatización. Me caracterizo por mi proactividad, capacidad de aprendizaje continuo y
                        enfoque en el desarrollo de aplicaciones eficientes y escalables. </p>
                    <div class="row mb-3">
                        <div class="col-sm-6 py-2">
                            <h6>Nombre: <span class="text-secondary">Larry Garcia</span></h6>
                        </div>
                        <div class="col-sm-6 py-2">
                            <h6>Nacimiento: <span class="text-secondary">31 Mayo 2001</span></h6>
                        </div>
                        <div class="col-sm-6 py-2">
                            <h6>Titulo: <span class="text-secondary">Tecnologo en Analisis y Desarollo de
                                    Software</span></h6>
                        </div>
                        <div class="col-sm-6 py-2">
                            <h6>Experiencia: <span class="text-secondary">6 Meses</span></h6>
                        </div>
                        <div class="col-sm-6 py-2">
                            <h6>Celular/WhatsApp: <span class="text-secondary">+573173328716</span></h6>
                        </div>
                        <div class="col-sm-6 py-2">
                            <h6>Email: <span class="text-secondary">windonpc125@gmail.com</span></h6>
                        </div>
                        <div class="col-sm-6 py-2">
                            <h6>Address: <span class="text-secondary">Bogota-DC-Dg. 52b Sur #53-08 </span></h6>
                        </div>
                        <div class="col-sm-6 py-2">
                            <h6>Freelance: <span class="text-secondary">Disponible</span></h6>
                        </div>
                        <div class="col-sm-6 py-2">
                            <h6>GitHub: <a href="https://github.com/Larry-adso">https://github.com/Larry-adso</a></h6>
                        </div>
                    </div>
                    <!-- <a href="" class="btn btn-outline-primary mr-4">Hire Me</a>
                    <a href="" class="btn btn-outline-primary">Learn More</a> -->
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Qualification Start -->
    <div class="container-fluid py-5" id="qualification">
        <div class="container">
            <div class="position-relative d-flex align-items-center justify-content-center">
                <h1 class="display-1 text-uppercase text-white" style="-webkit-text-stroke: 1px #dee2e6;">Experiencia
                </h1>
                <h1 class="position-absolute text-uppercase text-primary">Educacion & Experiencia</h1>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h3 class="mb-4">Mi Educacion</h3>
                    <div class="border-left border-primary pt-2 pl-4 ml-2">
                        <div class="position-relative mb-4">
                            <i class="far fa-dot-circle text-primary position-absolute"
                                style="top: 2px; left: -32px;"></i>
                            <h5 class="font-weight-bold mb-1">Bachiller académico.</h5>
                            <p class="mb-2"><strong>Institución educativa técnica la ceiba
                                </strong> | <small>2020</small></p>
                            <!-- <p>Tempor eos dolore amet tempor dolor tempor. Dolore ea magna sit amet dolor eirmod. Eos ipsum est tempor dolor. Clita lorem kasd sed ea lorem diam ea lorem eirmod duo sit ipsum stet lorem diam</p> -->
                        </div>

                        <div class="position-relative mb-4">
                            <i class="far fa-dot-circle text-primary position-absolute"
                                style="top: 2px; left: -32px;"></i>
                            <h5 class="font-weight-bold mb-1">Técnico profesional en agroindustria alimentaria. </h5>
                            <p class="mb-2"><strong>Servicio Nacional de Aprendizaje - SENA.</strong> | <small>2018 -
                                    2020</small></p>
                            <!-- <p>Tempor eos dolore amet tempor dolor tempor. Dolore ea magna sit amet dolor eirmod. Eos ipsum est tempor dolor. Clita lorem kasd sed ea lorem diam ea lorem eirmod duo sit ipsum stet lorem diam</p> -->
                        </div>
                        <div class="position-relative mb-4">
                            <i class="far fa-dot-circle text-primary position-absolute"
                                style="top: 2px; left: -32px;"></i>
                            <h5 class="font-weight-bold mb-1">Tecnólogo en Análisis y desarrollo de software.</h5>
                            <p class="mb-2"><strong>Servicio Nacional de Aprendizaje - SENA.</strong> | <small>2022 -
                                    2025</small></p>
                            <!-- <p>Tempor eos dolore amet tempor dolor tempor. Dolore ea magna sit amet dolor eirmod. Eos ipsum est tempor dolor. Clita lorem kasd sed ea lorem diam ea lorem eirmod duo sit ipsum stet lorem diam</p> -->
                        </div>
                        <div class="position-relative mb-4">
                            <i class="far fa-dot-circle text-primary position-absolute"
                                style="top: 2px; left: -32px;"></i>
                            <h5 class="font-weight-bold mb-1">Visualizacion de datos Python </h5>
                            <p class="mb-2"><strong>Servicio Nacional de Aprendizaje - SENA.</strong> | <small>2022 -
                                    2025</small></p>
                                    <a class="border-bottom border-primary text-decoration-none" href="lib/cv/python.pdf" target="_blank" >Ver certificado</a>

                            <!-- <p>Tempor eos dolore amet tempor dolor tempor. Dolore ea magna sit amet dolor eirmod. Eos ipsum est tempor dolor. Clita lorem kasd sed ea lorem diam ea lorem eirmod duo sit ipsum stet lorem diam</p> -->
                        </div>
                        <div class="position-relative mb-4">
                            <i class="far fa-dot-circle text-primary position-absolute"
                                style="top: 2px; left: -32px;"></i>
                            <h5 class="font-weight-bold mb-1">JavaScript</h5>
                            <p class="mb-2"><strong>Servicio Nacional de Aprendizaje - SENA.</strong> | <small>2022 -
                                    2025</small></p>
                                    <a class="border-bottom border-primary text-decoration-none" href="lib/cv/javascript.pdf" target="_blank" >Ver certificado</a>

                            <!-- <p>Tempor eos dolore amet tempor dolor tempor. Dolore ea magna sit amet dolor eirmod. Eos ipsum est tempor dolor. Clita lorem kasd sed ea lorem diam ea lorem eirmod duo sit ipsum stet lorem diam</p> -->
                        </div>
                        <div class="position-relative mb-4">
                            <i class="far fa-dot-circle text-primary position-absolute"
                                style="top: 2px; left: -32px;"></i>
                            <h5 class="font-weight-bold mb-1">Creacion de aplicaciones Web en tiempo real NODE JS</h5>
                            <p class="mb-2"><strong>Servicio Nacional de Aprendizaje - SENA.</strong> | <small>2022 -
                                    2025</small></p>
                                    <a class="border-bottom border-primary text-decoration-none" href="lib/cv/node.pdf" target="_blank" >Ver certificado</a>

                            <!-- <p>Tempor eos dolore amet tempor dolor tempor. Dolore ea magna sit amet dolor eirmod. Eos ipsum est tempor dolor. Clita lorem kasd sed ea lorem diam ea lorem eirmod duo sit ipsum stet lorem diam</p> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h3 class="mb-4">Mi Experiencia Laboral</h3>
                    <div class="border-left border-primary pt-2 pl-4 ml-2">
                        <div class="position-relative mb-4">
                            <i class="far fa-dot-circle text-primary position-absolute"
                                style="top: 2px; left: -32px;"></i>
                            <h5 class="font-weight-bold mb-1">Personal de Apoyo y logistica</h5>
                            <p class="mb-2"><strong> ESCUELA DE MÚSICA ROVIRA TOLIMA</strong> | <small>2018 -
                                    2019</small></p>
                            <p>Mantenimiento de equipos de computo, instalaciones electricas y funciones de apoyo en
                                servicios generales.</p>
                        </div>
                        <div class="position-relative mb-4">
                            <i class="far fa-dot-circle text-primary position-absolute"
                                style="top: 2px; left: -32px;"></i>
                            <h5 class="font-weight-bold mb-1">TECNÓLOGO EN ANÁLISIS Y DESARROLLO DE SOFTWARE</h5>
                            <p class="mb-2"><strong>HOSTDIME.COM.CO S.A.S</strong> | <small>2024 - 2025</small></p>
                            <p>Desarrollo y soporte de aplicaciones web utilizando frameworks de
                                JavaScript (Node.js, Sails) y PHP, garantizando soluciones escalables y eficientes.

                                Implementación de procesos de automatización con N8N para optimizar flujos de
                                trabajo e integrar múltiples plataformas.

                                Configuración y gestión de flujos de chat de WhatsApp mediante SendPulse,
                                incluyendo personalización, monitoreo y campañas de mensajería.

                                Consumo e integración de APIs para mensajería masiva, enfocadas en la
                                comunicación automatizada y el envío de notificaciones a gran escala.

                                Realización de web scraping para la recopilación y análisis de datos, facilitando la
                                toma de decisiones basadas en información en tiempo real.
                            </p>
                            <a class="border-bottom border-primary text-decoration-none" href="lib/cv/Certificacion Laboral Larry.pdf" target="_blank" >Ver certificado</a>

                        </div>
                        <!-- <div class="position-relative mb-4">
                            <i class="far fa-dot-circle text-primary position-absolute" style="top: 2px; left: -32px;"></i>
                            <h5 class="font-weight-bold mb-1">Web Designer</h5>
                            <p class="mb-2"><strong>Soft Company</strong> | <small>2000 - 2050</small></p>
                            <p>Tempor eos dolore amet tempor dolor tempor. Dolore ea magna sit amet dolor eirmod. Eos ipsum est tempor dolor. Clita lorem kasd sed ea lorem diam ea lorem eirmod duo sit ipsum stet lorem diam</p>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Qualification End -->


    <!-- Skill Start -->
    <div class="container-fluid py-5" id="skill">
        <div class="container">
            <div class="position-relative d-flex align-items-center justify-content-center">
                <h1 class="display-1 text-uppercase text-white" style="-webkit-text-stroke: 1px #dee2e6;">Habilidades</h1>
                <h1 class="position-absolute text-uppercase text-primary">Mis Habilidades</h1>
            </div>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="skill mb-4">
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-bold">HTML</h6>
                            <h6 class="font-weight-bold">95%</h6>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="95" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="skill mb-4">
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-bold">CSS</h6>
                            <h6 class="font-weight-bold">85%</h6>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="85" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="skill mb-4">
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-bold">PHP</h6>
                            <h6 class="font-weight-bold">97%</h6>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="97" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="skill mb-4">
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-bold">Javascript</h6>
                            <h6 class="font-weight-bold">50%</h6>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="skill mb-4">
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-bold">Base De Datos SQL</h6>
                            <h6 class="font-weight-bold">95%</h6>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-dark" role="progressbar" aria-valuenow="95" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="skill mb-4">
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-bold">Python</h6>
                            <h6 class="font-weight-bold">30%</h6>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="30" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Skill End -->


    <!-- Services Start -->
    <div class="container-fluid pt-5" id="service">
        <div class="container">
            <div class="position-relative d-flex align-items-center justify-content-center">
                <h1 class="display-1 text-uppercase text-white" style="-webkit-text-stroke: 1px #dee2e6;">Servicios</h1>
                <h1 class="position-absolute text-uppercase text-primary">Mis servicios</h1>
            </div>
            <div class="row pb-3">
                <div class="col-lg-4 col-md-6 text-center mb-5">
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <i class="fa fa-2x fa-laptop service-icon bg-primary text-white mr-3"></i>
                        <h4 class="font-weight-bold m-0">Diseñador Web</h4>
                    </div>
                    <p>Soy desarrollador web con experiencia en la creación de sitios web funcionales y atractivos. Ofrezco soluciones personalizadas, desde el diseño hasta la implementación, asegurando una experiencia de usuario óptima y un rendimiento eficiente.</p>
                    <!-- <a class="border-bottom border-primary text-decoration-none" href="">Read More</a> -->
                </div>
                <div class="col-lg-4 col-md-6 text-center mb-5">
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <i class="fa fa-2x fa-laptop-code service-icon bg-primary text-white mr-3"></i>
                        <h4 class="font-weight-bold m-0">Desarrollador Web</h4>
                    </div>
                    <p>Soy desarrollador web especializado en la creación de aplicaciones y sitios web robustos y escalables. Con un enfoque en la calidad del código y la eficiencia, ofrezco soluciones completas para proyectos personalizados, adaptadas a las necesidades de cada cliente.</p>
                    <!-- <a class="border-bottom border-primary text-decoration-none" href="">Read More</a> -->
                </div>
                <div class="col-lg-4 col-md-6 text-center mb-5">
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <i class="fas fa-database service-icon bg-primary text-white mr-3"></i>
                        <h4 class="font-weight-bold m-0">DBA</h4>
                    </div>
                    <p>Administrador de Bases de Datos (DBA) con experiencia en la gestión, optimización y seguridad de bases de datos SQL.
                    </p>
                    <!-- <a class="border-bottom border-primary text-decoration-none" href="">Read More</a> -->
                </div>
                <div class="col-lg-4 col-md-6 text-center mb-5">
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <i class="fas fa-2x fa-server service-icon bg-primary text-white mr-3"></i>
                        <h4 class="font-weight-bold m-0">Desarrollador Backend</h4>
                    </div>
                    <p>
                        Soy desarrollador Backend con experiencia en la creación de aplicaciones robustas y escalables. Trabajo principalmente con tecnologías como PHP, Node.js, Python, Java, etc para construir y mantener la lógica del servidor, bases de datos y API,
                        garantizando un rendimiento eficiente y seguro. Mi enfoque está en optimizar el flujo de datos y la integridad de los sistemas.</p>
                    <!-- <a class="border-bottom border-primary text-decoration-none" href="">Read More</a> -->
                </div>
                <div class="col-lg-4 col-md-6 text-center mb-5">
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <i class="fa fa-2x fa-search service-icon bg-primary text-white mr-3"></i>
                        <h4 class="font-weight-bold m-0">API Development</h4>
                    </div>
                    <p>Soy un desarrollador con experiencia en la creación e integración de APIs (RESTful),
                        lo que permite que las aplicaciones y los servicios se comuniquen de manera eficiente.
                        Mi enfoque está en el diseño y la implementación de interfaces claras y bien documentadas, asegurando la interoperabilidad entre sistemas y facilitando la integración de servicios de terceros.</p>
                    <!-- <a class="border-bottom border-primary text-decoration-none" href="">Read More</a> -->
                </div>
                <div class="col-lg-4 col-md-6 text-center mb-5">
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <i class="fa fa-2x fa-edit service-icon bg-primary text-white mr-3"></i>
                        <h4 class="font-weight-bold m-0">(Git, GitHub, GitLab)</h4>
                    </div>
                    <p>Tengo una sólida comprensión de las herramientas de control de versiones como Git, y plataformas como GitHub y GitLab. Esto me permite gestionar de manera efectiva el código fuente, colaborar en equipos de desarrollo y mantener un historial claro de cambios en proyectos,
                        lo que facilita la colaboración y mejora el flujo de trabajo de desarrollo.</p>
                    <!-- <a class="border-bottom border-primary text-decoration-none" href="">Read More</a> -->
                </div>
            </div>
        </div>
    </div>
    <!-- Services End -->


    <!-- Portfolio Start -->
    <div class="container-fluid pt-5 pb-3" id="portfolio">
        <div class="container">
            <div class="position-relative d-flex align-items-center justify-content-center">
                <h1 class="display-1 text-uppercase text-white" style="-webkit-text-stroke: 1px #dee2e6;">Gallery</h1>
                <h1 class="position-absolute text-uppercase text-primary">Mi Portafolio</h1>
            </div>
            <div class="row">
                <div class="col-12 text-center mb-2">
                    <ul class="list-inline mb-4" id="portfolio-flters">
                        <li class="btn btn-sm btn-outline-primary m-1 active" data-filter="*">All</li>
                        <li class="btn btn-sm btn-outline-primary m-1" data-filter=".first">Diseño</li>
                        <li class="btn btn-sm btn-outline-primary m-1" data-filter=".second">Desarrollo</li>
                        <!-- <li class="btn btn-sm btn-outline-primary m-1" data-filter=".third">Marketing</li> -->
                    </ul>
                </div>
            </div>
            <div class="row portfolio-container">
                <div class="col-lg-4 col-md-6 mb-4 portfolio-item first">
                    <div class="position-relative overflow-hidden mb-2">
                        <img class="img-fluid rounded w-100" src="img/genesis1.png" alt="">
                        <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                            <a href="https://agnusit.com/" target="_blank">
                                <i class="fa fa-plus text-white" style="font-size: 100px;"></i>
                            </a>

                        </div>
                        <p style="text-align: center;" >Frontend</p>

                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 portfolio-item second">
                    <div class="position-relative overflow-hidden mb-2">
                        <img class="img-fluid rounded w-100" src="img/calculadora2.png" alt="">
                        <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                            <a href="https://www.hostdime.com.co/productionnwco/cotizar-colocation-quoter-cotizador-web" target="_blank">
                                <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                            </a>
                        </div>
                        <p style="text-align: center;" >Aplicacion NODEJS</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4 portfolio-item second">
                    <div class="position-relative overflow-hidden mb-2">
                        <img class="img-fluid rounded w-100" src="img/api.png" alt="">
                        <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                            <a href="lib/cv/api.pdf" target="_blank">
                                <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                            </a>
                        </div>
                            <p style="text-align: center;" >API</p>
                    </div>
                </div> 
                <div class="col-lg-4 col-md-6 mb-4 portfolio-item second">
                    <div class="position-relative overflow-hidden mb-2">
                        <img class="img-fluid rounded w-100" src="img/nomina3.png" alt="">
                        <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                            <a href="php/nomina/" target="_blank">
                                <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                            </a>
                        </div>
                        <p style="text-align: center;" >Aplicacion PHP</p>

                    </div>
                </div>
                <!-- <div class="col-lg-4 col-md-6 mb-4 portfolio-item third">
                    <div class="position-relative overflow-hidden mb-2">
                        <img class="img-fluid rounded w-100" src="img/portfolio-6.jpg" alt="">
                        <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                            <a href="img/portfolio-6.jpg" data-lightbox="portfolio">
                                <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                            </a>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <!-- Portfolio End -->


    <!-- Testimonial Start -->
    <!-- <div class="container-fluid py-5" id="testimonial">
        <div class="container">
            <div class="position-relative d-flex align-items-center justify-content-center">
                <h1 class="display-1 text-uppercase text-white" style="-webkit-text-stroke: 1px #dee2e6;">Review</h1>
                <h1 class="position-absolute text-uppercase text-primary">Clients Say</h1>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="owl-carousel testimonial-carousel">
                        <div class="text-center">
                            <i class="fa fa-3x fa-quote-left text-primary mb-4"></i>
                            <h4 class="font-weight-light mb-4">Dolor eirmod diam stet kasd sed. Aliqu rebum est eos.
                                Rebum elitr dolore et eos labore, stet justo sed est sed. Diam sed sed dolor stet
                                accusam amet eirmod eos, labore diam clita</h4>
                            <img class="img-fluid rounded-circle mx-auto mb-3" src="img/testimonial-1.jpg"
                                style="width: 80px; height: 80px;">
                            <h5 class="font-weight-bold m-0">Client Name</h5>
                            <span>Profession</span>
                        </div>
                        <div class="text-center">
                            <i class="fa fa-3x fa-quote-left text-primary mb-4"></i>
                            <h4 class="font-weight-light mb-4">Dolor eirmod diam stet kasd sed. Aliqu rebum est eos.
                                Rebum elitr dolore et eos labore, stet justo sed est sed. Diam sed sed dolor stet
                                accusam amet eirmod eos, labore diam clita</h4>
                            <img class="img-fluid rounded-circle mx-auto mb-3" src="img/testimonial-2.jpg"
                                style="width: 80px; height: 80px;">
                            <h5 class="font-weight-bold m-0">Client Name</h5>
                            <span>Profession</span>
                        </div>
                        <div class="text-center">
                            <i class="fa fa-3x fa-quote-left text-primary mb-4"></i>
                            <h4 class="font-weight-light mb-4">Dolor eirmod diam stet kasd sed. Aliqu rebum est eos.
                                Rebum elitr dolore et eos labore, stet justo sed est sed. Diam sed sed dolor stet
                                accusam amet eirmod eos, labore diam clita</h4>
                            <img class="img-fluid rounded-circle mx-auto mb-3" src="img/testimonial-3.jpg"
                                style="width: 80px; height: 80px;">
                            <h5 class="font-weight-bold m-0">Client Name</h5>
                            <span>Profession</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Testimonial End -->


    <!-- Blog Start -->
    <!-- <div class="container-fluid pt-5" id="blog">
        <div class="container">
            <div class="position-relative d-flex align-items-center justify-content-center">
                <h1 class="display-1 text-uppercase text-white" style="-webkit-text-stroke: 1px #dee2e6;">Blog</h1>
                <h1 class="position-absolute text-uppercase text-primary">Latest Blog</h1>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-5">
                    <div class="position-relative mb-4">
                        <img class="img-fluid rounded w-100" src="img/blog-1.jpg" alt="">
                        <div class="blog-date">
                            <h4 class="font-weight-bold mb-n1">01</h4>
                            <small class="text-white text-uppercase">Jan</small>
                        </div>
                    </div>
                    <h5 class="font-weight-medium mb-4">Rebum lorem no eos ut ipsum diam tempor sed rebum elitr ipsum
                    </h5>
                    <a class="btn btn-sm btn-outline-primary py-2" href="">Read More</a>
                </div>
                <div class="col-lg-4 mb-5">
                    <div class="position-relative mb-4">
                        <img class="img-fluid rounded w-100" src="img/blog-2.jpg" alt="">
                        <div class="blog-date">
                            <h4 class="font-weight-bold mb-n1">01</h4>
                            <small class="text-white text-uppercase">Jan</small>
                        </div>
                    </div>
                    <h5 class="font-weight-medium mb-4">Rebum lorem no eos ut ipsum diam tempor sed rebum elitr ipsum
                    </h5>
                    <a class="btn btn-sm btn-outline-primary py-2" href="">Read More</a>
                </div>
                <div class="col-lg-4 mb-5">
                    <div class="position-relative mb-4">
                        <img class="img-fluid rounded w-100" src="img/blog-3.jpg" alt="">
                        <div class="blog-date">
                            <h4 class="font-weight-bold mb-n1">01</h4>
                            <small class="text-white text-uppercase">Jan</small>
                        </div>
                    </div>
                    <h5 class="font-weight-medium mb-4">Rebum lorem no eos ut ipsum diam tempor sed rebum elitr ipsum
                    </h5>
                    <a class="btn btn-sm btn-outline-primary py-2" href="">Read More</a>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Blog End -->
    <br><br><br><br>

    <!-- Contact Start -->
    
    <div class="container-fluid py-5" id="contact">
        <div class="container">
            <div class="position-relative d-flex align-items-center justify-content-center">
                <h1 class="display-1 text-uppercase text-white" style="-webkit-text-stroke: 1px #dee2e6;">Contacto</h1>
                <h1 class="position-absolute text-uppercase text-primary">Contactame</h1>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contact-form text-center">
                        <!--<form action="mail/contact.php" method="POST" >-->
                        <div id="success"></div>
                        <!-- Este formulario recibe toda la Data y la envia a un Filtro JavaScript -->
                         <form name="sentMessage" id="contactForm" novalidate="novalidate"> 
                            <div class="form-row">
                                <div class="control-group col-sm-6">
                                    <input type="text" class="form-control p-4" name="name" id="name" placeholder="Tu nombre"
                                        required="required" data-validation-required-message="Please enter your name" />
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="control-group col-sm-6">
                                    <input type="email" class="form-control p-4" name="email" id="email" placeholder="Tu correo"
                                        required="required"
                                        data-validation-required-message="Please enter your email" />
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <input type="text" class="form-control p-4" name="subject" id="subject" placeholder="Telefono / WhatsApp"
                                    required="required" data-validation-required-message="Please enter a subject" />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="control-group">
                                <textarea class="form-control py-3 px-4" rows="5" name="message" id="message" placeholder="Lo que me quieras decir"
                                    required="required"
                                    data-validation-required-message="Please enter your message"></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                            <div>
                                <button class="btn btn-outline-primary" type="submit" id="sendMessageButton">Send
                                    Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contact End -->


    <!-- Footer Start -->
    <div class="container-fluid bg-primary text-white mt-5 py-5 px-sm-3 px-md-5">
        <div class="container text-center py-5">
            <div class="d-flex justify-content-center mb-4">
                <!-- <a class="btn btn-light btn-social mr-2" href="#"><i class="fab fa-twitter"></i></a> -->
                <a class="btn btn-light btn-social mr-2" href="https://es-la.facebook.com/people/Garcia-Larry/pfbid0Db8SEYUxzLFv8KyMYn1As5WLNzAtCyY6WYBykHwBfiVPr7ZbFjU5CnhyTVCYXpRgl/"><i class="fab fa-facebook-f"></i></a>
                <a class="btn btn-light btn-social mr-2" href="https://www.linkedin.com/in/larry-garc%C3%ADa-morales-1577a4272/"><i class="fab fa-linkedin-in"></i></a>
                <a class="btn btn-light btn-social" href="#"><i class="fab fa-instagram"></i></a>
            </div>
            <!-- <div class="d-flex justify-content-center mb-3">
                <a class="text-white" href="#">Privacy</a>
                <span class="px-3">|</span>
                <a class="text-white" href="#">Terms</a>
                <span class="px-3">|</span>
                <a class="text-white" href="#">FAQs</a>
                <span class="px-3">|</span>
                <a class="text-white" href="#">Help</a>
            </div> -->
            <br>
            <p class="m-0">&copy; <a class="text-white font-weight-bold" href="https://larrydev.shop/">larrydev.shop/</a>. All Rights Reserved.
                Designed by <a class="text-white font-weight-bold" href="#">Larry Garcia</a>
            </p>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Scroll to Bottom -->
    <i class="fa fa-2x fa-angle-down text-white scroll-to-bottom"></i>

    <!-- Back to Top -->
    <a href="#" class="btn btn-outline-dark px-0 back-to-top"><i class="fa fa-angle-double-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/typed/typed.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/isotope/isotope.pkgd.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>