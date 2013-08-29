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
   
   if($_POST){
      $conn = new DbConnect();
      foreach($_POST['medalhaOuro'] as $key=>$medalha){
         mysql_query("update cidade set medalha_total_ouro = " . $_POST['medalhaOuro'][$key] . ", medalha_total_prata = " . $_POST['medalhaPrata'][$key] . ",
                     medalha_total_bronze = " . $_POST['medalhaBronze'][$key] . ", data_atualizacao_medalhas = CURRENT_TIMESTAMP where id = {$key};");
      }
      Cidade::atualiza_posicao('medalhas', 1);
      Cidade::atualiza_posicao('medalhas', 2);
      $conn->close();
   }
   
   $cidadesDiv1 = Cidade::get_cidades_medalhas_divisao(1);
   $cidadesDiv2 = Cidade::get_cidades_medalhas_divisao(2);
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
   <body class="quadro">
      <div id="wrapper">
         <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/header.php'); ?>
         <div id="contentTb">
            <h2>Quadro de Medalhas</h2>
            <?php /*
            <ul id="menuSubSec">
               <li><a<?php echo isset($pg_quadro_de_medalhas) ? ' class="hover"' : ''; ?> href="/admin/quadro-de-medalhas.php">Home</a></li>
               <li><a<?php echo isset($pg_quadro_de_medalhas_adicionar) ? ' class="hover"' : ''; ?> href="/admin/quadro-de-medalhas/adicionar.php">Adicionar / Editar</a></li>
            </ul>
                  */ ?>
            <br clear="all" />
            <div class="contentInnerClass">
               <form id="formAtualizaMed" method="post" action="#" style="padding-bottom: 10px;">
                  <?php if($cidadesDiv1 !== FALSE) : $posPrev = ''; ?>
                     <h3>1ª divisão</h3>
                     <table class="tbVotos tbClass" style="margin-bottom: 40px;">
                        <tr>
                           <th style="width: 80px;">Posição</th>
                           <th style="width: 240px;">Cidade</th>
                           <th>Ouro</th>
                           <th>Prata</th>
                           <th>Bronze</th>
                           <th style="width: 100px;">Total</th>
                        </tr>
                        <?php foreach($cidadesDiv1 as $cidade) : ?>
                           <tr>
                              <td><?php echo $cidade->pos_medalhas != $posPrev ? $cidade->pos_medalhas . 'º' : ''; ?></td>
                              <td><?php echo $cidade->nome; ?></td>
                              <td><input type="text" class="inputClassMed form" name="medalhaOuro[<?php echo $cidade->id; ?>]" id="medalhaOuro[<?php echo $cidade->id; ?>]" value="<?php echo $cidade->medalha_total_ouro; ?>" /></td>
                              <td><input type="text" class="inputClassMed form" name="medalhaPrata[<?php echo $cidade->id; ?>]" id="medalhaPrata[<?php echo $cidade->id; ?>]" value="<?php echo $cidade->medalha_total_prata; ?>" /></td>
                              <td><input type="text" class="inputClassMed form" name="medalhaBronze[<?php echo $cidade->id; ?>]" id="medalhaBronze[<?php echo $cidade->id; ?>]" value="<?php echo $cidade->medalha_total_bronze; ?>" /></td>
                              <td><?php echo $cidade->medalha_total; ?></td>
                           </tr>
                        <?php $posPrev = $cidade->pos_medalhas; endforeach; ?>
                     </table>
                  <?php endif; if($cidadesDiv2 !== FALSE) : $posPrev = ''; ?>
                     <h3>2ª divisão</h3>
                      <table class="tbVotos tbClass" style="margin-bottom: 40px;">
                        <tr>
                           <th style="width: 80px;">Posição</th>
                           <th style="width: 240px;">Cidade</th>
                           <th>Ouro</th>
                           <th>Prata</th>
                           <th>Bronze</th>
                           <th style="width: 100px;">Total</th>
                        </tr>
                        <?php foreach($cidadesDiv2 as $cidade) : ?>
                           <tr>
                              <td><?php echo $cidade->pos_medalhas != $posPrev ? $cidade->pos_medalhas . 'º' : ''; ?></td>
                              <td><?php echo $cidade->nome; ?></td>
                              <td><input type="text" class="inputClassMed" name="medalhaOuro[<?php echo $cidade->id; ?>]" id="medalhaOuro[<?php echo $cidade->id; ?>]" value="<?php echo $cidade->medalha_total_ouro; ?>" /></td>
                              <td><input type="text" class="inputClassMed" name="medalhaPrata[<?php echo $cidade->id; ?>]" id="medalhaPrata[<?php echo $cidade->id; ?>]" value="<?php echo $cidade->medalha_total_prata; ?>" /></td>
                              <td><input type="text" class="inputClassMed" name="medalhaBronze[<?php echo $cidade->id; ?>]" id="medalhaBronze[<?php echo $cidade->id; ?>]" value="<?php echo $cidade->medalha_total_bronze; ?>" /></td>
                              <td><?php echo $cidade->medalha_total; ?></td>
                           </tr>
                        <?php $posPrev = $cidade->pos_medalhas; endforeach; ?>
                     </table>
                     <div class="contentLinkClass">
                        <a href="#" class="btnVerTodosClass">[Atualizar]</a>
                     </div>
                     <br clear="all" />
                  <?php endif; ?>
               </form>
            </div>
         </div>
         <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php'); ?>
      </div>
      <script type="text/javascript" src="/admin/js/jquery.js"></script>
      <script type="text/javascript" src="/admin/js/jquery.fancybox.js"></script>
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