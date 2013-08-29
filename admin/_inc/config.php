<?php
    define('PROJ_MODE', 3);
    define('DIR_CLASSES', '/admin/_lib/');
    
    if(PROJ_MODE === 1){
        define('BASE_URL', 'http://v3.jogosabertos.dev');
    } elseif(PROJ_MODE === 2){
        define('BASE_URL', 'http://www.jogosabertos2011.tboom.net');
    } elseif(PROJ_MODE === 3) {
        define('BASE_URL', 'http://www.jogosabertos2011.com.br');
    }
        
    $error_reporting = error_reporting(PROJ_MODE === 1 || PROJ_MODE === 2 ? E_ALL : 0);
    
    function __autoload__class($className){
        if (($f1 = file_exists($c1 = $_SERVER['DOCUMENT_ROOT'] . DIR_CLASSES . "$className.class.php")) || ($f2 = $_SERVER['DOCUMENT_ROOT'] . file_exists($c2 = DIR_CLASSES . "$className.php"))){
            if(PROJ_MODE === 1){
                require_once($f1 ? $c1 : $c2);
            } else {
                @require_once($f1 ? $c1 : $c2);
            }
        }
    }
    
    date_default_timezone_set('America/Sao_Paulo');
    spl_autoload_register('__autoload__class');
?>