<?php
session_start();
require_once 'clases/myConexionPDO.php';
require_once 'clases/CSRFProteccion.php';

$clasePDO = new mod_db();
$conn = $clasePDO->getConexion();

$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$valor = isset($_POST['valor']) ? trim($_POST['valor']) : '';

if ($tipo === 'correo') {
    $query = $conn->prepare("SELECT * FROM usuarios WHERE Correo = :valor");
} else if ($tipo === 'usuario') {
    $query = $conn->prepare("SELECT * FROM usuarios WHERE Usuario = :valor");
} else {
    echo 'error';
    exit;
}

$query->bindParam(':valor', $valor, PDO::PARAM_STR);
$query->execute();
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($resultado) >= 1) {
    echo 'existe';
} else {
    echo 'libre';
}