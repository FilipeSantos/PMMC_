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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="A maior festa esportiva da América-Latina em um formato muito mais interativo, dinâmico e moderno. Assista e compartilhe os vídeos sobre os Jogos. " />
<meta name="keywords" content="vídeos, multimídia, galeria, interativo, jogos, JAI, Mogi, Jogos, Abertos, Interior, 2011" />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title>Galeria Multimídia | Jogos Abertos do Interior 2011 </title>
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/style_2.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.scrollpane.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
<link rel="canonical" href="http://www.jogosabertos2011.com.br<?php echo $_SERVER['REQUEST_URI']; ?>" />
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="/_js/jquery.scrollpane.js"></script>
<script type="text/javascript" src="/_js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/_js/jquery.em.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
<script type="text/javascript" src="/_js/functions.js"></script>
<script>
function loading(elem){
	elem.html('<div class="loading"><img src="/_img/load.gif" width="34" height="34" alt="Carregando..." /></div>');
}

function loadMidia($id, $tipo){
	$.ajax({
		type: 'POST',
		data: 'tipo=' + $tipo + '&id=' + $id,
		dataType: "text",
		cache: false,
		url: '/_inc/get-galeria.php',
		success: function($html){
			$('#' + $tipo + '_destaque').html($html);
			$('#' + $tipo + '_destaque .itemGalDestaque').hide().fadeIn('slow');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
		    $('.contLoadingInsertVideo .boxLoading').hide();
		    alert('Ocorreu um erro. Tente novamente.');
		}
	});
}
$(function(){
	
	<?php
		$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (integer) $_GET['id'] : FALSE;
	?>
	
	$('.scroll-pane').jScrollPane({ scrollbarWidth: 16 });
	
	$('.listagem ul li').click(function(){
		var $id = $(this).attr('id').split('|')[1];
		var $tipo = $(this).attr('id').split('|')[0];
		
		if(typeof $id != 'undefined' && typeof $tipo != 'undefined'){
			if(typeof $clickInit == 'undefined' || $clickInit != true){
				$('html').animate({ scrollTop: $('#content_galeria').offset().top }, 800);
			} else {
				delete($clickInit);
			}
			
			loading($('#' + $tipo + '_destaque'));
			loadMidia($id, $tipo);
		}
		return false;
	});
	
	$('.listagem').each(function(){
		$clickInit = true;
		if($(this).find('li.selected').length){
			$(this).find('li.selected').click();
		} else {
			$(this).find('li:first').click();
		}		
	});

});
</script>
</head>

<body>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/bg.php') ?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/header.php') ?>
    <div class="content p_modalidade" id="content">
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/navbar.php') ?>
        <h1 class="title yellow">
        	<div class="sombra" style="margin:-4px 0 0 -24px; height:65px;"></div> 
        	Galeria Multimídia
        </h1>
	<?php
		require($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Galeria.class.php');
		$galeriaVideo = Galeria::get_galeria(FALSE, 2, FALSE, TRUE, TRUE);
	?>
        <div id="content_galeria">
		<div class="videos">
			<h2 class="subtitle">Vídeo em destaque</h2>
			<div id="video_destaque">
				<div class="loading"><img src="/_img/load.gif" width="34" height="34" alt="Carregando..." /></div>
			</div>
			<div class="listagem" id="listagem_video">
				<h2 class="subtitle">Listagem de Vídeos</h2>
				<ul class="scroll-pane">
				<?php if($galeriaVideo) : foreach($galeriaVideo as $item) :
					$categs = '';
					if(isset($item->categorias)){
						foreach($item->categorias as $itemCateg){
							$categs = $itemCateg['titulo'] . ', ';
						}
						$categs = substr($categs, 0, -2);
					}
					
				?>
					<li id="video|<?php echo $item->id; ?>"<?php echo ($item->id == $id) ? ' class="selected"' : ''; ?>>
						<a href="#" title="<?php echo htmlspecialchars($item->titulo); ?>">
							<img src="<?php echo $item->thumb; ?>" width="120" height="90" alt="<?php echo htmlspecialchars($item->titulo); ?>" />
						</a>
						<p class="itemDataGal"><span><?php echo $item->data; ?><br><?php echo $categs; ?></span></p>
						<p class="itemTltGal"><span class="title"><?php echo $item->titulo; ?></span></p>
						<br clear="all" />
					</li>
				<?php endforeach; endif; ?>
				</ul>
			</div>
		</div>
		<div class="fotos">
			<?php
				$galeriaFoto = Galeria::get_galeria(FALSE, 1, FALSE, FALSE, TRUE);
			?>
			<h2 class="subtitle">Foto em destaque</h2>
			<div id="foto_destaque">
				<div class="loading"><img src="/_img/load.gif" width="34" height="34" alt="Carregando..." /></div>
			</div>
			<div class="listagem" id="listagem_fotos">
				<h2 class="subtitle">Listagem de fotos</h2>
				<ul class="scroll-pane">
				<?php if($galeriaFoto) : foreach($galeriaFoto as $item) : ?>
					<li id="foto|<?php echo $item->id; ?>"<?php echo ($item->id == $id) ? ' class="selected"' : ''; ?>>
					    <a href="#" title="<?php echo htmlspecialchars($item->titulo); ?>">
						<img src="/upload/galeria/thumb/<?php echo $item->thumb; ?>" width="65" height="65" alt="<?php echo htmlspecialchars($item->titulo); ?>" />
					    </a>
					</li>
				<?php endforeach; endif; ?>
			</ul>
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