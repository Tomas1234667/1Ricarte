<?php
require '../models/Database.php'; 
session_start(); 

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    $_SESSION['error'] = "Acceso denegado. Solo el administrador puede realizar esta acción.";
    header('Location: ../../views/index.php');
    exit;
}

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
} catch (\PDOException $e) {
    $_SESSION['error'] = "Error de conexión a la base de datos: " . $e->getMessage();
    header('Location: ../../views/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'create_user':
            $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
            $password = $_POST['password'] ?? '';
            if ($nombre_usuario === "" || $password === "") {
                $_SESSION['error'] = "Error al crear usuario: El usuario y la contraseña son obligatorios.";
                break;
            }
            try {
                $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE nombre_usuario = ?");
                $stmt_check->execute([$nombre_usuario]);
                if ($stmt_check->fetchColumn() > 0) {
                    $_SESSION['error'] = "Error: El nombre de usuario '$nombre_usuario' ya está en uso.";
                    break;
                }
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuario (nombre_usuario, password) VALUES (?, ?)");
                $stmt->execute([$nombre_usuario, $hashed_password]);
                $_SESSION['success'] = "Usuario '$nombre_usuario' creado correctamente.";
            } catch (\PDOException $e) {
                $_SESSION['error'] = "Error de base de datos al crear usuario: " . $e->getMessage();
            }
            break;
        
        case 'update_user':
            $id_usuario = $_POST['id_usuario'] ?? null;
            $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
            $new_password = $_POST['new_password'] ?? '';
            if (!$id_usuario || $nombre_usuario === "") {
                $_SESSION['error'] = "Error: Faltan datos para actualizar el usuario.";
                break;
            }
            try {
                if ($new_password !== "") {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE usuario SET nombre_usuario = ?, password = ? WHERE id_usuario = ?");
                    $stmt->execute([$nombre_usuario, $hashed_password, $id_usuario]);
                } else {
                    $stmt = $pdo->prepare("UPDATE usuario SET nombre_usuario = ? WHERE id_usuario = ?");
                    $stmt->execute([$nombre_usuario, $id_usuario]);
                }
                $_SESSION['success'] = "Usuario con ID $id_usuario actualizado correctamente.";
            } catch (\PDOException $e) {
                $_SESSION['error'] = "Error al actualizar usuario: " . $e->getMessage();
            }
            break;

        case 'delete_user':
            $id_usuario = $_POST['id_usuario'] ?? null;
            if (!$id_usuario) {
                $_SESSION['error'] = "Error: ID de usuario para eliminar no proporcionado.";
                break;
            }
            try {
                $stmt = $pdo->prepare("DELETE FROM usuario WHERE id_usuario = ?");
                $stmt->execute([$id_usuario]);
                $_SESSION['success'] = "Usuario con ID $id_usuario eliminado correctamente.";
            } catch (\PDOException $e) {
                $_SESSION['error'] = "Error al eliminar usuario: " . $e->getMessage();
            }
            break;

        case 'create_lenguaje':
            $lenguaje = trim($_POST['lenguaje'] ?? '');
            if ($lenguaje === "") {
                $_SESSION['error'] = "Error al crear lenguaje: El nombre es obligatorio.";
                break;
            }
            try {
                $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM lenguaje WHERE lenguaje = ?");
                $stmt_check->execute([$lenguaje]);
                if ($stmt_check->fetchColumn() > 0) {
                    $_SESSION['error'] = "Error: El lenguaje '$lenguaje' ya existe.";
                    break;
                }
                $stmt = $pdo->prepare("INSERT INTO lenguaje (lenguaje) VALUES (?)");
                $stmt->execute([$lenguaje]);
                $_SESSION['success'] = "Lenguaje '$lenguaje' creado correctamente.";
            } catch (\PDOException $e) {
                $_SESSION['error'] = "Error de base de datos al crear lenguaje: " . $e->getMessage();
            }
            break;

        case 'update_lenguaje':
            $id_lenguaje = $_POST['id_lenguaje'] ?? null;
            $lenguaje = trim($_POST['lenguaje'] ?? '');
            if (!$id_lenguaje || $lenguaje === "") {
                $_SESSION['error'] = "Error: Faltan datos para actualizar el lenguaje.";
                break;
            }
            try {
                $stmt = $pdo->prepare("UPDATE lenguaje SET lenguaje = ? WHERE id_lenguaje = ?");
                $stmt->execute([$lenguaje, $id_lenguaje]);
                $_SESSION['success'] = "Lenguaje con ID $id_lenguaje actualizado correctamente.";
            } catch (\PDOException $e) {
                $_SESSION['error'] = "Error al actualizar lenguaje: " . $e->getMessage();
            }
            break;

        case 'delete_lenguaje':
            $id_lenguaje = $_POST['id_lenguaje'] ?? null;
            if (!$id_lenguaje) {
                $_SESSION['error'] = "Error: ID de lenguaje para eliminar no proporcionado.";
                break;
            }
            try {
                $stmt = $pdo->prepare("DELETE FROM lenguaje WHERE id_lenguaje = ?");
                $stmt->execute([$id_lenguaje]);
                $_SESSION['success'] = "Lenguaje con ID $id_lenguaje eliminado correctamente.";
            } catch (\PDOException $e) {
                $_SESSION['error'] = "Error al eliminar lenguaje: " . $e->getMessage();
            }
            break;

        case 'create_libro':
            $titulo = trim($_POST['titulo'] ?? '');
            $id_lenguaje = $_POST['id_lenguaje'] ?? null;
            $id_autor = $_POST['id_autor'] ?? null;
            $id_editorial = $_POST['id_editorial'] ?? null;
            $precio = $_POST['precio'] ?? null;
            $stock = $_POST['stock'] ?? null;
            $id_usuario = $_SESSION['id_usuario'] ?? null;
            if ($titulo === "") {
                $_SESSION['error'] = "Error al crear libro: El título es obligatorio.";
                break;
            }
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO libro (titulo, id_lenguaje, id_autor, id_editorial, precio, stock, id_usuario)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$titulo, $id_lenguaje, $id_autor, $id_editorial, $precio, $stock, $id_usuario]);
                $_SESSION['success'] = "Libro '$titulo' creado correctamente.";
            } catch (\PDOException $e) {
                $_SESSION['error'] = "Error al crear libro: " . $e->getMessage();
            }
            break;

        case 'update_libro':
            $id_libro = $_POST['id_libro'] ?? null;
            $titulo = trim($_POST['titulo'] ?? '');
            $id_lenguaje = $_POST['id_lenguaje'] ?? null;
            $id_autor = $_POST['id_autor'] ?? null;
            $id_editorial = $_POST['id_editorial'] ?? null;
            $precio = $_POST['precio'] ?? null;
            $stock = $_POST['stock'] ?? null;
            if (!$id_libro || $titulo === "") {
                $_SESSION['error'] = "Error: Faltan datos para actualizar el libro.";
                break;
            }
            try {
                $stmt = $pdo->prepare("
                    UPDATE libro
                    SET titulo = ?, id_lenguaje = ?, id_autor = ?, id_editorial = ?, precio = ?, stock = ?
                    WHERE id_libro = ?
                ");
                $stmt->execute([$titulo, $id_lenguaje, $id_autor, $id_editorial, $precio, $stock, $id_libro]);
                $_SESSION['success'] = "Libro con ID $id_libro actualizado correctamente.";
            } catch (\PDOException $e) {
                $_SESSION['error'] = "Error al actualizar libro: " . $e->getMessage();
            }
            break;

        case 'delete_libro':
            $id_libro = $_POST['id_libro'] ?? null;
            if (!$id_libro) {
                $_SESSION['error'] = "Error: ID de libro para eliminar no proporcionado.";
                break;
            }
            try {
                $stmt = $pdo->prepare("DELETE FROM libro WHERE id_libro = ?");
                $stmt->execute([$id_libro]);
                $_SESSION['success'] = "Libro con ID $id_libro eliminado correctamente.";
            } catch (\PDOException $e) {
                $_SESSION['error'] = "Error al eliminar libro: " . $e->getMessage();
            }
            break;

        default:
            $_SESSION['error'] = "Acción desconocida.";
            break;
    }
}

header('Location: ../../views/index.php');
exit;
?>