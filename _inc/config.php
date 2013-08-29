<?php

    echo $_SERVER["SERVER_NAME"];
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
     $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    //return $pageURL;

    define('DEV_MODE', 1);
    define('DIR_CLASSES', '/_inc/');
    define('BASE_URL', '');
    
    $error_reporting = error_reporting(DEV_MODE ? E_ALL : 0);
    
    function __autoload__class($className){
        if (($f1 = file_exists($c1 = $_SERVER['DOCUMENT_ROOT'] . DIR_CLASSES . "$className.class.php")) || ($f2 = $_SERVER['DOCUMENT_ROOT'] . file_exists($c2 = DIR_CLASSES . "$className.php"))){
            if(DEV_MODE){
                require_once($f1 ? $c1 : $c2);
            } else {
                @require_once($f1 ? $c1 : $c2);
            }
        }
    }
    
    spl_autoload_register('__autoload__class');
?>