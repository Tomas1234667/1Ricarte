<?php 
ob_start(); 
session_start(); 
require_once '../app/models/Database.php';
function getAllUsers() {
    try {
        $db = Database::getInstance();
        $pdo = $db->getConnection();
        $stmt = $pdo->query("SELECT id_usuario, nombre_usuario FROM usuario ORDER BY id_usuario ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        error_log("Error al obtener usuarios: " . $e->getMessage());
        $_SESSION['error'] = "Error al cargar la lista de usuarios: " . $e->getMessage();
        return [];
    }
}
function getAllLenguajes() {
    try {
        $db = Database::getInstance();
        $pdo = $db->getConnection();
        $stmt = $pdo->query("SELECT id_lenguaje, lenguaje FROM lenguaje ORDER BY id_lenguaje ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        error_log("Error al obtener lenguajes: " . $e->getMessage());
        $_SESSION['error'] = "Error al cargar la lista de lenguajes: " . $e->getMessage();
        return [];
    }
}
function getAllLibros() {
    try {
        $db = Database::getInstance();
        $pdo = $db->getConnection();
        $stmt = $pdo->query("SELECT id_libro, titulo, id_lenguaje, id_autor, id_editorial, precio, stock, id_usuario FROM libro ORDER BY id_libro ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        error_log("Error al obtener libros: " . $e->getMessage());
        $_SESSION['error'] = "Error al cargar la lista de libros: " . $e->getMessage();
        return [];
    }
}
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
$users = $is_admin ? getAllUsers() : [];
$lenguajes = $is_admin ? getAllLenguajes() : [];
$libros = $is_admin ? getAllLibros() : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CodeLib - Tu Biblioteca de Programacion</title>
<link rel="stylesheet" href="../public/Biblio.css">
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
    .error-message, .success-message {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        padding: 15px 30px;
        border-radius: 8px;
        font-weight: bold;
        z-index: 1000;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        text-align: center;
        max-width: 90%;
        cursor: pointer; 
        animation: fadeAndShrink 5s ease-in-out forwards;
    }
    .error-message {
        background-color: #f44336;
        color: white;
    }
    .success-message {
        background-color: #4CAF50;
        color: white;
    }
    @keyframes fadeAndShrink {
        0% { opacity: 0; transform: translateX(-50%) scale(0.8); }
        10% { opacity: 1; transform: translateX(-50%) scale(1); }
        80% { opacity: 1; transform: translateX(-50%) scale(1); }
        100% { opacity: 0; transform: translateX(-50%) scale(0.9); }
    }
    .modal {
        display: none; 
        position: fixed;
        z-index: 100; 
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.7);
    }
    .modal-content {
        background-color: #2c3e50;
        margin: 10% auto; 
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        width: 90%;
        max-width: 400px;
    }
    .modal-title {
        color: #4DD0E1;
        text-align: center;
        margin-bottom: 20px;
    }
    .login-form label {
        color: white;
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .login-form input[type="text"],
    .login-form input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: none;
        border-radius: 5px;
        box-sizing: border-box;
    }
    .btn-login-submit {
        width: 100%;
        padding: 12px;
        background: linear-gradient(to bottom, #4DD0E1, #3bbac9);
        border: none;
        border-radius: 5px;
        color: white;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }
    .btn-login-submit:hover {
        background: #3bbac9;
    }
    .admin-dashboard {
        background-color: #2a2a2a;
        padding: 30px;
        margin-top: 20px;
        margin-bottom: 40px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
    }
    .admin-dashboard h2 {
        color: #FFEB3B;
        border-bottom: 2px solid #FFEB3B;
        padding-bottom: 10px;
        margin-bottom: 25px;
    }
    .btn-add {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        margin-bottom: 20px;
        transition: background 0.3s;
        display: inline-block;
    }
    .btn-add:hover {
        background-color: #45a049;
    }
   .admin-table {
        width: 100%;
        border-collapse: collapse;
        color: white;
       margin-bottom: 30px;
    }
    .admin-table th, .admin-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #3a3a3a;
    }
    .admin-table th {
        background-color: #3b3b3b;
        color: #4DD0E1;
        font-weight: bold;
    }
    .admin-table tr:hover {
        background-color: #333333;
    }
    .btn-admin {
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
       font-weight: bold;
        margin-right: 5px;
       transition: background 0.3s;
    }
    .btn-update {
        background-color: #2196F3;
        color: white;
    }
    .btn-update:hover {
        background-color: #1976D2;
    }
    .btn-delete {
        background-color: #F44336;
        color: white;
    }
    .btn-delete:hover {
        background-color: #D32F2F;
   }
    .crud-modal .modal-content {
        margin: 5% auto; 
        max-width: 450px;
        background-color: #3b3b3b;
    }
    .crud-modal input[type="text"],
    .crud-modal input[type="password"] {
        background-color: #2a2a2a;
       color: white;
    }
    .crud-modal label {
        color: #4DD0E1;
    }
    .crud-modal textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: none;
        border-radius: 5px;
        box-sizing: border-box;
        background-color: #2a2a2a;
        color: white;
        resize: vertical;
    }
    </style>
    </head>
<body>
<?php if (isset($_SESSION['error'])): ?>
    <div class="error-message" onclick="this.style.display='none'">
       🚨 ERROR: <?php echo htmlspecialchars($_SESSION['error']); ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['success'])): ?>
    <div class="success-message" onclick="this.style.display='none'">
        ✅ ÉXITO: <?php echo htmlspecialchars($_SESSION['success']); ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
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
                <?php if ($is_admin): ?>
                    <span class="admin-badge" style="color: #1a1a1a; background-color: #FFEB3B; margin-right: 15px; padding: 8px 15px; border-radius: 5px; font-weight: bold;">
                        UTCJ ADMIN 
                    </span>
                <?php endif; ?>
                <a href="../app/controllers/logout.php" class="btn-buscar" style="background: linear-gradient(to bottom, #E57373, #D32F2F);">
                    Salir
                </a>
            <?php else: ?>
                <a href="registro.php" class="btn-registro-header"> 
                    📝 Registrarse
                </a>
                <button id="btnLogin" class="btn-buscar" style="margin-right: 15px; background: linear-gradient(to bottom, #4DD0E1, #3bbac9);">
                    👤 Iniciar Sesión
                </button>
            <?php endif; ?>
        </div>
    </header>
    <nav class="navbar">
        <div class="nav-section">
            <h3 class="nav-title">Categorias Principales</h3>
            <ul class="vertical-menu">
                
                <li><a href="index.php" class="nav-link active">Todos los Cursos</a></li>
                <?php 
                $nav_lenguajes = $is_admin ? array_slice($lenguajes, 0, 3) : [['lenguaje' => 'C#'], ['lenguaje' => 'Python'], ['lenguaje' => 'C++']];
                foreach ($nav_lenguajes as $lang): 
                ?>
                    <li><a href="index.php#<?php echo strtolower($lang['lenguaje']); ?>" class="nav-link"><?php echo htmlspecialchars($lang['lenguaje']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="nav-section">
            <h3 class="nav-title">Informacion</h3>
            <ul class="vertical-menu">
                <li><a href="info.php#acerca" class="nav-link">Acerca de CodeLib</a></li>
                <li><a href="info.php#contacto" class="nav-link">Contacto</a></li>
            </ul>
        </div>
    </nav>
    <section class="main">
        <h1 class="main-title">Cursos de Programacion Disponibles</h1>
       <?php if ($is_admin): ?>
        <h1 class="main-title" style="color: #FFEB3B; margin-top: 40px;"> Panel de Administracion</h1>
        <section class="admin-dashboard">
            <h2>👤 Gestion de Usuarios</h2>
            <button class="btn-add" onclick="openCreateModal()">
                ➕ Añadir Nuevo Usuario
            </button>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id_usuario']); ?></td>
                        <td><?php echo htmlspecialchars($user['nombre_usuario']); ?></td>
                        <td>
                            <button class="btn-admin btn-update" 
                                onclick="openUpdateModal(<?php echo $user['id_usuario']; ?>, '<?php echo htmlspecialchars($user['nombre_usuario'], ENT_QUOTES); ?>')">
                                 Editar
                            </button>
                            <button class="btn-admin btn-delete" 
                                onclick="openDeleteModal(<?php echo $user['id_usuario']; ?>, '<?php echo htmlspecialchars($user['nombre_usuario'], ENT_QUOTES); ?>')">
                                 Eliminar
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($users)): ?>
                <p style="color: #ccc; text-align: center; margin-top: 20px;">No hay usuarios registrados (aparte del Admin).</p>
            <?php endif; ?>
        </section>
        <section class="admin-dashboard">
            <h2>💻 Gestión de Lenguajes</h2>
            
            <button class="btn-add" onclick="openCreateModalLenguaje()">
                ➕ Añadir Nuevo Lenguaje
            </button>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Lenguaje</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lenguajes as $lang): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($lang['id_lenguaje']); ?></td>
                        <td><?php echo htmlspecialchars($lang['lenguaje']); ?></td>
                        <td>
                            <button class="btn-admin btn-update" 
                                onclick="openUpdateModalLenguaje(<?php echo $lang['id_lenguaje']; ?>, '<?php echo htmlspecialchars($lang['lenguaje'], ENT_QUOTES); ?>')">
                                Editar
                            </button>
                            <button class="btn-admin btn-delete" 
                                onclick="openDeleteModalLenguaje(<?php echo $lang['id_lenguaje']; ?>, '<?php echo htmlspecialchars($lang['lenguaje'], ENT_QUOTES); ?>')">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($lenguajes)): ?>
                <p style="color: #ccc; text-align: center; margin-top: 20px;">No hay lenguajes registrados.</p>
            <?php endif; ?>
        </section>
        <section class="admin-dashboard">
            <h2>📚 Gestion de Libros</h2>
            <button class="btn-add" onclick="openCreateModalLibro()">
                ➕ Añadir Nuevo Libro
            </button>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($libros as $libro): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($libro['id_libro']); ?></td>
                        <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
                        <td><?php echo htmlspecialchars($libro['id_autor']); ?></td>
                        <td>
                            <button class="btn-admin btn-update" 
                                onclick="openUpdateModalLibro(<?php echo $libro['id_libro']; ?>, 
                                                            '<?php echo htmlspecialchars($libro['titulo'], ENT_QUOTES); ?>',
                                                            '<?php echo htmlspecialchars($libro['id_autor'], ENT_QUOTES); ?>')">
                                 Editar
                            </button>
                            <button class="btn-admin btn-delete" 
                                onclick="openDeleteModalLibro(<?php echo $libro['id_libro']; ?>, '<?php echo htmlspecialchars($libro['titulo'], ENT_QUOTES); ?>')">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($libros)): ?>
                <p style="color: #ccc; text-align: center; margin-top: 20px;">No hay libros registrados.</p>
            <?php endif; ?>
        </section>
        <?php endif; ?>
        <div class="carousel-container">
            <img id="course-image" 
                src="../public/7.jpg" 
                onerror="this.onerror=null; this.src='https://placehold.co/800x400/2c3e50/4DD0E1?text=CodeLib+Curso'"
                alt="Imagen de Curso" 
                class="carousel-image"> 
        </div>
        <p class="section-description">Explore nuestros cursos de programacion mas populares. Haz clic en cualquiera para ver los detalles, horarios y costes.</p>
        <div class="course-list">
            <article class="course-card">
                <h2 class="course-title">Master en C# con .NET 💻</h2>
                <p class="course-info">Aprende el lenguaje de Microsoft para crear aplicaciones web, de escritorio y videojuegos.</p>
                <p class="course-price">Costo: $59.99 USD</p>
                <a href="cursos.php" class="btn-course-details">Ver Detalles y Horarios</a>
            </article>
            <article class="course-card">
                <h2 class="course-title">Python para Data Science 📊</h2>
                <p class="course-info">Curso enfocado en el analisis de datos, machine learning y automatizacion con librerias clave.</p>
                <p class="course-price">Costo: $79.99 USD</p>
                <a href="cursos.php" class="btn-course-details">Ver Detalles y Horarios</a>
            </article>
            <article class="course-card">
                <h2 class="course-title">Introduccion a C++ 🚀</h2>
                <p class="course-info">Domina los fundamentos de C++ para desarrollo de sistemas de alto rendimiento y videojuegos.</p>
                <p class="course-price">Costo: $49.99 USD</p>
                <a href="cursos.php" class="btn-course-details">Ver Detalles y Horarios</a>
            </article>
            <article class="course-card">
                <h2 class="course-title">Desarrollo Web Frontend 💻</h2>
                <p class="course-info">HTML, CSS, y JavaScript de la mano para crear interfaces de usuario atractivas y funcionales.</p>
                <p class="course-price">Costo: $69.99 USD</p>
                <a href="cursos.php" class="btn-course-details">Ver Detalles y Horarios</a>
            </article>
        </div>
    </section>
    <aside class="sidebar">
        <h3 class="sidebar-title">Novedades y Ofertas</h3>
        <div class="sidebar-item">
            <p><strong>¡Nuevo Curso!</strong> 💻 Curso de Ciberseguridad Ofensiva. ¡20% de descuento por lanzamiento!</p>
            <a href="cursos.php?id=ciberseguridad" class="sidebar-link">Mas informacion</a>
        </div>
        <div class="sidebar-item">
            <p><strong>Libro del Mes:</strong> "Diseño de Algoritmos Eficientes". ¡Descarga gratuita!</p>
            <a href="libros.php#gratis" class="sidebar-link">Descargar ahora</a>
        </div>
        <div class="sidebar-item">
            <p>¿Necesitas ayuda? 💡 Consulta nuestra seccion de <a href="info.php#faq" class="sidebar-link">Preguntas Frecuentes</a>.</p>
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
        <span class="close-button" onclick="closeModal('loginModal')">&times;</span>
        <h2 class="modal-title">Iniciar Sesión en CodeLib</h2>
        <form action="../app/controllers/login_process.php" method="post" class="login-form">
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
<div id="createModal" class="modal crud-modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal('createModal')">&times;</span>
        <h2 class="modal-title" style="color: #4CAF50;">Crear Nuevo Usuario</h2>
        <form action="../app/controllers/admin_crud_actions.php" method="post" class="login-form">
            <input type="hidden" name="action" value="create_user">
            <label for="create_nombre_usuario">Nombre de Usuario:</label>
            <input type="text" id="create_nombre_usuario" name="nombre_usuario" required minlength="3" maxlength="50"> 
            <label for="create_password">Contraseña:</label>
            <input type="password" id="create_password" name="password" required minlength="6">
            <button type="submit" class="btn-login-submit" style="background: linear-gradient(to bottom, #4CAF50, #45a049);">Crear Usuario</button>
        </form>
    </div>
</div>
<div id="updateModal" class="modal crud-modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal('updateModal')">&times;</span>
        <h2 class="modal-title" style="color: #2196F3;">Actualizar Usuario</h2>
        <form action="../app/controllers/admin_crud_actions.php" method="post" class="login-form">
            <input type="hidden" name="action" value="update_user">
            <input type="hidden" id="update_id_usuario" name="id_usuario">
            <label for="update_nombre_usuario">Nuevo Nombre de Usuario:</label>
            <input type="text" id="update_nombre_usuario" name="nombre_usuario" required minlength="3" maxlength="50"> 
            <label for="update_new_password">Nueva Contraseña (Dejar vacío para no cambiar):</label>
            <input type="password" id="update_new_password" name="new_password">
            <button type="submit" class="btn-login-submit" style="background: linear-gradient(to bottom, #2196F3, #1976D2);">Guardar Cambios</button>
        </form>
    </div>
</div>
<div id="deleteModal" class="modal crud-modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal('deleteModal')">&times;</span>
    <h2 class="modal-title" style="color: #F44336;">Confirmar Eliminación de Usuario</h2>
        <p style="color: white; text-align: center; margin-bottom: 20px;">
            ¿Estás seguro de que deseas eliminar al usuario 
            <strong id="delete_username_display" style="color: #FFEB3B;">[Usuario]</strong> 
            (ID: <span id="delete_id_display" style="color: #FFEB3B;">[ID]</span>)?
            Esta acción es irreversible.
        </p>
        <form action="../app/controllers/admin_crud_actions.php" method="post" class="login-form">
            <input type="hidden" name="action" value="delete_user">
            <input type="hidden" id="delete_id_usuario" name="id_usuario">
            <button type="submit" class="btn-login-submit" style="background: linear-gradient(to bottom, #F44336, #D32F2F);">
                Confirmar Eliminación
            </button>
        </form>
    </div>
</div>
<div id="createModalLenguaje" class="modal crud-modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal('createModalLenguaje')">&times;</span>
        <h2 class="modal-title" style="color: #4CAF50;">Crear Nuevo Lenguaje</h2>
        <form action="../app/controllers/admin_crud_actions.php" method="post" class="login-form">
            <input type="hidden" name="action" value="create_lenguaje">
            <label for="create_nombre_lenguaje">Nombre del Lenguaje:</label>
            <input type="text" id="create_nombre_lenguaje" name="lenguaje" required minlength="1" maxlength="50"> 
            <button type="submit" class="btn-login-submit" style="background: linear-gradient(to bottom, #4CAF50, #45a049);">Crear Lenguaje</button>
        </form>
    </div>
</div>
<div id="updateModalLenguaje" class="modal crud-modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal('updateModalLenguaje')">&times;</span>
        <h2 class="modal-title" style="color: #2196F3;">Actualizar Lenguaje</h2>
        <form action="../app/controllers/admin_crud_actions.php" method="post" class="login-form">
            <input type="hidden" name="action" value="update_lenguaje">
            <input type="hidden" id="update_id_lenguaje" name="id_lenguaje">
            <label for="update_nombre_lenguaje">Nuevo Nombre del Lenguaje:</label>
            <input type="text" id="update_nombre_lenguaje" name="lenguaje" required minlength="1" maxlength="50"> 
            <button type="submit" class="btn-login-submit" style="background: linear-gradient(to bottom, #2196F3, #1976D2);">Guardar Cambios</button>
        </form>
    </div>
</div>
<div id="deleteModalLenguaje" class="modal crud-modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal('deleteModalLenguaje')">&times;</span>
        <h2 class="modal-title" style="color: #F44336;">Confirmar Eliminación de Lenguaje</h2>
        <p style="color: white; text-align: center; margin-bottom: 20px;">
            ¿Estás seguro de que deseas eliminar el lenguaje? 
            <strong id="delete_nombre_lenguaje_display" style="color: #FFEB3B;">[Lenguaje]</strong> 
            (ID: <span id="delete_id_lenguaje_display" style="color: #FFEB3B;">[ID]</span>)?
            Esta acción es irreversible.
        </p>
        <form action="../app/controllers/admin_crud_actions.php" method="post" class="login-form">
            <input type="hidden" name="action" value="delete_lenguaje">
            <input type="hidden" id="delete_id_lenguaje" name="id_lenguaje">
            <button type="submit" class="btn-login-submit" style="background: linear-gradient(to bottom, #F44336, #D32F2F);">
                Confirmar Eliminación
            </button>
        </form>
    </div>
</div>
<div id="createModalLibro" class="modal crud-modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal('createModalLibro')">&times;</span>
        <h2 class="modal-title" style="color: #4CAF50;">Crear Nuevo Libro</h2>
        <form action="../app/controllers/admin_crud_actions.php" method="post" class="login-form">
            <input type="hidden" name="action" value="create_libro">
            <label for="create_titulo_libro">Título del Libro:</label>
            <input type="text" id="create_titulo_libro" name="titulo" required minlength="1" maxlength="100"> 
            <label for="create_autor_libro">Autor (id_autor):</label>
            <input type="text" id="create_autor_libro" name="id_autor" required minlength="1" maxlength="100">
            <button type="submit" class="btn-login-submit" style="background: linear-gradient(to bottom, #4CAF50, #45a049);">Crear Libro</button>
        </form>
    </div>
</div>
<div id="updateModalLibro" class="modal crud-modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal('updateModalLibro')">&times;</span>
        <h2 class="modal-title" style="color: #2196F3;">Actualizar Libro</h2>
        <form action="../app/controllers/admin_crud_actions.php" method="post" class="login-form">
            <input type="hidden" name="action" value="update_libro">
            <input type="hidden" id="update_id_libro" name="id_libro">
            <label for="update_titulo_libro">Nuevo Título:</label>
            <input type="text" id="update_titulo_libro" name="titulo" required minlength="1" maxlength="100"> 
            
            <label for="update_autor_libro">Nuevo Autor (id_autor):</label>
            <input type="text" id="update_autor_libro" name="id_autor" required minlength="1" maxlength="100">
            <button type="submit" class="btn-login-submit" style="background: linear-gradient(to bottom, #2196F3, #1976D2);">Guardar Cambios</button>
        </form>
    </div>
</div>
<div id="deleteModalLibro" class="modal crud-modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal('deleteModalLibro')">&times;</span>
        <h2 class="modal-title" style="color: #F44336;">Confirmar Eliminación de Libro</h2>
        <p style="color: white; text-align: center; margin-bottom: 20px;">
            ¿Estás seguro de que deseas eliminar el libro? 
            <strong id="delete_titulo_libro_display" style="color: #FFEB3B;">[Libro]</strong> 
            (ID: <span id="delete_id_libro_display" style="color: #FFEB3B;">[ID]</span>)?
            Esta acción es irreversible.
        </p>
        <form action="../app/controllers/admin_crud_actions.php" method="post" class="login-form">
            <input type="hidden" name="action" value="delete_libro">
            <input type="hidden" id="delete_id_libro" name="id_libro">
            <button type="submit" class="btn-login-submit" style="background: linear-gradient(to bottom, #F44336, #D32F2F);">
                Confirmar Eliminación
            </button>
        </form>
    </div>
</div>
<script>
    const loginModal = document.getElementById('loginModal');
    const createModal = document.getElementById('createModal');
    const updateModal = document.getElementById('updateModal');
    const deleteModal = document.getElementById('deleteModal');
    const createModalLenguaje = document.getElementById('createModalLenguaje');
    const updateModalLenguaje = document.getElementById('updateModalLenguaje');
    const deleteModalLenguaje = document.getElementById('deleteModalLenguaje');
    const createModalLibro = document.getElementById('createModalLibro');
    const updateModalLibro = document.getElementById('updateModalLibro');
    const deleteModalLibro = document.getElementById('deleteModalLibro');
    const btnLogin = document.getElementById('btnLogin');
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }
    if (btnLogin) { 
        btnLogin.onclick = function() { loginModal.style.display = "block"; } 
    }
    window.onclick = function(event) {
        if (event.target == loginModal) { closeModal('loginModal'); }
        if (event.target == createModal) { closeModal('createModal'); }
        if (event.target == updateModal) { closeModal('updateModal'); }
        if (event.target == deleteModal) { closeModal('deleteModal'); }
        if (event.target == createModalLenguaje) { closeModal('createModalLenguaje'); }
        if (event.target == updateModalLenguaje) { closeModal('updateModalLenguaje'); }
        if (event.target == deleteModalLenguaje) { closeModal('deleteModalLenguaje'); }
        if (event.target == createModalLibro) { closeModal('createModalLibro'); }
        if (event.target == updateModalLibro) { closeModal('updateModalLibro'); }
        if (event.target == deleteModalLibro) { closeModal('deleteModalLibro'); }
    }
    function openCreateModal() {
        createModal.style.display = "block";
    }
    function openUpdateModal(id, username) {
        document.getElementById('update_id_usuario').value = id;
        document.getElementById('update_nombre_usuario').value = username;
        document.getElementById('update_new_password').value = ''; 
        updateModal.style.display = "block";
    }
    function openDeleteModal(id, username) {
        document.getElementById('delete_id_usuario').value = id;
        document.getElementById('delete_id_display').textContent = id;
        document.getElementById('delete_username_display').textContent = username;
        deleteModal.style.display = "block";
    }
    function openCreateModalLenguaje() {
        document.getElementById('create_nombre_lenguaje').value = ''; 
        createModalLenguaje.style.display = "block";
    }
    function openUpdateModalLenguaje(id, lenguaje) {
        document.getElementById('update_id_lenguaje').value = id;
        document.getElementById('update_nombre_lenguaje').value = lenguaje;
        updateModalLenguaje.style.display = "block";
    }
    function openDeleteModalLenguaje(id, lenguaje) {
        document.getElementById('delete_id_lenguaje').value = id;
        document.getElementById('delete_id_lenguaje_display').textContent = id;
        document.getElementById('delete_nombre_lenguaje_display').textContent = lenguaje;
        deleteModalLenguaje.style.display = "block";
    }
    function openCreateModalLibro() {
        document.getElementById('create_titulo_libro').value = ''; 
        document.getElementById('create_autor_libro').value = ''; 
        createModalLibro.style.display = "block";
    }
    function openUpdateModalLibro(id, titulo, autor) {
        document.getElementById('update_id_libro').value = id;
        document.getElementById('update_titulo_libro').value = titulo;
        document.getElementById('update_autor_libro').value = autor;
        updateModalLibro.style.display = "block";
    }
    function openDeleteModalLibro(id, titulo) {
        document.getElementById('delete_id_libro').value = id;
        document.getElementById('delete_id_libro_display').textContent = id;
        document.getElementById('delete_titulo_libro_display').textContent = titulo;
        deleteModalLibro.style.display = "block";
    }
</script>
</body>
</html>