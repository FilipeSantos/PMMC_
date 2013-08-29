<?php
    require($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Programacao.class.php');
    
    $data = isset($_POST['data']) && !empty($_POST['data']) ? $_POST['data'] : FALSE;
    $divisao = isset($_POST['divisao']) && !empty($_POST['divisao']) ? $_POST['divisao'] : FALSE;
    $cidade = isset($_POST['cidade']) && !empty($_POST['cidade']) ? $_POST['cidade'] : FALSE;
    $modalidade = isset($_POST['modalidade']) && !empty($_POST['modalidade']) ? $_POST['modalidade'] : FALSE;
    $local = isset($_POST['local']) && !empty($_POST['local']) ? $_POST['local'] : FALSE;
    
    if($data || $divisao || $cidade || $modalidade || $local) :
        $progObj = new Programacao();
        $programacao = $progObj->get_programacao(FALSE, $divisao, $cidade, $modalidade, $local, $data);
        
        if($programacao === FALSE){
            echo '<p class="infoNenhumEvento">Nenhum evento cadastrado.</p>';
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
                        <h4><a href="/modalidade/<?php echo htmlspecialchars($item->slug_modalidade); ?>"><?php echo htmlspecialchars($item->modalidade); ?></a></h4>
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
                                
                                $txtCidade = '';
                                if(is_array($item->cidades) && !empty($item->cidades)) {
                                    $txt = '';
                                    foreach($item->cidades as $cid){
                                        $txt .= '<a href="/jogos/cidades-participantes/' . htmlspecialchars($cid->slug) . '">' . htmlspecialchars($cid->nome) . '</a>, ';
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
                            <b>Local de Competição: </b><?php echo htmlspecialchars($item->local); ?> <a href="/mapa-interativo?modalidade=<?php echo htmlentities($item->slug_modalidade); ?>">(veja mapa dos Locais de Competição)</a><br>
                            <?php if(!empty($txtCidade)) : ?>
                                <b>Cidades : </b><?php echo $txtCidade; ?><br>
                            <?php endif; ?>
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
                                <img src="/_img/cidade/bandeiras/<?php echo $item->cidades[0]->bandeira; ?>" title="<?php echo htmlspecialchars($item->cidades[0]->nome); ?>" />
                                <span class="nome"><?php echo $item->cidades[0]->nome . ($item->cidades[1]->resultado === 'w' ? ' <span class="cidWo">(venceu por W.O.)</span>' : ''); ?></span>
                            </p>
                            <span class="x">X</span>
                            <p class="<?php echo $item->cidades[1]->resultado === 'v' ? 'vitoria' : ''; ?>">
                                <span class="pts"><?php echo $item->cidades[1]->resultado_total; ?></span>
                                <img src="/_img/cidade/bandeiras/<?php echo $item->cidades[1]->bandeira; ?>" title="<?php echo htmlspecialchars($item->cidades[1]->nome); ?>" />
                                <span class="nome"><?php echo $item->cidades[1]->nome . ($item->cidades[0]->resultado === 'w' ? ' <span class="cidWo">(venceu por W.O.)</span>' : ''); ?></span>
                            </p>
                            
                        <?php
                            elseif($tipoResult === '2') :
                                
                                $arrOrdCid = Utility::object2Array($item->cidades);
                                $arrOrdCidPrint = array();
                                $i = 0;
                                $j = 0;
                    
                                do{
                                    if(is_array($arrOrdCid[$i]['resultado_colocacao'])){
                                        if(count($arrOrdCid[$i]['resultado_colocacao']) === 1){
                                            $arrOrdCidPrint[$j] = $arrOrdCid[$i];
                                            $arrOrdCidPrint[$j]['resultado_colocacao'] = array_shift($arrOrdCidPrint[$j]['resultado_colocacao']);
                                            if(is_array($arrOrdCidPrint[$j]['nome_atleta']) && !empty($arrOrdCidPrint[$j]['nome_atleta'])){
                                                $arrOrdCidPrint[$j]['nome_atleta'] = array_shift($arrOrdCidPrint[$j]['nome_atleta']);
                                            }
                                            $j++;
                                        } else {
                                            $itemColTemp = array_shift($arrOrdCid[$i]['resultado_colocacao']);
                                            if(is_array($arrOrdCid[$i]['nome_atleta']) && !empty($arrOrdCid[$i]['nome_atleta'])){
                                                $itemAltTemp = array_shift($arrOrdCid[$i]['nome_atleta']);
                                            }
                                            $arrOrdCid[] = $arrOrdCidPrint[$j] = $arrOrdCid[$i];
                                            $arrOrdCidPrint[$j]['resultado_colocacao'] = $itemColTemp;
                                            $arrOrdCidPrint[$j]['nome_atleta'] = isset($itemAltTemp) ? $itemAltTemp : NULL;
                                            $j++;
                                        }
                                    }
                                    $i++;
                                } while($i < count($arrOrdCid));
                                
                                for($i = 0; $i < 3; $i++){
                                    for($j = $i; $j < 3; $j++){
                                        if($arrOrdCidPrint[$i]['resultado_colocacao'] > $arrOrdCidPrint[$j]['resultado_colocacao']){
                                            list($arrOrdCidPrint[$i], $arrOrdCidPrint[$j]) = array($arrOrdCidPrint[$j], $arrOrdCidPrint[$i]);
                                        }
                                    }
                                }
                            
                                $arrOrdCidPrint = array_map('Utility::array2Object', $arrOrdCidPrint);
                        ?>
                            
                            <?php
                                for($i = 0; $i < 3; $i++) : ?>
                                <p>
                                    <span class="pts"><?php echo $arrOrdCidPrint[$i]->resultado_colocacao; ?>º</span>
                                    <img src="/_img/cidade/bandeiras/<?php echo $arrOrdCidPrint[$i]->bandeira; ?>" title="<?php echo htmlspecialchars($arrOrdCidPrint[$i]->nome); ?>" />
                                    <span class="nome"><b><?php echo $arrOrdCidPrint[$i]->nome_atleta; ?></b><br />
                                    (<?php echo $arrOrdCidPrint[$i]->nome; ?>)</span>
                                </p>
                            <?php endfor; ?>
                        
                        <?php elseif($tipoResult === '3') : ?>
                        
                            <p class="<?php echo $item->cidades[0]->resultado === 'v' ? 'vitoria' : ''; ?>">
                                <span class="pts"><?php echo $item->cidades[0]->resultado_total; ?></span>
                                <img src="/_img/cidade/bandeiras/<?php echo $item->cidades[0]->bandeira; ?>" title="<?php echo htmlspecialchars($item->cidades[0]->nome); ?>" />
                                <span class="nome"><b><?php echo $item->cidades[0]->nome_atleta[0]; ?></b><br />
                                (<?php echo $item->cidades[0]->nome . ($item->cidades[1]->resultado === 'w' ? ' <span class="cidWo">[venceu por W.O.]</span>' : ''); ?>)</span>
                            </p>
                            <span class="x">X</span>
                            <p class="<?php echo $item->cidades[1]->resultado === 'v' ? 'vitoria' : ''; ?>">
                                <span class="pts"><?php echo $item->cidades[1]->resultado_total; ?></span>
                                <img src="/_img/cidade/bandeiras/<?php echo $item->cidades[1]->bandeira; ?>" title="<?php echo htmlspecialchars($item->cidades[1]->nome); ?>" />
                                <span class="nome"><b><?php echo $item->cidades[1]->nome_atleta[0]; ?></b><br />
                                (<?php echo $item->cidades[0]->nome . ($item->cidades[0]->resultado === 'w' ? ' <span class="cidWo">[venceu por W.O.]</span>' : ''); ?>)</span>
                            </p>
                        
                        <?php elseif($tipoResult === '4' && $item->resultado_link_pdf !== NULL) : ?>
                        
                                <a href="/upload/programacao_resultado/<?php echo $item->resultado_link_pdf; ?>" target="_blank" class="ver_res"><span>ver resultado</span></a>

                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    
    <?php endif; ?>