<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Cidade.class.php');
	$cidadesDiv1 = Cidade::get_cidades_medalhas_divisao(1);
	$cidadesDiv2 = Cidade::get_cidades_medalhas_divisao(2);
	$atualizacao = Medalha::data_atualizacao();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Dúvidas sobre os Jogos Abertos do Interior 2011. Envie críticas e sugestões à organização do evento." />
<meta name="keywords" content="Mogi, sorteio, técnico, Jogos, Abertos, sugestões, Mogi, Cruzes, competição, cidades, linha." />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title>Quadro de Medalhas | Jogos Abertos do Interior 2011</title>
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/style_2.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.scrollpane.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
<link rel="canonical" href="http://www.jogosabertos2011.com.br<?php echo $_SERVER['REQUEST_URI']; ?>" />
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
<script type="text/javascript" src="/_js/jquery.jscrollpane.js"></script>
<script type="text/javascript" src="/_js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/_js/jquery.em.js"></script>
<script type="text/javascript" src="/_js/functions.js"></script>
<script>
$(function(){
	$('.scroll-pane').jScrollPane({ 'animateScroll': true, 'verticalGutter': 0, 'autoReinitialise': true, 'scrollbarWidth': 16  });
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
        	Quadro de Medalhas
        </h1>
        <br clear="all" />
        
	<div id="content_sorteio" class="content_quadro">
		
		<p class="first">Acompanhe sua cidade na disputa pelo ouro dos Jogos Abertos 2011. Confira o quadro de medalhas completo da<br> 
		competição e prepare seu coração. São mais de <b>15 mil</b> atletas contando com a sua torcida para conquistar um lugar no topo.</p>
		<ul id="listTab">
		<?php if($cidadesDiv1 !== FALSE) : ?>
		    <li><a href="#box1a">1ª Divisão</a></li>
		<?php endif; if($cidadesDiv2 !== FALSE) : ?>
		    <li><a href="#box2a">2ª Divisão</a></li>
		<?php endif; ?>
		</ul>
		
		<div id="containerTab" class="medalhas">
			<?php if($cidadesDiv1 !== FALSE && is_array($cidadesDiv1)) : $posPrev = 0; ?>
				<div class="tab" id="box1a">
					<div class="info">
						<span class="ouro" title="Ouro"></span>
						<span class="prata" title="Prata"></span>
						<span class="bronze" title="Bronze"></span>
						<span class="total" title="Total"></span>
					</div>
					<div class="scroll-pane">
						<?php foreach($cidadesDiv1 as $i=>$cidade) : ?>
							<div class="item">
								<h3 class="posicao"><?php echo $cidade->pos_medalhas != $posPrev ? $cidade->pos_medalhas . 'º' : ''; ?></h3>
								<img src="/_img/cidade/bandeiras/<?php echo strtolower($cidade->sigla); ?>.jpg" alt="<?php echo $cidade->nome; ?>" class="bandeira" />
								<h3><?php echo $cidade->nome; ?></h3>
								<span class="ouro"><?php echo $cidade->medalha_total_ouro; ?></span>
								<span class="prata"><?php echo $cidade->medalha_total_prata; ?></span>
								<span class="bronze"><?php echo $cidade->medalha_total_bronze; ?></span>
								<span class="total"><?php echo $cidade->medalha_total; ?></span>
							</div>
						<?php $posPrev = $cidade->pos_medalhas; endforeach; ?>
					</div>
				</div>
			<?php endif; if($cidadesDiv2 !== FALSE && is_array($cidadesDiv2)) : $posPrev = 0; ?>			
				<div class="tab" id="box2a">
					<div class="info">
						<span class="ouro" title="Ouro"></span>
						<span class="prata" title="Prata"></span>
						<span class="bronze" title="Bronze"></span>
						<span class="total" title="Total"></span>
					</div>
					<div class="scroll-pane">
						<?php foreach($cidadesDiv2 as $i=>$cidade) : ?>
							<div class="item">
								<h3 class="posicao"><?php echo $cidade->pos_medalhas != $posPrev ? $cidade->pos_medalhas . 'º' : ''; ?></h3>
								<img src="/_img/cidade/bandeiras/<?php echo strtolower($cidade->sigla); ?>.jpg" alt="<?php echo $cidade->nome; ?>" class="bandeira" />
								<h3><?php echo $cidade->nome; ?></h3>
								<span class="ouro"><?php echo $cidade->medalha_total_ouro; ?></span>
								<span class="prata"><?php echo $cidade->medalha_total_prata; ?></span>
								<span class="bronze"><?php echo $cidade->medalha_total_bronze; ?></span>
								<span class="total"><?php echo $cidade->medalha_total; ?></span>
							</div>
						<?php $posPrev = $cidade->pos_medalhas; endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
			<p class="update updateMedalhas">Última atualização: <?php echo $atualizacao; ?></p>
		</div>
		<div class="veja_tambem">
        	<h3>Veja Também</h3>
        	<div class="item">
            	<h4><a href="classificacao">classificação</a></h4>
                <div class="imagem">
                	<a href="classificacao"><img src="/_img/ft_veja_classificacao.jpg" width="210" height="84" alt="Imagem 1" /></a>
                </div>
                <p><a href="classificacao">Fique ligado na corrida pela liderança dos Jogos Abertos 2011</a></p>
            </div>
        	<div class="item">
            	<h4><a href="/cidade/cidade-sede">programação</a></h4>
                <div class="imagem">
                	<a href="/cidade/cidade-sede"><img src="/_img/ft_veja_programacao.jpg" width="210" height="84" alt="Imagem 1" /></a>
                </div>
                <p><a href="/cidade/cidade-sede">Saiba tudo o que acontece na maior festa esportiva da América Latina.</a></p>
            </div>
        	<div class="item">
            	<h4><a href="/modalidades">locais de competição</a></h4>
                <div class="imagem">
                	<a href="/modalidades"><img src="/_img/ft_veja_locais.jpg" width="210" height="84" alt="Imagem 1" /></a>
                </div>
                <p><a href="/modalidades">Saiba por que Mogi das Cruzes foi eleita a casa dos Jogos Abertos deste ano.</a></p>
            </div>
        </div>  
	</div>
	<br clear="all" />
	</div>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>
