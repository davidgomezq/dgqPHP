<?php

/**
 * Class ValidacionForm
 * Este objeto se encarga de la validacion de los y la información del tipo de error
 */
class ValidateForm {

    private $regexpr = Array(
        'date' => "^[0-9]{4}[-/][0-9]{1,2}[-/][0-9]{1,2}\$",
        'string' => "/^[a-zA-Z][a-zA-Z ]+$/",
        'amount' => "^[-]?[0-9]+\$",
        'number' => "^[-]?[0-9,]+\$",
        'not_empty' => "[a-z0-9A-Z]+",
        'words' => "^[A-Za-z]+[A-Za-z \\s]*\$",
        'phone' => "/^([69])\\d{8}$/",
        'zipcode' => "^[1-9][0-9]{3}[a-zA-Z]{2}\$",
        'none' => "/^[\\d\\D]{0,}$/" // Para campos de text que no son requeridos
    );
    private $filter = Array(
        'email' => FILTER_VALIDATE_EMAIL,
        'int' => FILTER_VALIDATE_INT,
        'float' => FILTER_VALIDATE_FLOAT,
        'boolean' => FILTER_VALIDATE_BOOLEAN,
        'ip' => FILTER_VALIDATE_IP,
        'url' => FILTER_VALIDATE_URL,
        'mac' => FILTER_VALIDATE_MAC
    );
    private $pendientes = [], $correctos = [], $campos = [];

    public function __construct() {
        $this->campos = FormInfo::getPatronCamposPaso(FormInfo::getPaso());
    }

    /**
     * Validación individual de cada campo. Se rellenan los arrays de control
     * 'public' por los posibles usos que se le puedan dar.
     */
    public function validarCampo($nameCampo, $tipo) {
        if ((!isset($_POST[$nameCampo]) || !$_POST[$nameCampo]) && $tipo != 'none') {
            $this->writePendiente($nameCampo, Errors::ERROR_UNSET);
            return;
        }

        $value = (!isset($_POST[$nameCampo])) ? "" : $_POST[$nameCampo];

        if (array_key_exists($tipo, $this->regexpr)) {
            if (!preg_match($this->regexpr[$tipo], $value)) {
                $this->writePendiente($nameCampo, Errors::ERROR_FORMATO);
                return;
            }
        } elseif (array_key_exists($tipo, $this->filter))
            if (!filter_var($value, $this->filter[$tipo])) {
                $this->writePendiente($nameCampo, Errors::ERROR_FORMATO);
                return;
            }

        $this->writeCorrecto($nameCampo, $value);
    }

    /**
     * Validacion de todos los campos requeridos.
     */
    public function validarCampos() {
        if (empty($this->campos))
            die("<h1>ERROR: Validación de datos vacia</h1>");

        foreach ($this->campos as $fila)
            $this->validarCampo($fila[0], $fila[1]);
    }

    /**
     * Writes de los atributos del objeto y de $_SESSION
     */
    public function writePendiente($name, $error) {
        $this->pendientes[$name] = $error;

        // Lo mandamos guardar a la session
        Session::writePendiente($name, $error);

        // Lo mandamos borrar
        $this->removeCorrecto($name);
    }
    public function writeCorrecto($name, $value) {
        $this->correctos[$name] = $value;

        // Lo mandamos guardar a la session
        Session::writeCorrecto($name, $value);

        // Lo mandamos borrar
        $this->removePendiente($name);
    }

    /**
     * Removes de los atributos del objeto y de $_SESSION
     */
    public function removePendiente($name) {
        if (array_key_exists($name, $this->pendientes))
            unset($this->pendientes[$name]);

        // Lo mandamos borrar a la session
        Session::removePendiente($name);
    }
    public function removeCorrecto($name) {
        if (array_key_exists($name, $this->correctos))
            unset($this->correctos[$name]);

        // Lo mandamos borrar a la session
        Session::removeCorrecto($name);
    }
}