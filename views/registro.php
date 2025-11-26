<?php

session_start();

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario - CodeLib</title>
    <link rel="stylesheet" href="../public/Biblio.css">
    <style>
        body {
            background-color: #1a1a1a;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        
        .registro-container {
            background-color: #2c3e50;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            width: 350px;
            margin-top: 10px;
        }
        
        .registro-container h2 {
            color: #4DD0E1;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .registro-form label {
            color: white;
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .registro-form input[type="text"],
        .registro-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
        }
        
        .btn-registro-submit {
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
        
        .btn-registro-submit:hover {
            background: #3bbac9;
        }
        
        .error-message {
            background-color: #f44336;
            color: white;
            padding: 12px;
            margin-bottom: 20px;
            width: 350px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            margin-bottom: 20px;
            width: 350px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message">
            ERROR: <?php echo htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="success-message">
            EXITO: <?php echo htmlspecialchars($_SESSION['success']); ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="registro-container">
        <h2>Crear Cuenta en CodeLib</h2>
        <form action="../app/controllers/registro_process.php" method="post" class="registro-form">
            <label for="new_username">Nombre de Usuario:</label>
            <input type="text" id="new_username" name="nombre_usuario" required>

            <label for="new_password">Contraseña:</label>
            <input type="password" id="new_password" name="password" required>

            <button type="submit" class="btn-registro-submit">Registrarse</button>
            <p style="text-align: center; margin-top: 15px;"><a href="index.php" style="color: #4DD0E1;">Volver al Inicio</a></p>
        </form>
    </div>
</body>

</html>