<?php
   require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
   $login = new Login();
   if(!$login->verificaLogin()){
      header('Location:/admin/login.php');
      exit();
   }
   $login->atualizaSession();
   
   $data = isset($_GET['data']) && !empty($_GET['data']) ? $_GET['data'] : FALSE;
   $divisao = isset($_GET['divisao']) && !empty($_GET['divisao']) && is_numeric($_GET['divisao']) ? (integer) $_GET['divisao'] : FALSE;
   $modalidade = isset($_GET['modalidade']) && !empty($_GET['modalidade']) && is_numeric($_GET['modalidade']) ? (integer) $_GET['modalidade'] : FALSE;
   $cidade = isset($_GET['cidade']) && !empty($_GET['cidade']) && is_numeric($_GET['cidade']) ? (integer) $_GET['cidade'] : FALSE;
   $local = isset($_GET['local']) && !empty($_GET['local']) && is_numeric($_GET['local']) ? (integer) $_GET['local'] : FALSE;
   
   $progObj = new Programacao();
   $datasProg = $progObj->get_datas_programacao(FALSE);
   $divisoesProg = $progObj->get_divisoes_programacao(FALSE);
   $modalidadesProg = $progObj->get_modalidades_programacao(FALSE);
   $cidadesProg = $progObj->get_cidades_programacao($modalidade, FALSE);
   $locaisProg = $progObj->get_locais_programacao($modalidade, FALSE);
   $programacao = $progObj->get_programacao(FALSE, $divisao, $cidade, $modalidade, $local, $data);
   
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
   <body class="programacao">
      <div id="wrapper">
         <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/header.php'); ?>
         <div id="contentTb">
            <h2>Programação</h2>
            <ul id="menuSubSec">
               <li><a<?php echo isset($pg_programacao) ? ' class="hover"' : ''; ?> href="/admin/programacao.php">Home</a></li>
               <li><a<?php echo isset($pg_programacao_cadastrar) ? ' class="hover"' : ''; ?> href="/admin/programacao/cadastrar.php">Cadastrar</a></li>
            </ul>
            <br clear="all" />
         <?php if($programacao !== FALSE) : ?>
            <div class="boxFiltro boxFiltroProgramacao">
               <form id="formFiltro" class="formFiltroProg" method="get" action="/admin/programacao.php">
                  <span>Filtrar por:</span>
                  <div class="itensFiltro">
                     <?php if($datasProg !== FALSE) : ?>
                        <div class="item">
                           <label for="data">Data</label>
                           <select name="data" id="data">
                              <option value="">Todas</option>
                              <?php foreach($datasProg as $item) : ?>
                                 <option value="<?php echo date('d-m-Y', $item->data); ?>"<?php echo ($data == date('d-m-Y', $item->data)) ? ' selected="selected"' : ''; ?>><?php echo date('d\/m', $item->data); ?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                     <?php endif; ?>
                     
                     <?php if($divisoesProg !== FALSE) : ?>
                        <div class="item">
                           <label for="divisao">Divisão</label>
                           <select name="divisao" id="divisao">
                              <option value="">Todas</option>
                              <?php foreach($divisoesProg as $item) : ?>
                                 <option value="<?php echo $item->divisao; ?>"<?php echo ($divisao == $item->divisao) ? ' selected="selected"' : ''; ?>><?php echo $item->divisao == '3' ? 'Divisão Especial' : ($item->divisao == '4' ? 'Modalidades Extras' : $item->divisao . 'º Divisão'); ?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                     <?php endif; ?>
                     
                     <?php if($modalidadesProg !== FALSE) : ?>
                        <div class="item">
                           <label for="modalidade">Modalidade</label>
                           <select name="modalidade" id="modalidade">
                              <option value="">Todas</option>
                              <?php foreach($modalidadesProg as $item) : ?>
                                 <option value="<?php echo $item->id; ?>"<?php echo ($modalidade == $item->id) ? ' selected="selected"' : ''; ?>><?php echo $item->titulo; ?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                     <?php endif; ?>   
                     
                     <?php if($locaisProg !== FALSE) : ?>
                        <div class="item">
                           <label for="local">Local</label>
                           <select name="local" id="local">
                              <option value="">Todas</option>
                              <?php foreach($locaisProg as $item) : ?>
                                 <option value="<?php echo $item->id; ?>"<?php echo ($local == $item->id) ? ' selected="selected"' : ''; ?>><?php echo $item->nome; ?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                     <?php endif; ?>
                     
                     <?php if($cidadesProg !== FALSE) : ?>
                        <div class="item">
                           <label for="cidade">Cidade</label>
                           <select name="cidade" id="cidade">
                              <option value="">Todas</option>
                              <?php foreach($cidadesProg as $item) : ?>
                                 <option value="<?php echo $item->id; ?>"<?php echo ($cidade == $item->id) ? ' selected="selected"' : ''; ?>><?php echo $item->nome; ?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                     <?php endif; ?>
                     
                     <div class="item">
                        <input type="submit" id="submit" class="submitProg" value="Filtrar" />
                     </div>
                     
                     <br clear="all" />
                  </div>
                  <br clear="all" />
               </form>
            </div>
         <?php endif; ?>
            <div class="boxInfo"></div>
            
            <?php if($programacao !== FALSE) : ?>
               <table id="tbVotos" class="tbProg">
                  <tr>
                     <th>Data - Hora</th>
                     <th>Descrição</th>
                     <th>Divisão</th>
                     <th>Sexo</th>
                     <th>Modalidade</th>
                     <th>Status</th>
                     <th style="width: 100px;">&nbsp;</th>
                  </tr>
                  <?php foreach($programacao as $item) : ?>
                     <tr>
                        <td><?php echo date('d/m - H:i', $item->data); ?></td>
                        <td class="userNome"><?php echo $item->descricao; ?></td>
                        <td>
                           <?php
                              $divisao = explode(',', $item->divisao);
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
                                 echo substr_replace($txt, ' e', $posLast, 1);
                              } else {
                                 echo $txt;
                              }
                           ?>
                        </td>
                        <td>
                           <?php
                              $sexo = explode(',', $item->sexo);
                              $txt = '';
                              foreach($sexo as $sx){
                                 switch($sx){
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
                                 echo substr_replace($txt, ' e', $posLast, 1);
                              } else {
                                 echo $txt;
                              }
                           ?>
                              
                        </td>
                        <td><?php echo $item->modalidade; ?></td>
                        <td>
                           <?php
                              switch($item->resultado_tipo){
                                 case '1':
                                    $txtResultado = '<span class="status resultado-extra">Resultado Extra-oficial</span>';
                                    $txtLink = '<a class="btnLink btnResultProg" href="/admin/_func/programacao-resultado.php?id=' . $item->id . '">[Editar Resultado]</a>';
                                    break;
                                 case '2':
                                    $txtResultado = '<span class="status resultado-oficial">Resultado Oficial</span>';
                                    $txtLink = '<a class="btnLink btnResultProg" href="/admin/_func/programacao-resultado.php?id=' . $item->id . '">[Editar Resultado]</a>';
                                    break;
                                 default:
                                    $txtResultado = '<span class="status sem-resultado">Sem resultado</span>';
                                    $txtLink = '<a class="btnLink btnResultProg" href="/admin/_func/programacao-resultado.php?id=' . $item->id . '">[Cadastrar Resultado]</a>';
                              }
                              echo "$txtResultado $txtLink";
                           ?>
                        </td>
                        <td><a class="btnLink" href="/admin/programacao/editar.php?id=<?php echo $item->id; ?>">[Editar]</a><a class="btnLink btnExcluir" rel="btnExcluirProgramacao" href="#<?php echo $item->id; ?>">[Excluir]</a></td>
                     </tr>
                  <?php endforeach; ?>
               </table>
            <?php endif; ?>
         </div>
         <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php'); ?>
      </div>
      <script type="text/javascript" src="/admin/js/jquery.js"></script>
      <script type="text/javascript" src="/admin/js/jquery.metadata.js"></script>
      <script type="text/javascript" src="/admin/js/jquery.validate.js"></script>
      <script type="text/javascript" src="/admin/js/jquery.ui.custom.js"></script>
      <script type="text/javascript" src="/admin/js/jquery.timepickeraddon.js"></script>
      <script type="text/javascript" src="/admin/js/swfobject.js"></script>
      <script type="text/javascript" src="/admin/js/jquery.uploadify.js"></script>
      <script type="text/javascript" src="/admin/js/tiny_mce/tiny_mce.js"></script>
      <script type="text/javascript" src="/admin/js/jquery.color.js"></script>
      <script type="text/javascript" src="/admin/js/jquery.Jcrop.js"></script>
      <script type="text/javascript" src="/admin/js/jquery.maskedInput.js"></script>
      <script type="text/javascript" src="/admin/js/jquery.fcbkcomplete.js"></script>
      <script type="text/javascript" src="/admin/js/jquery.fancybox.js"></script>
      <script type="text/javascript" src="/admin/js/jquery.rules.admin.js"></script>
   </body>
   </html>