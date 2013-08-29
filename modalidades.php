<?php
	require($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
	
	$modObj = new Modalidade();	
	$modalidades = $modObj->get_modalidades(FALSE, TRUE, TRUE);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Informações, fotos e histórias sobre as 27 modalidades que serão disputadas em Mogi das Cruzes, durante os Jogos Abertos do Interior 2011." />
<meta name="keywords" content="Modalidades, esportivas, disputas, categorias, futebol, vôlei, natação, basquete, tênis, atletismo, ciclismo, luta." />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title>Modalidades | Jogos Abertos do Interior 2011 </title>
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
<script type="text/javascript" src="/_js/functions.js"></script>
</head>

<body>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/bg.php'); ?>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/header.php') ?>
	<div class="content p_modalidade" id="content">
		<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/navbar.php') ?>
		<h1 class="title">
			<div class="sombra" style="margin:-4px 0 0 -24px; height:65px;"></div> 
			Modalidades
		</h1>
		
	<?php if($modalidades !== FALSE) : $modalidadesExtras = array(); ?>
		<div id="content_mod" class="mod-interna">

			<ul class="listModInterna">
			<?php foreach($modalidades as $key=>$item) :
				if($item->modalidade_extra > 0){
					$modalidadesExtras[] = $modalidades[$key];
					unset($modalidades[$key]);
					continue;
				} else {
			?>
				<li><a class="btnMod<?php echo htmlspecialchars($item->id); ?>" href="/modalidade/<?php echo htmlspecialchars($item->slug); ?>"><?php echo htmlspecialchars($item->titulo); ?></a></li>
			<?php } endforeach; ?>
			</ul>
			<br clear="all" />
			
			<?php if(!empty($modalidadesExtras)) : ?>
				<h2 id="modExtra">Modalidades Extras</h2>
				<ul class="listModInterna">
				<?php foreach($modalidadesExtras as $item) : ?>
					<li><a class="btnMod<?php echo htmlspecialchars($item->id); ?>" href="/modalidade/<?php echo htmlspecialchars($item->slug); ?>"><?php echo htmlspecialchars($item->titulo); ?></a></li>
				<?php endforeach; ?>
				</ul>	
			<?php endif; ?>

		</div>
	<?php endif; ?>
	
	<br clear="all" />
	</div>
	
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
	</div>
</body>
</html>
