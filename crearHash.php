<?php
session_start();

$hash_generado = '';
$resultado_verificacion = '';
$resultado_tipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['accion'])) {

        if ($_POST['accion'] === 'generar') {
            $clave = $_POST['clave'];
            $options = ['cost' => 13];
            $hash_generado = password_hash($clave, PASSWORD_BCRYPT, $options);
        }

        if ($_POST['accion'] === 'verificar') {
            $clave = $_POST['clave_verificar'];
            $hash = $_POST['hash_verificar'];

            if (password_verify($clave, $hash)) {
                $resultado_verificacion = 'La contrasena coincide con el hash';
                $resultado_tipo = 'exito';
            } else {
                $resultado_verificacion = 'La contrasena NO coincide con el hash';
                $resultado_tipo = 'fallo';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Herramienta de Hash</title>
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
            padding: 30px 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
            font-size: 22px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 16px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
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

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        textarea {
            height: 80px;
            resize: none;
            font-family: monospace;
        }

        input:focus, textarea:focus {
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
            font-size: 15px;
            cursor: pointer;
        }

        .btn:hover {
            background: #357abd;
        }

        .resultado {
            padding: 12px;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 13px;
            word-break: break-all;
            border: 1px solid #c0d8f0;
            background: #f0f7ff;
            color: #333;
        }

        .resultado.hash {
            font-family: monospace;
        }

        .exito {
            background: #e8fde8;
            border-color: #a0d8a0;
            color: #2d7a2d;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .fallo {
            background: #ffe0e0;
            border-color: #f0a0a0;
            color: red;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .volver {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #4a90e2;
            font-size: 14px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Herramienta de Hash</h1>

    <div class="card">
        <h3>Generar Hash de Contrasena</h3>
        <form method="POST">
            <input type="hidden" name="accion" value="generar">
            <div class="campo">
                <label>Contrasena</label>
                <input type="text" name="clave" placeholder="Escribe una contrasena" required>
            </div>
            <button type="submit" class="btn">Generar Hash</button>
        </form>

        <?php if ($hash_generado): ?>
            <div class="resultado hash">
                <strong>Hash generado:</strong><br>
                <?php echo htmlspecialchars($hash_generado); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3>Verificar Contrasena contra Hash</h3>
        <form method="POST">
            <input type="hidden" name="accion" value="verificar">
            <div class="campo">
                <label>Contrasena</label>
                <input type="text" name="clave_verificar" placeholder="Escribe la contrasena" required>
            </div>
            <div class="campo">
                <label>Hash</label>
                <textarea name="hash_verificar" placeholder="Pega el hash aqui" required></textarea>
            </div>
            <button type="submit" class="btn">Verificar</button>
        </form>

        <?php if ($resultado_verificacion): ?>
            <div class="resultado <?php echo $resultado_tipo; ?>">
                <?php echo $resultado_verificacion; ?>
            </div>
        <?php endif; ?>
    </div>

    <a href="login.php" class="volver">Volver al Login</a>
</div>

</body>
</html>