<?php

class SanitizarEntrada {

    // Limpia nombres y apellidos (permite tildes y espacios)
    public static function limpiarTexto($valor) {
        $valor = trim($valor);
        $valor = strip_tags($valor);
        $valor = htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
        return $valor;
    }

    // Limpia y valida correo electronico
    public static function limpiarCorreo($valor) {
        $valor = trim($valor);
        $valor = filter_var($valor, FILTER_SANITIZE_EMAIL);
        return $valor;
    }

    // Valida que el correo tenga formato correcto
    public static function validarCorreo($valor) {
        return filter_var($valor, FILTER_VALIDATE_EMAIL);
    }

    // Limpia usuario (solo letras, numeros y guion bajo)
    public static function limpiarUsuario($valor) {
        $valor = trim($valor);
        $valor = preg_replace('/[^a-zA-Z0-9_]/', '', $valor);
        return $valor;
    }

    // Valida que el sexo sea M o F
    public static function validarSexo($valor) {
        return in_array($valor, ['M', 'F']);
    }

}