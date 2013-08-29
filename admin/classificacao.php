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
      foreach($_POST['pontos'] as $key=>$classi){
         $_POST['pontos'][$key] = str_replace(',', '.', $_POST['pontos'][$key]);
         mysql_query("update cidade set pontos  = " . $_POST['pontos'][$key] . ", data_atualizacao_pontos = CURRENT_TIMESTAMP where id = {$key};");
      }
      Cidade::atualiza_posicao('pontos', 1);
      Cidade::atualiza_posicao('pontos', 2);
      $conn->close();
   }
   
   $cidadesDiv1 = Cidade::get_cidades_classificacao_divisao(1);
   $cidadesDiv2 = Cidade::get_cidades_classificacao_divisao(2);
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
            <h2>Classificação</h2>
            <?php /*
            <ul id="menuSubSec">
               <li><a<?php echo isset($pg_classificacao) ? ' class="hover"' : ''; ?> href="/admin/classificacao.php">Home</a></li>
               <li><a<?php echo isset($pg_classificacao_adicionar) ? ' class="hover"' : ''; ?> href="/admin/classificacao/adicionar.php">Adicionar / Editar</a></li>
            </ul> */ ?>
            <br clear="all" />
            <form id="formAtualizaMed" method="post" action="#" style="padding-bottom: 10px;">
            <div class="contentInnerClass">
               <?php if($cidadesDiv1 !== FALSE) : $posPrev = ''; ?>
                  <h3>1ª divisão</h3>
                  <table class="tbVotos tbClass" style="margin-bottom: 40px;">
                     <tr>
                        <th style="width: 50px;">Posição</th>
                        <th>Cidade</th>
                        <th style="text-align: center;">Pontos</th>
                     </tr>
                     <?php foreach($cidadesDiv1 as $cidade) : ?>
                        <tr>
                           <td><?php echo $cidade->pos_pontos != $posPrev ? $cidade->pos_pontos . 'º' : ''; ?></td>
                           <td><?php echo $cidade->nome; ?></td>
                           <td align="center"><input type="text" class="inputClassMed" name="pontos[<?php echo $cidade->id; ?>]" id="pontos[<?php echo $cidade->id; ?>]" value="<?php echo number_format($cidade->pontos, 1, ',', '.'); ?>" /></td>
                        </tr>
                     <?php $posPrev = $cidade->pos_pontos; endforeach; ?>
                  </table>
               <?php endif; if($cidadesDiv2 !== FALSE) : $posPrev = ''; ?>
                  <h3>2ª divisão</h3>
                  <table class="tbVotos tbClass" style="margin-bottom: 40px;">
                     <tr>
                        <th style="width: 50px;">Posição</th>
                        <th>Cidade</th>
                        <th style="text-align: center;">Pontos</th>
                     </tr>
                     <?php foreach($cidadesDiv2 as $cidade) : ?>
                        <tr>
                           <td><?php echo $cidade->pos_pontos != $posPrev ? $cidade->pos_pontos . 'º' : ''; ?></td>
                           <td><?php echo $cidade->nome; ?></td>
                           <td align="center"><input type="text" class="inputClassMed" name="pontos[<?php echo $cidade->id; ?>]" id="pontos[<?php echo $cidade->id; ?>]" value="<?php echo number_format($cidade->pontos, 1, ',', '.'); ?>" /></td>
                        </tr>
                     <?php $posPrev = $cidade->pos_pontos; endforeach; ?>
                  </table>
                  <div class="contentLinkClass">
                     <a href="#" class="btnVerTodosClass">[Atualizar]</a>
                  </div>
               <?php endif; ?>
            </div>
            </form>
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