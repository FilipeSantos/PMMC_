<?php
	$strBusca = isset($_GET['busca']) && !empty($_GET['busca']) ? addslashes(urldecode($_GET['busca'])) : FALSE;

	if($strBusca !== FALSE){
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Busca.class.php');
		require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/paginator.class.php');
		$busca = new Busca($strBusca);
		
		$column = array(
			array(
				'nome' => 'titulo',
				'peso' => 1.2
			), array(
				'nome' => 'descricao',
				'peso' => 0.5
			), array(
				'nome' => 'texto',
				'peso' => 0.4
			), array(
				'nome' => 'tags',
				'peso' => 0.6
			), array(
				'nome' => 'categoria',
				'peso' => 0.8
			)
		);
		
		$column2 = array(
			array(
				'nome' => 'titulo',
				'peso' => 1.2
			), array(
				'nome' => 'descricao',
				'peso' => 0.5
			), array(
				'nome' => 'tags',
				'peso' => 0.6
			), array(
				'nome' => 'categoria',
				'peso' => 0.8
			)
		);
		
		$busca->Add_tipo('noticia', 'search_noticia', $column);
		$busca->Add_tipo('foto', 'search_galeria', $column2);
		$busca->Add_tipo('video', 'search_galeria', $column2);
		$busca->Set_total();
		$totalPage = 10;
		$totalItem = $busca->total['noticia'];

		if($totalItem > $totalPage){
			$pagination = new Paginator();
			$pagination->items_total = $totalItem;
			$pagination->items_per_page = $totalPage;
			$pagination->mid_range = 7;
			$pagination->paginate();
		}
		
		$results = $busca->Buscar((isset($pagination->limit) ? $pagination->limit : ''));

		$resultsNot = $resultsFoto = $resultsVideo = array();
		
		if($results !== FALSE){
		    foreach($results as $item){
			  if((integer) $item->tipo == 3){
				  array_push($resultsNot, $item);
			  } elseif((integer) $item->tipo === 1){
				  array_push($resultsFoto, $item);
			  } elseif((integer) $item->tipo === 2){
				  array_push($resultsVideo, $item);
			  }
		    }
		}		

		unset($results);
	}
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
<title>Busca | Jogos Abertos do Interior 2011</title>
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
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
        	RESULTADO DA BUSCA
	</h1>
        <div id="content_not">
		<?php if(!empty($strBusca)) : ?>
			<p class="txtBusca">Foram encontrados <?php echo $totalItem; ?> resultados para <strong>"<?php echo (isset($_GET['busca']) && !empty($_GET['busca'])) ? urldecode($_GET['busca']) : ''; ?>"</strong></p>
			<div class="collum_left base collum_left_noticias collum_left_busca">
				<?php if($busca->total['noticia'] > 0): ?>
					<div class="itemCategData itemCategDataMargin">Em notícias</div>
					<?php foreach($resultsNot as $result) : ?>
					<div class="itens">
						<p class="data_dmenor"><?php echo $result->data; ?></p>
						<div class="boxTxt">
							<p class="categoria_dmenor"><a href="/<?php echo (integer) $result->mod_categ === 1 ? 'modalidade' : 'categoria'; ?>/<?php echo $result->permalink_categ; ?>"><?php echo $result->categoria; ?></a><br clear="all" /></p>
							<h3 class="txt_dmenor"><a href="/noticia/<?php echo $result->permalink; ?>"><?php echo $result->titulo; ?></a></h3>
							<p class="txtResultBusca"><?php echo Utility::limit_string($result->texto, 150); ?></p>
						</div>
						<br clear="all" />
					</div>
					<br clear="all" />
				<?php endforeach; endif; ?>
			</div>
			<div class="collum_right noticia_interna_right noticia_busca_right">
				<?php if(!empty($resultsFoto)) : ?>
					<h2 id="titUltimasNoticias"><span class="bgYellow">Fotos</span><span class="bgGray">&nbsp;</span></h2>
					<div id="boxSideFotos">
						<ul>
							<?php
								foreach($resultsFoto as $item) :
									$titleAlt = htmlspecialchars($item->titulo);
							?>
								<li><a href="/galeria-multimidia/<?php echo $item->id; ?>" title="<?php echo $titleAlt; ?>"><img src="/upload/galeria/thumb/<?php echo $item->thumb; ?>" alt="<?php echo $titleAlt; ?>" /></a></li>
							<?php endforeach; ?>
						</ul>
						<br clear="all" />
					</div>
				<?php endif; if(!empty($resultsVideo)) : ?>
					<h2 id="titUltimasNoticias"><span class="bgYellow">Vídeos</span><span class="bgGray">&nbsp;</span></h2>
					<?php foreach($resultsVideo as $item) : ?>
						<div class="itemSideVideos">
							<a class="img" href="/galeria-multimidia/<?php echo $item->id; ?>"><img src="<?php echo $item->thumb; ?>" alt="<?php echo $item->titulo; ?>" /></a>
							<div class="txt">
								<h5><a href="/<?php echo (integer) $item->mod_categ === 1 ? 'modalidade' : 'categoria'; ?>/<?php echo $item->permalink_categ; ?>"><?php echo $item->categoria; ?></a></h5>
								<h3><a href="/galeria-multimidia/<?php echo $item->id; ?>"><?php echo $item->titulo; ?></a></h3>
							</div>
							<br clear="all" />
						</div>
				<?php endforeach; endif; ?>
			</div>
			<br clear="all" />
			<div class="paginationContainner">
				<div class="pagination">
				  <?php echo ($totalItem > $totalPage) ? $pagination->display_pages() : ''; ?>
				</div>
			</div>
		<?php endif; ?>
        </div>
	<br clear="all" />
    </div>
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>