<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/Cache.class.php');
	$cache = new Cache();
	$cache->set_arquivo('page_' . $_SERVER['REQUEST_URI']);
	$cache->start();
	
	if($cache->cached === FALSE):
?>
<?php
	$slugNot = (isset($_GET['slug']) && !empty($_GET['slug'])) ? addslashes($_GET['slug']) : FALSE;
	if($slugNot){
		require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/db.class.php');
		require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/functions.php');
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Utility.class.php');
		$conn = new DbConnect();
		$rs = mysql_query("select id, titulo, texto, imagemThumb, date_format(dataPublicacao, '%d\/%m\/%Y - %H:%i') as data from noticia where permalink = '$slugNot' and status = 1 and dataPublicacao <= CURRENT_TIMESTAMP limit 0, 1;");
		if($rs && mysql_num_rows($rs)){
			$noticia = array();
			$noticia['id'] = mysql_result($rs, 0, 'id');
			$noticia['titulo'] = mysql_result($rs, 0, 'titulo');
			$noticia['texto'] = mysql_result($rs, 0, 'texto');
			$noticia['data'] = mysql_result($rs, 0, 'data');
			$noticia['thumb'] = mysql_result($rs, 0, 'imagemThumb');
			$rs = mysql_query("select a.titulo, a.permalink, a.categ from categoria as a inner join noticia_categoria as b on a.id = b.idCategoria and b.idNoticia = " . $noticia['id'] . ";");
			$categorias = array();
			while($item = mysql_fetch_assoc($rs)){
				$categorias[] = $item;
			}
		} else {
			Header("HTTP/1.0 404 Not Found");
			@include_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/404.php');
			die();
		}
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Organização, pré-jogos, atletas, modalidades, competições, cidades participantes e os últimos acontecimentos dos Jogos Abertos de Mogi das Cruzes. Clique aqui" />
<meta name="keywords" content="últimas, notícias, Mogi, fatos, acontecimentos, organização, JAI, Jogos, Abertos, Interior, 2011" />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title><?php echo $slugNot ? $noticia['titulo'] . ' | ' : '' ?>Notícias | Jogos Abertos do Interior 2011</title>
<meta property="og:image" content="<?php echo $slugNot ? 'http://' . $_SERVER['SERVER_NAME'] . str_replace('/upload/', '/upload/thumbMid/', $noticia['thumb']) : ''; ?>" />
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="../_css/reset.css">
<link rel="stylesheet" type="text/css" href="../_css/style.css">
<link rel="stylesheet" type="text/css" href="../_css/jquery.countdown.css">
<link rel="canonical" href="http://www.jogosabertos2011.com.br<?php echo $_SERVER['REQUEST_URI']; ?>" />
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
<script type="text/javascript" src="/_js/functions.js"></script>
<script type="text/javascript">
jQuery(function($) {
	$addThis.init();
});
function truncar(texto,limite){
	if(texto.length>limite){
		limite--;
		last = texto.substr(limite-1,1);
		while(last!=' ' && limite > 0){
			limite--;
			last = texto.substr(limite-1,1);
		}
		last = texto.substr(limite-2,1);
		if(last == ',' || last == ';'  || last == ':'){
			 texto = texto.substr(0,limite-2) + '...';
		} else if(last == '.' || last == '?' || last == '!'){
			 texto = texto.substr(0,limite-1);
		} else {
			 texto = texto.substr(0,limite-1) + '...';
		}
	}
	return texto;
}
</script>
</head>

<body>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/bg.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/header.php') ?>
    <div class="content" id="content">
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/navbar.php') ?>
      <h1 class="title red">
    <div class="sombra" style="margin:-4px 0 0 -24px; height:65px;"></div> 
        	Notícias
        </h1>
        <div id="content_not">
        	<div class="collum_left noticia_interna">
			<?php if($slugNot && isset($noticia)) : ?>
				<!-- Data -->
				<p class="data"><?php echo $noticia['data']; ?><a href="/noticias" class="bntVoltarNot">Voltar para noticias</a></p>
				<!-- Categoria -->
				<p class="categoria categoriaNot">
					<?php
						$txtCategorias = '';
						foreach($categorias as $categoria){
							$baseUrl = (integer) $categoria['categ'] !== 0 ? 'modalidade' : 'categoria';
							$txtCategorias = $txtCategorias . '<a href="/' . $baseUrl . '/' . $categoria['permalink'] . '">' . $categoria['titulo']  .'</a>, ';
						}
						echo substr($txtCategorias, 0, -2);
					?>
				</p>
				<!-- Título -->
				<h2><?php echo stripslashes($noticia['titulo']); ?></h2>
				<div class="contentNoticia">
					<a target="_blank" href="/noticia/imprimir/<?php echo $noticia['id']; ?>" class="btnPrint">imprimir</a>
					<br clear="all" />
					<?php echo stripslashes($noticia['texto']); ?>
				</div>
				<br clear="all" />
				<div class="redes_sociais">
					<ul class="listOpt">
						<li><a href="/noticia/imprimir/<?php echo $noticia['id']; ?>" class="btnPrint" title="Imprimir">Imprimir</a></li>
						<li class="last"><a href="#" class="btnEmail addthis_button_email" addthis:title="<?php echo htmlspecialchars($noticia['titulo']); ?>" addthis:url="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" title="Enviar por email">&nbsp;</a></li>
					</ul>
					<ul class="listSocial">
						<li class="last"><a href="#" class="btnFb addthis_button_facebook" addthis:title="<?php echo htmlspecialchars($noticia['titulo']); ?>" addthis:url="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" title="Compartilhar pelo Facebook">&nbsp;</a></li>
						<li><a href="#" class="btnTw addthis_button_twitter" addthis:title="<?php echo htmlspecialchars($noticia['titulo']); ?>" addthis:url="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" title="Compartilhar pelo Twitter">&nbsp;</a></li>
					</ul>
					<br clear="all" />
				</div>
			<?php endif; ?>
		</div>
            
            <div class="collum_right noticia_interna_right">
		<?php if($slugNot && isset($noticia)) : ?>
			<?php
				$rs = mysql_query("select a.titulo, a.id, a.permalink, a.imagemThumb, date_format(a.dataPublicacao, '%d\/%m\/%Y - %H:%i') as data, count(distinct c.titulo) as total from noticia as a
						  inner join noticia_tag as b on a.id = b.idnoticia inner join tag as c on b.idtag = c.id
						  and b.idtag in (select idtag from noticia_tag where idnoticia = " . $noticia['id'] .") and a.id != " . $noticia['id'] . "
						  and a.status = 1 and a.dataPublicacao <= CURRENT_TIMESTAMP group by a.titulo order by count(distinct c.titulo) desc, a.dataPublicacao desc limit 0 , 8;");
				if(mysql_num_rows($rs) === 0) {
					$rs = mysql_query("select a.titulo, a.id, a.permalink, a.imagemThumb, date_format(a.dataPublicacao, '%d\/%m\/%Y - %H:%i') as data from noticia as a
						  inner join noticia_categoria as b on a.id = b.idNoticia and b.idCategoria in (select distinct idCategoria from noticia_categoria where idNoticia = $noticia[id]) and a.id != $noticia[id] order by a.id desc limit 0,8;");
				}
				if(mysql_num_rows($rs)) :
			?>
			<h2 id="titUltimasNoticias"><span class="bgRed">notícias relacionadas</span><span class="bgGray">&nbsp;</span></h2>
			<?php
				while($item = mysql_fetch_assoc($rs)) :
					$rsCateg = mysql_query("select a.titulo, a.permalink, a.categ from categoria as a
							       inner join noticia_categoria as b on a.id = b.idCategoria and b.idNoticia = " . $item['id'] . " order by a.titulo limit 0,1;");
					$txtCateg = '';
					while($itemCateg = mysql_fetch_assoc($rsCateg)){
						$baseUrl = (integer) $itemCateg['categ'] !== 0 ? 'modalidade' : 'categoria';
						$txtCateg = $txtCateg . '<a href="/' . $baseUrl . '/' . $itemCateg['permalink'] .'">' . $itemCateg['titulo'] . '</a>, ';
					}
					$txtCateg = substr($txtCateg, 0, -2);
			?>
				<div class="itemNoticiaInterna">
					<p class="hora data_menor"><?php echo $item['data']; ?></p>
					<?php if($item['imagemThumb'] != NULL) : $limit = 55; ?>
						<a class="img" href="/noticia/<?php echo $item['permalink']; ?>" title="<?php echo htmlspecialchars(stripslashes($item['titulo'])); ?>"><img src="<?php echo str_replace('/upload/', '/upload/thumbMid/', $item['imagemThumb']); ?>" alt="<?php echo htmlspecialchars(stripslashes($item['titulo'])); ?>" /></a>
					<?php else: $limit = 300; endif; ?>
					<div class="txt<?php echo ($item['imagemThumb'] == NULL) ? ' txtBig' : ''; ?>">
						<h5><?php echo $txtCateg; ?></h5>
						<h3><a href="/noticia/<?php echo $item['permalink']; ?>" title="<?php echo htmlspecialchars(stripslashes($item['titulo'])); ?>"><?php echo Utility::limit_string(htmlspecialchars(stripslashes($item['titulo'])), $limit); ?></a></h3>
					</div>
					<br clear="all" />
				</div>
			<?php endwhile; ?>
				<div class="mais_noticias">
					<a href="/todas-noticias">todas as notícias</a>
				</div>
			<?php endif; ?>
		<?php
			endif;
			mysql_close($conn->conn);
		?>
	</div>
        </div>
	<br clear="all" />
	</div>
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>
<?php endif; $cache->close(); ?>