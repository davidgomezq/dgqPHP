<?php

/**
 * Class Session
 *
 * Clase que gestiona la variable suplerglobal $_SESSION
 */

class Session {

    /* FUNCIONES DE CONTROL DE SESSION */

    public static function startSession() {
        if (self::isOpen())
            return;

        session_start();
        $_SESSION["on"] = true;
    }

    public static function closeSession() {
        if (!self::isOpen())
            return;

        session_unset();
        session_destroy();
    }

    public static function resetSession() {
        self::startSession();
        self::closeSession();
    }

    public static function isOpen() {
        if (!isset($_SESSION["on"]))
            return false;

        return true;
    }

    /* FUNCIONES DE CONTROL DE CAMPOS */
    const PENDIENTES = "pendientes";
    const CORRECTOS = "correctos";

    public static function initSession() {
        $paso = FormInfo::getPaso();
        if (self::checkSession())
            return;

        $_SESSION[$paso][self::PENDIENTES] = array();
        $_SESSION[$paso][self::CORRECTOS] = array();
    }

    public static function checkSession() {
        return (isset($_SESSION[FormInfo::getPaso()]));
    }

    public static function setError($bool) {
        $_SESSION[FormInfo::getPaso()]["error"] = $bool;
    }
    public static function hasError() {
        return ($_SESSION[FormInfo::getPaso()]["error"]);
    }

    public static function writePendiente($name, $error) {
        $_SESSION[FormInfo::getPaso()][self::PENDIENTES][$name] = $error;
    }

    public static function removePendiente($name) {
        if (array_key_exists($name, $_SESSION[FormInfo::getPaso()][self::PENDIENTES]))
            unset($_SESSION[FormInfo::getPaso()][self::PENDIENTES][$name]);
    }

    public static function getPendientes() {
        return $_SESSION[FormInfo::getPaso()][self::PENDIENTES];
    }

    public static function writeCorrecto($name, $value) {
        $_SESSION[FormInfo::getPaso()][self::CORRECTOS][$name] = $value;
    }

    public static function removeCorrecto($name) {
        if (array_key_exists($name, $_SESSION[FormInfo::getPaso()][self::CORRECTOS]))
            unset($_SESSION[FormInfo::getPaso()][self::CORRECTOS][$name]);
    }

    public static function getCorrectos() {
        return $_SESSION[FormInfo::getPaso()][self::CORRECTOS];
    }

    /* Para la muestra de datos en el paso final */
    public static function getValueCampo($paso, $name) {
        return $_SESSION[$paso][Session::CORRECTOS][$name];
    }
}
