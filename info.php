<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informacion | CodeLib</title>
    <link rel="stylesheet" href="Biblio.css">
    <style>
        body {
            background-color: #1a1a1a;
        }
    </style>
</head>

<body>
    <div class="grid-container">
        <header class="header">
            <div class="navbar-wrapper">
                <div class="navbar-left">
                    <a href="index.php" class="logo"><span class="logo-code">CODE</span><br><span class="logo-lib">LIB</span></a>
                </div>
                <nav class="navbar-center">
                    <ul class="horizontal-menu">
                        <li><a href="index.php" class="nav-item">💡 Cursos</a></li>
                        <li><a href="lenguajes.php" class="nav-item">💻 Lenguajes</a></li>
                        <li><a href="libros.php" class="nav-item">📚 Libros</a></li>
                        <li><a href="info.php" class="nav-item active">ℹ Informacion</a></li>
                    </ul>
                </nav>
            </div>
            <div class="header-contenido">
                <form action="#" method="get" class="buscador-form">
                    <input type="text" id="campoBusqueda" placeholder="Buscar Informacion..." required>
                    <button type="submit" class="btn-buscar">Buscar</button>
                </form>
            </div>
        </header>

        <nav class="navbar">
            <div class="nav-section">
                <h3 class="nav-title">Navegacion</h3>
                <ul class="vertical-menu">
                    <li><a href="index.php" class="nav-link">⬅ Volver a Cursos</a></li>
                    <li><a href="#acerca" class="nav-link active">Acerca de CodeLib</a></li>
                    <li><a href="#contacto" class="nav-link">Contacto</a></li>
                    <li><a href="#faq" class="nav-link">Preguntas Frecuentes </a></li>
                </ul>
            </div>
        </nav>

        <section class="main">
            <h1 class="main-title">Informacion y Contacto</h1>

            <div class="course-details-grid">
                <div class="detail-card" id="acerca">
                    <h2 class="detail-title">Acerca de CodeLib</h2>
                    <p><strong>CodeLib</strong> nacio de la pasion por la enseñanza de la programacion. Nuestro objetivo es ser tu biblioteca digital de referencia, ofreciendo cursos de alta calidad y recursos descargables para que puedas avanzar en tu carrera profesional a tu propio ritmo.</p>
                    <p>Creemos en el conocimiento accesible y en la practica constante para dominar el arte de la codificacion.</p>
                </div>

                <div class="detail-card" id="contacto">
                    <h2 class="detail-title">Contacto</h2>
                    <p>¿Tienes alguna pregunta, sugerencia o problema tecnico? Contactanos a traves de:</p>
                    <ul>
                        <!-- Corregido: La estructura del enlace mailto estaba rota y se ha arreglado para que el correo sea clickable. -->
                        <li><strong>Email:</strong> <a href="mailto:AzulRonquillo@codelib.com" class="sidebar-link">AzulRonquillo@codelib.com</a></li>
                        <li><strong>Telefono:</strong> +52 656 822-3750</li>
                        <li><strong>Direccion:</strong> Ciudad Juarez Avenida de las arecas</li>
                    </ul>
                </div>

                <div class="detail-card full-width" id="faq">
                    <h2 class="detail-title">Preguntas Frecuentes </h2>
                    <h3 class="schedule-title">¿Como puedo acceder a un curso?</h3>
                    <p>Simplemente haz clic en 'Ver Detalles y Horarios' en la pagina principal, revisa la informacion y usa el boton de inscripcion.</p>

                    <h3 class="schedule-title">¿Los libros gratuitos son de descarga inmediata?</h3>
                    <p>Si, la mayoria de nuestros libros gratuitos son de descarga directa en formato PDF.</p>
                </div>
            </div>
        </section>

        <aside class="sidebar">
            <h3 class="sidebar-title">Novedades y Ofertas</h3>
            <div class="sidebar-item">
                <p><strong>¡Nuevo Curso!</strong> 💻 Curso de Ciberseguridad Ofensiva. ¡20% de descuento por lanzamiento!</p>
                <a href="cursos.html?id=ciberseguridad" class="sidebar-link">Mas informacion</a>
            </div>
            <div class="sidebar-item">
                <p><strong>Libro del Mes:</strong> "Diseño de Algoritmos Eficientes". ¡Descarga gratuita!</p>
                <a href="libros.html#gratis" class="sidebar-link">Descargar ahora</a>
            </div>
            <div class="sidebar-item">
                <p>¿Necesitas ayuda? 💡 Consulta nuestra seccion de <a href="info.html#faq" class="sidebar-link">Preguntas Frecuentes</a>.</p>
            </div>
        </aside>

        <footer class="footer">
            <p>&copy; 2025 CodeLib - Tu Biblioteca de Programacion. Todos los derechos reservados.</p>
            <div class="footer-links">
                <a href="#">Politica de Privacidad</a> |
                <a href="#">Terminos y Condiciones</a>
            </div>
        </footer>

    </div>
</body>

</html>