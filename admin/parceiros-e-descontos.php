<?php
   require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
   $login = new Login();
   if(!$login->verificaLogin()){
      header('Location:/admin/login.php');
      exit();
   }
   $login->atualizaSession();
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
   <body class="parceiros">
      <div id="wrapper">
         <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/header.php'); ?>
         <div id="contentTb">
            <h2>Parceiros e Descontos</h2>
            <ul id="menuSubSec">
               <li><a<?php echo isset($pg_releases) ? ' class="hover"' : ''; ?> href="/admin/parceiros-e-descontos.php">Home</a></li>
               <li><a<?php echo isset($pg_release_cadastrar) ? ' class="hover"' : ''; ?> href="/admin/parceiros-e-descontos/cadastrar.php">Cadastrar</a></li>
            </ul>
            <br clear="all" />
            <div class="boxFiltro">
               <?php
                  $filtroStatus = isset($_GET['inputStatus']) && is_numeric($_GET['inputStatus']) ? $_GET['inputStatus'] : FALSE;
                  $filtroServico = isset($_GET['inputSegmento']) && is_numeric($_GET['inputSegmento']) ? $_GET['inputSegmento'] : FALSE;  
               ?>
               <form id="formFiltro" method="get" action="/admin/parceiros-e-descontos.php">
                  <span>Filtrar por:</span>
                  <div class="item">
                     <label for="inputSegmento">Serviço</label>
                     <select name="inputSegmento" id="inputSegmento">
                        <option value=""<?php echo $filtroServico === FALSE ? ' selected="selected"' : ''; ?>>Todos</option>
                        <?php
                           $serv = Parceiro::get_servicos();
                           if($serv && is_array($serv)) :
                              foreach($serv as $item) :
                        ?>
                              <option value="<?php echo $item->id; ?>"<?php echo $filtroServico == $item->id ? ' selected="selected"' : ''; ?>><?php echo $item->titulo; ?></option>
                        <?php endforeach; endif; ?>
                     </select>
                     <br clear="all" />
                  </div>
                  <div class="item">
                     <label for="inputStatus">Status</label>
                     <select name="inputStatus" id="inputStatus">
                        <option value=""<?php echo !$filtroStatus ? ' selected="selected"' : ''; ?>>Todos</option>
                        <option value="1"<?php echo $filtroStatus === '1' ? ' selected="selected"' : ''; ?>>Ativo</option>
                        <option value="0"<?php echo $filtroStatus === '0' ? ' selected="selected"' : ''; ?>>Inativo</option>
                     </select>
                     <br clear="all" />
                  </div>
                  <input type="submit" id="submit" value="Filtrar" />
                  <br clear="all" />
               </form>
            </div>
            <br clear="all" />
            <?php
               $totalItem = Parceiro::get_total();
               $totalPage = 10;
               
               if($totalItem > $totalPage){
                  $pagination = new Paginator();
                  $pagination->items_total = $totalItem;
                  $pagination->items_per_page = $totalPage;
                  $pagination->mid_range = 3;
                  $pagination->paginate();
               }
               
               $parceiro = Parceiro::get_parceiro(FALSE, ($filtroStatus !== FALSE ? $filtroStatus : FALSE), FALSE, (isset($pagination->limit) ? $pagination->limit : ""), ($filtroServico !== FALSE ? array($filtroServico) : array()));

               if($parceiro && !empty($parceiro)):
            ?>
               <?php if($totalItem > $totalPage) : ?>
                  <div class="pagination">
                     <?php echo $pagination->display_pages(); ?>
                  </div>
               <?php endif; ?>
               <table id="tbVotos">
               <tr>
                  <th>Nome</th>
                  <th>Descricao</th>
                  <th>Desconto</th>
                  <th>E-mail</th>
                  <th>Status</th>
                  <th>&nbsp;</th>
                  <?php foreach($parceiro as $item) : ?>
                     <tr>
                        <td class="userNome"><?php echo $item->nome; ?></td>
                        <td><?php echo Utility::limit_string($item->descricao, 140); ?></td>
                        <td><?php echo $item->desconto; ?></td>
                        <td><?php echo $item->email; ?></td>
                        <?php if($item->status) : ?>
                           <td><span class="aprovado">Ativo</span><a class="btnStatus btnStatusParceiro" href="reprovar|<?php echo $item->id; ?>">Desativar</a></td>
                        <?php else : ?>
                           <td><a class="btnStatus btnStatusParceiro" href="aprovar|<?php echo $item->id; ?>">Ativar</a><br /><span class="reprovado">Inativo</span></td>
                        <?php endif; ?>
                        <td align="center">
                           <strong><a class="btnEditar" href="/admin/parceiros-e-descontos/editar.php?id=<?php echo $item->id; ?>">[editar]</a> -
                           <a id="btnExcluirParceiro" class="btnExcluir" href="#<?php echo $item->id; ?>">[excluir]</a></strong>
                        </td>
                     </tr>
                  <?php endforeach; ?>
               </table>
               <?php if($totalItem > $totalPage) : ?>
                  <div class="pagination">
                     <?php echo $pagination->display_pages(); ?>
                  </div>
               <?php endif; ?>
            <?php else : ?>
               <span>Nenhum resultado encontrado.</span>
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
      <script type="text/javascript" src="/admin/js/jquery.rules.admin.js"></script>
   </body>
   </html>