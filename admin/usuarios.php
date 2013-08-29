<?php
   require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
   $login = new Login();
   if(!$login->verificaLogin()){
      header('Location:/admin/login.php');
      exit();
   } else {
      if($_SESSION['capability'] !== '1'){
         header('Location:/admin/index.php?erro=perfil');
         exit();
      }
   }
   $login->atualizaSession();
   $conn = new DbConnect();
?>
   <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
   <html lang="pt" xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://developers.facebook.com/schema/">
   <head>
      <title>Jogos Abertos do Interior de 2011 - Admin - Usuários</title>
      <meta name="title" content="Jogos Abertos do Interior de 2011 - Admin - Usuários" />
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
   <body class="usuarios">
      <div id="wrapper">
         <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/header.php'); ?>
         <div id="contentTb">
            <h2>Usuários</h2>
            <ul id="menuSubSec">
               <li><a<?php echo isset($pg_usuarios) ? ' class="hover"' : ''; ?> href="/admin/usuarios.php">Home</a></li>
               <li><a<?php echo isset($pg_usuario_cadastrar) ? ' class="hover"' : ''; ?> href="/admin/usuario/cadastrar.php">Cadastrar</a></li>
            </ul>
            <br clear="all" />
            <?php
               $users = new Usuario();
               $users->set_user_id('all');
               $total = $users->get_total();
               $totalPage = 10;
               
               if($total > $totalPage){
                  $pagination = new Paginator();
                  $pagination->items_total = $total;
                  $pagination->items_per_page = $totalPage;
                  $pagination->mid_range = 3;
                  $pagination->paginate();
               }
               
               $usuarios = $users->get_usuario((isset($pagination->limit) ? $pagination->limit : ""));
               
            ?>
            <div class="infoSecao">
               <p><span class="total"><?php echo $total; ?></span> usuários cadastrados, <span class="ativos"><?php echo $users->get_ativos(); ?></span> ativos.</p>
            </div>
            
            <div class="pagination">
               <?php echo ($total > $totalPage) ? $pagination->display_pages() : ''; ?>
            </div>
            <table id="tbVotos">
               <tr>
                  <th>Nome</th>
                  <th>E-mail</th>
                  <th>CPF</th>
                  <th>Telefone</th>
                  <th>Celular</th>
                  <th>Empresa</th>
                  <th>Perfil</th>
                  <th>Status</th>
                  <th>&nbsp;</th>
               </tr>
               <?php foreach($usuarios as $usuario) : ?>
                  <tr>
                     <td class="userNome"><?php echo $usuario->nome; ?></td>
                     <td><?php echo $usuario->email; ?></td>
                     <td><?php echo $usuario->cpf; ?></td>
                     <td><?php echo $usuario->telefone; ?></td>
                     <td><?php echo $usuario->celular; ?></td>
                     <td><?php echo $usuario->empresa; ?></td>
                     <td><?php echo $usuario->perfil; ?></td>
                     <?php if($usuario->status) : ?>
                        <td><span class="aprovado">Ativo</span><a class="btnStatus btnStatusUsuario" href="reprovar|<?php echo $usuario->id; ?>">Desativar</a></td>
                     <?php else : ?>
                        <td><a class="btnStatus btnStatusUsuario" href="aprovar|<?php echo $usuario->id; ?>">Ativar</a><br /><span class="reprovado">Inativo</span></td>
                     <?php endif; ?>
                     <td align="center"><strong><a class="btnEditar" href="/admin/usuario/editar.php?id=<?php echo $usuario->id; ?>">[editar]</a><br /><a id="btnExcluirUsuario" class="btnExcluir" href="#<?php echo $usuario->id; ?>">[excluir]</a></strong></td>
                  </tr>
               <?php endforeach; ?>
            </table>
            <div class="pagination">
               <?php echo ($total > $totalPage) ? $pagination->display_pages() : ''; ?>
            </div>
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