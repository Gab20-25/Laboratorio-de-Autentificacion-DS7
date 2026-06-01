<?php
session_start();
require_once 'clases/myConexionPDO.php';
require_once 'clases/CSRFProteccion.php';

// Verificar token CSRF
CSRFProtection::verificarFormulario();

$clasePDO = new mod_db();
$conn = $clasePDO->getConexion();

$usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$clave = isset($_POST['clave']) ? $_POST['clave'] : '';
$ip = $_SERVER['REMOTE_ADDR'];

// Buscar usuario en la BD
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE Usuario = :usuario");
$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetchObject();

$estado = 'fallido';

if ($user && password_verify($clave, $user->HashMagic)) {
    $estado = 'exitoso';

    // Guardar datos en sesión (primera fase)
    $_SESSION['usuario_id'] = $user->id;
    $_SESSION['usuario_nombre'] = $user->Usuario;
    $_SESSION['login_fase1'] = 'SI';

    // Registrar intento exitoso
    $audit = $conn->prepare("INSERT INTO intentos_login (Usuario, ipRemoto, Estado) VALUES (:usuario, :ip, :estado)");
    $audit->bindParam(':usuario', $usuario);
    $audit->bindParam(':ip', $ip);
    $audit->bindParam(':estado', $estado);
    $audit->execute();

    // Ir a verificar el código 2FA
    header("Location: verificar2fa.php");
    exit;

} else {
    // Registrar intento fallido
    $audit = $conn->prepare("INSERT INTO intentos_login (Usuario, ipRemoto, Estado) VALUES (:usuario, :ip, :estado)");
    $audit->bindParam(':usuario', $usuario);
    $audit->bindParam(':ip', $ip);
    $audit->bindParam(':estado', $estado);
    $audit->execute();

    $_SESSION['error_login'] = 'Usuario o contraseña incorrectos';
    header("Location: login.php");
    exit;
}
?>