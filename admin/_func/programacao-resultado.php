<?php
        require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
        $login = new Login();
        if(!$login->verificaLogin()){
           header('Location:/admin/login.php');
           exit();
        }
        $login->atualizaSession();

        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? (integer) $_GET['id'] : FALSE;
        
        if($id === FALSE){
                exit();
        }
        
        $_SESSION['idProgramacao'] = $id;
        $progObj = new Programacao();
        $modObj = new Modalidade();
        
        $programacao = $progObj->get_programacao($id);
        $programacao = $programacao[0];        
        $edicao = $programacao->resultado_tipo != 0;
        $totalProvas = count($progObj->get_provas_item_programacao($programacao->id));
        $provaUnica =   (strpos($programacao->divisao, ',') === FALSE)
                        && (strpos($programacao->sexo, ',') === FALSE)
                        && (strpos($programacao->categoria, ',') === FALSE)
                        && ($totalProvas === 1);
        $confrontoDireto = $modObj->verifica_modalidade_prova_confronto($programacao->id_modalidade);
        $cidadesTotal = $programacao->total_cidades <= 2;

        if($programacao->total_cidades === '0'){
            $_SESSION['tipoResultado'] = '3';
        } elseif($provaUnica){
                if($confrontoDireto){
                    if($cidadesTotal){
                        $_SESSION['tipoResultado'] = '1';
                    } else {
                        $_SESSION['tipoResultado'] = '3';
                    }
                } else {
                        $_SESSION['tipoResultado'] = '2';
                }
        } else {
                $_SESSION['tipoResultado'] = '3';
        }

        $divisao = explode(',', $programacao->divisao);
        $txt = '';
        foreach($divisao as $div){
                switch($div){
                        case '3':
                                $txt .= 'Divisão Especial, ';
                                break;
                        case '4':
                                $txt .= 'Modalidades Extras, ';
                                break;
                        default:
                                $txt .= "{$div}ª Divisão, ";
                }
        }
        $posLast = strrpos($txt = substr($txt, 0, -2), ',');
        if($posLast !== FALSE){
                $programacao->divisao = substr_replace($txt, ' e', $posLast, 1);
        } else {
                $programacao->divisao = $txt;
        }
        
        $categoria = explode(',', $programacao->categoria);
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
                $programacao->categoria = substr_replace($txt, ' e', $posLast, 1);
        } else {
                $programacao->categoria = $txt;
        }
        
        if(is_array($programacao->provas) && !empty($programacao->provas)){
                $txt = '';
                foreach($programacao->provas as $prova){
                        $txt .= "{$prova->titulo}, ";
                }
                $posLast = strrpos($txt = substr($txt, 0, -2), ',');
                if($posLast !== FALSE){
                        $programacao->provas = substr_replace($txt, ' e', $posLast, 1);
                } else {
                        $programacao->provas = $txt;
                }
        }
        
        $edit = FALSE;
        if((integer) $programacao->resultado_tipo !== 0){
                $edit = TRUE;
        }

        if((integer) $_SESSION['tipoResultado'] === 1){
            if(count($programacao->cidades) < 2){
                array_push($programacao->cidades, $programacao->cidades[0]);
            }
        }

    

        if($edit && ((integer) $programacao->resultado_layout_tipo === 2)){

            $arrOrdCid = Utility::object2Array($programacao->cidades);
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
        }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="pt" xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://developers.facebook.com/schema/">
   <head>
      <title>Jogos Abertos do Interior de 2011 - Admin</title>
      <meta name="title" content="Jogos Abertos do Interior de 2011 - Admin" />
      <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
      <meta content="pt" http-equiv="Content-Language" />
      <meta content="0" http-equiv="Expires" />
      <meta content="no-cache, must-revalidate" http-equiv="Cache-Control" />
      <meta content="no-cache" http-equiv="Pragma" />
      <meta name="revisit-after" content="3 days" />
      <meta name="author" content="Tboom Interactive - http://tboom.net" />
      <meta name="language" content="portuguese" />
      <meta name="distribution" content="Global" />
      <meta name="robots" content="noindex, nofollow" />
      <meta name="rating" content="General" />
      <meta name="geo.country" content="Brasil" />
      <meta name="dc.language" content="pt" />
      <link rel="SHORTCUT ICON" href="/admin/_img/favicon.ico">
      <style type="text/css">
         @import url(/admin/css/style.admin.css);
      </style>
   </head>
   <body>
        <div id="boxResultProg" class="resultado-tipo<?php echo $_SESSION['tipoResultado']; ?>">
                <h2><?php echo $edicao ? 'Edição' : 'Cadastro'; ?> de Resultados</h2>
                <div id="containerResultProg">
                        <ul id="listResultProg">
                                <li>
                                        <span class="tlt">Data</span>
                                        <span class="info"><?php echo date('d\/m\/Y', $programacao->data); ?></span>
                                        <br clear="all" />
                                </li>
                                <li>
                                        <span class="tlt">Descrição</span>
                                        <span class="info"><?php echo $programacao->descricao; ?></span>
                                        <br clear="all" />
                                </li>
                                <li>
                                        <span class="tlt">Divisão</span>
                                        <span class="info"><?php echo $programacao->divisao; ?></span>
                                        <br clear="all" />
                                </li>
                                <li>
                                        <span class="tlt">Categoria</span>
                                        <span class="info"><?php echo $programacao->categoria; ?></span>
                                        <br clear="all" />
                                </li>
                                <li>
                                        <span class="tlt">Modalidade</span>
                                        <span class="info"><?php echo $programacao->modalidade; ?></span>
                                        <br clear="all" />
                                </li>
                                <?php if(!empty($programacao->provas)) : ?>
                                        <li>
                                                <span class="tlt">Prova</span>
                                                <span class="info infoProvas"><?php echo $programacao->provas; ?></span>
                                                <br clear="all" />
                                        </li>
                                <?php endif; ?>
                        </ul>
                        
                        <h3>Resultado</h3>
                        <form id="formResultProgramacao" method="post" action="/admin/_func/programacao-resultado-salvar.php">
                                <?php if($confrontoDireto === TRUE && $provaUnica === TRUE && $cidadesTotal === TRUE && $_SESSION['tipoResultado'] !== '3') : ?>
                                        <div id="resultProgConfronto">
                                                <div class="item itemMid">
                                                        <label for="result[<?php echo $programacao->cidades[0]->id; ?>]" class="tltCidade"><?php echo $programacao->cidades[0]->nome; ?></label>
                                                        
                                                        <input type="text" name="result[<?php echo $programacao->cidades[0]->id; ?>]" id="result[<?php echo $programacao->cidades[0]->id; ?>]"
                                                                value="<?php echo ($edit && $programacao->cidades[0]->resultado_total !== NULL) ? $programacao->cidades[0]->resultado_total : ''; ?>"
                                                                class="form formConf formConfResult" />
                                                        <br clear="all" />
                                                        
                                                        <div class="containerAtl">
                                                                <input type="text" name="nomeAtleta[<?php echo $programacao->cidades[0]->id; ?>]"
                                                                        id="nomeAtleta[<?php echo $programacao->cidades[0]->id; ?>]" class="form formMin"
                                                                        value="<?php echo ($edit && !empty($programacao->cidades[0]->nome_atleta)) ? $programacao->cidades[0]->nome_atleta[0] : ''; ?>" />
                                                                        
                                                                <span class="infoAtl">Nome do Atleta (opcional)</span>
                                                        </div>
                                                </div>                                
                                                <div class="item itemCenter itemMid">
                                                        <span class="mark">X</span>
                                                </div>
                                                <div class="item itemMid">
                                                        <input type="text" name="result[<?php echo $programacao->cidades[1]->id; ?>]" id="result[<?php echo $programacao->cidades[1]->id; ?>]"
                                                                value="<?php echo ($edit && $programacao->cidades[1]->resultado_total !== NULL) ? $programacao->cidades[1]->resultado_total : ''; ?>"
                                                                class="form formConf formConfRight formConfResult" />
                                                        
                                                        <label for="result[<?php echo $programacao->cidades[1]->id; ?>]" class="tltCidade tltCidadeRight"><?php echo $programacao->cidades[1]->nome; ?></label>
                                                        <br clear="all" />
                                                        <div class="containerAtl containerAtlRight">
                                                                <input type="text" name="nomeAtleta[<?php echo $programacao->cidades[1]->id; ?>]"
                                                                        id="nomeAtleta[<?php echo $programacao->cidades[1]->id; ?>]" class="form formMin formRight"
                                                                        value="<?php echo ($edit && !empty($programacao->cidades[1]->nome_atleta)) ? $programacao->cidades[1]->nome_atleta[0] : ''; ?>" />
                                                                <span class="infoAtl infoAtlRight">Nome do Atleta (opcional)</span>
                                                        </div>
                                                </div>
                                                <br clear="all" />
                                                <label for="cidade[<?php echo $programacao->cidades[0]->id; ?>]" class="itemTlt"><div class="containerAtl">Vencedor</div></label>
                                                <label for="cidade[0]" class="itemTlt itemTltCenter">Empate</label>
                                                <label for="cidade[<?php echo $programacao->cidades[1]->id; ?>]" class="itemTlt"><div class="containerAtl containerAtlRight">Vencedor</div></label>
                                                <br clear="all" />
                                                
                                                <div class="item itemFirst">
                                                        <div class="containerAtl">
                                                                <input type="radio" name="cidadeVenc" id="cidade[<?php echo $programacao->cidades[0]->id; ?>]"
                                                                        value="<?php echo $programacao->cidades[0]->id; ?>" class="checkVencedor"
                                                                        <?php echo ($edit && $programacao->cidades[0]->resultado === 'v') ? ' checked="checked"' : ''; ?> />
                                                        </div>
                                                </div>
                                                <div class="item itemCenter itemFirst">
                                                                <input type="radio" name="cidadeVenc" id="cidade[0]" value="0" class="checkVencedor"
                                                                       <?php echo ($edit && $programacao->cidades[0]->resultado === 'e' && $programacao->cidades[1]->resultado === 'e') ? ' checked="checked"' : ''; ?> />
                                                </div>
                                                <div class="item itemFirst">
                                                        <div class="containerAtl containerAtlRight">
                                                                <input type="radio" name="cidadeVenc" id="cidade[<?php echo $programacao->cidades[1]->id; ?>]"
                                                                        value="<?php echo $programacao->cidades[1]->id; ?>" class="checkVencedor"
                                                                        <?php echo ($edit && $programacao->cidades[1]->resultado === 'v') ? ' checked="checked"' : ''; ?> />
                                                        </div>
                                                </div>
                                                <br clear="all" />
                                        </div>
                                
                                <?php elseif($provaUnica === TRUE && $confrontoDireto === FALSE && $_SESSION['tipoResultado'] !== '3') : ?>

                                        <div id="resultProgList">
                                                <div class="item">
                                                        <span class="tlt tlt1">Colocação</span>
                                                        <span class="tlt tlt2">Cidade</span>
                                                        <span class="tlt tlt3">Atleta(s)</span>
                                                        <br clear="all" />
                                                </div>
                                                
                                                <?php for($i = 0; $i < 3; $i++) : ?>
                                                        <div class="item">
                                                                <?php if(!$edit) : ?>
                                                                
                                                                        <select class="lblCol form" name="colocacao[<?php echo $i; ?>]" id="colocacao<?php echo $i; ?>">
                                                                                <option value="1"<?php echo ($i === 0) ? ' selected="selected"' : ''; ?>>1º</option>
                                                                                <option value="2"<?php echo ($i === 1) ? ' selected="selected"' : ''; ?>>2º</option>
                                                                                <option value="3"<?php echo ($i === 2) ? ' selected="selected"' : ''; ?>>3º</option>                                                                        
                                                                        </select>
                                                                        <select name="cidade[<?php echo $i; ?>]" id="cidade<?php echo $i; ?>" class="form">
                                                                                <option value="">Escolha a cidade</option>
                                                                                <?php foreach($programacao->cidades as $itemCid) : ?>
                                                                                        <option value="<?php echo $itemCid->id; ?>"><?php echo $itemCid->nome; ?></option>
                                                                                <?php endforeach; ?>
                                                                        </select>
                                                                        <input type="text" name="atleta[<?php echo $i; ?>]" id="atleta<?php echo $i; ?>" value="" class="form" />
                                                                        <br clear="all" />
                                                                        
                                                                <?php else : ?>
                                                                        
                                                                        <select class="lblCol form" name="colocacao[<?php echo $i; ?>]" id="colocacao<?php echo $i; ?>">
                                                                                <option value="1"<?php echo ((isset($arrOrdCidPrint) && (integer) $arrOrdCidPrint[$i]->resultado_colocacao === 1)) ? ' selected="selected"' : ''; ?>>1º</option>
                                                                                <option value="2"<?php echo ((isset($arrOrdCidPrint) && (integer) $arrOrdCidPrint[$i]->resultado_colocacao === 2)) ? ' selected="selected"' : ''; ?>>2º</option>
                                                                                <option value="3"<?php echo ((isset($arrOrdCidPrint) && (integer) $arrOrdCidPrint[$i]->resultado_colocacao === 3)) ? ' selected="selected"' : ''; ?>>3º</option>                                                                        
                                                                        </select>
                                                                        <select name="cidade[<?php echo $i; ?>]" id="cidade<?php echo $i; ?>" class="form">
                                                                                <option value="">Escolha a cidade</option>
                                                                                <?php foreach($programacao->cidades as $itemCid) : ?>
                                                                                        <option value="<?php echo $itemCid->id; ?>"
                                                                                                <?php echo (isset($arrOrdCidPrint) && $arrOrdCidPrint[$i]->id === $itemCid->id) ? ' selected="selected"' : ''; ?>><?php echo $itemCid->nome; ?></option>
                                                                                <?php endforeach; ?>
                                                                        </select>
                                                                        <input type="text" name="atleta[<?php echo $i; ?>]" id="atleta<?php echo $i; ?>"
                                                                                value="<?php echo (isset($arrOrdCidPrint) && !empty($arrOrdCidPrint[$i]->nome_atleta)) ? $arrOrdCidPrint[$i]->nome_atleta : ''; ?>" class="form" />
                                                                        <br clear="all" />
                                                                <?php endif; ?>
                                                        </div>
                                                <?php endfor; ?>
                                        </div>
                                
                                <?php endif; ?>
                                
                                <?php if(($provaUnica === TRUE && $cidadesTotal === TRUE && $confrontoDireto === TRUE && $_SESSION['tipoResultado'] !== '3') || ($provaUnica === TRUE && $confrontoDireto === FALSE && $_SESSION['tipoResultado'] !== '3')) : ?>
                                        <div class="boxPdf">
                                                
                                                <a class="btnBoxPdfResult<?php echo ($edit && $programacao->resultado_link_pdf !== NULL) ? ' hideBtn' : ''; ?>"
                                                        href="#">[ou cadastre um PDF]</a>
                                                
                                                <div class="boxInput">
                                                        <input type="hidden" name="arquivo" id="hdArquivo" class="required"
                                                               value="<?php echo ($edit && $programacao->resultado_link_pdf !== NULL) ? htmlentities($programacao->resultado_link_pdf) : ''; ?>" />
                                                        
                                                        <?php if(!$edit || $programacao->resultado_link_pdf === NULL) : ?>
                                                                <input type="hidden" name="upload" id="uploadArquivo" />
                                                        <?php endif; ?>
                                                        
                                                        <input type="file" name="Filedata" id="arquivo" class="form" autocomplete="off" />
                                                        <div class="boxInfoArquivo">
                                                                <?php if($edit && $programacao->resultado_link_pdf !== NULL) : ?>
                                                                        <strong class="strgPdf strgPdfNoHeiht">Arquivo:</strong>
                                                                        <span class="userArquivo" style="display: inline;"><?php echo $programacao->resultado_link_pdf; ?></span>
                                                                        <a class="excArquivo excProg" href="#" style="display: inline;">[alterar/excluir]</a>
                                                                <?php else : ?>
                                                                        <span class="userArquivo"></span>
                                                                        <a class="excArquivo excProg" href="#">[alterar/excluir]</a>
                                                                <?php endif; ?>
                                                        </div>
                                                        <br clear="all" />
                                                </div>
                                        </div>
                                <?php else : ?>
                                        <div class="boxPdf">

                                                <strong class="strgPdf">Arquivo:</strong>
                                                
                                                <input type="hidden" name="arquivo" id="hdArquivo" class="required"
                                                    value="<?php echo ($edit && $programacao->resultado_link_pdf !== NULL) ? htmlentities($programacao->resultado_link_pdf) : ''; ?>" />
                                                
                                                <?php if(!$edit || $programacao->resultado_link_pdf === NULL) : ?>
                                                    <input type="hidden" name="upload" id="uploadArquivo" />
                                                <?php endif; ?>
                                                
                                                <input type="file" name="Filedata" id="arquivo" class="form arquivoShow" autocomplete="off" />
                                                <div class="boxInfoArquivo">
                                                        
                                                        <?php if($edit && $programacao->resultado_link_pdf !== NULL) : ?>
                                                            <span class="userArquivo" style="display: inline;"><?php echo $programacao->resultado_link_pdf; ?></span>
                                                            <a class="excArquivo excProg" href="#" style="display: inline;">[alterar/excluir]</a>
                                                        <?php else : ?>
                                                            <span class="userArquivo"></span>
                                                            <a class="excArquivo excProg" href="#">[alterar/excluir]</a>
                                                        <?php endif; ?>
                                                </div>
                                                <?php if(!$edit || ($edit && $programacao->resultado_link_pdf === NULL)) : ?>
                                                    <script type="text/javascript">
                                                            createUploadifyInstanceArquivo('#arquivo', '*.pdf', 'Arquivos PDF', 'programacao-resultado-upload.php');
                                                    </script>
                                                <?php endif; ?>
                                                <br clear="all" />
                                        </div>
                                        <br clear="all" />
                                <?php endif; ?>
                                        <div class="itemTipo">
                                                <input type="radio" id="tipoResultado1" name="tipoResultado" value="1"
                                                       <?php echo !$edit ? 'checked="checked"' :
                                                                ((integer) $programacao->resultado_tipo === 1 ? 'checked="checked"' : ''); ?> />
                                                <label for="tipoResultado1">Resultado Extraoficial</label>
                                                <br clear="all" />
                                                <input type="radio" id="tipoResultado2" name="tipoResultado" value="2"
                                                       <?php echo (integer) $programacao->resultado_tipo === 2 ? 'checked="checked"' : ''; ?> />
                                                <label for="tipoResultado2">Resultado Oficial</label>
                                        </div>
                                        
                                        <?php if($confrontoDireto !== FALSE && $provaUnica !== FALSE) : ?>                                        
                                                <div class="itemWo">
                                                        <input type="checkbox" id="wo" name="wo" value="1" autocomplete="off"
                                                               <?php echo ($edit && ($programacao->cidades[0]->resultado === 'w' || $programacao->cidades[1]->resultado === 'w'))
                                                                        ? 'checked="checked"' : ''; ?> />
                                                        <label for="wo">WO</label>
                                                </div>
                                        <?php endif; ?>
                                        
                                        <br clear="all" />
                                        <input type="submit" id="submit" value="Cadastrar" />
                                        <div class="contLoading">
                                                <div id="boxLoading"><img src="/admin/img/loading2.gif" alt="Carregando" /></div>
                                        </div>
                                        <div class="boxMsg">
                                                <p class="msgError">Erro: Verifique os campos em vermelho</p>
                                                <p class="msgErrorServer">Ocorreu um erro durante o cadastramento. Tente novamente.</p>
                                                <p class="msgSucesso">Resultado salvo com sucesso!</p>
                                        </div>
                                        <br clear="all" />
                                </div>
                                <div id="msgLabel">&nbsp;</div>
                        </form>
                </div>
        </div>
        
        <div class="boxHide">
                <div id="lighboxSucesso">
                        <p>Programação atualizada com sucesso!</p>
                </div>
        </div>
   </body>
</html>