<?php
session_start();

// Si no viene del registro, redirigir
if (!isset($_SESSION['qr_url']) || !isset($_SESSION['usuario_registro'])) {
    header("Location: registro.php");
    exit;
}

$qr_url = $_SESSION['qr_url'];
$usuario = $_SESSION['usuario_registro'];
$hash = $_SESSION['hash_generado'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Google Authenticator</title>
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
            line-height: 1.5;
        }

        .qr-container {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: inline-block;
        }

        .qr-container img {
            display: block;
        }

        .usuario {
            background: #e8f4fd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            color: #4a90e2;
            font-weight: bold;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: #4a90e2;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 10px;
        }

        .btn:hover {
            background: #357abd;
        }

        .pasos {
            text-align: left;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .pasos li {
            font-size: 13px;
            color: #555;
            margin-bottom: 8px;
            margin-left: 20px;
        }

        .hash-section {
            background: #f0fff4;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: left;
        }

        .hash-section h3 {
            color: #2d6a4f;
            font-size: 15px;
            margin-bottom: 10px;
        }

        .hash-section p {
            font-size: 12px;
            color: #555;
            margin-bottom: 6px;
            word-break: break-all;
        }

        .hash-section .estado {
            color: #28a745;
            font-weight: bold;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Configura tu 2FA</h1>

    <div class="usuario">
        Usuario: <?php echo htmlspecialchars($usuario); ?>
    </div>

    <p>Escanea este código QR con la app <strong>Google Authenticator</strong></p>

    <div class="qr-container">
        <img src="<?php echo $qr_url; ?>" alt="Código QR">
    </div>

    <div class="pasos">
        <strong>Pasos:</strong>
        <ol>
            <li>Descarga Google Authenticator en tu celular</li>
            <li>Abre la app y toca el botón +</li>
            <li>Selecciona "Escanear código QR"</li>
            <li>Apunta la cámara a este código</li>
            <li>Listo, ya puedes iniciar sesión</li>
        </ol>
    </div>

    <?php if ($hash): ?>
    <div class="hash-section">
        <h3>Hash de Contraseña Generado</h3>
        <p><strong>Algoritmo:</strong> bcrypt (PASSWORD_BCRYPT)</p>
        <p><strong>Hash:</strong> <?= htmlspecialchars($hash) ?></p>
        <p class="estado">Tu contraseña fue protegida correctamente.</p>
    </div>
    <?php endif; ?>

    <a href="login.php" class="btn">Ir al Login</a>
</div>

</body>
</html>