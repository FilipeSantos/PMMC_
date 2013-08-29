<?php
   require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
   $login = new Login();
   if(!$login->verificaLogin()){
      header('Location:/admin/login.php');
      exit();
   } else {
      if($login->get_capability() !== '1'){
         header('Location:/admin/index.php?erro=perfil');
         exit();
      }
   }
   $login->atualizaSession();
   $conn = new DbConnect();
   $idUser = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : FALSE;
   $usuarios = new Usuario();
   $usuarios->set_user_id($idUser);
   $usuarios = $usuarios->get_usuario();
?>
   <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
   <html lang="pt" xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://developers.facebook.com/schema/">
   <head>
      <title>Jogos Abertos do Interior de 2011 - Admin - Usuários - Editar</title>
      <meta name="title" content="Jogos Abertos do Interior de 2011 - Admin - Usuários - Editar" />
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
            <h2>- Usuários > Editar -</h2>
            <ul id="menuSubSec">
               <li><a<?php echo isset($pg_usuarios) ? ' class="hover"' : ''; ?> href="/admin/usuarios.php">Home</a></li>
               <li><a<?php echo isset($pg_usuario_cadastrar) ? ' class="hover"' : ''; ?> href="/admin/usuario/cadastrar.php">Cadastrar</a></li>
            </ul>
            <br clear="all" />
            <div class="infoSecao">
               <p>* Campos Obrigatórios</p>
            </div>
            <?php
               if(count($usuarios)){
                  foreach($usuarios as $usuario) {
                     require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/formUsuario.php');
                  }
               }
            ?>
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