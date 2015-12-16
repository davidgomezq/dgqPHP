<?php

/**
 * Class View
 *
 * Se encarga de la generación de los HTML
 */
class View {

    /**
     * Función que genera la vista HTML y la muestra
     */
    public static function generarContenido($vista) {
        $vista = file_get_contents($vista);
        $vista = self::replaceErrores( $vista);

        // CAMPOS DE TEXTO - CORRECTOS
        $campos = Session::getCorrectos();
        if (!empty($campos))
            foreach ($campos as $name => $valor)
                $vista = self::replaceEtiquetas($name, Session::CORRECTOS, $vista, "has-success");

        // CAMPOS DE TEXTO - PENDIENTES O FORMATO INCORRECTO
        $campos = Session::getPendientes();
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
     * Función que reemplaza las etiquetas {} del HTML
     *
     * @param $name: nombre del campo
     * @param $penOrMan: Indica si es un campo que esta correcto o aun pendiente
     * @param $vista: file_get_contents del HTML
     * @param $class: Tipo de clase CSS ha aplicar
     * @return string del HTML.
     */
    private static function replaceEtiquetas($name, $penOrMan, $vista, $class) {
        $tags = ($penOrMan == Session::CORRECTOS) ?
            array(
                '{c-'.$name.'}', // class del div
                '{v-'.$name.'}') // value del input
            :
            '{c-'.$name.'}';

        $replace = ($penOrMan == Session::CORRECTOS) ?
            array(
                'class="'.$class.'"', // class del div
                "value='".$_SESSION[FormInfo::getPaso()][$penOrMan][$name]."'") // value del input
            :
            'class="'.$class.'"';

        return str_replace($tags, $replace, $vista);
    }

    /**
     * Funcion que sustituye el tag {errores} por la información de los errores
     */
    private static function replaceErrores($vista) {
        $errores = "";
        $pendientes = Session::getPendientes();

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
    public static function printVistaContenido($contenido) {
        $vista = file_get_contents("templates/startHTML.html");
        $vista .= $contenido;
        $vista .= file_get_contents("templates/endHTML.html");
        print ($vista);
    }

    /**
     * Funcion que imprime el documento HTML con el contenido indicado mediando la ruta.
     *
     * @param $contenido: Ruta del contenido (normalmente paso1.html, paso2.html...)
     */
    public static function printVista($startHTML, $contenido, $endHTML) {
        $vista = file_get_contents($startHTML);
        $vista .= file_get_contents($contenido);
        $vista .= file_get_contents($endHTML);

        // Si hay tag {errores} lo quitamos
        $vista = str_replace('{errores}', "", $vista);
        print ($vista);
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
