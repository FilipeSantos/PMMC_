<?php
   require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
   $login = new Login();
   if(!$login->verificaLogin()){
      header('Location:/admin/login.php');
      exit();
   }
   $login->atualizaSession();
   $conn = new DbConnect();
?>
   <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
   <html lang="pt" xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://developers.facebook.com/schema/">
   <head>
      <title>Jogos Abertos do Interior de 2011 - Admin - Banco de Arquivos</title>
      <meta name="title" content="Jogos Abertos do Interior de 2011 - Admin - Banco de Arquivos" />
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
   <body class="bd-arquivos">
      <div id="wrapper">
         <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/header.php'); ?>
         <div id="contentTb">
            <h2>Releases</h2>
            <ul id="menuSubSec">
               <li><a<?php echo isset($pg_banco_de_arquivos) ? ' class="hover"' : ''; ?> href="/admin/banco-de-arquivos.php">Home</a></li>
               <li><a<?php echo isset($pg_banco_de_arquivos_cadastrar) ? ' class="hover"' : ''; ?> href="/admin/banco-de-arquivos/cadastrar.php">Cadastrar</a></li>
            </ul>
            <br clear="all" />
            <div class="boxFiltro">
               <?php $filtroStatus = isset($_GET['inputStatus']) && is_numeric($_GET['inputStatus']) ? $_GET['inputStatus'] : NULL; ?>
               <form id="formFiltro" method="get" action="/admin/banco-de-arquivos.php">
                  <span>Filtrar por:</span>
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
               $ba = new BancoArquivo();
               $total = $ba->get_total(array('status' => $filtroStatus));
               $totalPage = 10;
               
               if($total > $totalPage){
                  $pagination = new Paginator();
                  $pagination->items_total = $total;
                  $pagination->items_per_page = $totalPage;
                  $pagination->mid_range = 3;
                  $pagination->paginate();
               }
               
               $results = $ba->get_bancoArquivo('all', (isset($pagination->limit) ? $pagination->limit : ""), array('status' => $filtroStatus));
            ?>
            
            <?php if($total > $totalPage) : ?>
               <div class="pagination">
                  <?php echo $pagination->display_pages(); ?>
               </div>
            <?php endif; ?>
            <table id="tbVotos">
               <tr>
                  <th>Título</th>
                  <th>Thumb</th>
                  <th>Arquivo</th>
                  <th>Formato</th>
                  <th>Tamanho</th>
                  <th>Status</th>
                  <th>&nbsp;</th>
               </tr>
               <?php foreach($results as $item) : ?>
                  <tr>
                     <td class="userNome"><?php echo $item->titulo; ?></td>
                     <td><img src="/upload/banco_de_arquivos/thumb/<?php echo $item->thumb; ?>" alt="<?php echo $item->titulo; ?>" width="100" height="86" />
                     <td><?php echo $item->arquivo; ?></td>
                     <td><?php echo strtoupper($item->formato); ?></td>
                     <td><?php echo ($item->tamanho > 1024) ? number_format($item->tamanho / 1024, 2) . ' mb' : $item->tamanho . ' kb' ; ?></td>
                     <?php if($item->status) : ?>
                        <td><span class="aprovado">Ativo</span><a class="btnStatus btnStatusBanco" href="reprovar|<?php echo $item->id; ?>">Desativar</a></td>
                     <?php else : ?>
                        <td><a class="btnStatus btnStatusBanco" href="aprovar|<?php echo $item->id; ?>">Ativar</a><br /><span class="reprovado">Inativo</span></td>
                     <?php endif; ?>
                     <td align="center"><strong><a class="btnEditar" href="/admin/banco-de-arquivos/editar.php?id=<?php echo $item->id; ?>">[editar]</a><br /><a id="btnExcluirBanco" class="btnExcluir" href="#<?php echo $item->id; ?>">[excluir]</a></strong></td>
                  </tr>
               <?php endforeach; ?>
            </table>
            <?php if($total > $totalPage) : ?>
               <div class="pagination">
                  <?php echo $pagination->display_pages(); ?>
               </div>
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