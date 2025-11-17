<?php
require 'Database.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    
    header("Location: index.php");
    exit();
}

// 1. FILTRO DE SANEAMIENTO (Limpiar la entrada)
// Saneamos el nombre de usuario de cualquier caracter que no deba estar
// El password se pasa sin sanear porque es un hash, pero se valida después.
// 🟢 CORRECCIÓN 1: Se usa la variable de POST 'nombre_usuario' del formulario.
$nombre_usuario = trim($_POST['nombre_usuario'] ?? ''); 
$password = $_POST['password'] ?? ''; 

// 2. FILTRO DE VALIDACIÓN (Asegurar que los campos no estén vacíos)
if (empty($nombre_usuario) || empty($password)) {
    $_SESSION['login_error'] = "Por favor, ingrese usuario y contraseña.";
    header("Location: index.php");
    exit();
}

// 3. FILTRO DE VALIDACIÓN ADICIONAL (Opcional: Limitar longitud o caracteres permitidos)
// Esto previene entradas demasiado largas que puedan sobrecargar el sistema.
if (strlen($nombre_usuario) < 3 || strlen($nombre_usuario) > 50) {
    $_SESSION['login_error'] = "El usuario debe tener entre 3 y 50 caracteres.";
    // Redirección corregida a index.php
    header("Location: index.php"); 
    exit();
}
// También se podría usar filter_var para validar emails si el usuario fuera un email.
// $username = filter_var($username, FILTER_SANITIZE_EMAIL); 

try {
    
    $db = Database::getInstance();
    $pdo = $db->getConnection();

    // FILTRO DE INYECCIÓN SQL (Ya implementado correctamente con prepare/execute)
    // 🟢 CORRECCIÓN 2: Se usa la tabla 'usuario' y la columna 'nombre_usuario' de la DB.
    $stmt = $pdo->prepare("SELECT id_usuario, nombre_usuario, password FROM usuario WHERE nombre_usuario = :nombre_usuario");
    
    
    // Se usa el marcador de posición (placeholder) :nombre_usuario
    $stmt->execute(['nombre_usuario' => $nombre_usuario]); 
    
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    
    if ($user && password_verify($password, $user['password'])) {
        
        $_SESSION['user_id'] = $user['id_usuario']; // Se usa 'id_usuario' de la DB
        // 🟢 CORRECCIÓN 3: Se usa 'nombre_usuario' de la DB para la sesión.
        $_SESSION['username'] = $user['nombre_usuario']; 
        $_SESSION['logged_in'] = true;
        
        // Redirigir al usuario autenticado
        header("Location: index.php"); 
        exit();
        
    } else {
        
        $_SESSION['login_error'] = "Usuario o contraseña incorrectos.";
        header("Location: index.php");
        exit();
    }

} catch (\PDOException $e) {
    
    error_log("Error de autenticación: " . $e->getMessage());
    $_SESSION['login_error'] = "Ha ocurrido un error en el sistema. Intente de nuevo más tarde.";
    header("Location: index.php");
    exit();
}
?>