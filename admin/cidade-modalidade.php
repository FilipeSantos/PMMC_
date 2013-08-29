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
   
   if($_POST){
      $modalidade = $divisao = $pcd = $sexo = $idade = array();
      $cidade = isset($_POST['cidade']) && is_numeric($_POST['cidade']) ? (integer) $_POST['cidade'] : FALSE;
      $total = count($_POST['modalidade']);
      
      foreach($_POST['modalidade'] as $mod){
         $modalidade[] = is_numeric($mod) ? (integer) $mod : 0;
      }
      foreach($_POST['divisao'] as $div){
         $divisao[] = is_numeric($div) ? (integer) $div : 0;
      }
      foreach($_POST['pcd'] as $pess){
         $pcd[] = is_numeric($pess) ? (integer) $pess : 0;
      }
      foreach($_POST['sexo'] as $sx){
         $sexo[] = is_numeric($sx) ? (integer) $sx : 0;
      }
      foreach($_POST['idade'] as $id){
         $idade[] = is_numeric($id) ? (integer) $id : 'NULL';
      }
      
      $erro = 0;
      for($i = 1; $i < $total; $i++){
         $rs = mysql_query("insert into cidade_modalidade(idCidade, idModalidade, divisao, pcd, sexo, idade)
                         values($cidade, $modalidade[$i], $divisao[$i], $pcd[$i], $sexo[$i], $idade[$i]);");
         if($rs === FALSE){
            $erro++;
         }
      }
   }
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
            <form action="/admin/cidade-modalidade.php" method="post">
               <div class="item" style="padding-bottom: 30px;">
                  <label for="cidade">Cidade:</label>
                  <select name="cidade" id="cidade">
                     <?php
                        $cidades = Cidade::get_list_cidades();
                        foreach($cidades as $cidade) : 
                     ?>
                        <option value="<?php echo $cidade->id; ?>"><?php echo $cidade->nome; ?></option>
                     <?php endforeach; ?>
                  </select>
               </div>
               <div id="itemCopyOrigin" class="itensCopy" style="padding-bottom: 16px;">
                  <div class="item">
                     <label for="modalidade">Modalidade</label>
                     <select name="modalidade[0]" id="modalidade-0">
                        <?php
                           $conn = new DbConnect();
                           $rs = mysql_query("select id, titulo from modalidade where status = 1 order by titulo;");
                           if($rs && mysql_num_rows($rs)){
                              while($mod = mysql_fetch_object($rs)){
                                 echo "<option value=\"$mod->id\">$mod->titulo</option>";
                              }
                           }
                        ?>
                     </select>
                  </div>
                  <div class="item">
                     <label for="divisao">Divisão</label>
                     <select name="divisao[0]" id="divisao-0">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3 (especial)</option>
                     </select>
                  </div>
                  <div class="item">
                     <label for="pcd">PCD</label>
                     <input type="radio" name="pcd[0]" id="pcdSim-0" value="1" />
                     <label for="pcdSim-0" style="margin-right: 20px;">Sim</label>
                     <input type="radio" name="pcd[0]" id="pcdNao-0" value="0" checked="checked" />
                     <label for="pcdNao-0" style="margin-right: 20px;">Não</label>
                  </div>
                  <div class="item">
                     <label for="sexo">Sexo</label>
                     <select name="sexo[0]" id="sexo-0">
                        <option value="1">1 (masculino)</option>
                        <option value="2">2 (feminino)</option>
                        <option value="3">3 (misto)</option>
                     </select>
                  </div>
                  <div class="item">
                     <label for="idade">Idade</label>
                     <input type="text" style="width: 40px;" name="idade[0]" id="idade-0" />
                  </div>
               </div>
               <br clear="all" />
               <a style="font-weight: bold; margin-left: 200px; line-height: 32px;" id="btnAddCidadeMod" href="#1">[adicionar]</a>
               <br clear="all" />
               <input type="submit" value="Enviar" id="btnSubmit" /><br /><br />
            </form>
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