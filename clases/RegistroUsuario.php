<?php

require_once 'myConexionPDO.php';
require_once 'SanitizarEntrada.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

class RegistroUsuario {

    private $id;
    private $Nombre;
    private $Apellido;
    private $Usuario;
    private $Correo;
    private $Sexo;
    private $contrasena;
    private $hastGenerado;
    private $secret_2fa;
    private $pdo;
    private $tabla;
    private $FechaSistema;

    public function __construct($datos, $pdo, &$arrMensaje) {
        $this->pdo = $pdo;
        $this->tabla = "usuarios";
        $this->FechaSistema = date("Y-m-d H:i:s");

        if (isset($datos["nombre"])) {
            $this->Nombre = SanitizarEntrada::limpiarTexto($datos["nombre"]);
        } else {
            $arrMensaje[1] = "No trajo datos la Columna Nombre";
        }

        if (isset($datos["apellido"])) {
            $this->Apellido = SanitizarEntrada::limpiarTexto($datos["apellido"]);
        } else {
            $arrMensaje[2] = "No trajo datos la Columna Apellido";
        }

        if (isset($datos["usuario"])) {
            $this->Usuario = SanitizarEntrada::limpiarUsuario($datos["usuario"]);
        } else {
            $arrMensaje[3] = "No trajo datos la Columna Usuario";
        }

        if (isset($datos["correo"])) {
            $this->Correo = SanitizarEntrada::limpiarCorreo($datos["correo"]);
        } else {
            $arrMensaje[4] = "No trajo datos la Columna Correo";
        }

        if (isset($datos["clave"])) {
            $this->contrasena = $datos["clave"];
        } else {
            $arrMensaje[5] = "No trajo datos la Columna Clave";
        }

        if (isset($datos["sexo"])) {
            $this->Sexo = $datos["sexo"];
        } else {
            $arrMensaje[6] = "No trajo datos la Columna Sexo";
        }
    }

    public function encriptarClave() {
        $options = ['cost' => 13];
        $this->hastGenerado = password_hash($this->contrasena, PASSWORD_BCRYPT, $options);
    }

    public function Guardar_RegistroUsuario() {
        $this->encriptarClave();

        $data = array(
            "Nombre"       => $this->Nombre,
            "Apellido"     => $this->Apellido,
            "Usuario"      => $this->Usuario,
            "Correo"       => $this->Correo,
            "HashMagic"    => $this->hastGenerado,
            "Sexo"         => $this->Sexo,
            "FechaSistema" => $this->FechaSistema
        );

        $this->pdo->insertSeguro($this->tabla, $data);
        $this->id = $this->pdo->insert_id();
    }

    public function GuardarMySecreto($secreto) {
        $datoSecreto = array("secret_2fa" => $secreto);
        $condicion = array("id" => $this->id);

        if ($this->pdo->updateSeguro($this->tabla, $datoSecreto, $condicion)) {
            return true;
        }
    }

    public function getUsuario() {
        return $this->Usuario;
    }

    public function getId() {
        return $this->id;
    }
}