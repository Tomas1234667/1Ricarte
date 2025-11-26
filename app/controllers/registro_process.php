<?php
require '../models/Database.php'; 
session_start(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    if (strlen($nombre_usuario) < 3 || strlen($nombre_usuario) > 50) {
        $_SESSION['error'] = "ERROR: El usuario debe tener entre 3 y 50 caracteres.";
        header('Location: ../../views/registro.php');
        exit;
    }

    if (empty($nombre_usuario) || empty($password)) {
        $_SESSION['error'] = "ERROR: Todos los campos son obligatorios.";
    header('Location: ../../views/registro.php');
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT); 

    try {
        $db = Database::getInstance();
        $pdo = $db->getConnection();

        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE nombre_usuario = ?");
        $checkStmt->execute([$nombre_usuario]);
        if ($checkStmt->fetchColumn() > 0) {
            $_SESSION['error'] = "ERROR: El nombre de usuario ya está en uso.";
       header('Location: ../../views/registro.php');
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO usuario (nombre_usuario, password) VALUES (?, ?)");

        if ($stmt->execute([$nombre_usuario, $hashed_password])) {
            $_SESSION['success'] = "¡Registro exitoso! Ya puedes iniciar sesión.";

            header('Location: ../../views/index.php');
            exit;
        } else {
            $errorInfo = $stmt->errorInfo();
            $_SESSION['error'] = "ERROR: No se pudo registrar al usuario. Detalle: " . $errorInfo[2];
    header('Location: ../../views/registro.php');
            exit;
        }

    } catch (\PDOException $e) {
        error_log("Error de registro: " . $e->getMessage());
        $_SESSION['error'] = "ERROR: Ha ocurrido un error en el sistema. Intente de nuevo más tarde.";
   header('Location: ../../views/registro.php');
        exit;
    }
} else {
   header('Location: ../../views/registro.php');
    exit;
}