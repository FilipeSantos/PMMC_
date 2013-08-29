<?php
        $divisao = isset($_POST['divisao']) && is_numeric($_POST['divisao']) ? (integer) $_POST['divisao'] : FALSE;

        if($divisao !== FALSE){
                require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Programacao.class.php');
                
                $progObj = new Programacao();
                $programacao = $progObj->get_programacao(FALSE, $divisao, FALSE, FALSE, FALSE, FALSE, strtotime('-2 hours'), 20);
                
                if($programacao !== FALSE){
                        $totalProg = count($programacao);
                        for($i = 0; $i < $totalProg; $i++){
        
                                if(is_array($programacao[$i]->provas) && !empty($programacao[$i]->provas)) {
                                        $txt = '';
                                        foreach($programacao[$i]->provas as $prova){
                                                $txt .= "{$prova->titulo}, ";
                                        }
                                        $posLast = strrpos($txt = substr($txt, 0, -2), ',');
                                        if($posLast !== FALSE){
                                                $programacao[$i]->provas = substr_replace($txt, ' e', $posLast, 1);
                                        } else {
                                                $programacao[$i]->provas = $txt;
                                        }
                                }
                                
                                $programacao[$i]->data = date('d\/m - H:i', $programacao[$i]->data);
                        }
                        
                        $arr = array();
                        $arr['status'] = 'ok';
                        $arr['item'] = $programacao;
                        
                        echo json_encode($arr);
                        exit;
                }
        }
        echo '{"status":"error"}';
        exit;
?>