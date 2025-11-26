<?php
require '../models/Database.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header('Location: ../../views/index.php');
    exit();
}

$nombre_usuario = trim($_POST['nombre_usuario'] ?? ''); 
$password = $_POST['password'] ?? ''; 

if (empty($nombre_usuario) || empty($password)) {
    $_SESSION['error'] = "Por favor, ingrese usuario y contraseña.";
    $_SESSION['show_login'] = true; 
    header('Location: ../../views/index.php');
    exit();
}

if (strlen($nombre_usuario) < 3 || strlen($nombre_usuario) > 50) {
    $_SESSION['error'] = "El usuario debe tener entre 3 y 50 caracteres.";
    $_SESSION['show_login'] = true; 
    header('Location: ../../views/index.php');
    exit();
}

try {
    
    $db = Database::getInstance();
    $pdo = $db->getConnection();

    $stmt = $pdo->prepare("SELECT id_usuario, nombre_usuario, password FROM usuario WHERE nombre_usuario = :nombre_usuario");
    $stmt->execute(['nombre_usuario' => $nombre_usuario]); 
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    
    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id_usuario']; 
        $_SESSION['username'] = $user['nombre_usuario']; 
        $_SESSION['logged_in'] = true;
        
        if ($user['nombre_usuario'] === 'Admin') {
            $_SESSION['is_admin'] = true;
        } else {
            $_SESSION['is_admin'] = false;
        }
        unset($_SESSION['error']);
        unset($_SESSION['show_login']);

        header('Location: ../../views/index.php');
        exit();
        
    } else {
        $_SESSION['error'] = "Usuario o contraseña incorrectos.";
        $_SESSION['show_login'] = true; 
        header('Location: ../../views/index.php');
        exit();
    }

} catch (\PDOException $e) {
    
    error_log("Error de autenticación: " . $e->getMessage());
    $_SESSION['error'] = "Ha ocurrido un error en el sistema. Intente de nuevo más tarde.";
    $_SESSION['show_login'] = true; 
    header('Location: ../../views/index.php');
    exit();
}