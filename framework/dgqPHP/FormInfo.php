<?php

/**
 * Class FormInfo
 *
 * Contiene la información esencial del formulario y sus pasos
 */

class FormInfo {

    /**
     * DEFINICIÓN DE LOS PASOS
     * IMPORTANTE: Seguir nomenclatura 'pX'
     */
    const PASO_1 = "p1";
    const PASO_2 = "p2";
    const PASO_3 = "p3";
    const PASO_4 = "p4";

    /**
     * DEFINICIÓN DE LOS CAMPOS
     * IMPORTANTE: Seguir la sintaxis de array asociado bidimensional
     */
    private static $patronCampos = [
        self::PASO_1 => [
            ['nombre', 'string'],
            ['movil', 'phone'],
            ['pwd', 'none']
        ],
        self::PASO_2 => [
            ['apellidos', 'string'],
            ['fijo', 'phone']
        ],
        self::PASO_3=> [
            ['email','email']
        ],
        self::PASO_4=> [
            ['precio1','none'],
            ['precio2','float']
        ]
    ];

    /* Devuelve el array campos entero */
    public static function getPatronCampos() {
        return self::$patronCampos;
    }

    /* Devuelve el paso que se le indice del array campos */
    public static function getPatronCamposPaso($paso) {
        return self::$patronCampos[$paso];
    }

    /**
     * ATRIBUTO $paso QUE NOS INDICA EN QUE PASO DEL FORMULARIO NOS ENCONTRAMOS
     */
    private static $paso;

    public static function setPaso($paso) {
        self::$paso = $paso;
    }

    public static function getPaso() {
        return self::$paso;
    }

    public static function getPasoInt() {
        return (int)substr(self::$paso, -1);
    }
}
