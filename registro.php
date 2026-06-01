<?php
session_start();
require_once 'clases/CSRFProteccion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
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
            max-width: 450px;
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

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        input:focus, select:focus {
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
            color: red;
            font-size: 12px;
            margin-top: 3px;
            display: none;
        }

        #mensaje-correo, #mensaje-usuario {
            font-size: 12px;
            margin-top: 3px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Crear Cuenta</h1>

    <form id="form-registro" action="procesarRegistro.php" method="POST">
        <?php echo CSRFProtection::campoHidden(); ?>

        <div class="campo">
            <label>Nombre</label>
            <input type="text" name="nombre" id="nombre" placeholder="Tu nombre">
            <span class="error" id="error-nombre">El nombre es requerido</span>
        </div>

        <div class="campo">
            <label>Apellido</label>
            <input type="text" name="apellido" id="apellido" placeholder="Tu apellido">
            <span class="error" id="error-apellido">El apellido es requerido</span>
        </div>

        <div class="campo">
            <label>Usuario</label>
            <input type="text" name="usuario" id="usuario" placeholder="Nombre de usuario">
            <span id="mensaje-usuario"></span>
        </div>

        <div class="campo">
            <label>Correo Electrónico</label>
            <input type="email" name="correo" id="correo" placeholder="tucorreo@ejemplo.com">
            <span id="mensaje-correo"></span>
        </div>

        <div class="campo">
            <label>Contraseña</label>
            <input type="password" name="clave" id="clave" placeholder="Mínimo 6 caracteres">
            <span class="error" id="error-clave">La contraseña debe tener mínimo 6 caracteres</span>
        </div>

        <div class="campo">
            <label>Repetir Contraseña</label>
            <input type="password" name="clave_again" id="clave_again" placeholder="Repite tu contraseña">
            <span class="error" id="error-clave-again">Las contraseñas no coinciden</span>
        </div>

        <div class="campo">
            <label>Sexo</label>
            <select name="sexo" id="sexo">
                <option value="">-- Selecciona --</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
            </select>
            <span class="error" id="error-sexo">Selecciona un sexo</span>
        </div>

        <button type="submit" class="btn">Registrarse</button>

        <div class="mensaje">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
        </div>
    </form>
</div>

<script>
// Validación en tiempo real del correo
document.getElementById('correo').addEventListener('blur', function() {
    const correo = this.value;
    const mensaje = document.getElementById('mensaje-correo');

    if (correo === '') return;

    fetch('verificarDuplicado.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'tipo=correo&valor=' + encodeURIComponent(correo)
    })
    .then(res => res.text())
    .then(data => {
        if (data === 'existe') {
            mensaje.style.color = 'red';
            mensaje.textContent = 'Este correo ya está en uso';
        } else {
            mensaje.style.color = 'green';
            mensaje.textContent = 'Correo disponible';
        }
    });
});

// Validación en tiempo real del usuario
document.getElementById('usuario').addEventListener('blur', function() {
    const usuario = this.value;
    const mensaje = document.getElementById('mensaje-usuario');

    if (usuario === '') return;

    fetch('verificarDuplicado.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'tipo=usuario&valor=' + encodeURIComponent(usuario)
    })
    .then(res => res.text())
    .then(data => {
        if (data === 'existe') {
            mensaje.style.color = 'red';
            mensaje.textContent = 'Este usuario ya está en uso';
        } else {
            mensaje.style.color = 'green';
            mensaje.textContent = 'Usuario disponible';
        }
    });
});

// Validación del formulario antes de enviar
document.getElementById('form-registro').addEventListener('submit', function(e) {
    let valido = true;

    const nombre = document.getElementById('nombre').value.trim();
    const apellido = document.getElementById('apellido').value.trim();
    const clave = document.getElementById('clave').value;
    const claveAgain = document.getElementById('clave_again').value;
    const sexo = document.getElementById('sexo').value;

    if (nombre === '') {
        document.getElementById('error-nombre').style.display = 'block';
        valido = false;
    } else {
        document.getElementById('error-nombre').style.display = 'none';
    }

    if (apellido === '') {
        document.getElementById('error-apellido').style.display = 'block';
        valido = false;
    } else {
        document.getElementById('error-apellido').style.display = 'none';
    }

    if (clave.length < 6) {
        document.getElementById('error-clave').style.display = 'block';
        valido = false;
    } else {
        document.getElementById('error-clave').style.display = 'none';
    }

    if (clave !== claveAgain) {
        document.getElementById('error-clave-again').style.display = 'block';
        valido = false;
    } else {
        document.getElementById('error-clave-again').style.display = 'none';
    }

    if (sexo === '') {
        document.getElementById('error-sexo').style.display = 'block';
        valido = false;
    } else {
        document.getElementById('error-sexo').style.display = 'none';
    }

    if (!valido) e.preventDefault();
});
</script>

</body>
</html>