<?php
session_start();
require_once 'clases/myConexionPDO.php';

// Verificar que completó ambas fases
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== 'SI') {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['2fa_verificado']) || $_SESSION['2fa_verificado'] !== 'SI') {
    header("Location: verificar_2fa.php");
    exit;
}

// Obtener datos del usuario
$clasePDO = new mod_db();
$conn = $clasePDO->getConexion();

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['usuario_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetchObject();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
        }

        .navbar {
            background: #4a90e2;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .navbar h2 {
            font-size: 18px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
        }

        .navbar a:hover {
            background: rgba(255,255,255,0.3);
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .bienvenida {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .bienvenida h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .bienvenida p {
            color: #666;
            font-size: 14px;
        }

        .info-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .info-card h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 16px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .info-row {
            display: flex;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .info-label {
            color: #888;
            width: 120px;
            flex-shrink: 0;
        }

        .info-valor {
            color: #333;
            font-weight: bold;
        }

        .badge {
            background: #e8f4fd;
            color: #4a90e2;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .sesiones {
            background: #e8fde8;
            padding: 15px;
            border-radius: 5px;
            font-size: 13px;
            color: #2d7a2d;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h2>Lab2FA</h2>
    <a href="salir.php">Cerrar Sesión</a>
</div>

<div class="container">

    <div class="bienvenida">
        <h1>Bienvenido, <?php echo htmlspecialchars($user->Nombre); ?>!</h1>
        <p>Has iniciado sesión correctamente con autenticación de dos factores.</p>
    </div>

    <div class="info-card">
        <h3>Información del Usuario</h3>
        <div class="info-row">
            <span class="info-label">Nombre:</span>
            <span class="info-valor"><?php echo htmlspecialchars($user->Nombre . ' ' . $user->Apellido); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Usuario:</span>
            <span class="info-valor"><?php echo htmlspecialchars($user->Usuario); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Correo:</span>
            <span class="info-valor"><?php echo htmlspecialchars($user->Correo); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Sexo:</span>
            <span class="info-valor"><?php echo $user->Sexo === 'M' ? 'Masculino' : 'Femenino'; ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">2FA:</span>
            <span class="badge">Activo</span>
        </div>
    </div>

    <div class="info-card">
        <h3>Estado de la Sesión</h3>
        <div class="sesiones">
            Fase 1 (Usuario + Contraseña): Verificada<br>
            Fase 2 (Código 2FA): Verificada<br>
            Acceso completo concedido
        </div>
    </div>

</div>

</body>
</html>