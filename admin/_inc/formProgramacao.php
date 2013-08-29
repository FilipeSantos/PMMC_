<form id="formProgramacao" class="boxForm boxFormProg" method="post" enctype="multipart/form-data" action="/admin/_func/programacao.php">
    
    <?php if($idProgramacao !== FALSE) :
        $_SESSION['idProgramacao'] = $idProgramacao;
    ?>
        <input type="hidden" id="id" name="id" value="<?php echo $idProgramacao; ?>" />
        <?php if((integer) $programacao->resultado_tipo !== 0) : ?>
            <input type="hidden" id="provaResultado" name="provaResultado" value="<?php echo $idProgramacao; ?>" />
        <?php endif; ?>
    <?php else :
            unset($_SESSION['idProgramacao']);
        endif;
    ?>
    
    <div class="item">
        <label for="dataPub" class="lblTlt">*Data</label>
        <input type="text" name="dataPub" id="dataPub" class="form form2 required" value="<?php echo (isset($programacao) && $programacao !== FALSE) ? date('d\/m\/Y H:i', $programacao->data) : date('d\/m\/Y H:i', time()); ?>" />
        <br clear="all" />
    </div>
    
    <div class="item">
        <label for="descricao" class="lblTlt">*Descrição</label>
        <input type="text" name="descricao" id="descricao" class="form"
            value="<?php echo (isset($programacao) && $programacao !== FALSE) ? $programacao->descricao : ''; ?>" maxlength="30" />
        <span class="info">Máximo: 30 caracteres</span>
        <br clear="all" />
    </div>
    
    <div id="itemProgModalidade" class="item">
        <label for="modalidade" class="lblTlt">*Modalidade</label>
        <select name="modalidade" id="modalidade" class="form required" autocomplete="off">
            <option value="">Selecione</option>
            <?php foreach($listModalidades as $modalidade) : ?>
                <option value="<?php echo $modalidade->id; ?>" rel="<?php echo $modalidade->prefixo; ?>"<?php echo (isset($programacao) && $programacao !== FALSE && $programacao->id_modalidade == $modalidade->id) ? ' selected="selected"' : ''; ?>><?php echo $modalidade->titulo; ?></option>
            <?php endforeach; ?>
        </select>
        <br clear="all" />
        <div id="loading2" class="contLoadingTop">
           <div class="boxLoading"<?php echo (isset($programacao) && $programacao !== FALSE) ? ' style="display: block;"' : ''; ?>><img src="/admin/img/loading2.gif" alt="Carregando" /></div>
        </div>
    </div>
    
    <div id="itemProgLocal" class="item itemHideValidate<?php echo (!isset($programacao) || $programacao === FALSE) ? ' itemHide' : ''; ?>">
        <label class="lblTlt">*Local</label>
        <?php if((isset($programacao) && $programacao !== FALSE)) : ?>
            <select name="local" id="local" class="form form3">
                <?php
                    $locais = $modObj->get_locais_modalidade($programacao->id_modalidade);
                    if($locais !== FALSE) :
                        foreach($locais as $local) : 
                ?>
                    <option value="<?php echo $local->id; ?>"<?php echo ($local->id == $programacao->id_local) ? ' selected="selected"' : ''; ?>><?php echo $local->nome; ?></option>
                <?php endforeach; endif; ?>
            </select>
        <?php else : ?>
            <select name="local" id="local" class="form form3"><option value=""></option></select>
        <?php endif; ?>
    </div>
    
    <div class="item itemHideValidate<?php echo (!isset($programacao) || $programacao === FALSE) ? ' itemHide' : ''; ?>">
        <label class="lblTlt">*Divisão</label>
        <?php if((isset($programacao) && $programacao !== FALSE)) : ?>
            <?php
                $divisoes = $modObj->get_divisoes_modalidade($programacao->id_modalidade);
                if($divisoes !== FALSE) :
                    $txtDiv = '';
            ?>
                <div class="containerDivisao">
                <?php foreach($divisoes as $divisao) :
                            switch($divisao){
                                case '3':
                                    $txtDiv = 'Divisão Especial';
                                    break;
                                case '4':
                                    $txtDiv = 'Modalidades Extras';
                                    break;
                                default:
                                    $txtDiv = $divisao . 'ª Divisão';
                            }
                            $txtRel = $divisao;
                ?>
                    <div class="itemInnerRadio">
                        <input type="checkbox" name="divisao[]" id="divisao<?php echo $divisao; ?>" value="<?php echo $divisao; ?>"
                              class="check checkDiv" autocomplete="off"<?php echo (strpos($programacao->divisao, $divisao) !== FALSE) ? ' checked="checked"' : ''; ?> rel="<?php echo $txtRel; ?>" />
                        <label for="divisao<?php echo $divisao; ?>"><?php echo $txtDiv; ?></label>
                    </div>
            <?php endforeach; ?>
                    <br clear="all" />
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="containerDivisao">&nbsp;</div>
        <?php endif; ?>
    </div>
    
    <div class="item itemHideValidate<?php echo (!isset($programacao) || $programacao === FALSE) ? ' itemHide' : ''; ?>">
        <label class="lblTlt">*Sexo</label>
        <?php if((isset($programacao) && $programacao !== FALSE)) :
            $sexo = $modObj->get_sexo_modalidade($programacao->id_modalidade);
            if($sexo !== FALSE) :
                $txtDiv = '';
        ?>
            <div class="containerSexo">
        <?php foreach($sexo as $itemSexo) :
                switch($itemSexo){
                    case '1':
                        $txtDiv = 'Masculino';
                        $txtRel = 'mas';
                        break;
                    case '2':
                        $txtDiv = 'Feminino';
                        $txtRel = 'fem';
                        break;
                    case '3' :
                        $txtDiv = 'Misto';
                        $txtRel = 'mis';
                }
        ?>                
                <div class="itemInnerRadio">
                    <input type="checkbox" name="sexo[]" id="sexo<?php echo $itemSexo; ?>" value="<?php echo $itemSexo; ?>"
                          class="check checkSexo" autocomplete="off"<?php echo (strpos($programacao->sexo, $itemSexo) !== FALSE) ? ' checked="checked"' : ''; ?> rel="<?php echo $txtRel; ?>" />
                    <label for="sexo<?php echo $itemSexo; ?>"><?php echo $txtDiv; ?></label>
                </div>
        <?php endforeach; ?>
                <br clear="all" />
            </div>
        <?php endif; ?>
        <?php else : ?>
            <div class="containerSexo">&nbsp;</div>
        <?php endif; ?>
    </div>
    
    <div id="itemProgCateg" class="item itemHideValidate<?php echo (!isset($programacao) || $programacao === FALSE) ? ' itemHide' : ''; ?>">
        <label class="lblTlt">*Categoria</label>
        <?php if((isset($programacao) && $programacao !== FALSE)) :
            $categorias = $modObj->get_categoria_modalidade($programacao->id_modalidade);
            if($categorias !== FALSE) :
                $txtDiv = '';
        ?>
            <div class="containerCategoria">
            <?php foreach($categorias as $categoria) :
                switch($categoria){
                    case '0':
                        $txtDiv = 'Livre';
                        $txtRel = 'l';
                        break;
                    default:
                        $txtDiv = 'Até ' . $categoria . ' anos';
                        $txtRel = $categoria;
                }
            ?>
                <div class="itemInnerRadio">
                    <input type="checkbox" name="categoria[]" id="categoria<?php echo $categoria; ?>" value="<?php echo $categoria; ?>"
                          class="check checkCateg" autocomplete="off"<?php echo (strpos($programacao->categoria, $categoria) !== FALSE) ? ' checked="checked"' : ''; ?> rel="<?php echo $txtRel; ?>" />
                    <label for="categoria<?php echo $categoria; ?>"><?php echo $txtDiv; ?></label>
                </div>
            <?php endforeach; ?>                
                <br clear="all" />
            </div>
        <?php endif; ?>
        <?php else : ?>
            <div class="containerCategoria">&nbsp;</div>
        <?php endif; ?>
    </div>
    
    <div id="itemProgProva" class="item itemHideValidate<?php echo (!isset($programacao) || $programacao === FALSE || empty($programacao->provas)) ? ' itemHide' : ''; ?>">
        <label class="lblTlt lblTltFull">*Selecione a prova</label>
        <?php if((isset($programacao) && $programacao !== FALSE)) :
            $provas = $modObj->get_prova_modalidade($programacao->id_modalidade);
            if($provas !== FALSE) :
        ?>
            <div class="containerProvas">
            <?php
                $divPrev = '';
                $sexoPrev = '';
                $categPrev = '';
                foreach($provas as $key=>$prova) :
                    if($divPrev != $prova->divisao){
                        $sexoPrev = '';
                        $categPrev = '';

                        if($prova->divisao == '3'){
                              $txtDiv = '<h3 class="tltDiv tltDivFloat">Divisão Especial</h3><a class="btnSelectAll" href="#boxProvaSexo">[selecionar todas]</a><a class="btnDeleteAll" href="#boxProvaSexo">[desfazer seleção]</a>';
                        } elseif($prova->divisao == '4'){
                              $txtDiv = '<h3 class="tltDiv tltDivFloat">Modalidades Extras</h3><a class="btnSelectAll" href="#boxProvaSexo">[selecionar todas]</a><a class="btnDeleteAll" href="#boxProvaSexo">[desfazer seleção]</a>';
                        } else {
                              $txtDiv = '<h3 class="tltDiv tltDivFloat">' . $prova->divisao . 'ª Divisão</h3><a class="btnSelectAll" href="#boxProvaSexo">[selecionar todas]</a><a class="btnDeleteAll" href="#boxProvaSexo">[desfazer seleção]</a>';
                        }
                        
                        echo '<div class="boxProvaDiv" rel="' . $prova->divisao . '">' . $txtDiv;
                    }
                    $divPrev = $prova->divisao;

                    if($sexoPrev != $prova->sexo){
                        if($prova->sexo == '1'){
                            $txtSexo = '<h3 class="tltSexo">Masculino</h3>';
                        } elseif($prova->sexo == '2'){
                            $txtSexo = '<h3 class="tltSexo">Feminino</h3>';
                        } elseif($prova->sexo == '3'){
                            $txtSexo = '<h3 class="tltSexo">Misto</h3>';
                        }
                        
                        echo '<div class="boxProvaSexo" rel="' . $prova->sexo . '">' . $txtSexo;
                        
                    }
                    $sexoPrev = $prova->sexo;
                    
                    if($categPrev != $prova->categoria_idade){
                        if($prova->categoria_idade == '0'){
                            $txtCateg = '<h3 class="tltCateg">Livre</h3>';
                        } else {
                            $txtCateg = '<h3 class="tltSexo">Até ' . $prova->categoria_idade . ' anos</h3>';
                        }
                        
                        echo '<div class="boxProvaCateg" rel="' . $prova->categoria_idade . '">' . $txtCateg;
                    }
                    $categPrev = $prova->categoria_idade;
            ?>
                <div class="itemInnerRadio">
                    <input type="checkbox" name="prova[]" id="prova<?php echo $prova->id; ?>" value="<?php echo $prova->id; ?>"
                          class="check" autocomplete="off"<?php echo $programacao->provas !== FALSE ? ((Utility::in_array_recursive($prova->id, $programacao->provas) !== FALSE) ? ' checked="checked"' : '') : ''; ?> />
                    <label for="prova<?php echo $prova->id; ?>"><?php echo $prova->titulo; ?></label>
                </div>
            <?php
                    if(!isset($provas[$key + 1]) || (isset($provas[$key + 1]) && ($divPrev != $provas[$key + 1]->divisao))){
                        echo '<br clear="all" /></div>';
                        $sexoPrev = '';
                        $categPrev = '';
                    }
                    
                    if(!isset($provas[$key + 1]) || (isset($provas[$key + 1]) && ($sexoPrev != $provas[$key + 1]->sexo))){
                        echo '<br clear="all" /></div>';
                        $categPrev = '';
                    }
                    
                    if(!isset($provas[$key + 1]) || (isset($provas[$key + 1]) && ($categPrev != $provas[$key + 1]->categoria_idade))){
                        echo '<br clear="all" /></div><br clear="all" />';
                    }
                    
                endforeach;
            ?>
                <br clear="all" />
            </div>
        <?php endif; ?>            
        <?php else : ?>
            <div class="containerProvas">&nbsp;</div>
        <?php endif; ?>
    </div>
    
    <div id="itemProgCidade" class="item itemHide itemHideValidate">
        <label class="lblTlt lblTltFull lblTltFullCidade">*Selecione a cidade</label>
        <a class="btnSelectAll btnSelectAllCid" href="#containerCidades">[selecionar todas]</a>
        <a class="btnDeleteAll btnDeleteAllCid" href="#containerCidades">[desfazer seleção]</a>
        <?php if((isset($programacao) && $programacao !== FALSE)) :
            $cidades = $modObj->get_cidade_modalidade($programacao->id_modalidade);
            if($cidades !== FALSE) :
        ?>
            <div class="containerCidades">
            <?php
                $idsCidades = array();
                $lengthCid = is_array($programacao->cidades) && !empty($programacao->cidades) ? count($programacao->cidades) : 0;
                for($k = 0; $k < $lengthCid; $k++){
                    $idsCidades[] = $programacao->cidades[$k]->id;
                }
                foreach($cidades as $cidade) : ?>
                
                <div class="itemInnerRadio">
                    <input type="checkbox" name="cidade[]" id="cidade<?php echo $cidade->id; ?>" value="<?php echo $cidade->id; ?>"
                          class="check" autocomplete="off" rel="<?php echo implode('|', $cidade->modalidade_sub); ?>"<?php echo (in_array($cidade->id, $idsCidades) !== FALSE) ? ' checked="checked"' : ''; ?> />
                    <label for="cidade<?php echo $cidade->id; ?>"><?php echo $cidade->nome; ?></label>
                </div>
            <?php endforeach;?>
                <br clear="all" />
            </div>
        <?php endif; ?>            
        <?php else : ?>
            <div class="containerCidades">&nbsp;</div>
        <?php endif; ?>
    </div>    
    
    <div id="boxFormBot" class="itemHide itemHideValidate">
        <div class="item">
            <?php if(isset($programacao) && $programacao !== FALSE) : ?>
                <input type="checkbox" name="status" id="status" value="1" <?php echo ($programacao->status == '1') ? 'checked="checked"' : ''; ?> style="float: left;" />
            <?php else : ?>
                <input type="checkbox" name="status" id="status" value="1" checked="checked" style="float: left;" />
            <?php endif; ?>
            <label class="lblTlt" for="status" style="width: auto;">Ativo</label>
            <br clear="all" />
        </div>
        <div class="item" style="padding-top: 20px;">
            <?php if(isset($programacao)) : ?>
                <?php if((integer) $programacao->resultado_tipo !== 0) : ?>
                    <a class="btnLink btnResultProg" href="/admin/_func/programacao-resultado.php?id=<?php echo $idProgramacao; ?>">[Editar Resultado]</a>
                <?php else : ?>
                    <a class="btnLink btnResultProg" href="/admin/_func/programacao-resultado.php?id=<?php echo $idProgramacao; ?>">[Cadastrar Resultado]</a>
                <?php endif; ?>                        
            <?php endif; ?>
        </div>
        <div class="boxMsg">
           <p class="error">Preencha os campos corretamente.</p>
           <p class="errorServer">Ocorreu um erro durante o cadastramento. Tente novamente.</p>
        </div>
        <div class="contLoading">
           <div id="boxLoading"><img src="/admin/img/loading2.gif" alt="Carregando" /></div>
        </div>
        <input type="submit" id="submit" value="Salvar" />
        <br clear="all" />
    </div>
    
    <div id="msgSucesso" class="itemHide itemHideValidate">
        <p>Programação atualizada com sucesso!</p>
    </div>
    <div id="msgLabel">&nbsp;</div>
    <a href="/admin/programacao.php" id="btnVoltar" class="btnLink hideBtn">[Voltar]</a>
 </form>