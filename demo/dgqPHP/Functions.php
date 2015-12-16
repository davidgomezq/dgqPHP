<?php

function guardarCampos($paso) {
    FormInfo::setPaso($paso);
    Session::startSession();
    Session::initSession();

    $validacion = new ValidateForm();
    $validacion->validarCampos();
}

function siguientePaso($paso) {
    FormInfo::setPaso($paso);
    Session::startSession();
    Session::initSession();
    $pasoINT = FormInfo::getPasoInt();

    $validacion = new ValidateForm();
    $validacion->validarCampos();

    if(empty(Session::getPendientes())) {
        FormInfo::setPaso("p".++$pasoINT);
        Session::initSession();

        $contenido = View::generarContenido("templates/paso".$pasoINT.".html");
    } else
        $contenido = View::generarContenido("templates/paso".$pasoINT.".html");

    // Imprimimos la vista una vez el contenido este generado
    View::printVistaContenido($contenido);

    echo "SESSION ".json_encode($_SESSION)."<br>";
}

function pasoAtras($paso) {
    FormInfo::setPaso($paso);
    Session::startSession();
    Session::initSession();

    // Imprimimos la vista una vez el contenido este generado
    $contenido = View::generarContenido("templates/paso".FormInfo::getPasoInt().".html");
    View::printVistaContenido($contenido);

    echo "SESSION ".json_encode($_SESSION)."<br>";
}