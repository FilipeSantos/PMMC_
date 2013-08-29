<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Download de arquivos de divulgação, imagens em alta qualidade e todo o material necessário para a cobertura jornalística dos Jogos Abertos do Interior 2011. " />
<meta name="keywords" content="JAI, Jogos, Abertos, Mogi, interior, olimpíadas, esporte, 2011, banco, arquivos, downloads, divulgação, imagens, jornalista, Mogi" />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title>Banco de Arquivos | Jogos Abertos do Interior 2011 </title>
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
        	banco de arquivos
        </h1>
        <div id="content_ba">
        	<div class="collum_left base">
			<p class="intro">Faça download de arquivos dos Jogos Abertos 2011. Logomarcas, fotos, imagens de divulgação em alta qualidade e todo material de apoio necessário para o seu trabalho.</p>
			
			<?php
				require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/db.class.php');
				require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Release.class.php');
				require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/Utility.class.php');
				require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/paginator.class.php');
				
				$ba = new BancoArquivo();
				$total = $ba->get_total_front();
				$totalPage = 15;
				
				if($total > $totalPage){
				   $pagination = new Paginator();
				   $pagination->items_total = $total;
				   $pagination->items_per_page = $totalPage;
				   $pagination->mid_range = 3;
				   $pagination->paginate();
				}
				
				$results = $ba->get_bancoArquivo_front(isset($pagination->limit) ? $pagination->limit : "");
			?>
		</div>
		<br clear="all" />
		
		<?php foreach($results as $item) : ?>
			<div class="itemba">
				<img src="/upload/banco_de_arquivos/thumb/<?php echo $item->thumb; ?>" width="147" height="126" alt="<?php echo $item->titulo; ?>" align="left" />
				<div class="txt">
					<h4><?php echo $item->titulo; ?></h4>
					<p>Formato: <?php echo strtoupper($item->formato); ?></p>
					<p>Tamanho: <?php echo ($item->tamanho > 1024) ? number_format($item->tamanho / 1024, 2) . ' mb' : $item->tamanho . ' kb' ; ?></p>
					<a href="/download.php?arquivo=<?php echo $item->arquivo; ?>" class="btn_download">Download</a>
				</div>
			</div>
		<?php endforeach; ?>
		
		<!--
		<div class="itemba">
                	<img src="/_img/ft_banco_arquivos1.png" width="147" height="126" alt="Imagem 1" align="left" />
			<div class="txt">
				<h4>logomarca</h4>
				<p>Formato: ZIP (EPS)</p>
				<p>Tamanho: 137 kb</p>
				<a href="bancoarquivos/logomarca.zip" class="btn_download">Download</a>
			</div>
                </div>
		-->
		
		<br clear="all" />
		<?php if($total > $totalPage) : ?>
			<div class="paginationContainner paginationCenter">
				<div class="pagination">
					<?php echo $pagination->display_pages(); ?>
				</div>
			</div>
		<?php endif; ?>

            <div class="veja_tambem">
            	<h3>Veja Também</h3>
            	<div class="item">
                	<h4><a href="/jogos/historia-dos-jogos">história dos jogos</a></h4>
                    <div class="imagem">
                    	<a href="/jogos/historia-dos-jogos"><img src="/_img/ft_vejatambem1.jpg" width="210" height="84" alt="Imagem 1" /></a>
                    </div>
                    <p><a href="/jogos/historia-dos-jogos">De 1936 a 2011, os fatos mais marcantes dos tradicionais Jogos Abertos.</a></p>
                </div>
            	<div class="item">
                	<h4><a href="/cidade/cidade-sede">Cidade-sede</a></h4>
                    <div class="imagem">
                    	<a href="/cidade/cidade-sede"><img src="/_img/ft_vejatambem2.jpg" width="210" height="84" alt="Imagem 1" /></a>
                    </div>
                    <p><a href="/cidade/cidade-sede">Saiba por que Mogi das Cruzes foi eleita a casa dos Jogos Abertos deste ano.</a></p>
                </div>
            	<div class="item">
                	<h4><a href="/modalidades">modalidades</a></h4>
                    <div class="imagem">
                    	<a href="/modalidades"><img src="/_img/ft_vejatambem3.jpg" width="210" height="84" alt="Imagem 1" /></a>
                    </div>
                    <p><a href="/modalidades">27 modalidades vão esquentar as disputas dos Jogos Abertos de 2011. Clique e conheça.</a></p>
                </div>
            </div>
        </div>
        <br clear="all" />
    </div>
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>
