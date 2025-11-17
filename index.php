<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeLib - Tu Biblioteca de Programacion</title>
    <link rel="stylesheet" href="Biblio.css">
    <style>
        body {
            background-color: #1a1a1a;
        }
        .btn-registro-header {
            background: linear-gradient(to bottom, #7289da, #5b6fb5); 
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            line-height: normal;
            transition: background 0.3s;
        }
        .btn-registro-header:hover {
            background: #5b6fb5;
        }
    </style>
</head>

<body>
    <div class="grid-container">

        <header class="header">
            <div class="navbar-wrapper">
                <div class="navbar-left">
                    <a href="index.php" class="logo">
                        <span class="logo-code">CODE</span>
                        <br>
                        <span class="logo-lib">LIB</span>
                    </a>
                </div>
                <nav class="navbar-center">
                    <ul class="horizontal-menu">
                        <li><a href="index.php" class="nav-item active">💡 Cursos</a></li>
                        <li><a href="lenguajes.php" class="nav-item">💻 Lenguajes</a></li>
                        <li><a href="libros.php" class="nav-item">📚 Libros</a></li>
                        <li><a href="info.php" class="nav-item">ℹ Informacion</a></li>
                    </ul>
                </nav>
            </div>
            
            <div class="header-contenido">
                <form action="#" method="get" class="buscador-form">
                    <input type="text" id="campoBusqueda" placeholder="¿Que quieres aprender hoy?" required>
                    <button type="submit" class="btn-buscar">Buscar</button>
                </form>
            </div>
            
            <div class="header-contenido" style="justify-content: flex-end;"> 
                
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    <span class="user-greeting" style="color: white; margin-right: 15px; font-weight: bold; padding: 8px 15px; border-radius: 5px; background-color: #4DD0E1;">
                        ¡Hola, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                    </span>
                    <a href="logout.php" class="btn-buscar" style="background: linear-gradient(to bottom, #E57373, #D32F2F);">
                        🚪 Salir
                    </a>
                <?php else: ?>
                    <a href="registro.php" class="btn-registro-header"> 
                        📝 Registrarse
                    </a>

                    <button id="btnLogin" class="btn-buscar" style="margin-right: 15px; background: linear-gradient(to bottom, #4DD0E1, #3bbac9);">
                        🔑 Iniciar Sesión
                    </button>
                <?php endif; ?>
            </div>
        </header>

        <nav class="navbar">
            <div class="nav-section">
                <h3 class="nav-title">Categorias Principales</h3>
                <ul class="vertical-menu">
                    
                    <li><a href="./index.php" class="nav-link active">Todos los Cursos</a></li>
                    <li><a href="./index.php#desarrollo-web" class="nav-link">C#</a></li>
                    <li><a href="./index.php#ciencia-datos" class="nav-link">Python</a></li>
                    <li><a href="./index.php#desarrollo-movil" class="nav-link">C++</a></li>
                    </ul>
            </div>
            <div class="nav-section">
                <h3 class="nav-title">Informacion</h3>
                <ul class="vertical-menu">
                    <li><a href="info.html#acerca" class="nav-link">Acerca de CodeLib</a></li>
                    <li><a href="info.html#contacto" class="nav-link">Contacto</a></li>
                </ul>
            </div>
        </nav>

        <section class="main">
            <h1 class="main-title">Cursos de Programacion Disponibles</h1>

            <div class="carousel-container">
                <img id="course-image" src="7.jpg" alt="Imagen de Curso" class="carousel-image"> 
            </div>

            <p class="section-description">Explore nuestros cursos de programacion mas populares. Haz clic en cualquiera para ver los detalles, horarios y costes.</p>

            <div class="course-list">
                <article class="course-card">
                    <h2 class="course-title">Master en C# con .NET 💻</h2>
                    <p class="course-info">Aprende el lenguaje de Microsoft para crear aplicaciones web, de escritorio y videojuegos.</p>
                    <p class="course-price">Costo: $59.99 USD</p>
                    <a href="#" class="btn-course-details">Ver Detalles y Horarios</a>
                </article>
                <article class="course-card">
                    <h2 class="course-title">Python para Data Science 📊</h2>
                    <p class="course-info">Curso enfocado en el analisis de datos, machine learning y automatizacion con librerias clave.</p>
                    <p class="course-price">Costo: $79.99 USD</p>
                    <a href="#" class="btn-course-details">Ver Detalles y Horarios</a>
                </article>
                <article class="course-card">
                    <h2 class="course-title">Introduccion a C++ 🚀</h2>
                    <p class="course-info">Domina los fundamentos de C++ para desarrollo de sistemas de alto rendimiento y videojuegos.</p>
                    <p class="course-price">Costo: $49.99 USD</p>
                    <a href="#" class="btn-course-details">Ver Detalles y Horarios</a>
                </article>
                <article class="course-card">
                    <h2 class="course-title">Desarrollo Web Frontend</h2>
                    <p class="course-info">HTML, CSS, y JavaScript de la mano para crear interfaces de usuario atractivas y funcionales.</p>
                    <p class="course-price">Costo: $69.99 USD</p>
                    <a href="#" class="btn-course-details">Ver Detalles y Horarios</a>
                </article>
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
                <a href="libros.php#gratis" class="sidebar-link">Descargar ahora</a>
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

    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2 class="modal-title">Iniciar Sesión en CodeLib</h2>
            <form action="login_process.php" method="post" class="login-form">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="nombre_usuario" required> 

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="btn-login-submit">Acceder</button>
            </form>
            
            <p style="text-align: center; margin-top: 15px;">
                ¿No tienes cuenta? 
                <a href="registro.php" style="color: #4DD0E1; font-weight: bold; text-decoration: none;">Regístrate aquí</a> 
            </p>
        </div>
    </div>
    
    <script>
        const modal = document.getElementById('loginModal');
        const btn = document.getElementById('btnLogin');
        const span = document.getElementsByClassName('close-button')[0];

        if (btn) {
            btn.onclick = function() {
                modal.style.display = "block";
            }
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const images = ['7.jpg', '8.jpg'];
            let currentIndex = 0;
            const courseImage = document.getElementById('course-image');

            function changeImage() {
                currentIndex = (currentIndex + 1) % images.length;
                courseImage.src = images[currentIndex];
            }

            setInterval(changeImage, 3000);
        });
    </script>
</body>

</html>