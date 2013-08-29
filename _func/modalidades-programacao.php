<?php
    $data = isset($_POST['data']) && !empty($_POST['data']) ? $_POST['data'] : FALSE;
    $modalidade = isset($_POST['modalidade']) && !empty($_POST['modalidade']) ? $_POST['modalidade'] : FALSE;
    
    if($data !== FALSE && $modalidade !== FALSE){
        require($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Programacao.class.php');
        
        $modalidade = (integer) $modalidade;

        $progObj = new Programacao();
	$programacao = $progObj->get_programacao(FALSE, FALSE, FALSE, $modalidade, FALSE, $data);

	if($programacao !== FALSE){
		
	    foreach($programacao as $i=>$item){
		$item->data = date('H:i', $item->data);
		
		$divFormat = explode(',', $item->divisao);
		$txt = '';
		foreach($divFormat as $divItem){
			switch($divItem){
				case '3':
					$txt .= 'Divisão Especial, ';
					break;
				case '4':
					$txt .= 'Modalidades Extras, ';
					break;
				default:
					$txt .= ($divItem . 'ª Divisão, ');
			}
		}

		$posLast = strrpos($txt = substr($txt, 0, -2), ',');
		if($posLast !== FALSE){
			$item->divisao = substr_replace($txt, ' e', $posLast, 1);
		} else {
			$item->divisao = $txt;
		}
		
		if(is_array($item->provas) && !empty($item->provas)) {
		    $txt = '';
		    foreach($item->provas as $prova){
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
                    
                        $txt = '<strong>Cidades:</strong> ';
                        foreach($item->cidades as $cid){
		        $txt .= ('<a href="/jogos/cidades-participantes/' . htmlspecialchars($cid->slug) . '">' . htmlspecialchars($cid->nome) . '</a>, ');
                        }
                        $posLast = strrpos($txt = substr($txt, 0, -2), ',');
                        if($posLast !== FALSE){
                            $txtCidade = substr_replace($txt, ' e', $posLast, 1);
                        } else {
                            $txtCidade = $txt;
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