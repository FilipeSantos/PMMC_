<?php
   require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
   $login = new Login();
   if(!$login->verificaLogin()){
      header('Location:/admin/login.php');
      exit();
   }
   $login->atualizaSession();
   $conn = new DbConnect();
   
   $arrayCateg = array();
   if($rs = mysql_query("select id, titulo from categoria where 1;")){
      while($item = mysql_fetch_assoc($rs)){
         $arrayCateg[] = $item;
      }
   } else {
      $arrayCateg = FALSE;
   }
  
   $edit = FALSE;
?>
   <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
   <html lang="pt" xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://developers.facebook.com/schema/">
   <head>
      <title>Jogos Abertos do Interior de 2011 - Admin - Notícias</title>
      <meta name="title" content="Jogos Abertos do Interior de 2011 - Admin - Notícias" />
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
      <link rel="SHORTCUT ICON" href="../_img/favicon.ico">
      <style type="text/css">
         @import url(/admin/css/style.admin.css);
      </style>
   </head>
   <body class="noticia">
      <div id="wrapper">
         <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/header.php'); ?>
         <div id="contentTb">
            <h2>Notícias - Cadastrar</h2>
            <ul id="menuSubSec">
               <li><a<?php echo isset($pg_noticias) ? ' class="hover"' : ''; ?> href="/admin/noticias.php">Home</a></li>
               <li><a<?php echo isset($pg_noticia_cadastrar) ? ' class="hover"' : ''; ?> href="/admin/noticia/cadastrar.php">Cadastrar</a></li>
            </ul>
            <br clear="all" />
            <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/formNoticia.php'); ?>
         </div>
         <div id="footer">
            <address>Copyright &copy; – Jogos Abertos do Interior 2011</address>
         </div>
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