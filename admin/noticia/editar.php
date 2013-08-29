<?php
   require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
   $login = new Login();
   if(!$login->verificaLogin()){
      header('Location:/admin/login.php');
      exit();
   }
   $login->atualizaSession();
   $conn = new DbConnect();
   
   $rs = mysql_query("select id, titulo from categoria where 1 order by titulo");
   if(mysql_num_rows($rs)){
      $i = 0;
      while($item = mysql_fetch_assoc($rs)){
         $arrayCateg[$i]['id'] = $item['id'];
         $arrayCateg[$i++]['titulo'] = $item['titulo'];
      }
   }
   
   $edit = TRUE;
   $id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : FALSE;
   if($id){      
      if($_SESSION['capability'] > 2){
         $rs = mysql_query("select id from noticia where id = $id and idLog = $_SESSION[idUser] and status = 0 and aprovacao = 1 limit 1;");
         if(!mysql_num_rows($rs)){
            $id = FALSE;
            $breakNot = TRUE;
            $msgStatus = 'Você não tem permissão para editar essa notícia.';
         }
      }
      
      if(!isset($breakNot) || !$breakNot){
         $noticia = array();
         $rs = mysql_query("select titulo, descricao, texto, imagemThumb, permalink, exibirHome, importancia, status, aprovacao, date_format(dataPublicacao, '%d/%m/%Y %H:%i') as dataPublicacao from noticia where id = $id limit 0,1;");
         if(mysql_num_rows($rs)){
            while($item = mysql_fetch_assoc($rs)){
               $noticia['titulo'] = htmlspecialchars($item['titulo']);
               $noticia['descricao'] = htmlspecialchars($item['descricao']);
               $noticia['texto'] = $item['texto'];
               $noticia['imagemThumb'] = $item['imagemThumb'];
               $noticia['permalink'] = $item['permalink'];
               $noticia['exibirHome'] = $item['exibirHome'];
               $noticia['importancia'] = $item['importancia'];
               $noticia['status'] = $item['status'];
               $noticia['aprovacao'] = $item['aprovacao'];
               $noticia['dataPublicacao'] = $item['dataPublicacao'];
            }
            
            $noticiaTag = array();
            $i = 0;
            $rs = mysql_query("select a.id, a.titulo from tag as a inner join noticia_tag as b on a.id = b.idTag and b.idNoticia = $id;");
            while($item = mysql_fetch_assoc($rs)){
               $noticiaTag[$i]['id'] = $item['id'];
               $noticiaTag[$i++]['titulo'] = $item['titulo'];
            }
            
            $noticaCateg = array();
            $i = 0;
            $rs = mysql_query("select a.id from categoria as a inner join noticia_categoria as b on a.id = b.idCategoria and b.idNoticia = $id;");
            while($item = mysql_fetch_assoc($rs)){
               $noticaCateg[$i++] = $item['id'];
            }
         } else {
            $id = FALSE;
            $msgStatus = 'Notícia não encontrada.';
         }
      }      
   }
   
   $rs = mysql_query("select count(*) as total from noticia where 1;");
   $i = mysql_result($rs, 0, 'total');

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
            <h2>Editar Notícia</h2>
            <p><?php echo ($edit && !$id) ? isset($msgStatus) ? $msgStatus : 'Notícia não encontrada.' : ''; ?></p>
            <?php if(($edit && $id) || !$edit){ require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/formNoticia.php'); } ?>
         </div>
         <div id="footer">
            <span class="btnLogo">Jogos Abertos</span>
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
   </html>