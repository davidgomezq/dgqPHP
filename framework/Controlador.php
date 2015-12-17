<?php

class Controlador {

    /**
     * Funcion que se encarga de la ejecución de pasos
     */
    function formulario() {
        switch (true) {
            /* SUBMITS */
            case (isset($_POST["submit1"])):
                $this->siguientePaso(Config::PASO_1);
                break;
            case (isset($_POST["submit2"])):
                $this->siguientePaso(Config::PASO_2);
                break;
            case (isset($_POST["submit3"])):
                $this->siguientePaso(Config::PASO_3);
                break;
            case (isset($_POST["submit4"])):
                $this->siguientePaso(Config::PASO_4);
                break;
            /* BACKS */
            case (isset($_POST["back1"])):
                $this->pasoAtras(Config::PASO_2);
                break;
            case (isset($_POST["back2"])):
                $this->pasoAtras(Config::PASO_3);
                break;
            case (isset($_POST["back3"])):
                $this->pasoAtras(Config::PASO_4);
                break;
            /* PRIMERA PAGINA DEL FORMULARIO */
            default:
                $session = new Session();
                $session->resetSession();
                Vista::printVistaInicial();
        }
    }

    /**
     * Se encarga de procesar el paso y de pasar al siguiente si todo esta correcto.
     *
     * @param $paso: Recibe el parametro del paso en el que estamos, no al que queremos ir
     */
    function siguientePaso($paso)
    {
        Config::setPaso($paso);

        // Inciamos $_SESSION e inicializamos la estructura del paso en $_SESSION
        $session = new Session();
        $session->startSession();
        $session->initSession();

        // Validacion de campos
        $validacion = new ValidateForm();
        $validacion->validarCampos();
        $ko = $validacion->getPendientes();
        $ok = $validacion->getCorrectos();

        // Guardado de datos en $_SESSION
        $session->setPendientes($ko);
        $session->setCorrectos($ok);

        // Comprobación de campos pendienes para cambiar de paso
        if (empty($ko)) {
            Config::setPaso(++$paso);
            $session->initSession();
        }

        if (Config::PASO_FINAL == $paso)
            $this->pasoFinal();
        else {
            // Generamos e imprimimos la vista
            $vista = new Vista($session->getPendientes(), $session->getCorrectos());
            $contenido = $vista->generarContenido($paso);
            $vista->printVistaContenido($contenido);
        }

        echo "SESSION " . json_encode($_SESSION) . "<br>";
    }

    /**
     * Se encarga de guardar los campos del paso actual y de dar un salta hacia el anterior paso
     *
     * @param $paso: Recibe el parametro del paso en el que estamos no al que queremos ir
     */
    function pasoAtras($paso)
    {
        Config::setPaso($paso);

        // Gestion de $_SESSION
        $session = new Session();
        $session->startSession();
        $session->initSession();

        // Validamos los campos del paso actual
        $validacion = new ValidateForm();
        $validacion->validarCampos();
        $ko = $validacion->getPendientes();
        $ok = $validacion->getCorrectos();

        // Guardado de datos en $_SESSION
        $session->setPendientes($ko);
        $session->setCorrectos($ok);

        // Cambiamos el paso y generamos e imprimimos la vista
        Config::setPaso(--$paso);
        $vista = new Vista($session->getPendientes(), $session->getCorrectos());
        $contenido = $vista->generarContenido($paso);
        $vista->printVistaContenido($contenido);

        echo "SESSION " . json_encode($_SESSION) . "<br>";
    }

    /**
     * Se encarga de mostrar el paso final del formulario con la muestra de datos
     */

    function pasoFinal() {
        $session = new Session();
        Vista::printPasoFinal($session->getCamposForm());
    }

}