<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
    if(isset($_SERVER['HTTP_REFERER']) && (strpos($_SERVER['HTTP_REFERER'], BASE_URL) === 0)){
        $arquivo = isset($_GET['arquivo']) && !empty($_GET['arquivo']) ? $_GET['arquivo'] : FALSE;
        if($arquivo){
            $arquivo = str_replace('..', '', $arquivo);
            $arquivo = str_replace('/', '', $arquivo);
            $local = explode('/', $_SERVER['HTTP_REFERER']);
            $local = array_pop($local);
            switch($local){
                case 'banco-de-arquivos':
                    $arquivo = '/upload/banco_de_arquivos/' . $arquivo;
                    break;
                case 'releases':
                    $arquivo = '/upload/releases/' . $arquivo;
                    break;
                default :
                    header("Location:$_SERVER[HTTP_REFERER]");
            }
            if(file_exists($_SERVER['DOCUMENT_ROOT'] . $arquivo)){
                switch(strtolower(substr(strrchr(basename($arquivo),'.'),1))){
                    case 'pdf':
                        $tipo = 'application/pdf';
                        break;
                    case 'zip':
                        $tipo = 'application/zip';
                        break;
                    case 'rar':
                        $tipo = 'application/x-rar-compressed';
                        break;
                    case 'doc':
                        $tipo = 'application/msword';
                        break;
                    case 'xls':
                        $tipo = 'application/vnd.ms-excel';
                        break;
                    case 'ppt':
                        $tipo = 'application/vnd.ms-powerpoint';
                        break;
                    case 'gif':
                        $tipo = 'image/gif';
                        break;
                    case 'png':
                        $tipo = 'image/png';
                        break;
                    case 'jpg': $tipo = 'image/jpg';
                        break;
                    case 'mp3':
                        $tipo = 'audio/mpeg';
                        break;
                    default :
                        header("Location: $_SERVER[HTTP_REFERER]");
                }
                header('Content-Type: ' . $tipo);
                header('Content-Length: ' . filesize($_SERVER['DOCUMENT_ROOT'] . $arquivo));
                header('Content-Disposition: attachment; filename= ' . basename($arquivo));
                header('Content-Description: File Transfer');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Transfer-Encoding: binary');                
                @readfile($_SERVER['DOCUMENT_ROOT'] . $arquivo);
            } else {
                header("Location: $_SERVER[HTTP_REFERER]");
            }
        } else {
            header("Location: $_SERVER[HTTP_REFERER]");
        }
    }
    exit();
?>