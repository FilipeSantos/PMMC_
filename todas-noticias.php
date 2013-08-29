<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/Cache.class.php');
	$cache = new Cache();
	$cache->set_arquivo('page_' . $_SERVER['REQUEST_URI']);
	$cache->start();
	
	if($cache->cached === FALSE):
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Organização, pré-jogos, atletas, modalidades, competições, cidades participantes e os últimos acontecimentos dos Jogos Abertos de Mogi das Cruzes. Clique aqui" />
<meta name="keywords" content="últimas, notícias, Mogi, fatos, acontecimentos, organização, JAI, Jogos, Abertos, Interior, 2011" />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title>Todas as Notícias | Jogos Abertos do Interior 2011 </title>
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
        	Notícias
        </h1>
        <div id="content_not">
        	<div class="collum_left base todas">
			<?php
				require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/db.class.php');
				require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/functions.php');
				require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/paginator.class.php');
				$conn = new DbConnect();
				$i = 0;
				
				$rs = mysql_query("select count(*) as total from noticia as a where a.status = 1 and a.dataPublicacao <= CURRENT_TIMESTAMP;");
				$totalNoticias = mysql_result($rs, 0, 'total');
				$totalPage = 10;
				
				if($totalNoticias > $totalPage){
					$pagination = new Paginator();
					$pagination->items_total = $totalNoticias;
					$pagination->items_per_page = $totalPage;
					$pagination->mid_range = 7;
					$pagination->paginate();
				}
				
				$rs = mysql_query("select id, titulo, texto, permalink, date_format(dataPublicacao, '%d\/%m\/%Y') as data,
						  date_format(dataPublicacao, '%H:%i') as hora from
						  noticia where status = 1 and dataPublicacao <= CURRENT_TIMESTAMP
						  order by dataPublicacao desc " . (isset($pagination->limit) ? $pagination->limit : "") . ";");
				
				$dataPrev = '';
				while($item = mysql_fetch_assoc($rs)) :
					$rsCateg = mysql_query("select a.titulo, a.permalink, a.categ from categoria as a
							       inner join noticia_categoria as b on a.id = b.idCategoria and b.idNoticia = " . $item['id'] . " order by a.titulo limit 0,1;");
					$txtCateg = '';
					while($itemCateg = mysql_fetch_assoc($rsCateg)){
						$baseUrl = (integer) $itemCateg['categ'] !== 0 ? 'modalidade' : 'categoria';
						$txtCateg = $txtCateg . '<a href="/' . $baseUrl . '/' . $itemCateg['permalink'] .'">' . stripslashes($itemCateg['titulo']) . '</a>, ';
					}
					$txtCateg = substr($txtCateg, 0, -2);
					
			?>
			<?php if($dataPrev != $item['data']) : $dataPrev = $item['data']; ?>
				<div class="itemCategData<?php echo $i++ ? ' itemCategDataMargin' : ''; ?>"><?php echo $item['data']; ?></div>
			<?php endif; ?>
				<div class="itens">
					<p class="data_dmenor"><?php echo $item['hora']; ?></p>
					<p class="categoria_dmenor"><?php echo $txtCateg; ?><br clear="all" /></p>
					<h3 class="txt_dmenor"><a href="/noticia/<?php echo $item['permalink']; ?>"><?php echo limit_string(stripslashes($item['titulo']), 100); ?></a></h3>
				</div>
				<br clear="all" />
			<?php
				endwhile;
			?>
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
        </div>
	<br clear="all" />
	</div>
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>
<?php endif; $cache->close(); ?>