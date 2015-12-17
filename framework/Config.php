<?php

/**
 * Class Config
 *
 * Contiene la información esencial del formulario y sus pasos y la configuración
 */

class Config {

    /**
     * CONFIGURACIÓN BASICA: Ruta de archivos HTML, prefijos...
     */
    const HTML_PATH = 'htmls'; // Ruta relativa respecto al Raiz
    const HTML_HEAD = 'startHTML.html';
    const HTML_END = 'endHTML.html';
    const HTML_PREFIX_PASO = 'paso'; // Los archivos se llamaran (HTML_PREFIX_PASO)1.html, (HTML_PREFIX_PASO)2.html

    /**
     * DEFINICIÓN DE LOS PASOS
     * IMPORTANTE: Seguir nomenclatura 'pX'
     */
    const PASO_1 = 1;
    const PASO_2 = 2;
    const PASO_3 = 3;
    const PASO_4 = 4;
    const PASO_FINAL = 5;

    /**
     * DEFINICIÓN DE LOS CAMPOS
     * IMPORTANTE: Seguir la sintaxis de array asociado bidimensional
     */
    private static $patron = [
        'p'.self::PASO_1 => [
            ['nombre', 'string'],
            ['movil', 'phone'],
            ['pwd', 'none']
        ],
        'p'.self::PASO_2 => [
            ['apellidos', 'string'],
            ['fijo', 'phone']
        ],
        'p'.self::PASO_3=> [
            ['email','email']
        ],
        'p'.self::PASO_4=> [
            ['precio1','none'],
            ['precio2','float']
        ]
    ];

    /**
     * LOGICA DE Config
     *
     * NOTA: Se recomienda no tocar esta parte del codigo.
     */
    public static function getPatronCampos() {
        return self::$patron;
    }

    /* Devuelve el paso que se le indice del array campos */
    public static function getPatronCamposPaso($paso) {
        return self::$patron[$paso];
    }

    /**
     * ATRIBUTO $paso QUE NOS INDICA EN QUE PASO DEL FORMULARIO NOS ENCONTRAMOS
     */
    private static $paso, $pasoINT;

    public static function setPaso($paso) {
        self::$paso = 'p'.$paso;
        self::$pasoINT = $paso;
    }

    public static function getPaso() {
        return self::$paso;
    }

    public static function getPasoInt() {
        return self::$pasoINT;
    }
}
