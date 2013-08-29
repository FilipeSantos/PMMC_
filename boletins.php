<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="Informações e notícias sobre a organização de Mogi na fase pré-jogos, atletas da cidade, modalidades, competições, e todo conteúdo atualizado sobre o evento." />
<meta name="keywords" content="JAI, Jogos, Abertos, Mogi, interior, olimpíadas, esporte, 2011, Boletins, imprensa, jornalismo, cobertura, jornalística, esporte, notícias, futebol, atletismo, vôlei, natação." />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title>Boletins | Jogos Abertos do Interior 2011 </title>
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
        	Boletins
        </h1>
        <div id="content_rel">
        	<div class="collum_left base">
            	<p>Acesse conteúdos completos sobre todos os acontecimentos dos Jogos Abertos do Interior 2011</p>
		
		<?php
			require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/db.class.php');
			require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Boletim.class.php');
			require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/Utility.class.php');
			require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/paginator.class.php');
			
			$boletim = new Boletim();
			$total = $boletim->get_total_front();
			$totalPage = 15;
			
			if($total > $totalPage){
			   $pagination = new Paginator();
			   $pagination->items_total = $total;
			   $pagination->items_per_page = $totalPage;
			   $pagination->mid_range = 3;
			   $pagination->paginate();
			}
			
			$results = $boletim->get_boletim_front(isset($pagination->limit) ? $pagination->limit : "");
		?>
		
                <ul class="itemrel">
                    <li class="itens">
                    	<ul class="titulo">
                        	<li class="first frBoletins">data</li>
                        	<li class="second secBoletins">título</li>
                        	<li class="third thBoletins">arquivo para download</li>
                        </ul>
                    </li>
		<?php foreach($results as $item) : ?>
			<li class="itens">
			    <ul>
				    <li class="first frBoletins"><?php echo $item->data; ?></li>
				    <li class="second secBoletins"><?php echo $item->titulo; ?></li>
				    <li class="third thBoletins"><a href="/upload/boletins/<?php echo $item->arquivo; ?>" target="/_blank"><img src="/_img/ico_pdf.png" width="46" height="45" alt="Baixe o arquivo em PDF" /></a></li>
			    </ul>
			</li>
		<?php endforeach; ?>
                </ul>
	</div>
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
                	<h4><a href="/sala-de-imprensa/banco-de-arquivos">classificação</a></h4>
                    <div class="imagem">
                    	<a href="/sala-de-imprensa/banco-de-arquivos"><img src="/_img/ft_veja_classificacao.jpg" width="210" height="84" alt="Imagem 1" /></a>
                    </div>
                    <p><a href="/sala-de-imprensa/banco-de-arquivos">Faça download dos arquivos de divulgação dos Jogos Abertos 2011.</a></p>
                </div>
            	<div class="item">
                	<h4><a href="/noticias">quadro de medalhas</a></h4>
                    <div class="imagem">
                    	<a href="/noticias"><img src="_img/ft_veja_quadro.jpg" width="210" height="84" alt="Imagem 1" /></a>
                    </div>
                    <p><a href="/noticias">Saiba tudo o que acontece na maior festa esportiva da América Latina.</a></p>
                </div>
            	<div class="item">
                	<h4><a href="/sala-de-imprensa/cadastramento">modalidades</a></h4>
                    <div class="imagem">
                    	<a href="/sala-de-imprensa/cadastramento"><img src="_img/ft_veja_modalidades.jpg" width="210" height="84" alt="Imagem 1" /></a>
                    </div>
                    <p><a href="/sala-de-imprensa/cadastramento">Cadastre-se, receba releases por e-mail e faça a cobertura jornalística do evento.</a></p>
                </div>
            </div>
        </div>
	<br clear="all" />
    </div>
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>
