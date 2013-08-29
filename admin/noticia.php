<?php
   require_once('_inc/login.class.php');
   $login = new Login();
   if(!$login->verificaLogin()){
      header('Location:login.php');
      exit();
   }
   $login->atualizaSession();
   date_default_timezone_set('America/Sao_Paulo');
   require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/db.class.php');
   $conn = new DbConnect();
   mysql_set_charset('utf8', $conn->conn);
   
   $rs = mysql_query("select id, titulo from categoria where 1 order by titulo");
   if(mysql_num_rows($rs)){
      $i = 0;
      while($item = mysql_fetch_assoc($rs)){
         $arrayCateg[$i]['id'] = $item['id'];
         $arrayCateg[$i++]['titulo'] = $item['titulo'];
      }
   }
   $edit = FALSE;
   
   if(isset($_GET['acao']) && $_GET['acao'] == 'editar'){
      $edit = TRUE;
      $id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : FALSE;
      if($id){
         $noticia = array();
         $rs = mysql_query("select titulo, descricao, texto, imagemThumb, imagem1, imagem2, permalink, exibirHome, importancia, status, date_format(dataPublicacao, '%d/%m/%Y %H:%i') as dataPublicacao from noticia where id = $id limit 0,1;");
         while($item = mysql_fetch_assoc($rs)){
            $noticia['titulo'] = htmlspecialchars($item['titulo']);
            $noticia['descricao'] = htmlspecialchars($item['descricao']);
            $noticia['texto'] = $item['texto'];
            $noticia['imagemThumb'] = $item['imagemThumb'];
            $noticia['imagem1'] = $item['imagem1'];
            $noticia['imagem2'] = $item['imagem2'];
            $noticia['permalink'] = $item['permalink'];
            $noticia['exibirHome'] = $item['exibirHome'];
            $noticia['importancia'] = $item['importancia'];
            $noticia['status'] = $item['status'];
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
         $edit = FALSE;
      }
   }
   
   $rs = mysql_query("select count(*) as total from noticia where 1;");
   $i = mysql_result($rs, 0, 'total');

?>
   <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
   <html lang="pt" xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://developers.facebook.com/schema/">
   <head>
      <title>Jogos Abertos do Interior de 2011 - Admin de Notícias - <?php echo $edit ? 'Editar Notícia' : 'Cadastrar Nova Notícia' ?></title>
      <meta name="title" content="Jogos Abertos do Interior de 2011 - Admin de Notícias - <?php echo $edit ? 'Editar Notícia' : 'Cadastrar Nova Notícia' ?>" />
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
         @import url(css/style.admin.css);
      </style>
   </head>
   <body class="noticia">
      <div id="wrapper">
         <h1 class="home"><a href="index.php">Jogos Abertos</a></h1>
         <div class="infoTop infoTopNoticia">
            <div class="infoLog">
               <p>Olá <strong><?php echo $_SESSION['user']; ?>&nbsp;&nbsp;&nbsp;<a class="btnSair" href="login.php?logout=true">[sair]</a></strong></p>
               <p>Notícias cadastradas: <strong><?php echo $i; ?></strong></p>
            </div>
            <ul id="menuTop">
               <li><a href="index.php">Home</a></li>
               <li><a <?php echo !$edit ? 'class="hover" ' : '' ?>href="noticia.php">Cadastrar Notícia</a></li>
            </ul>
         </div>
         <br clear="all" />         
         <div id="contentTb">
            <h2><?php echo $edit ? 'Editar Notícia' : 'Cadastrar Notícia' ?></h2>
            <form id="formNoticia" action="_func/noticia.php" method="POST">
               <div class="boxFloat">
                  <div class="item">
                     <label for="titulo">Título</label>
                     <input type="text" name="titulo" id="titulo" class="form" <?php echo $edit ? 'value="' . $noticia['titulo'] . '"' : '' ?> />
                  </div>
                  <div class="item">
                     <label for="descricao">Descrição</label>
                     <input type="text" name="descricao" id="descricao" class="form" <?php echo $edit ? 'value="' . $noticia['descricao'] . '"' : '' ?> />
                  </div>
                  <div class="item itemImgThumb" rel="thumb">
                     <label for="imagemThumb">Thumb</label>
                     <input type="file" name="imagemThumb" id="imagemThumb" class="form imgUpload" />
                     <?php if($edit && $noticia['imagemThumb']) : ?>
                        <div class="jcropContent jcropContentThumb">
                           <img alt="Thumb" src="<?php echo $noticia['imagemThumb']; ?>">
                           <a href="<?php echo $noticia['imagemThumb']; ?>" id="btnExcluirImg" class="btnActImg btnActImgAlt">Excluir</a>
                           <br clear="all">
                        </div>
                     <?php endif; ?>
                     <input type="hidden" name="imgUploadThumb" id="imgUploadThumb" class="imgUploadUrl" <?php echo $edit ? 'value="' . $noticia['imagemThumb'] . '"' : '' ?> /> 
                  </div>
                  <div class="item itemImg1" rel="img1">
                     <label for="imagem1">Imagem 1</label>
                     <input type="file" name="imagem1" id="imagem1" class="form imgUpload" />
                     <?php if($edit && $noticia['imagem1']) : ?>
                        <div class="jcropContent jcropContentImg">
                           <img alt="Thumb" src="<?php echo $noticia['imagem1']; ?>">
                           <span class="urlImg"><strong>URL:</strong><br /><?php echo 'http://' . $_SERVER['SERVER_NAME'] . $noticia['imagem1']; ?></span>
                           <a href="<?php echo $noticia['imagem1']; ?>" id="btnExcluirImg" class="btnActImg btnActImgAlt">Excluir</a>
                           <br clear="all">
                        </div>
                     <?php endif; ?>
                     <input type="hidden" name="imgUploadImagem1" id="imgUploadImagem1" class="imgUploadUrl" <?php echo $edit ? 'value="' . $noticia['imagem1'] . '"' : '' ?> />
                  </div>
                  <div class="item" rel="img2">
                     <label for="imagem2">Imagem 2</label>
                     <input type="file" name="imagem2" id="imagem2" class="form imgUpload" />
                     <?php if($edit && $noticia['imagem2']) : ?>
                        <div class="jcropContent jcropContentImg">
                           <img alt="Thumb" src="<?php echo $noticia['imagem2']; ?>">
                           <span class="urlImg"><strong>URL:</strong><br /><?php echo 'http://' . $_SERVER['SERVER_NAME'] . $noticia['imagem2']; ?></span>
                           <a href="<?php echo $noticia['imagem2']; ?>" id="btnExcluirImg" class="btnActImg btnActImgAlt">Excluir</a>
                           <br clear="all">
                        </div>
                     <?php endif; ?>
                     <input type="hidden" name="imgUploadImagem2" id="imgUploadImagem2" class="imgUploadUrl" <?php echo $edit ? 'value="' . $noticia['imagem2'] . '"' : '' ?> />
                  </div>
               </div>
               <div class="boxFloat">
                  <div class="item">
                     <label for="dataPub">Publicar em</label>
                     <input type="text" name="dataPub" id="dataPub" class="form form2" value="<?php echo ($edit && $noticia['dataPublicacao'] != NULL) ? $noticia['dataPublicacao'] : date('d\/m\/Y H:i', time()) ?>" />
                  </div>
                  <div class="item itemCateg">
                     <label for="categoria">Categoria</label>                     
                     <div class="listCateg">
                        <ul>
                           <?php if(isset($arrayCateg)) :
                              foreach($arrayCateg as $categ){
                           ?>
                              <li>
                                 <input type="checkbox" name="categoria[]" id="categoria<?php echo $categ['id'] ?>" value="<?php echo $categ['id'] ?>" class="check" <?php echo ($edit && in_array($categ['id'], $noticaCateg)) ? 'checked="checked"' : '' ?> />
                                 <label for="categoria<?php echo $categ['id'] ?>"><?php echo $categ['titulo'] ?></label>&nbsp;&nbsp;
                                 <a href="#<?php echo $categ['id'] ?>" class="excluirCateg">[Excluir]</a>
                              </li>
                           <?php }
                              endif;
                           ?>
                        </ul>
                     </div>
                     <input type="text" id="categoriaTemp" class="form" />
                     <a id="btnAddCateg" href="#">Adicionar</a>
                  </div>
               </div>
               <br clear="all" />
               <div class="item itemTexto">
                  <label for="texto">Texto</label>
                  <textarea name="texto" id="texto" cols="30" rows="10" class="form"><?php echo $edit ? $noticia['texto'] : '' ?></textarea>
               </div>
               <div class="itemInfos">
                  <div class="item itemTag">
                     <label for="tags">Tags</label>
                     <select id="tags" name="tags">
                        <?php
                           if($edit){
                              foreach($noticiaTag as $item){
                                 echo '<option value="' . $item['titulo'] . '" class="selected">' . $item['titulo'] . '</option>';
                              }
                           }
                        ?>
                     </select>
                  </div>
                  <div class="item itemExibir">
                     <input type="checkbox" name="exibirHome" id="exibirHome" value="1" class="check" <?php echo ($edit && $noticia['exibirHome']) ? 'checked="checked"' : '' ?> />
                     <label for="exibirHome">Exibir notícia na Home</label>
                  </div>
                  <div class="item itemDestaq">
                     <label for="destaque">Destaque</label>
                     <select name="destaque" id="destaque">
                        <option value="0">Nenhum</option>
                        <?php for($i=1; $i<=3; $i++) : ?>
                           <option value="<?php echo $i ?>" <?php echo ($edit && $i == $noticia['importancia']) ? 'selected="selected"' : ''; ?>><?php echo $i ?></option>
                        <?php endfor; ?>
                     </select>
                  </div>
                  <div class="item itemAtivo">
                     <input type="checkbox" name="ativo" id="ativo" value="1" class="check" <?php echo ($edit && !$noticia['status']) ? '' : 'checked="checked"'; ?> />
                     <label for="ativo">Ativo</label>
                  </div>
               </div>
               <br clear="all" />
               <div class="loadingNot"><img src="/admin/img/loading2.gif" alt="Carregando..." /></div>
               <input type="submit" id="submit" value="salvar" />
               <br clear="all" />
               <input type="hidden" name="tipoNoticia" id="tipoNoticia" value="<?php echo $edit ? 'editar' : 'criar' ?>" <?php echo $edit ? 'rel="' . $id . '"' : ''; ?> />
               <?php if($edit) : ?>
                  <input type="hidden" name="idNoticia" id="idNoticia" value="<?php echo $id; ?>" />
               <?php endif; ?>
            </form>
         </div>
         <div id="footer">
            <span class="btnLogo">Uniban Brasil</span>
            <address>Copyright &copy; – Jogos Abertos do Interior 2011</address>
         </div>
      </div>
      <script type="text/javascript" src="js/jquery.js"></script>
      <script type="text/javascript" src="js/jquery.metadata.js"></script>
      <script type="text/javascript" src="js/jquery.validate.js"></script>
      <script type="text/javascript" src="js/jquery.ui.custom.js"></script>
      <script type="text/javascript" src="js/jquery.timepickeraddon.js"></script>
      <script type="text/javascript" src="js/swfobject.js"></script>
      <script type="text/javascript" src="js/jquery.uploadify.js"></script>
      <script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
      <script type="text/javascript" src="js/jquery.color.js"></script>
      <script type="text/javascript" src="js/jquery.Jcrop.js"></script>
      <script type="text/javascript" src="js/jquery.fcbkcomplete.js"></script>
      <script type="text/javascript" src="js/jquery.rules.admin.js"></script>
   </body>