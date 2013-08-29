<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Programacao.class.php');
    require($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Modalidade.class.php');
    
    $divisao = isset($_POST['divisao']) && !empty($_POST['divisao']) ? (integer) $_POST['divisao'] : FALSE;
    $modalidade = isset($_POST['modalidade']) && !empty($_POST['modalidade']) ? (integer) $_POST['modalidade'] : FALSE;
    
    if($divisao) {
        $progObj = new Programacao();
        $modObj = new Modalidade();
        
        if($modalidade) {
            
        }
    }
        
        $programacao = $progObj->get_programacao(FALSE, $divisao, $cidade, $modalidade, $local, $data);
        
        if($programacao === FALSE){
            echo '<p>Nenhum evento cadastrado para essa data.</p>';
            exit;
        }
?>       
        <?php foreach($programacao as $key=>$item) : ?>
            <div class="item item<?php echo ($key % 2 == 0) ? '1' : '2'; ?>">
                <div class="data">
                    <div class="data-left">
                        <span><?php echo date('H\hi', $item->data); ?></span>
                        <img src="/_img/modalidades/ico_cat<?php echo $item->id_modalidade; ?>.png" alt="<?php echo htmlentities($item->modalidade); ?>" />
                    </div>
                    <div class="data-right">
                        <h4><?php echo htmlspecialchars($item->modalidade); ?></h4>
                        <p>
                            <?php
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
                                    foreach($item->cidades as $cid){
                                        $txt .= "{$cid->nome}, ";
                                    }
                                    $posLast = strrpos($txt = substr($txt, 0, -2), ',');
                                    if($posLast !== FALSE){
                                        $txtCidade = substr_replace($txt, ' e', $posLast, 1);
                                    } else {
                                        $txtCidade = $txt;
                                    }
                                }
                            ?>
                            
                            <?php if(!empty($item->provas)) : ?>
                                <b>Prova:  </b><?php echo htmlspecialchars($item->provas); ?><br />
                            <?php endif; ?>
                            <b>Categoria: </b><?php echo htmlspecialchars($item->sexo) . ' / ' . htmlspecialchars($item->categoria); ?><br>
                            <b>Local de Competição: </b><?php echo htmlspecialchars($item->local); ?> <a href="/mapa-interativo">(veja mapa dos Locais de Competição)</a><br>
                            <b>Cidades : </b><?php echo htmlspecialchars($txtCidade); ?><br>
                        </p>
                    </div>
                </div>
                <?php
                    if((integer) $item->resultado_layout_tipo !== 0) :
                        if((integer) $item->resultado_layout_tipo === 1){
                            if(!empty($item->cidades[0]->nome_atleta)){
                                $tipoResult = '3';
                            } else {
                                $tipoResult = '1';
                            }
                        } elseif((integer) $item->resultado_layout_tipo === 2){
                            $tipoResult = '2';
                        } else {
                            $tipoResult = '4';
                        }

                ?>
                    <div class="res resultado_<?php echo $tipoResult; ?>">
                        
                        <?php if($tipoResult === '1') : ?>
                        
                            <p class="<?php echo $item->cidades[0]->resultado === 'v' ? 'vitoria' : ''; ?>">
                                <span class="pts"><?php echo $item->cidades[0]->resultado_total; ?></span>
                                <img src="/_img/cidade/bandeiras/<?php echo $item->cidades[0]->bandeira; ?>" />
                                <span class="nome"><?php echo $item->cidades[0]->nome; ?></span>
                            </p>
                            <span class="x">X</span>
                            <p class="<?php echo $item->cidades[1]->resultado === 'v' ? 'vitoria' : ''; ?>">
                                <span class="pts"><?php echo $item->cidades[1]->resultado_total; ?></span>
                                <img src="/_img/cidade/bandeiras/<?php echo $item->cidades[1]->bandeira; ?>" />
                                <span class="nome"><?php echo $item->cidades[1]->nome; ?></span>
                            </p>
                            
                        <?php
                            elseif($tipoResult === '2') :
                                
                                $arrOrdCid = array();
                                $arrAtl = array();
                                
                                foreach($item->cidades as $cid){
                                    if(!empty($cid->resultado_colocacao) && is_array($cid->resultado_colocacao)){
                                        foreach($cid->resultado_colocacao as $key=>$itlPos){
                                            $prevItem = FALSE;
                                            if(isset($arrOrdCid[$itlPos])){
                                                $prevItem = TRUE;
                                                $itlPos++;
                                            }
                                            $arrOrdCid[$itlPos] = $cid;
                                            if($prevItem === TRUE){
                                                $arrOrdCid[$itlPos]->repete = 1;
                                            }
                                            $arrAtl[$itlPos] = $cid->nome_atleta[$key];
                                            unset($arrOrdCid[$itlPos]->resultado_colocacao);
                                        }
                                    }
                                }
                                
                                if(!empty($arrOrdCid)){
                                    ksort($arrOrdCid);
                                }
                        ?>
                            
                            <?php
                                for($i = 0; $i < 3; $i++) :
                                    $keys = array_keys($arrOrdCid);
                                    $key = $keys[$i];
                                    $colocacao = isset($arrOrdCid[$key]->repete) ? $key - 1 : $key;
                            ?>
                                <p>
                                    <span class="pts"><?php echo $key; ?>º</span>
                                    <img src="/_img/cidade/bandeiras/<?php echo $arrOrdCid[$key]->bandeira; ?>" />
                                    <span class="nome"><b><?php echo $arrAtl[$key]; ?></b><br />
                                    (<?php echo $arrOrdCid[$key]->nome; ?>)</span>
                                </p>
                            <?php endfor; ?>
                        
                        <?php elseif($tipoResult === '3') : ?>
                        
                            <p class="<?php echo $item->cidades[0]->resultado === 'v' ? 'vitoria' : ''; ?>">
                                <span class="pts"><?php echo $item->cidades[0]->resultado_total; ?></span>
                                <img src="/_img/cidade/bandeiras/<?php echo $item->cidades[0]->bandeira; ?>" />
                                <span class="nome"><b><?php echo $item->cidades[0]->nome_atleta[0]; ?></b><br />
                                (<?php echo $item->cidades[0]->nome; ?>)</span>
                            </p>
                            <span class="x">X</span>
                            <p class="<?php echo $item->cidades[1]->resultado === 'v' ? 'vitoria' : ''; ?>">
                                <span class="pts"><?php echo $item->cidades[1]->resultado_total; ?></span>
                                <img src="/_img/cidade/bandeiras/<?php echo $item->cidades[1]->bandeira; ?>" />
                                <span class="nome"><b><?php echo $item->cidades[1]->nome_atleta[0]; ?></b><br />
                                (<?php echo $item->cidades[1]->nome; ?>)</span>
                            </p>
                        
                        <?php elseif($tipoResult === '4') : ?>
                        
                                <a href="/upload/programacao_resultado/<?php echo $item->resultado_link_pdf; ?>" class="ver_res"><span>ver resultado</span></a>

                        <?php endif; ?>
                            
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    
    <?php endif; ?>