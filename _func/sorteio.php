<?php
        $last = isset($_POST['last']) ? $_POST['last'] : FALSE;
        
        if($last !== FALSE){
                include($_SERVER['DOCUMENT_ROOT'] . '/_inc/SorteioTecnico.class.php');
        
                $sorteio = new SorteioTecnico(TRUE);

                $files = $sorteio->GetLoadedFiles(NULL, $last);
                if($files !== FALSE){
                        echo json_encode($files);
                } else {
                        echo '{"status":"0"}';
                }

                unset($files);
        }        
?>