<?php
session_start();
require_once 'clases/myConexionPDO.php';
require_once 'clases/CSRFProteccion.php';
require_once 'vendor/autoload.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

// Si no completó la fase 1 del login, redirigir
if (!isset($_SESSION['login_fase1']) || $_SESSION['login_fase1'] !== 'SI') {
    header("Location: login.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    CSRFProtection::verificarFormulario();

    $codigo = isset($_POST['codigo_2fa']) ? trim($_POST['codigo_2fa']) : '';

    $clasePDO = new mod_db();
    $conn = $clasePDO->getConexion();

    $stmt = $conn->prepare("SELECT secret_2fa FROM usuarios WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['usuario_id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetchObject();

    $g = new GoogleAuthenticator();

    if ($user && $g->checkCode($user->secret_2fa, $codigo)) {
        // Fase 2 completada, acceso total
        $_SESSION['autenticado'] = 'SI';
        $_SESSION['2fa_verificado'] = 'SI';
        unset($_SESSION['login_fase1']);

        header("Location: dashboard.php");
        exit;
    } else {
        $error = 'Código incorrecto. Intenta de nuevo.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación 2FA</title>
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
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 22px;
        }

        p {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .campo {
            margin-bottom: 15px;
            text-align: left;
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
            font-size: 20px;
            text-align: center;
            letter-spacing: 5px;
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

        .error {
            background: #ffe0e0;
            color: red;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .usuario {
            background: #e8f4fd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            color: #4a90e2;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Verificación 2FA</h1>

    <div class="usuario">
        Usuario: <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
    </div>

    <p>Abre Google Authenticator e ingresa el código de 6 dígitos</p>

    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="verificar2fa.php" method="POST">
        <?php echo CSRFProtection::campoHidden(); ?>

        <div class="campo">
            <label>Código de verificación</label>
            <input type="text" name="codigo_2fa" maxlength="6" 
                   placeholder="000000" autofocus required>
        </div>

        <button type="submit" class="btn">Verificar</button>
    </form>
</div>

</body>
</html>