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
   
   $divisao = isset($_GET['divisao']) && is_numeric($_GET['divisao']) ? (integer) addslashes($_GET['divisao']) : FALSE;
   
   if($divisao === FALSE){
      exit();
   }
   
   $cidadesDiv = Cidade::get_cidades_medalhas_divisao($divisao);
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
      <div class="contentInnerClass contentInnerClassTb">
      <?php if($cidadesDiv !== FALSE) : $posPrev = ''; ?>
         <h3><?php echo $divisao; ?>ª divisão</h3>
         <table class="tbVotos tbClass">
            <tr>
               <th>Posição</th>
               <th>Cidade</th>
               <th>Ouro</th>
               <th>Prata</th>
               <th>Bronze</th>
               <th>Total</th>
               <th>&nbsp;</th>
            </tr>
            <?php foreach($cidadesDiv as $cidade) : ?>
               <tr>
                  <td><?php echo $cidade->pos_medalhas != $posPrev ? $cidade->pos_medalhas . 'º' : ''; ?></td>
                  <td><?php echo $cidade->nome; ?></td>
                  <td><?php echo $cidade->medalha_total_ouro; ?></td>
                  <td><?php echo $cidade->medalha_total_prata; ?></td>
                  <td><?php echo $cidade->medalha_total_bronze; ?></td>
                  <td><?php echo $cidade->medalha_total; ?></td>
                  <td><a class="btnEditar" href="/admin/quadro-de-medalhas/adicionar.php?id=<?php echo $cidade->id; ?>">[Editar]</a></td>
               </tr>
            <?php $posPrev = $cidade->pos_medalhas; endforeach; ?>
         </table>
      <?php endif; ?>
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
      <script type="text/javascript" src="/admin/js/jquery.fancybox.js"></script>
      <script type="text/javascript" src="/admin/js/jquery.fcbkcomplete.js"></script>
      <script type="text/javascript" src="/admin/js/jquery.rules.admin.js"></script>
   </body>
   </html>