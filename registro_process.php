<?php
require 'Database.php'; 
session_start(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($nombre_usuario) || empty($password)) {
        $_SESSION['error'] = "ERROR: Todos los campos son obligatorios.";
        header('Location: registro.php');
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT); 

    try {
        $db = Database::getInstance();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("INSERT INTO usuario (nombre_usuario, password) VALUES (?, ?)");

        if ($stmt->execute([$nombre_usuario, $hashed_password])) {
            $_SESSION['success'] = "¡Registro exitoso! Ya puedes iniciar sesión.";
            header('Location: index.php');
            exit;
        } else {
            $errorInfo = $stmt->errorInfo();
           
            if ($errorInfo[0] === '23000') {
                $_SESSION['error'] = "ERROR: El nombre de usuario ya está en uso.";
            } else {
                $_SESSION['error'] = "ERROR: No se pudo registrar al usuario. Detalle: " . $errorInfo[2];
            }
            header('Location: registro.php');
            exit;
        }

    } catch (\PDOException $e) {
        $_SESSION['error'] = "ERROR: Ha ocurrido un error en el sistema. Intente de nuevo más tarde.";
        header('Location: registro.php');
        exit;
    }
} else {
    header('Location: registro.php');
    exit;
}
?>