<?php

/**
 * Class View
 *
 * Se encarga de la generación de los HTML
 */
class Vista {

    /**************************
     * Atributos y Constantes *
     **************************/
    private $pendientes = [], $correctos = [];

    /** CONSTRUCTOR **/
    public function __construct($ko, $ok) {
        $this->pendientes = $ko;
        $this->correctos = $ok;
    }

    /**
     * Función que genera la vista HTML del contenido y la muestra
     */
    public function generarContenido($paso) {
        $vista = file_get_contents(Config::HTML_PATH.'/'.Config::HTML_PREFIX_PASO.$paso.'.html');
        $vista = $this->replaceErrores($vista);

        // CAMPOS DE TEXTO - CORRECTOS
        $campos = $this->correctos;
        if (!empty($campos))
            foreach ($campos as $name => $valor)
                $vista = self::replaceEtiquetas($name, Session::CORRECTOS, $vista, "has-success");

        // CAMPOS DE TEXTO - PENDIENTES O FORMATO INCORRECTO
        $campos = $this->pendientes;
        if (!empty($campos))
            foreach ($campos as $name => $error)
                switch($error) {
                    case Errors::ERROR_UNSET:
                        $vista = self::replaceEtiquetas($name, Session::PENDIENTES, $vista, "has-error");
                        break;
                    case Errors::ERROR_FORMATO:
                        $vista = self::replaceEtiquetas($name, Session::PENDIENTES, $vista, "has-warning");
                        break;
                }

        return $vista;
    }

    /**
     * Función que genera la vista HTML del paso final y la muestra
     */
    public static function printPasoFinal($camposForm) {
        $vista = file_get_contents(Config::HTML_PATH.'/'.Config::HTML_HEAD);
        $vista .= file_get_contents(Config::HTML_PATH.'/'.Config::HTML_PREFIX_PASO.Config::PASO_FINAL.'.html');

        if (!empty($camposForm))
            foreach ($camposForm as $name => $value)
                $vista = str_replace('{v-'.$name.'}', $value, $vista);

        $vista .= file_get_contents(Config::HTML_PATH.'/'.Config::HTML_END);
        print ($vista);
    }

    /**
     * Función que reemplaza las etiquetas {} del HTML
     *
     * @param $name: nombre del campo
     * @param $penOrMan: Indica si es un campo que esta correcto o aun pendiente
     * @param $vista: file_get_contents del HTML
     * @param $class: Tipo de clase CSS ha aplicar
     * @return string del HTML.
     */
    private function replaceEtiquetas($name, $penOrMan, $vista, $class) {
        $tags = ($penOrMan == Session::CORRECTOS) ?
            array(
                '{c-'.$name.'}', // class del div
                '{v-'.$name.'}') // value del input
            :
            '{c-'.$name.'}';

        $replace = ($penOrMan == Session::CORRECTOS) ?
            array(
                'class="'.$class.'"', // class del div
                "value='".$_SESSION[Config::getPaso()][$penOrMan][$name]."'") // value del input
            :
            'class="'.$class.'"';

        return str_replace($tags, $replace, $vista);
    }

    /**
     * Funcion que sustituye el tag {errores} por la información de los errores
     */
    private function replaceErrores($vista) {
        $errores = "";
        $pendientes = $this->pendientes;

        // Si hay requeridos
        if (in_array(Errors::ERROR_UNSET, $pendientes))
            $errores .= Errors::errorToHTML(Errors::ERROR_UNSET);

        // Si hay errores de formato
        if (in_array(Errors::ERROR_FORMATO, $pendientes))
            $errores .= Errors::errorToHTML(Errors::ERROR_FORMATO);


        return str_replace('{errores}', $errores, $vista);
    }

    /**
     * Funcion que imprime el documento HTML con contenido generado dinamicamente.
     *
     * @param $contenido: se genera con la funcion 'generarContenido'
     */
    public function printVistaContenido($contenido) {
        $vista = file_get_contents(Config::HTML_PATH.'/'.Config::HTML_HEAD);
        $vista .= $contenido;
        $vista .= file_get_contents(Config::HTML_PATH.'/'.Config::HTML_END);
        print ($vista);
    }

    /**
     * Funcion que imprime el documento HTML con el contenido indicado mediando la ruta.
     *
     * @param $contenido: Ruta del contenido (normalmente paso1.html, paso2.html...)
     */
    public static function printVistaInicial() {
        $vista = file_get_contents(Config::HTML_PATH.'/'.Config::HTML_HEAD);
        $vista .= file_get_contents(Config::HTML_PATH.'/'.Config::HTML_PREFIX_PASO.'1.html');
        $vista .= file_get_contents(Config::HTML_PATH.'/'.Config::HTML_END);

        // Si hay tag {errores} lo quitamos
        $vista = str_replace('{errores}', "", $vista);
        print ($vista);
    }

    /********
     * Gets *
     ********/
    public function getPendientes() {
        return $this->pendientes;
    }

    public function getCorrectos() {
        return $this->correctos;
    }
}

/**
 * Class Errors
 *
 * Contiene la información de Errores del formulario
 */
class Errors {
    const ERROR_OK      = 1; // CUANDO NO HAY ERROR
    const ERROR_UNSET   = 2; // CUANDO EL CAMPO NO SE HA RELLENADO (REQUERIDOS)
    const ERROR_FORMATO = 3; // CUANDO EL FORMATO DEL CAMPO NO ES EL CORRECTO
    const ERROR_FOTO    = 4; // CUANDO HAY PROBLEMAS CON UNA FOTO

    public static function errorToHTML($error) {
        switch ($error) {
            case self::ERROR_UNSET:
                $html = "<div class=\"alert alert-error\">
                            <strong>¡Lo sentimos!</strong> Hay algunos campos requeridos que no han sido rellenado. Te los hemos marcado en <strong>ROJO</strong>.
                        </div>";
                break;
            case self::ERROR_FORMATO:
                $html = "<div class=\"alert alert-warning\">
                            <strong>¡Lo sentimos!</strong> Hay algunos campos con un formato incorrecto. Te los hemos marcado en <strong>AMARILLO</strong>.
                        </div>";
                break;
            default: $html = "";
        }

        return $html;
    }
}
