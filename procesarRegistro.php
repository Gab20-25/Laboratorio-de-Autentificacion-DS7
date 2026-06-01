<?php
session_start();
require_once 'clases/myConexionPDO.php';
require_once 'clases/RegistroUsuario.php';
require_once 'clases/CSRFProteccion.php';

require_once 'vendor/autoload.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

// Verificar token CSRF
CSRFProtection::verificarFormulario();

$clasePDO = new mod_db();
$arrMensaje = array();

try {
    $MyRegistro = new RegistroUsuario($_POST, $clasePDO, $arrMensaje);

    if (count($arrMensaje) == 0) {

        // Guardar usuario en la BD
        $MyRegistro->Guardar_RegistroUsuario();

        // Generar el secreto 2FA
        $g = new GoogleAuthenticator();
        $secret = $g->generateSecret();

        // Guardar el secreto en la BD
        $MyRegistro->GuardarMySecreto($secret);

        // Generar el QR
        $nombre_usuario = $MyRegistro->getUsuario();
        $nombre_app = 'Lab2FA';
        $otpauth = 'otpauth://totp/' . $nombre_app . ':' . $nombre_usuario . '?secret=' . $secret . '&issuer=' . $nombre_app;
$qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($otpauth);

        // Guardar en sesión para mostrar en la siguiente pantalla
        $_SESSION['qr_url'] = $qr_url;
        $_SESSION['usuario_registro'] = $nombre_usuario;

        // Redirigir a pantalla del QR
        header("Location: mostrarQR.php");
        exit;

    } else {
        foreach ($arrMensaje as $val) {
            echo $val . '<br>';
        }
    }

} catch (Exception $e) {
    echo "Ha ocurrido un error al procesar la solicitud. Intente más tarde.";
} finally {
    $clasePDO = null;
    $MyRegistro = null;
}
?>