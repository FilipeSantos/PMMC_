<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Informações e notícias sobre a organização de Mogi na fase pré-jogos, atletas da cidade, modalidades, competições, e todo conteúdo atualizado sobre o evento." />
<meta name="keywords" content="JAI, Jogos, Abertos, Mogi, interior, olimpíadas, esporte, 2011, Releases, imprensa, jornalismo, cobertura, jornalística, esporte, notícias, futebol, atletismo, vôlei, natação." />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title>Releases | Jogos Abertos do Interior 2011 </title>
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
        	Releases
        </h1>
        <div id="content_rel">
        	<div class="collum_left base">
            	<p>Acesse conteúdos completos sobre todos os acontecimentos dos Jogos Abertos do Interior 2011</p>
		
		<?php
			require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/db.class.php');
			require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Release.class.php');
			require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/Utility.class.php');
			require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/paginator.class.php');
			
			$release = new Release();
			$total = $release->get_total_front();
			$totalPage = 15;
			
			if($total > $totalPage){
			   $pagination = new Paginator();
			   $pagination->items_total = $total;
			   $pagination->items_per_page = $totalPage;
			   $pagination->mid_range = 3;
			   $pagination->paginate();
			}
			
			$results = $release->get_release_front(isset($pagination->limit) ? $pagination->limit : "");
		?>
		
                <ul class="itemrel">
                    <li class="itens">
                    	<ul class="titulo">
                        	<li class="first">data</li>
                        	<li class="second">título</li>
                        	<li class="third">arquivo para download</li>
                        	<li class="fourth">ver notícia</li>
                        </ul>
                    </li>
		<?php foreach($results as $item) : ?>
			<li class="itens">
			    <ul>
				    <li class="first"><?php echo $item->data; ?></li>
				    <li class="second"><?php echo $item->titulo; ?></li>
				    <li class="third"><a href="/download.php?arquivo=<?php echo $item->arquivo; ?>"><img src="/_img/ico_pdf.png" width="46" height="45" alt="Baixe o arquivo em PDF" /></a></li>
				    <li class="fourth"><a href="<?php echo $item->linkNoticia; ?>"><img src="/_img/ico_mao.png" width="43" height="46" alt="Ver notícia" /></a></li>
			    </ul>
			</li>
		<?php endforeach; ?>
                    
		    <!--<li class="itens">
                    	<ul>
                        	<li class="first">02/06/2011</li>
                        	<li>Alunos de Mogi das Cruzes têm "aula" sobre os Jogos Abertos</li>
                        	<li><a href="releases/20110602Release.pdf" target="/_blank"><img src="/_img/ico_pdf.png" width="46" height="45" alt="Baixe o arquivo em PDF" /></a></li>
                        	<li><a href="noticias_20110602_a"><img src="/_img/ico_mao.png" width="43" height="46" alt="Ver notícia" /></a></li>
                        </ul>
                    </li>
                    <li class="itens">
                    	<ul>
                        	<li class="first">25/05/2011</li>
                        	<li>Ibirapuera receberá atletismo dos Jogos Abertos do Interior</li>
                        	<li><a href="releases/20110525Release.pdf" target="/_blank"><img src="/_img/ico_pdf.png" width="46" height="45" alt="Baixe o arquivo em PDF" /></a></li>
                        	<li><a href="noticias_20110525"><img src="/_img/ico_mao.png" width="43" height="46" alt="Ver notícia" /></a></li>
                        </ul>
                    </li>
                    <li class="itens">
                    	<ul>
                        	<li class="first">13/05/2011</li>
                        	<li>Local das competições de boxe nos Jogos Abertos é definido</li>
                        	<li><a href="releases/20110513Release.pdf" target="/_blank"><img src="/_img/ico_pdf.png" width="46" height="45" alt="Baixe o arquivo em PDF" /></a></li>
                        	<li><a href="noticias_20110513"><img src="/_img/ico_mao.png" width="43" height="46" alt="Ver notícia" /></a></li>
                        </ul>
                    </li>
                    <li class="itens">
                    	<ul>
                        	<li class="first">08/04/2011</li>
                        	<li>Instalação de cadeiras no Ginásio Municipal para os Jogos Abertos tem licitação homologada</li>
                        	<li><a href="releases/20110408Release.pdf" target="/_blank"><img src="/_img/ico_pdf.png" width="46" height="45" alt="Baixe o arquivo em PDF" /></a></li>
                        	<li><a href="noticias_20110408"><img src="/_img/ico_mao.png" width="43" height="46" alt="Ver notícia" /></a></li>
                        </ul>
                    </li>
                    <li class="itens">
                    	<ul>
                        	<li class="first">06/04/2011</li>
                        	<li>Bertaiolli recebe visita do chefe do Comitê Dirigente dos Jogos Abertos</li>
                        	<li><a href="releases/20110406Release.pdf" target="/_blank"><img src="/_img/ico_pdf.png" width="46" height="45" alt="Baixe o arquivo em PDF" /></a></li>
                        	<li><a href="noticias_20110406"><img src="/_img/ico_mao.png" width="43" height="46" alt="Ver notícia" /></a></li>
                        </ul>
                    </li>
                    <li class="itens">
                    	<ul>
                        	<li class="first">24/03/2011</li>
                        	<li>Primeira fase da revitalização do Ginásio Municipal para os Jogos Abertos é entregue</li>
                        	<li><a href="releases/20110324Release.pdf" target="/_blank"><img src="/_img/ico_pdf.png" width="46" height="45" alt="Baixe o arquivo em PDF" /></a></li>
                        	<li><a href="noticias_20110324"><img src="/_img/ico_mao.png" width="43" height="46" alt="Ver notícia" /></a></li>
                        </ul>
                    </li>
                    <li class="itens">
                    	<ul>
                        	<li class="first">14/03/2011</li>
                        	<li>Secretaria de Esporte fecha parceria com o Palmeiras para equipe de judô</li>
                        	<li><a href="releases/20110314Release.pdf" target="/_blank"><img src="/_img/ico_pdf.png" width="46" height="45" alt="Baixe o arquivo em PDF" /></a></li>
                        	<li><a href="noticias_20110314"><img src="/_img/ico_mao.png" width="43" height="46" alt="Ver notícia" /></a></li>
                        </ul>
                    </li>
                    <li class="itens">
                    	<ul>
                        	<li class="first">23/02/2011</li>
                        	<li>Mogi recebe visita do chefe do Comitê Dirigente dos Jogos Abertos</li>
                        	<li><a href="releases/20110223Release.pdf" target="/_blank"><img src="/_img/ico_pdf.png" width="46" height="45" alt="Baixe o arquivo em PDF" /></a></li>
                        	<li><a href="noticias_20110223"><img src="/_img/ico_mao.png" width="43" height="46" alt="Ver notícia" /></a></li>
                        </ul>
                    </li>
                    <li class="itens">
                    	<ul>
                        	<li class="first">02/02/2011</li>
                        	<li>Bertaiolli e secretário estadual do Esporte discutem Jogos Abertos</li>
                        	<li><a href="releases/20110202Release.pdf" target="/_blank"><img src="/_img/ico_pdf.png" width="46" height="45" alt="Baixe o arquivo em PDF" /></a></li>
                        	<li><a href="noticias_20110202"><img src="/_img/ico_mao.png" width="43" height="46" alt="Ver notícia" /></a></li>
                        </ul>
                    </li>
                    <li class="itens">
                    	<ul>
                        	<li class="first">29/01/2011</li>
                        	<li>Parque Botyra é revitalizado pela Prefeitura e entregue à população</li>
                        	<li><a href="releases/20110129Release.pdf" target="/_blank"><img src="/_img/ico_pdf.png" width="46" height="45" alt="Baixe o arquivo em PDF" /></a></li>
                        	<li><a href="noticias_20110129"><img src="/_img/ico_mao.png" width="43" height="46" alt="Ver notícia" /></a></li>
                        </ul>
                    </li>
                     <li class="itens">
                    	<ul>
                        	<li class="first">10/12/2010</li>
                        	<li>Comitê dos Jogos Abertos e de Conselho do Desporto são empossados</li>
                        	<li><a href="releases/20101210Release.pdf" target="/_blank"><img src="/_img/ico_pdf.png" width="46" height="45" alt="Baixe o arquivo em PDF" /></a></li>
                        	<li><a href="noticias_20101210"><img src="/_img/ico_mao.png" width="43" height="46" alt="Ver notícia" /></a></li>
                        </ul>
                    </li>
                     <li class="itens">
                    	<ul>
                        	<li class="first">16/11/2010</li>
                        	<li>Campeões dos Jogos Abertos são homenageados por Mogi das Cruzes</li>
                        	<li><a href="releases/20101116Release.pdf" target="/_blank"><img src="/_img/ico_pdf.png" width="46" height="45" alt="Baixe o arquivo em PDF" /></a></li>
                        	<li><a href="noticias_20101116"><img src="/_img/ico_mao.png" width="43" height="46" alt="Ver notícia" /></a></li>
                        </ul>
                    </li>
                     <li class="itens">
                    	<ul>
                        	<li class="first">15/11/2010</li>
                        	<li>Mogi conquista os Jogos Abertos e se prepara para receber edição de 2011</li>
                        	<li><a href="releases/20101115Release.pdf" target="/_blank"><img src="/_img/ico_pdf.png" width="46" height="45" alt="Baixe o arquivo em PDF" /></a></li>
                        	<li><a href="noticias_20101115"><img src="/_img/ico_mao.png" width="43" height="46" alt="Ver notícia" /></a></li>
                        </ul>
                    </li>-->
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
                	<h4><a href="/sala-de-imprensa/banco-de-arquivos">banco de arquivos</a></h4>
                    <div class="imagem">
                    	<a href="/sala-de-imprensa/banco-de-arquivos"><img src="/_img/ft_veja_bancodearquivos.jpg" width="210" height="84" alt="Imagem 1" /></a>
                    </div>
                    <p><a href="/sala-de-imprensa/banco-de-arquivos">Faça download dos arquivos de divulgação dos Jogos Abertos 2011.</a></p>
                </div>
            	<div class="item">
                	<h4><a href="/noticias">notícias</a></h4>
                    <div class="imagem">
                    	<a href="/noticias"><img src="/_img/ft_veja_noticias.jpg" width="210" height="84" alt="Imagem 1" /></a>
                    </div>
                    <p><a href="/noticias">Saiba tudo o que acontece na maior festa esportiva da América Latina.</a></p>
                </div>
            	<div class="item">
                	<h4><a href="/sala-de-imprensa/cadastramento">cadastramento</a></h4>
                    <div class="imagem">
                    	<a href="/sala-de-imprensa/cadastramento"><img src="/_img/ft_veja_cadastramento.jpg" width="210" height="84" alt="Imagem 1" /></a>
                    </div>
                    <p><a href="/sala-de-imprensa/cadastramento">Cadastre-se, receba releases por e-mail e faça a cobertura jornalística do evento.</a></p>
                </div>
            </div>
        </div>
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>
