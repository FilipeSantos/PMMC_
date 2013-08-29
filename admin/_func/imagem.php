<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');

    register_shutdown_function('shutdownFunction');
    function shutDownFunction() {
        $error = error_get_last();
        if ($error['type'] == 1) {
            echo 'error';
        }
    }

    $acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : FALSE;

    switch($acao){
        case 'upload':
            $imageName = isset($_REQUEST['urlImg']) && ($_REQUEST['urlImg'] != 'NULL') ? $_REQUEST['urlImg'] : md5(time() . $_FILES['Filedata']['name']);
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
            $targetFile =  str_replace('//','/',$targetPath) . $imageName . '.jpg';
            $img = WideImage::loadFromFile($_FILES['Filedata']['tmp_name']);
            $img->saveToFile($targetFile);
            $imgUrl = str_replace($_SERVER['DOCUMENT_ROOT'],'/',$targetFile);
            $imgUrl = str_replace('//', '/', $imgUrl);
            $img->destroy();
            if(file_exists($targetFile)){
                if(isset($_REQUEST['origin']) && $_REQUEST['origin'] == 'plugin'){
                    list($width, $height) = getimagesize($targetFile);
                    echo "$width|$height|$imgUrl";
                } else {
                    echo "$imgUrl";
                }
            } else {
                echo 'error';
            }
            break;

        case 'salvar':
            $imgUrl = (isset($_POST['url']) && !empty($_POST['url'])) ? $_POST['url'] : FALSE;
            $local = (isset($_POST['local']) && !empty($_POST['local'])) ? $_POST['local'] : FALSE;
            if($imgUrl){
                $imgUrl = explode('?', $imgUrl);
                $imgUrl = $imgUrl[0];
                $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : FALSE;
                $imgX = (isset($_POST['x']) && is_numeric($_POST['x'])) ? $_POST['x'] : FALSE;
                $imgY = (isset($_POST['y']) && is_numeric($_POST['y'])) ? $_POST['y'] : FALSE;
                $imgX2 = (isset($_POST['x2']) && is_numeric($_POST['x2'])) ? $_POST['x2'] : FALSE;
                $imgY2 = (isset($_POST['y2']) && is_numeric($_POST['y2'])) ? $_POST['y2'] : FALSE;
                $imgW = (isset($_POST['w']) && is_numeric($_POST['w'])) ? $_POST['w'] : FALSE;
                $imgH = (isset($_POST['h']) && is_numeric($_POST['h'])) ? $_POST['h'] : FALSE;
                
                $img = WideImage::load($_SERVER['DOCUMENT_ROOT'] . $imgUrl);
                $width = $img->getWidth();
                $height = $img->getHeight();
                if(isset($_POST['origin']) && $_POST['origin'] == 'plugin'){
                    if($width / $height >= 1.13513){
                        $ratio = $width / 420;
                    } else {
                        $ratio = $height / 370;
                    }
                } else {
                    $ratio = $width / 420;
                }

                $imgX *= $ratio;
                $imgY *= $ratio;
                $imgX2 *= $ratio;
                $imgY2 *= $ratio;
                $imgW *= $ratio;
                $imgH *= $ratio;
                
                if(isset($_POST['origin']) && $_POST['origin'] == 'plugin'){
                    if($tipo === '1'){
                        $saveImage = $img->crop(ceil($imgX), ceil($imgY), ceil($imgW), ceil($imgH))->resize(593, 395);
                    } else {
                        $saveImage = $img->crop(ceil($imgX), ceil($imgY), ceil($imgW), ceil($imgH))->resize(350, 233);
                    }
                } else {
                    if($tipo == 'thumb'){
                        $saveImage = $img->crop(ceil($imgX), ceil($imgY), ceil($imgW), ceil($imgH))->resize(305, 203);
                        unset($img);
                        $img = WideImage::load($_SERVER['DOCUMENT_ROOT'] . $imgUrl);
                        $posInit = ceil($imgX + (($imgW - $imgH) / 2));
                        $saveImageMid = $img->crop($posInit, ceil($imgY), ceil($imgH), ceil($imgH))->resize(94, 94);
                    } elseif($tipo == 'img1' || $tipo == 'img2'){
                        $saveImage = $img->crop(ceil($imgX), ceil($imgY), ceil($imgW), ceil($imgH))->resize(593, 395);
                    } elseif($tipo == 'thumbGaleria'){
                        $saveImage = $img->crop(ceil($imgX), ceil($imgY), ceil($imgW), ceil($imgH))->resize(430, 430);
                        unset($img);
                        $img = WideImage::load($_SERVER['DOCUMENT_ROOT'] . $imgUrl);
                        $saveImageMid = $img->crop(ceil($imgX), ceil($imgY), ceil($imgW), ceil($imgH))->resize(66, 66);
                        $local = 'galeria';
                    } elseif($tipo == 'thumbBanco'){
                        $saveImage = $img->crop(ceil($imgX), ceil($imgY), ceil($imgW), ceil($imgH))->resize(147, 126);
                    }
                }
                
                if($local){
                    $newImage = $_SERVER['DOCUMENT_ROOT'] . str_replace('/temp', "/$local", $imgUrl);
                    if($tipo == 'thumbGaleria'){
                        $local = 'galeria/thumb';
                        $newImage2 = $_SERVER['DOCUMENT_ROOT'] . str_replace('/temp', "/$local", $imgUrl);
                    }
                } else {
                    $newImage = $_SERVER['DOCUMENT_ROOT'] . str_replace('/temp', '', $imgUrl);
                    if($tipo == 'thumb'){
                        $newImage2 = $_SERVER['DOCUMENT_ROOT'] . str_replace('/temp', "/thumbMid", $imgUrl);
                    }
                }
                
                $saveImage->saveToFile($newImage, 100);
                if($tipo == 'thumb' || $tipo == 'thumbGaleria'){
                    $saveImageMid->saveToFile($newImage2, 100);
                }
                if(file_exists($newImage)){
                    unlink($_SERVER['DOCUMENT_ROOT'] . $imgUrl);
                    echo '{"status":"ok","url":"' . str_replace($_SERVER['DOCUMENT_ROOT'],'',$newImage) . '"}';
                } else {
                    echo '{"status":"error"}';
                }
            }
            break;
        
        case 'deleteTemp':
            $imgUrl = (isset($_POST['url']) && !empty($_POST['url'])) ? $_POST['url'] : FALSE;
            $local = '';
            if(!isset($_POST['origin']) || $_POST['origin'] != 'plugin'){
                $idNoticia = (isset($_POST['idNoticia']) && !empty($_POST['idNoticia'])) ? $_POST['idNoticia'] : FALSE;
                $idBanco = (isset($_POST['idBanco']) && !empty($_POST['idBanco'])) ? $_POST['idBanco'] : FALSE;
                $local = (isset($_POST['local']) && !empty($_POST['local'])) ? $_POST['local'] . '/' : '';
            }

            if($imgUrl){
                @unlink($_SERVER['DOCUMENT_ROOT'] . $local . $imgUrl);
                if(!isset($_POST['origin']) || $_POST['origin'] != 'plugin'){
                    if($idNoticia){
                        $tipoImg = $_POST['tipoImg'];
                        switch($tipoImg){
                            case 'thumb':
                                $column = 'imagemThumb';
                                break;
                            case 'img1':
                                $column = 'imagem1';
                                break;
                            case 'img2':
                                $column = 'imagem2';
                        }
                        if(isset($column)){
                            $conn = new DbConnect();
                            if(mysql_query("update noticia set $column = NULL, exibirHome = 0, importancia = 0 where id = $idNoticia;")){
                                echo '{"status":"ok"}';
                            }
                            $conn->close();
                        }
                    } elseif($idBanco){
                        $conn = new DbConnect();
                        mysql_query("update `arquivo` set `thumb` = '' where id = $idBanco;");
                        $conn->close();
                    }
                } else {
                    echo '{"status":"ok"}';
                }
            }
            break;
    }
?>