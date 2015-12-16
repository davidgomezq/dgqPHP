<?php

require_once "dgqPHP/include.php";

switch (true){
    case (isset($_POST["submit1"])):
        siguientePaso(FormInfo::PASO_1);
        break;
    case (isset($_POST["submit2"])):
        siguientePaso(FormInfo::PASO_2);
        break;
    case (isset($_POST["back1"])):
        guardarCampos(FormInfo::PASO_2);
        pasoAtras(FormInfo::PASO_1);
        break;
    case (isset($_POST["submit3"])):
        siguientePaso(FormInfo::PASO_3);
        break;
    case (isset($_POST["back2"])):
        guardarCampos(FormInfo::PASO_3);
        pasoAtras(FormInfo::PASO_2);
        break;
    case (isset($_POST["submit4"])):
        siguientePaso(FormInfo::PASO_4);
        break;
    case (isset($_POST["back3"])):
        guardarCampos(FormInfo::PASO_4);
        pasoAtras(FormInfo::PASO_3);
        break;

    default:
        Session::resetSession();
        View::printVista(
            "templates/startHTML.html",
            "templates/paso1.html",
            "templates/endHTML.html");
}
