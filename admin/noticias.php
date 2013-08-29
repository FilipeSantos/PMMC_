<?php
   require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
   $login = new Login();
   if(!$login->verificaLogin()){
      header('Location:/admin/login.php');
      exit();
   }
   $login->atualizaSession();
   
   $conn = new DbConnect();
   
   if($excluir = isset($_GET['acao']) && $_GET['acao'] == 'excluir' ? $_GET['acao'] : FALSE){
      if($id = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : FALSE){
         mysql_query("delete from noticia_categoria where idNoticia = $id;");
         mysql_query("delete from noticia_tag where idNoticia = $id;");
         mysql_query("delete from noticia where id = $id;");
      }
   }

   $filtroCateg = isset($_GET['inputCategoria']) && is_numeric($_GET['inputCategoria']) ? $_GET['inputCategoria'] : FALSE;
   $filtroDestaque = isset($_GET['inputDestaque']) && is_numeric($_GET['inputDestaque']) ? $_GET['inputDestaque'] : FALSE;
   $filtroDestaqueHome = isset($_GET['inputDestaqueHome']) && is_numeric($_GET['inputDestaqueHome']) ? $_GET['inputDestaqueHome'] : FALSE;
   $filtroStatus = (isset($_GET['inputStatus']) && is_numeric($_GET['inputStatus']) && !empty($_GET['inputStatus'])) ? $_GET['inputStatus'] : FALSE;

   $cond = '';
   $filtro = 0;
   $orderDestaq = FALSE;
   if($filtroCateg !== FALSE){      
      $cond .= " inner join noticia_categoria as b on a.id = b.idNoticia and b.idCategoria = $filtroCateg and ";
      $filtro = 1;
   }
   if($filtroDestaque !== FALSE){
      $cond .= (!$filtro ? 'where ' : '') . "a.importancia = $filtroDestaque and ";
      $filtro = 1;
      $orderDestaq = 'dataImportancia desc, ';
   }
   if($filtroDestaqueHome !== FALSE){
      $cond .= (!$filtro ? 'where ' : '') . "a.exibirHome = $filtroDestaqueHome and ";
      $filtro = 1;
      $orderDestaq = 'dataImportanciaHome desc, ';
   }
   if($filtroStatus !== FALSE){
      $cond .= (!$filtro ? 'where ' : '') . "a.status = " . ($filtroStatus > 1 ? ($filtroStatus == 3 ? '0' : '1') : $filtroStatus) . ($filtroStatus > 1 && $filtroStatus != 3 ?  " and a.dataPublicacao > CURRENT_TIMESTAMP " : "") . ($filtroStatus == 3 ? ' and a.aprovacao = 1' : '') . ' and ';
   }
   $cond = !empty($cond) ? substr($cond, 0, -4) : '';

   if($_SESSION['capability'] <= 2){
      $rs = mysql_query("select count(*) as total from noticia as a " . (!empty($cond) ? $cond : ' where 1 ') . ";");
      if($rs && mysql_num_rows($rs)){
         $totalNoticias = mysql_result($rs, 0, 'total');
         $totalPage = 15;
         $rs = FALSE;
      }
      
      $idsDestaq = array();
      $idsDestaqHome = array();
      
      $rs = mysql_query("SELECT a.id FROM (
                                SELECT *
                                FROM noticia
                                WHERE importancia !=0
                                AND imagemThumb IS NOT NULL
                                AND STATUS = 1
                                AND aprovacao IS NULL
                                AND dataPublicacao <=
                                CURRENT_TIMESTAMP ORDER BY importancia, dataImportancia DESC
                            ) AS a WHERE 1 GROUP BY a.importancia ORDER BY a.importancia, a.dataImportancia DESC LIMIT 16;");
      if($rs && mysql_num_rows($rs)){
         while($item = mysql_fetch_assoc($rs)){
            $idsDestaq[] = $item['id'];
         }
         $rs = FALSE;
      }
      
      $rs = mysql_query("SELECT a.id FROM (
                                SELECT *
                                FROM noticia
                                WHERE exibirHome !=0
                                AND imagemThumb IS NOT NULL
                                AND STATUS = 1
                                AND aprovacao IS NULL
                                AND dataPublicacao <=
                                CURRENT_TIMESTAMP ORDER BY exibirHome, dataImportanciaHome DESC
                            ) AS a WHERE 1 GROUP BY a.exibirHome ORDER BY a.exibirHome, a.dataImportanciaHome DESC LIMIT 10;");
      if($rs && mysql_num_rows($rs)){
         while($item = mysql_fetch_assoc($rs)){
            $idsDestaqHome[] = $item['id'];
         }
         $rs = FALSE;
      }

      if($totalNoticias > $totalPage){
         $pagination = new Paginator();
         $pagination->items_total = $totalNoticias;
         $pagination->items_per_page = $totalPage;
         $pagination->mid_range = 3;
         $pagination->paginate();
      }
   
      $rs = mysql_query("select a.id, a.titulo, a.permalink, a.imagemThumb, a.importancia, a.exibirHome, a.status,
                           unix_timestamp(a.dataPublicacao) as data, a.aprovacao, date_format(a.dataPublicacao, \"%d\/%m\/%Y, às %H:%i\") as datePub
                           from noticia as a " . (!empty($cond) ? $cond : ' where 1 ') . " order by $orderDestaq a.id desc " . (isset($pagination->limit) ? $pagination->limit : "") . ";");
      
      $noticias = array();
      $i = 0;
      if($rs && mysql_num_rows($rs)){
         while($item = mysql_fetch_assoc($rs)){
            $noticias[$i]['id'] = $item['id'];
            $noticias[$i]['titulo'] = $item['titulo'];
            $noticias[$i]['permalink'] = $item['permalink'];
            $noticias[$i]['imagemThumb'] = $item['imagemThumb'];
            $noticias[$i]['destaque'] = !$item['importancia'] ? '<em>(nenhum)</em>' : $item['importancia'];
            $noticias[$i]['destaqueHome'] = !$item['exibirHome'] ? '<em>(nenhum)</em>' : $item['exibirHome'];
            $noticias[$i]['aprovacao'] = $item['aprovacao'];
            $noticias[$i++]['status'] = ($item['status'] == '0') ? 'não publicado' : ((time() >= $item['data']) ? 'publicado' : 'publicado <em>(agendado para ' . $item['datePub'] . ')</em>');
         }
      }
   } else {
      $rs = mysql_query("select count(*) as total from noticia as a inner join usuario as b on a.idLog = b.id and b.id = $_SESSION[idUser] $cond;");
      if($rs && mysql_num_rows($rs)){
         $totalNoticias = mysql_result($rs, 0, 'total');
         $totalPage = 15;
         $cond = !empty($cond) ? $cond . ' and' : 'where';
         $rs = FALSE;
      }
      
      $idsDestaq = array();
      $idsDestaqHome = array();
      
      $rs = mysql_query("SELECT a.id FROM (
                                SELECT *
                                FROM noticia
                                WHERE importancia !=0
                                AND imagemThumb IS NOT NULL
                                AND STATUS = 1
                                AND aprovacao IS NULL
                                AND dataPublicacao <=
                                CURRENT_TIMESTAMP and idLog = $_SESSION[idUser] ORDER BY importancia, dataImportancia DESC
                            ) AS a WHERE 1 GROUP BY a.importancia ORDER BY a.importancia, a.dataImportancia DESC LIMIT 16;");
      if($rs && mysql_num_rows($rs)){
         while($item = mysql_fetch_assoc($rs)){
            $idsDestaq[] = $item['id'];
         }
         $rs = FALSE;
      }
      
      $rs = mysql_query("SELECT a.id FROM (
                                SELECT *
                                FROM noticia
                                WHERE exibirHome !=0
                                AND imagemThumb IS NOT NULL
                                AND STATUS = 1
                                AND aprovacao IS NULL
                                AND dataPublicacao <=
                                CURRENT_TIMESTAMP and idLog = $_SESSION[idUser] ORDER BY exibirHome, dataImportanciaHome DESC
                            ) AS a WHERE 1 GROUP BY a.exibirHome ORDER BY a.exibirHome, a.dataImportanciaHome DESC LIMIT 10;");
      if($rs && mysql_num_rows($rs)){
         while($item = mysql_fetch_assoc($rs)){
            $idsDestaqHome[] = $item['id'];
         }
         $rs = FALSE;
      }
      
      if($totalNoticias > $totalPage){
         $pagination = new Paginator();
         $pagination->items_total = $totalNoticias;
         $pagination->items_per_page = $totalPage;
         $pagination->mid_range = 3;
         $pagination->paginate();
      }
   
      $rs = mysql_query("select a.id, a.titulo, a.permalink, a.imagemThumb, a.importancia, a.exibirHome, a.status,
                           unix_timestamp(a.dataPublicacao) as data, a.aprovacao, date_format(a.dataPublicacao, \"%d\/%m\/%Y, às %H:%i\") as datePub
                           from noticia as a $cond a.idLog = $_SESSION[idUser] order by a.id desc " . (isset($pagination->limit) ? $pagination->limit : "") . ";");
   
      $noticias = array();
      $i = 0;
      
      if($rs && mysql_num_rows($rs)){
         while($item = mysql_fetch_assoc($rs)){
            $noticias[$i]['id'] = $item['id'];
            $noticias[$i]['titulo'] = $item['titulo'];
            $noticias[$i]['permalink'] = $item['permalink'];
            $noticias[$i]['imagemThumb'] = $item['imagemThumb'];
            $noticias[$i]['destaque'] = !$item['importancia'] ? '<em>(nenhum)</em>' : $item['importancia'];
            $noticias[$i]['destaqueHome'] = !$item['exibirHome'] ? '<em>(nenhum)</em>' : $item['exibirHome'];
            $noticias[$i]['aprovacao'] = $item['aprovacao'];
            $noticias[$i++]['status'] = ($item['status'] == '0') ? 'não publicado' : ((time() >= $item['data']) ? 'publicado' : 'publicado <em>(agendado para ' . $item['datePub'] . ')</em>');
         }
      }
   }
?>
   <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
   <html lang="pt" xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://developers.facebook.com/schema/">
   <head>
      <title>Jogos Abertos do Interior de 2011 - Admin - Releases</title>
      <meta name="title" content="Jogos Abertos do Interior de 2011 - Admin - Releases" />
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
   <body class="noticias">
      <div id="wrapper">
         <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/header.php'); ?>
         <div id="contentTb">
            <h2>Notícias</h2>
            <ul id="menuSubSec">
               <li><a<?php echo isset($pg_noticias) ? ' class="hover"' : ''; ?> href="/admin/noticias.php">Home</a></li>
               <li><a<?php echo isset($pg_noticia_cadastrar) ? ' class="hover"' : ''; ?> href="/admin/noticia/cadastrar.php">Cadastrar</a></li>
            </ul>
            <br clear="all" />
            
            <div class="boxFiltro">
               <form id="formFiltro" method="get" action="/admin/noticias.php">
                  <span>Filtrar por:</span>
                  <div class="item">
                     <label for="inputCategoria">Categoria</label>
                     <select name="inputCategoria" id="inputCategoria">
                        <option value=""<?php !$filtroCateg ? ' selected="selected"' : ''; ?>>Todas</option>
                        <?php
                           $rs = mysql_query("select titulo, id from categoria where 1 order by titulo;");
                           if(mysql_num_rows($rs)){
                              while($item = mysql_fetch_assoc($rs)){
                                 echo '<option value="' . $item['id'] .'"' . ($filtroCateg == $item['id'] ? ' selected="selected"' : '') .'>' . $item['titulo'] .'</option>';
                              }
                           }
                        ?>
                     </select>
                     <br clear="all" />
                  </div>
                  <div class="item">
                     <label for="inputStatus">Status</label>
                     <select name="inputStatus" id="inputStatus">
                        <option value=""<?php echo $filtroStatus === FALSE ? ' selected="selected"' : ''; ?>>Todos</option>
                        <option value="1"<?php echo $filtroStatus === '1' ? ' selected="selected"' : ''; ?>>Publicado</option>
                        <option value="2"<?php echo $filtroStatus === '2' ? ' selected="selected"' : ''; ?>>Agendado</option>
                        <option value="0"<?php echo $filtroStatus === '0' ? ' selected="selected"' : ''; ?>>Inativo</option>
                        <option value="3"<?php echo $filtroStatus === '3' ? ' selected="selected"' : ''; ?>>Pendente</option>
                     </select>
                     <br clear="all" />
                  </div>
                  <br clear="all" />
                  <div class="item" style="padding-left: 80px;">
                     <label for="inputDestaqueHome">Destaque (Home)</label>
                     <select name="inputDestaqueHome" id="inputDestaqueHome">
                        <option value=""<?php echo !$filtroDestaqueHome ? ' selected="selected"' : ''; ?>>Nenhum</option>
                        <?php for($i=1; $i<=6; $i++) : ?>
                           <option value="<?php echo $i; ?>"<?php echo $filtroDestaqueHome == $i ? ' selected="selected"' : ''; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                     </select>
                     <br clear="all" />
                  </div>
                  <div class="item">
                     <label for="inputDestaque">Destaque (Notícias)</label>
                     <select name="inputDestaque" id="inputDestaque">
                        <option value=""<?php echo !$filtroDestaque ? ' selected="selected"' : ''; ?>>Nenhum</option>
                        <?php for($i=1; $i<=16; $i++) : ?>
                           <option value="<?php echo $i; ?>"<?php echo $filtroDestaque == $i ? ' selected="selected"' : ''; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                     </select>
                     <br clear="all" />
                  </div>
                  <input type="submit" id="submit" value="Filtrar" />
                  <br clear="all" />
               </form>
            </div>
            <br clear="all" />
            
            <?php if($totalNoticias) : ?>
               <?php if($_SESSION['capability'] > 2) : ?>
                  <h3>Suas notícias</h3>
               <?php endif; ?>
               <div class="pagination">
                  <?php echo ($totalNoticias > $totalPage) ? $pagination->display_pages() : ''; ?>
               </div>
               <table id="tbVotos">
                  <tr>
                     <th>Título</th>
                     <th>Thumb</th>
                     <th>Categoria(s)</th>
                     <th class="destaque">Destaque (<em>Home</em>)</th>
                     <th class="destaque">Destaque (<em>Notícias</em>)</th>
                     <th class="status">Status</th>
                     <th class="acao">&nbsp;</th>
                  </tr>
                  <?php foreach($noticias as $key=>$noticia) : ?>
                     <tr>
                        <td class="userNome"><strong><a href="/noticia/<?php echo $noticia['permalink']; ?>" target="_blank"><?php echo stripslashes($noticia['titulo']); ?></a></strong></td>
                        <td>
                           <?php if($noticia['imagemThumb']): ?>
                              <img src="<?php echo $noticia['imagemThumb']; ?>" alt="<?php echo $noticia['titulo']; ?>" width="100" height="67" />
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
                              echo stripslashes(substr($txtCateg, 0, -2));
                           ?>
                        </td>
                        <td><?php echo in_array($noticia['id'], $idsDestaqHome) ? $noticia['destaqueHome']. ' <em>(atual)</em>' : $noticia['destaqueHome'] ; ?></td>
                        <td><?php echo in_array($noticia['id'], $idsDestaq) ? $noticia['destaque'] . ' <em>(atual)</em>' : $noticia['destaque']; ?></td>
                        <td><?php echo $noticia['status']; ?></td>
                        <?php if($_SESSION['capability'] <= 2) : ?>
                           <td align="center"><strong><a class="btnEditar" href="/admin/noticia/editar.php?id=<?php echo $noticia['id']; ?>">[editar]</a> - <a id="btnExcluirNoticia" class="btnExcluir" href="#<?php echo $noticia['id']; ?>">[excluir]</a></strong>
                              <?php if($_SESSION['capability'] <= 2 && $noticia['aprovacao'] == 1 && $noticia['status'] == 'não publicado') : ?>
                                 <br /><br /><a class="btnNotAprovar" href="#<?php echo $noticia['id']; ?>"><strong>[aprovar]</strong></a>
                              <?php endif; ?>
                           </td>
                        <?php else : if($noticia['aprovacao'] == 1 && $noticia['status'] == 'não publicado') : ?>
                           <td align="center"><strong><a class="btnEditar" href="/admin/noticia/editar.php?id=<?php echo $noticia['id']; ?>">[editar]</a> - <a id="btnExcluirNoticia" class="btnExcluir" href="#<?php echo $noticia['id']; ?>">[excluir]</a></strong></td>
                           <?php else : ?>
                           <td align="center"><strong><a href="/noticia/<?php echo $noticia['permalink']; ?>" target="_blank">[visualizar]</a></strong></td>
                        <?php endif; endif; ?>
                     </tr>
                  <?php endforeach; ?>
               </table>
               <?php if($totalNoticias > $totalPage) : ?>
                  <div class="pagination">
                     <?php echo $pagination->display_pages(); ?>
                  </div>
               <?php endif; $conn->close(); ?>
               
            <?php else : ?>
               <h3>Nenhum resultado encontrado.</h3>
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