<?php
require 'Database.php'; 
session_start(); 

$lenguajes = [];
$error_db = null;

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    

    $stmt = $pdo->query("SELECT id_lenguaje, lenguaje FROM lenguaje ORDER BY id_lenguaje ASC");
    $lenguajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (\PDOException $e) {
    $error_db = "Error al cargar lenguajes desde la base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lenguajes de Programacion | CodeLib</title>
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
                        <li><a href="lenguajes.php" class="nav-item active">💻 Lenguajes</a></li> 
                        <li><a href="libros.php" class="nav-item">📚 Libros</a></li>
                        <li><a href="info.php" class="nav-item">ℹ Informacion</a></li>
                    </ul>
                </nav>
            </div>
            <div class="header-contenido">
                <form action="#" method="get" class="buscador-form">
                    <input type="text" id="campoBusqueda" placeholder="Buscar Lenguajes..." required>
                    <button type="submit" class="btn-buscar">Buscar</button>
                </form>
            </div>
        </header>

        <nav class="navbar">
            <div class="nav-section">
                <h3 class="nav-title">Categorias de Lenguajes</h3>
                <ul class="vertical-menu">
                    <li><a href="#" class="nav-link active">Backend</a></li>
                    <li><a href="#" class="nav-link">Frontend</a></li>
                    <li><a href="#" class="nav-link">Scripting</a></li>
                    <li><a href="#" class="nav-link">Bases de Datos</a></li>
                </ul>
            </div>
            <div class="nav-section">
                <h3 class="nav-title">Nivel de Dificultad</h3>
                <ul class="vertical-menu">
                    <li><a href="#" class="nav-link">Principiante</a></li>
                    <li><a href="#" class="nav-link">Intermedio</a></li>
                </ul>
            </div>
        </nav>

        <section class="main">
            <h1 class="main-title">Lenguajes de Programacion para Aprender</h1>
            <p class="section-description">Explora nuestra coleccion de tutoriales, guias y recursos para dominar los lenguajes de programacion mas demandados.</p>
            
            <?php if ($error_db): ?>
                <div style="background-color: #d9534f; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    🚨 **Error de Base de Datos:** <?php echo htmlspecialchars($error_db); ?>
                </div>
            <?php endif; ?>

            <div class="course-list">
                
                <?php if (!empty($lenguajes)): ?>
                    <?php foreach ($lenguajes as $lenguaje): ?>
                    <article class="course-card">
                        <h2 class="course-title"><?php echo htmlspecialchars($lenguaje['lenguaje']); ?> 💻</h2> 
                        
                        <p class="course-info">Tutoriales y guías para dominar este lenguaje de programación.</p>
                        
                        <a href="tutoriales.php?id=<?php echo htmlspecialchars($lenguaje['id_lenguaje']); ?>" class="btn-course-details">Ver Tutoriales</a>
                    </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php if (!$error_db): ?>
                    <p style="color: #ccc;">No se encontraron lenguajes de programación en la base de datos.</p>
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