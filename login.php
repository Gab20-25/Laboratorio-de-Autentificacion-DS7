<?php
session_start();
require_once 'clases/CSRFProteccion.php';

// Si ya está autenticado completamente, ir al dashboard
if (isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === 'SI') {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 22px;
        }

        .campo {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #4a90e2;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #4a90e2;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn:hover {
            background: #357abd;
        }

        .mensaje {
            text-align: center;
            margin-top: 10px;
            font-size: 13px;
        }

        .mensaje a {
            color: #4a90e2;
        }

        .error {
            background: #ffe0e0;
            color: red;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 13px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Iniciar Sesión</h1>

    <?php if (isset($_SESSION['error_login'])): ?>
        <div class="error">
            <?php echo $_SESSION['error_login']; unset($_SESSION['error_login']); ?>
        </div>
    <?php endif; ?>

    <form id="form-login" action="procesarLogin.php" method="POST">
        <?php echo CSRFProtection::campoHidden(); ?>

        <div class="campo">
            <label>Usuario</label>
            <input type="text" name="usuario" id="usuario" placeholder="Tu usuario" required>
        </div>

        <div class="campo">
            <label>Contraseña</label>
            <input type="password" name="clave" id="clave" placeholder="Tu contraseña" required>
        </div>

        <button type="submit" class="btn">Entrar</button>

        <div class="mensaje">
            ¿No tienes cuenta? <a href="registro.php">Regístrate</a>
        </div>
    </form>
</div>

</body>
</html>