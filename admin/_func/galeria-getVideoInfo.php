<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
    
    $login = new Login();
    if(!$login->verificaLogin()){
       header('Location:/admin/login.php');
       exit();
    }
    $login->atualizaSession();
    
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
    if($referer === NULL){
        die();
    }
    if(strpos($referer, BASE_URL) !== 0){
        die();
    }

    if(($url = isset($_POST['url']) && filter_var($_POST['url'], FILTER_VALIDATE_URL) ? $_POST['url'] : FALSE) !== FALSE){
        $valida = new Valida();
        $tipoVideo = $valida->tipoVideo($_POST['url']);
        $id = $valida->urlVideo($_POST['url']);
        if(!$valida->totalErros){
            $infos = array();
            $param = array('tipo' => $tipoVideo, 'id' => $id);
            array_push($infos, $param);
            $videoInfo = Video::get_infos($infos);

            if($videoInfo && is_array($videoInfo)){
                $videoInfo = $videoInfo[0];
                echo "{\"status\":\"ok\",\"id\":\"$videoInfo[id]\",\"titulo\":\"$videoInfo[titulo]\",\"data\":\"$videoInfo[data]\",\"descricao\":\"$videoInfo[descricao]\",\"thumb1\":\"$videoInfo[thumb1]\",\"thumb2\":\"$videoInfo[thumb2]\",\"thumb3\":\"$videoInfo[thumb3]\"}";
            } else {
                echo '{"status":"error"}';
            }
        }
    }
?>