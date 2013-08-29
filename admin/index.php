<?php
   require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
   $login = new Login();
   if(!$login->verificaLogin()){
      header('Location:/admin/login.php');
      exit();
   }
   $login->atualizaSession();
   
   $conn = new DbConnect();

   if($_SESSION['capability'] <= 2){
      $rs = mysql_query("select count(*) as total from noticia as a where a.aprovacao = 1 and a.status = 0;");
      $totalNoticias = mysql_result($rs, 0, 'total');
      $totalPage = 15;
      
      if($totalNoticias > $totalPage){
         $pagination = new Paginator();
         $pagination->items_total = $totalNoticias;
         $pagination->items_per_page = $totalPage;
         $pagination->mid_range = 3;
         $pagination->paginate();
      }
   
      $rs = mysql_query("select b.nome as autor, b.empresa, a.id, a.titulo, a.permalink, a.imagemThumb, a.importancia, a.status,
                           unix_timestamp(a.dataPublicacao) as data, a.aprovacao, date_format(a.dataPublicacao, \"%d\/%m\/%Y, às %H:%i\") as datePub
                           from noticia as a inner join usuario as b on a.idLog = b.id and a.aprovacao = 1 and a.status = 0 order by a.id desc " . (isset($pagination->limit) ? $pagination->limit : "") . ";");
      
      $noticias = array();
      $i = 0;
      
      while($item = mysql_fetch_assoc($rs)){
         $noticias[$i]['autor'] = $item['autor'];
         $noticias[$i]['id'] = $item['id'];
         $noticias[$i]['titulo'] = $item['titulo'];
         $noticias[$i]['permalink'] = $item['permalink'];
         $noticias[$i]['imagemThumb'] = $item['imagemThumb'];
         $noticias[$i]['destaque'] = !$item['importancia'] ? '<em>(nenhum)</em>' : $item['importancia'];
         $noticias[$i]['aprovacao'] = $item['aprovacao'];
         $noticias[$i++]['status'] = ($item['status'] == '0') ? 'não publicado' : ((time() >= $item['data']) ? 'publicado' : 'publicado <em>(agendado para ' . $item['datePub'] . ')</em>');
      }
   } else {
      $rs = mysql_query("select count(*) as total from noticia as a inner join usuario as b on a.idLog = b.id and b.id = $_SESSION[idUser] and a.status = 0 and a.aprovacao = 1;");
      $totalNoticias = mysql_result($rs, 0, 'total');
      $totalPage = 15;
      
      if($totalNoticias > $totalPage){
         $pagination = new Paginator();
         $pagination->items_total = $totalNoticias;
         $pagination->items_per_page = $totalPage;
         $pagination->mid_range = 3;
         $pagination->paginate();
      }
   
      $rs = mysql_query("select a.id, a.titulo, a.permalink, a.imagemThumb, a.importancia, a.status,
                           unix_timestamp(a.dataPublicacao) as data, a.aprovacao, date_format(a.dataPublicacao, \"%d\/%m\/%Y, às %H:%i\") as datePub
                           from noticia as a inner join usuario as b on a.idLog = b.id and b.id = $_SESSION[idUser] and a.status = 0 and a.aprovacao = 1 order by a.id desc " . (isset($pagination->limit) ? $pagination->limit : "") . ";");
      
      $noticias = array();
      $i = 0;
      
      while($item = mysql_fetch_assoc($rs)){
         $noticias[$i]['id'] = $item['id'];
         $noticias[$i]['titulo'] = $item['titulo'];
         $noticias[$i]['permalink'] = $item['permalink'];
         $noticias[$i]['imagemThumb'] = $item['imagemThumb'];
         $noticias[$i]['destaque'] = !$item['importancia'] ? '<em>(nenhum)</em>' : $item['importancia'];
         $noticias[$i]['aprovacao'] = $item['aprovacao'];
         $noticias[$i++]['status'] = ($item['status'] == '0') ? 'não publicado' : ((time() >= $item['data']) ? 'publicado' : 'publicado <em>(agendado para ' . $item['datePub'] . ')</em>');
      }
   }
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
   <body class="home">
      <div id="wrapper">
         <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/header.php'); ?>
         <div id="contentTb">
            <h2>Home</h2>
            <?php if($totalNoticias) : ?>
               <h3>Notícias com aprovação pendente:</h3>
               <div class="pagination">
                  <?php echo ($totalNoticias > $totalPage) ? $pagination->display_pages() : ''; ?>
               </div>
               <table id="tbVotos">
                  <tr>
                     <th>Título</th>
                     <th>Jornalista</th>
                     <th style="width: 90px;">Thumb</th>
                     <th>Categoria(s)</th>
                     <th style="width: 90px;">Destaque</th>
                     <th style="width: 100px;">Status</th>
                     <th>&nbsp;</th>
                  </tr>
                  <?php foreach($noticias as $noticia) : ?>
                     <tr>
                        <td><strong><a href="/admin/noticia/editar.php?id=<?php echo $noticia['id']; ?>" target="_blank"><?php echo stripslashes($noticia['titulo']); ?></a></strong></td>
                        <td><?php echo $noticia['autor'] . (isset($noticia['empresa']) ? "(<em>$noticia[empresa]</em>)" : ''); ?></td>
                        <td>
                           <?php if($noticia['imagemThumb']): ?>
                              <img src="<?php echo $noticia['imagemThumb']; ?>" alt="<?php echo  htmlspecialchars(stripslashes($noticia['titulo'])); ?>" width="100" height="67" />
                           <?php else : ?>
                              <em>(não cadastrado)</em>
                           <?php endif; ?>
                        </td>
                        <td>
                           <?php
                              $rsCateg = mysql_query("select a.titulo from categoria as a inner join noticia_categoria as b on a.id = b.idCategoria and b.idNoticia = " . $noticia['id'] . ";");
                              $txtCateg = '';
                              while($itemCateg = mysql_fetch_assoc($rsCateg)){
                                 $txtCateg = $txtCateg . $itemCateg['titulo'] . ', ';
                              }
                              echo substr($txtCateg, 0, -2);
                           ?>
                        </td>
                        <td><?php echo $noticia['destaque']; ?></td>
                        <td><?php echo $noticia['status']; ?></td>
                        <td align="center"><strong><a class="btnEditar" href="/admin/noticia/editar.php?id=<?php echo $noticia['id']; ?>">[editar]</a> - <a id="btnExcluirNoticia" class="btnExcluir" href="#<?php echo $noticia['id']; ?>">[excluir]</a></strong>
                           <?php if($_SESSION['capability'] <= 2 && $noticia['aprovacao'] == 1 && $noticia['status'] == 'não publicado') : ?>
                              <br /><br /><a class="btnNotAprovar" href="#<?php echo $noticia['id']; ?>"><strong>[aprovar]</strong></a>
                           <?php endif; ?>
                        </td>
                     </tr>
                  <?php endforeach; ?>
               </table>
               <?php if($totalNoticias > $totalPage) : ?>
                  <div class="pagination">
                     <?php echo $pagination->display_pages(); ?>
                  </div>
               <?php endif; $conn->close(); ?>
            <?php else : ?>
               <h3>Nenhuma notícia</h3>
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
   </html>