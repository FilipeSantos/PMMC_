<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/Cache.class.php');
	$cache = new Cache();
	$cache->set_arquivo('page_' . $_SERVER['REQUEST_URI']);
	$cache->start();
	
	if($cache->cached === FALSE):

		$slugNot = (isset($_GET['slug']) && !empty($_GET['slug'])) ? addslashes($_GET['slug']) : FALSE;
		if($slugNot) {
			require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/db.class.php');
			require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/functions.php');
			require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/paginator.class.php');
			$conn = new DbConnect();
			$rs = mysql_query("select titulo from categoria where permalink = '$slugNot' limit 1;");
			if($rs && mysql_num_rows($rs)){
				$tltCateg = mysql_result($rs, 0, 'titulo');
				$conn->close();
			} else {
				Header("HTTP/1.0 404 Not Found");
				@include_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/404.php');
				die();
			}
			
		} else {
			Header("HTTP/1.0 404 Not Found");
			@include_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/404.php');
			die();
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
<title><?php echo $tltCateg; ?> | Jogos Abertos do Interior 2011</title>
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
<link rel="canonical" href="http://www.jogosabertos2011.com.br<?php echo $_SERVER['REQUEST_URI']; ?>" />
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
<script type="text/javascript" src="/_js/functions.js"></script>
<script type="text/javascript">
jQuery(function($) {
});
</script>
</head>

<body>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/bg.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/header.php') ?>
    <div class="content" id="content">
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/navbar.php') ?>
	<h1 class="title red">
        	<div class="sombra" style="margin:-4px 0 0 -24px; height:65px;"></div>
		<span class="notTitle">Notícias</span>
        	<span class="categTitle"><?php echo $tltCateg; ?></span>
		<div class="placeSelect">
			<span class="itemSelected" rel="">Selecione a categoria</span>
			<div class="listSelectItem">
				<ul class="listItemCategoria">
					<?php
						require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/db.class.php');
						require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Categoria.class.php');
						$categorias = Categoria::get_categ();
						foreach($categorias as $categoria) {
					?>
						<li rel="<?php echo $categoria->permalink; ?>"><?php echo $categoria->titulo; ?></li>
					<?php } ?>
				</ul>
				<div class="bg">&nbsp;</div>
			</div>
			<span class="bgGray">&nbsp;</span>
		</div>
		<br clear="all" />
		<span class="bgTltNoticias">&nbsp;</span>
        </h1>
        <div id="content_not">
        	<div class="collum_left base todas">
		<a href="javascript:history.back();" class="bntVoltarNot">Voltar</a>
		<br clear="all" />
		<?php
			if($slugNot):
				$conn = new DbConnect();
				$rs = mysql_query("select count(*) as total, c.id as idCateg from noticia as a inner join noticia_categoria as b on a.id = b.idNoticia
						  inner join categoria as c on b.idCategoria = c.id and c.permalink = '$slugNot' and a.status = 1 and a.dataPublicacao <= CURRENT_TIMESTAMP;");
				$totalNoticias = mysql_result($rs, 0, 'total');
				$idCateg = mysql_result($rs, 0, 'idCateg');
				if($idCateg === NULL){
					$rs = mysql_query("select id from categoria where permalink = '$slugNot' limit 1;");
					if($rs && mysql_num_rows($rs)){
						$idCateg = mysql_result($rs, 0, 'id');
					}
				}
				$totalPage = 10;
				
				if($totalNoticias > $totalPage){
					$pagination = new Paginator();
					$pagination->items_total = $totalNoticias;
					$pagination->items_per_page = $totalPage;
					$pagination->mid_range = 7;
					$pagination->paginate();
				}
				
				$rs = mysql_query("select a.id, a.titulo, a.permalink, a.imagemThumb, a.texto, date_format(a.dataPublicacao, '%d\/%m\/%Y') as data,
						  date_format(a.dataPublicacao, '%H:%i') as hora,  c.titulo as tituloCateg, c.categ as categ_mod from
						  noticia as a inner join noticia_categoria as b on a.id = b.idNoticia inner join categoria as c
						  on c.id = b.idCategoria and c.permalink = '$slugNot' and status = 1 and dataPublicacao <= CURRENT_TIMESTAMP
						  order by dataPublicacao desc " . (isset($pagination->limit) ? $pagination->limit : "") . ";");
				if(mysql_num_rows($rs)):
					$dataPrev = '';
					$i = 0;
					while($item = mysql_fetch_assoc($rs)) :
						$baseUrlCateg = (integer) $item['categ_mod'] !== 0 ? 'modalidade' : 'categoria';
		?>
		<?php if($dataPrev != $item['data']) : $dataPrev = $item['data']; ?>
			<div class="itemCategData<?php echo $i++ ? ' itemCategDataMargin' : ''; ?>"><?php echo $item['data']; ?></div>
		<?php endif; ?>
                <div class="itens">
                	<p class="itemHora"><?php echo $item['hora']; ?></p>
			<div class="boxCategRight">
				<?php if($item['imagemThumb'] != NULL) : ?>
					<a class="img" href="/noticia/<?php echo $item['permalink']; ?>"><img src="<?php echo str_replace('/upload/', '/upload/thumbMid/', $item['imagemThumb']); ?>" alt="<?php echo htmlspecialchars(stripslashes($item['titulo'])); ?>" /></a>
				<?php endif; ?>
				<div class="txt<?php echo $item['imagemThumb'] != NULL ? ' txtHasThumb' : ''; ?>">
					<h5><a href="/<?php echo $baseUrlCateg; ?>/<?php echo $slugNot; ?>"><?php echo stripslashes($tltCateg); ?></a></h5>
					<h3><a href="/noticia/<?php echo $item['permalink']; ?>"><?php echo stripslashes($item['titulo']); ?></a></h3><br clear="all" />
					<p><?php echo limit_string(stripslashes(strip_tags($item['texto'])), 190); ?></p>
				</div>
				<br clear="all" />
			</div>
			<br clear="all" />
                </div>
		<br clear="all" />
		<?php endwhile; endif; endif; ?>
		<br clear="all" />
		<div class="paginationContainner">
			<div class="pagination">
			  <?php
			     echo ($totalNoticias > $totalPage) ? $pagination->display_pages() : '';
			     mysql_close($conn->conn);
			  ?>
			</div>
		</div>
        </div>
	<div class="collum_right noticia_interna_right">
		<?php
			require($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Galeria.class.php');
			$galeriaVideo = Galeria::get_galeria(FALSE, 2, FALSE, TRUE, FALSE, 'limit 0,2', array('idCateg' => $idCateg));
			$galeriaFoto = Galeria::get_galeria(FALSE, 1, FALSE, FALSE, FALSE, 'limit 0, 16', array('idCateg' => $idCateg));
		?>
		<?php if($galeriaVideo !== FALSE || $galeriaFoto !== FALSE) : ?>
			<h2 id="titUltimasNoticias"><span class="bgYellow">GALERIA MULTIMÍDIA</span><span class="bgGray">&nbsp;</span></h2>
	
			<?php if($galeriaFoto !== FALSE) : ?>
				<h5 class="tltYellow">Fotos</h5>		
				<div id="boxSideFotos">
					<ul>
						<?php
							if($galeriaFoto) : foreach($galeriaFoto as $item) :
								$titleAlt = htmlspecialchars($item->titulo);
						?>
							<li><a href="/galeria-multimidia/<?php echo $item->id; ?>" title="<?php echo $titleAlt; ?>"><img src="/upload/galeria/thumb/<?php echo $item->thumb; ?>" alt="<?php echo $titleAlt; ?>" /></a></li>
						<?php endforeach; endif; ?>
					</ul>
					<br clear="all" />
				</div>
			<?php endif; if($galeriaVideo !== FALSE) : ?>
				<h5 class="tltYellow">Vídeos</h5>
				<?php foreach($galeriaVideo as $item) :
					$categ = '';
					if(isset($item->categorias)){					
						foreach($item->categorias as $itemCateg){
							$categ = '<a href="/categoria/' . $itemCateg['permalink'] . '">' . $itemCateg['titulo'] . '</a>, ';
						}
						$categ = substr($categ, 0, -2);
					}
					?>
					<div class="itemSideVideos">
						<a class="img" href="/galeria-multimidia/<?php echo $item->id; ?>"><img src="<?php echo $item->thumb; ?>" alt="<?php echo $item->titulo; ?>" /></a>
						<div class="txt">
							<h5><?php echo $categ; ?></h5>
							<h3><a href="/galeria-multimidia/<?php echo $item->id; ?>"><?php echo $item->titulo; ?></a></h3>
						</div>
						<br clear="all" />
					</div>
			<?php endforeach; endif; ?>
			<a href="/galeria-multimidia" id="btnGaleriaComp">galeria completa</a>
		<?php endif; ?>
	</div>
	<br clear="all" />
        </div>
	<br clear="all" />
    </div>
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>
<?php endif; $cache->close(); ?>