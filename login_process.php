<?php
require 'Database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$nombre_usuario = trim($_POST['nombre_usuario'] ?? ''); 
$password_ingresada = $_POST['password'] ?? ''; 

if (empty($nombre_usuario) || empty($password_ingresada)) {
    $_SESSION['error'] = "ERROR: Debe ingresar el usuario y la contraseña.";
    header("Location: index.php");
    exit();
}

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();

    $stmt = $pdo->prepare("SELECT id_usuario, nombre_usuario, password FROM usuario WHERE nombre_usuario = ?");
    $stmt->execute([$nombre_usuario]);
    
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC); 

    if ($usuario) {
   
        if (password_verify($password_ingresada, $usuario['password'])) {
            
            $_SESSION['id_usuario'] = $usuario['id_usuario']; 
            $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
            $_SESSION['loggedin'] = true;
            
            $_SESSION['success'] = "¡Bienvenido, " . htmlspecialchars($usuario['nombre_usuario']) . "!";
            header("Location: index.php"); 
            exit();
            
        } else {
            $_SESSION['error'] = "ERROR: Usuario o contraseña incorrectos.";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "ERROR: Usuario o contraseña incorrectos.";
        header("Location: index.php");
        exit();
    }

} catch (\PDOException $e) {
    error_log("Error de autenticación: " . $e->getMessage());
    $_SESSION['error'] = "ERROR: Ha ocurrido un error en el sistema. Intente de nuevo más tarde.";
    header("Location: index.php");
    exit();
}
?>