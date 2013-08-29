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
<title>Notícias | Jogos Abertos do Interior 2011 </title>
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
        	<span class="notTitle" style="border: none;">Notícias</span>
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
		<span class="bgTltNoticias">&nbsp;</span>
        </h1>
        <div id="content_not">
		<div class="collum_left base collum_left_noticias">
			<div class="not_destaq not_destaqHome not_destaq_noticia">
		<?php
			require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/Utility.class.php');
			
			$noticias = Noticia::get_destaque();
			$utility = new Utility();
			$cont = 0;
			$idsExibidos = array();
		?>
		
				<div class="noticiaTop noticiaContent">
					<?php while(isset($noticias[$cont]) && $noticias[$cont]['importancia'] <= 2 && $cont <= 1) : $idsExibidos[] = $noticias[$cont]['id']; ?>
						<div class="itemNoticia">
							<h3><a href="/noticia/<?php echo $noticias[$cont]['permalink']; ?>"><?php echo stripslashes($noticias[$cont]['titulo']); ?></a></h3>
							<a class="img" href="/noticia/<?php echo $noticias[$cont]['permalink']; ?>"><img src="<?php echo $noticias[$cont]['imagemThumb']; ?>" alt="<?php echo htmlspecialchars(stripslashes($noticias[$cont]['titulo'])); ?>" /></a>
							<p><?php echo $utility->limit_string(strip_tags(stripslashes($noticias[$cont++]['texto'])), 170); ?></p>
						</div>
					<?php endwhile; ?>
					<br clear="all" />
				</div>
				
				<div class="destaquesSub">
					<div class="noticiaMid noticiaContent">
						<?php while(isset($noticias[$cont]) && $noticias[$cont]['importancia'] <= 4 && $cont <= 3) : $idsExibidos[] = $noticias[$cont]['id']; ?>
							<div class="itemNoticia">
								<a class="img" href="/noticia/<?php echo $noticias[$cont]['permalink']; ?>"><img src="<?php echo str_replace('/upload/', '/upload/thumbMid/', $noticias[$cont]['imagemThumb']); ?>" alt="<?php echo htmlspecialchars(stripslashes($noticias[$cont]['titulo'])); ?>" /></a>
								<div class="txt">
									<h5><?php echo $noticias[$cont]['categoria']; ?></h5>
									<h3><a href="/noticia/<?php echo $noticias[$cont]['permalink']; ?>"><?php echo stripslashes($noticias[$cont++]['titulo']); ?></a></h3>
								</div>
							</div>
						<?php endwhile; ?>
						<br clear="all" />
					</div>
					
					<div class="noticiaMid noticiaContent">
						<?php while(isset($noticias[$cont]) && $noticias[$cont]['importancia'] <= 6 && $cont <= 5) : $idsExibidos[] = $noticias[$cont]['id']; ?>
							<div class="itemNoticia">
								<a class="img" href="/noticia/<?php echo $noticias[$cont]['permalink']; ?>"><img src="<?php echo str_replace('/upload/', '/upload/thumbMid/', $noticias[$cont]['imagemThumb']); ?>" alt="<?php echo htmlspecialchars(stripslashes($noticias[$cont]['titulo'])); ?>" /></a>
								<div class="txt">
									<h5><?php echo $noticias[$cont]['categoria']; ?></h5>
									<h3><a href="/noticia/<?php echo $noticias[$cont]['permalink']; ?>"><?php echo stripslashes($noticias[$cont++]['titulo']); ?></a></h3>
								</div>
							</div>
						<?php endwhile; ?>
						<br clear="all" />
					</div>
					
					<div class="noticiaBot noticiaContent">
						<?php while(isset($noticias[$cont]) && $noticias[$cont]['importancia'] <= 10 && $cont <= 9) : $idsExibidos[] = $noticias[$cont]['id']; ?>
							<div class="itemNoticia">
								<a href="/noticia/<?php echo $noticias[$cont]['permalink']; ?>" class="img"><img src="<?php echo $noticias[$cont]['imagemThumb']; ?>" alt="<?php echo htmlspecialchars(stripslashes($noticias[$cont]['titulo'])); ?>" /></a>
								<h5><?php echo $noticias[$cont]['categoria']; ?></h5>
								<h3><a href="/noticia/<?php echo $noticias[$cont]['permalink']; ?>"><?php echo stripslashes($noticias[$cont++]['titulo']); ?></a></h3>
							</div>
						<?php endwhile;?>
						<br clear="all" />
					</div>
					
					<div class="noticiaMid noticiaContent">
						<?php while(isset($noticias[$cont]) && $noticias[$cont]['importancia'] <= 12 && $cont <= 11) : $idsExibidos[] = $noticias[$cont]['id']; ?>
							<div class="itemNoticia">
								<a class="img" href="/noticia/<?php echo $noticias[$cont]['permalink']; ?>"><img src="<?php echo str_replace('/upload/', '/upload/thumbMid/', $noticias[$cont]['imagemThumb']); ?>" alt="<?php echo htmlspecialchars(stripslashes($noticias[$cont]['titulo'])); ?>" /></a>
								<div class="txt">
									<h5><?php echo $noticias[$cont]['categoria']; ?></h5>
									<h3><a href="/noticia/<?php echo $noticias[$cont]['permalink']; ?>"><?php echo stripslashes($noticias[$cont++]['titulo']); ?></a></h3>
								</div>
							</div>
						<?php endwhile;?>
						<br clear="all" />
					</div>
					
					<div class="noticiaMid noticiaContent">
						<?php while(isset($noticias[$cont]) && $noticias[$cont]['importancia'] <= 14 && $cont <= 13) : $idsExibidos[] = $noticias[$cont]['id']; ?>
							<div class="itemNoticia">
								<a class="img" href="/noticia/<?php echo $noticias[$cont]['permalink']; ?>"><img src="<?php echo str_replace('/upload/', '/upload/thumbMid/', $noticias[$cont]['imagemThumb']); ?>" alt="<?php echo htmlspecialchars(stripslashes($noticias[$cont]['titulo'])); ?>" /></a>
								<div class="txt">
									<h5><?php echo $noticias[$cont]['categoria']; ?></h5>
									<h3><a href="/noticia/<?php echo $noticias[$cont]['permalink']; ?>"><?php echo stripslashes($noticias[$cont++]['titulo']); ?></a></h3>
								</div>
							</div>
						<?php endwhile; ?>
						<br clear="all" />
					</div>
					
					<div class="noticiaMid noticiaContent noticiaLast">
						<?php while(isset($noticias[$cont]) && $noticias[$cont]['importancia'] <= 16 && $cont <= 15) : $idsExibidos[] = $noticias[$cont]['id']; ?>
							<div class="itemNoticia">
								<a class="img" href="/noticia/<?php echo $noticias[$cont]['permalink']; ?>"><img src="<?php echo str_replace('/upload/', '/upload/thumbMid/', $noticias[$cont]['imagemThumb']); ?>" alt="<?php echo htmlspecialchars(stripslashes($noticias[$cont]['titulo'])); ?>" /></a>
								<div class="txt">
									<h5><?php echo $noticias[$cont]['categoria']; ?></h5>
									<h3><a href="/noticia/<?php echo $noticias[$cont]['permalink']; ?>"><?php echo stripslashes($noticias[$cont++]['titulo']); ?></a></h3>
								</div>
							</div>
						<?php endwhile; ?>
						<br clear="all" />
					</div>
				</div>
			</div>
		</div>
		<div class="collum_right noticia_interna_right">
			<h2 id="titUltimasNoticias"><span class="bgRed">Mais notícias</span><span class="bgGray">&nbsp;</span></h2>
			
			<?php
				$ultimasNoticias = Noticia::get_ultimas($idsExibidos);
				$dataPrev = '';
				$i = 0;
				foreach($ultimasNoticias as $item) :
					if($dataPrev != $item['data']) : $dataPrev = $item['data'];
			?>
						<div class="tit_data<?php echo !$i++ ? ' primeiro' : ''; ?>"><?php echo $utility->data_extenso($item['data']); ?></div>
					<?php endif; ?>
				<div class="item">
					<p class="hora"><?php echo $item['hora']; ?></p>
					<p class="categoria"><?php echo $item['categoria']; ?></p>
					<h3><a href="/noticia/<?php echo $item['permalink']; ?>"><?php echo $utility->limit_string($item['titulo'], 80); ?></a></h3>
				</div>
			<?php endforeach; ?>
	
			<div class="mais_noticias">
				<a href="/todas-noticias">todas as notícias</a>
			</div>
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