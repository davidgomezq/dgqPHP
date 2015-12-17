<?php

/**
 * Class Session
 *
 * Clase que gestiona la variable suplerglobal $_SESSION
 */

class Session {

    /**************************
     * Atributos y Constantes *
     **************************/
    const PENDIENTES = "pendientes";
    const CORRECTOS = "correctos";

    /** CONSTRUCTOR **/
    public function __construct() {}

    /************************
     * Control de $_SESSION *
     ************************/

    public function startSession() {
        if ($this->isOpen())
            return;

        session_start();
        $_SESSION["on"] = true;
    }

    public function closeSession() {
        if (!$this->isOpen())
            return;

        session_unset();
        session_destroy();
    }

    public function resetSession() {
        $this->startSession();
        $this->closeSession();
    }

    public function isOpen() {
        return isset($_SESSION["on"]);
    }

    /*******************************
     * Control de $_SESSION[$paso] *
     *******************************/
    public function initSession() {
        $paso = Config::getPaso();
        if (self::checkSession())
            return;

        $_SESSION[$paso][self::PENDIENTES] = array();
        $_SESSION[$paso][self::CORRECTOS] = array();
    }

    public function checkSession() {
        return (isset($_SESSION[Config::getPaso()]));
    }

    /***************
     * Gets y Sets *
     ***************/
    public function setPendientes($pendientes) {
        $_SESSION[Config::getPaso()][self::PENDIENTES] = $pendientes;
    }

    public function setCorrectos($correctos) {
        $_SESSION[Config::getPaso()][self::CORRECTOS] = $correctos;
    }

    public function getPendientes() {
        return $_SESSION[Config::getPaso()][self::PENDIENTES];
    }

    public function getCorrectos() {
        return $_SESSION[Config::getPaso()][self::CORRECTOS];
    }

    public function getCamposForm() {
        $campos = array();
        for ($i = Config::PASO_1; $i <= Config::PASO_FINAL; $i++)
            foreach ($_SESSION['p'.$i][self::CORRECTOS] as $name => $value)
                $campos[$name] = $value;
        return $campos;
    }
}
