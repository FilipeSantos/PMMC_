<?php
    $data = isset($_POST['data']) && !empty($_POST['data']) ? $_POST['data'] : FALSE;
    $cidade = isset($_POST['cidade']) && !empty($_POST['cidade']) ? $_POST['cidade'] : FALSE;
    $divisao = isset($_POST['divisao']) && !empty($_POST['divisao']) ? $_POST['divisao'] : FALSE;
    
    if($data !== FALSE && $cidade !== FALSE && $divisao !== FALSE){
        require($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Programacao.class.php');
        
        $data = $data;
        $cidade = (integer) $cidade;
        $divisao = (integer) $divisao;
        
        $progObj = new Programacao();
		$programacao = $progObj->get_programacao(FALSE, $divisao, $cidade, FALSE, FALSE, $data);

		if($programacao !== FALSE){
			
			foreach($programacao as $i=>$item){
                $item->data = date('H:i', $item->data);
                if(is_array($item->provas) && !empty($item->provas)) {
                    $txt = '';
                    foreach($item->provas as $prova){
                        if(strpos($txt, "{$prova->titulo}, " ) !== FALSE){
							continue;
						}
                        $txt .= "{$prova->titulo}, ";
                    }
                    $posLast = strrpos($txt = substr($txt, 0, -2), ',');
                    if($posLast !== FALSE){
                            $item->provas = substr_replace($txt, ' e', $posLast, 1);
                    } else {
                            $item->provas = $txt;
                    }
                }
                
                $sexo = explode(',', $item->sexo);
                $txt = '';
                foreach($sexo as $itemSexo){
                    switch($itemSexo){
                        case '1':
                          $txt .= 'Masculino, ';
                          break;
                        case '2':
                          $txt .= 'Feminino, ';
                          break;
                        case '3':
                          $txt .= 'Misto, ';
                    }
                }
                $posLast = strrpos($txt = substr($txt, 0, -2), ',');
                if($posLast !== FALSE){
                    $item->sexo = substr_replace($txt, ' e', $posLast, 1);
                } else {
                    $item->sexo = $txt;
                }
                
                $categoria = explode(',', $item->categoria);
                $txt = '';
                foreach($categoria as $cat){
                        switch($cat){
                          case '0':
                            $txt .= 'Livre, ';
                            break;
                          default:
                            $txt .= "Até $cat anos, ";
                        }
                }
                $posLast = strrpos($txt = substr($txt, 0, -2), ',');
                if($posLast !== FALSE){
                    $item->categoria = substr_replace($txt, ' e', $posLast, 1);
                } else {
                    $item->categoria = $txt;
                }
                
                if(is_array($item->cidades) && !empty($item->cidades)) {
                    $txt = '';
                    if(count($item->cidades) === 2){
                        $txtCidade = '<strong>' .
							($cidade !== (integer) $item->cidades[0]->id
								? '<a class="btnBold" href="/jogos/cidades-participantes/' . htmlspecialchars($item->cidades[0]->slug) . '">' . htmlspecialchars($item->cidades[0]->nome) . '</a>'
								: htmlspecialchars($item->cidades[0]->nome))
							. ' x ' . ($cidade !== (integer) $item->cidades[1]->id
								? '<a class="btnBold" href="/jogos/cidades-participantes/' . htmlspecialchars($item->cidades[1]->slug) . '">' . htmlspecialchars($item->cidades[1]->nome) . '</a>'
								: htmlspecialchars($item->cidades[1]->nome)) . '</strong>';
                    } else {
                        $txt = '<strong>Cidades:</strong> ';
                        foreach($item->cidades as $cid){
                            $txt .=
								($cidade !== (integer) $cid->id
									? '<a href="/jogos/cidades-participantes/' . htmlspecialchars($cid->slug) . '">' . htmlspecialchars($cid->nome) . '</a>'
									: htmlspecialchars($cid->nome))
								. ', ';
                        }
                        $posLast = strrpos($txt = substr($txt, 0, -2), ',');
                        if($posLast !== FALSE){
                            $txtCidade = substr_replace($txt, ' e', $posLast, 1);
                        } else {
                            $txtCidade = $txt;
                        }
                    }
                    $item->cidades = $txtCidade;
                }

                $programacao[$i] = $item;
			}
			
            $arr['status'] = 'ok';
            $arr['divisao'] = $programacao;

			echo json_encode($arr);
            exit;
		}
    }

    echo '{"status":"error"}';
    exit;
?>