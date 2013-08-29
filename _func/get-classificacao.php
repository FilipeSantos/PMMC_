<?php
        $divisao = isset($_POST['divisao']) && is_numeric($_POST['divisao']) ? (integer) $_POST['divisao'] : FALSE;
        $limit = isset($_POST['limit']) && is_numeric($_POST['limit']) ? (integer) $_POST['limit'] : FALSE;

        if($divisao !== FALSE){
                require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Cidade.class.php');
                require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Medalha.class.php');
                $cidadesDiv1 = Cidade::get_cidades_classificacao_divisao($divisao, $limit);
                $totalItens = count($cidadesDiv1);
                
                for($i=0; $i < $totalItens; $i++){
                        $cidadesDiv1[$i]->pontos = number_format($cidadesDiv1[$i]->pontos, 1, ',', '.');
                }
                
                $arr = array();
                $arr['status'] = 'ok';
                $arr['cidades'] = $cidadesDiv1;
                echo json_encode($arr);
                exit;
        }
        echo '{"status":"error"}';
        exit;
?>