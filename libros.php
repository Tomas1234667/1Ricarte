<?php

require 'Database.php';
session_start();

$libros = [];
$error_db = null;

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();

    $stmt = $pdo->query("
        SELECT
            l.id_libro,
            l.titulo,
            l.precio,
            le.lenguaje,
            u.nombre_usuario
        FROM libro l
        LEFT JOIN lenguaje le ON l.id_lenguaje = le.id_lenguaje
        LEFT JOIN usuario u ON l.id_usuario = u.id_usuario
        ORDER BY l.id_libro ASC
    ");
    $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (\PDOException $e) {
    $error_db = "Error al cargar libros desde la base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Libros de Programacion | CodeLib</title>
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
                        <li><a href="libros.php" class="nav-item active">📚 Libros</a></li>
                        <li><a href="info.php" class="nav-item">ℹ Informacion</a></li>
                    </ul>
                </nav>
            </div>
            <div class="header-contenido">
                <form action="#" method="get" class="buscador-form">
                    <input type="text" id="campoBusqueda" placeholder="Buscar Libros..." required>
                    <button type="submit" class="btn-buscar">Buscar</button>
                </form>
            </div>
        </header>

        <nav class="navbar">
            <div class="nav-section">
                <h3 class="nav-title">Clasificacion</h3>
                <ul class="vertical-menu">
                    <li><a href="#" class="nav-link active">Todos los Libros</a></li>
                    <li><a href="#" class="nav-link">Algoritmos y Estructuras</a></li>
                    <li><a href="#" class="nav-link">Desarrollo Web</a></li>
                    <li><a href="#" class="nav-link">Diseño de Sistemas</a></li>
                </ul>
            </div>
            <div class="nav-section">
                <h3 class="nav-title">Descarga</h3>
                <ul class="vertical-menu">
                    <li><a href="#" class="nav-link">Gratuitos</a></li>
                    <li><a href="#" class="nav-link">Premium</a></li>
                </ul>
            </div>
        </nav>

        <section class="main">
            <h1 class="main-title">Libros de Programacion para Descargar</h1>
            <p class="section-description">Descarga recursos esenciales para trabajar por tu cuenta. Estos libros cubren desde fundamentos hasta tecnicas avanzadas.</p>
            
            <?php if ($error_db): ?>
                <div style="background-color: #d9534f; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    🚨 **Error de Base de Datos:** <?php echo htmlspecialchars($error_db); ?>
                </div>
            <?php endif; ?>

            <div class="course-list">
                
                <?php if (!empty($libros)): ?>
                    <?php foreach ($libros as $libro): ?>
                    <article class="course-card">
                        <h2 class="course-title"><?php echo htmlspecialchars($libro['titulo']); ?> 📖</h2>
                        
                        <p class="course-info">
                            <?php if ($libro['lenguaje']): ?>
                                Lenguaje: **<?php echo htmlspecialchars($libro['lenguaje']); ?>**
                            <?php endif; ?>
                            <?php if ($libro['nombre_usuario']): ?>
                                | Subido por: **<?php echo htmlspecialchars($libro['nombre_usuario']); ?>**
                            <?php endif; ?>
                        </p>
                        
                        <p class="course-price">Costo:
                            <?php
                                $precio = (float)$libro['precio'];
                                if ($precio == 0.00) {
                                    echo "GRATIS";
                                } else {
                                    echo "$" . number_format($precio, 2) . " USD";
                                }
                            ?>
                        </p>
                        
                        <a href="descargar.php?id=<?php echo htmlspecialchars($libro['id_libro']); ?>" class="btn-course-details">Descargar Libro</a>
                    </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php if (!$error_db): ?>
                    <p style="color: #ccc;">No se encontraron libros de programación disponibles en este momento.</p>
                    <?php endif; ?>
                <?php endif; ?>
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
</body>

</html>