<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/Cache.class.php');
	$cache = new Cache();
	$cache->set_arquivo('page_' . $_SERVER['REQUEST_URI']);
	$cache->set_tempo(0);
	$cache->start();

	if($cache->cached === FALSE):

		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Cidade.class.php');
		$cidadesDiv1 = Cidade::get_cidades_classificacao_divisao(1, 6);
		$progObj = new Programacao();
		$programacao = $progObj->get_programacao(FALSE, 1, FALSE, FALSE, FALSE, FALSE, strtotime('-2 hours'), 20);
		
		if($programacao !== FALSE){
			$totalProg = count($programacao);
			for($i = 0; $i < $totalProg; $i++){
	
				if(is_array($programacao[$i]->provas) && !empty($programacao[$i]->provas)) {
					$txt = '';
					foreach($programacao[$i]->provas as $prova){
						$txt .= "{$prova->titulo}, ";
					}
					$posLast = strrpos($txt = substr($txt, 0, -2), ',');
					if($posLast !== FALSE){
						$programacao[$i]->provas = substr_replace($txt, ' e', $posLast, 1);
					} else {
						$programacao[$i]->provas = $txt;
					}
				}
	
			}
		}
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Site oficial dos Jogos Abertos do Interior 2011. Notícias, fotos, vídeos e tudo sobre as disputas, competidores e delegações" />
<meta name="keywords" content="JAI, Jogos, Abertos, Mogi, interior, olimpíadas, esporte, 2011, competição, campeonato, atletismo" />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<meta property="og:type" content="website" />
<meta property="og:url" content="http://www.jogosabertos2011.com.br/" />
<meta property="og:title" content="Jogos Abertos do Interior de 2011" />
<meta property="og:description" content="Site oficial dos Jogos Abertos do Interior 2011. Notícias, fotos, vídeos e tudo sobre as disputas, competidores e delegações"/>
<meta property="og:image" content="http://www.jogosabertos2011.com.br/_img/img_share.jpg" />
<meta property="og:site_name" content="Jogos Abertos do Interior - Mogi das Cruzes" />
<title>Jogos Abertos do Interior de 2011</title>
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.selectmenu.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.scrollpane.css">
<link rel="canonical" href="http://www.jogosabertos2011.com.br<?php echo $_SERVER['REQUEST_URI']; ?>" />
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.core.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.selectmenu.js"></script>
<script type="text/javascript" src="/_js/jquery.jscrollpane.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="/_js/jcarousellite_1.0.1.min.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
<script type="text/javascript" src="/_js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/_js/jquery.neosmart.fb.wall.js"></script>
<script type="text/javascript" src="/_js/jquery.em.js"></script>
<script type="text/javascript" src="/_js/util.js"></script>
<script type="text/javascript" src="/_js/functions.js"></script>
<script type="text/javascript" src="/_js/func_home.js"></script>


<script type="text/javascript">
	jQuery(function() {
		$('#quadro_medHome').selectmenu({ style:'dropdown',width: 315});
		$('#programacaoHome').selectmenu({ style:'dropdown',width: 315});	
		$('.scroll-pane, #boxScrollFbFeed').jScrollPane({ 'animateScroll': true, 'verticalGutter': 0, 'autoReinitialise': true, 'scrollbarWidth': 16  });	
	});
</script>
</head>

<body>
<div id="video"></div>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/bg.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/header.php') ?>
    <div class="content" id="content">
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/navbar.php') ?>
	<br clear="all" />
	<div id="boxModalidades">
		<h3>Veja as últimas da sua modalidade favorita:</h3>
		<ul id="listHomeModalidades">
			<li>
				<a id="btnFutebol" href="/modalidade/futebol">Futebol</a>
				<div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Futebol</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnVolei" href="/modalidade/volei">Vôlei</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Vôlei</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnJudo" href="/modalidade/judo">Judô</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Judô</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnBoxe" href="/modalidade/boxe">Boxe</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Boxe</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnBasquete" href="/modalidade/basquete">Basquete</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Basquete</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnHandebol" href="/modalidade/handebol">Handebol</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Handebol</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnNatacao" href="/modalidade/natacao">Natação</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Natação</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnLutaOlimpica" href="/modalidade/luta-olimpica">Luta Olímpica</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Luta Olímpica</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnTenisMesa" href="/modalidade/tenis-de-mesa">Tênis de Mesa</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Tênis de Mesa</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnCiclismo" href="/modalidade/ciclismo">Ciclismo</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Ciclismo</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnGinasticaRitmica" href="/modalidade/ginastica-ritmica">Ginástica Rítmica</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Ginástica Rítmica</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnGinasticaArtistica" href="/modalidade/ginastica-artistica">Ginástica Artística</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Ginástica Artística</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnTenis" href="/modalidade/tenis">Tênis</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Tênis</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnBiribol" href="/modalidade/biribol">Biribol</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Biribol</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnCapoeira" href="/modalidade/capoeira">Capoeira</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Capoeira</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnTaekwondo" href="/modalidade/taekwondo">Taekwondo</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Taekwondo</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnMalha" href="/modalidade/malha">Malha</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Malha</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnBocha" href="/modalidade/bocha">Bocha</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Bocha</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnDamas" href="/modalidade/damas">Damas</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Damas</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnAtletismo" href="/modalidade/atletismo">Atletismo</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Atletismo</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnVoleiPraia" href="/modalidade/volei-de-praia">Vôlei de Praia</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Vôlei de Praia</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnXadrez" href="/modalidade/xadrez">Xadrez</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Xadrez</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnFutsal" href="/modalidade/futsal">Futsal</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Futsal</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnKarate" href="/modalidade/karate">Karatê</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Karatê</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnKickboxing" href="/modalidade/kickboxing">Kickboxing</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Kickboxing</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnNatacaoPCD" href="/modalidade/natacao-pcd">Natação PCD</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Natação PCD</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
			<li>
				<a id="btnAtletismoPCD" href="/modalidade/atletismo-pcd">Atletismo PCD</a>
			  <div class="tooltip">
					<div class="top">&nbsp;</div>
					<div class="content">Atletismo PCD</div>
					<div class="bgGray">&nbsp;</div>
				</div>
			</li>
		</ul>
	</div>
	<div id="leftHome">
	
	<?php if($cidadesDiv1 !== FALSE) : $posPrev = 0; ?>
    	<div class="quadroHome">
    		<h3>Classificação</h3>
    		<select id="quadro_medHome" name="quadro_medHome" autocomplete="off">
    			<option value="1">1ª divisão</option>
    			<option value="2">2ª divisão</option>
    		</select>
    		<div class="quadro_in">
			<div class="bgLoading">&nbsp;</div>
			<div class="boxLoading" id="loadingMedalhas"><img src="/_img/load.gif" alt="Carregando..." /></div>
    			<div class="top">
	    			<span class="cidade">Cidade</span>
	    			<span class="pontos" title="Pontos">Pontos</span>
			</div>
			
			<div class="boxContent">
			<?php foreach($cidadesDiv1 as $cidClassificacao) : ?>
				<div class="item">
					<span class="span1 posicao"><?php echo $cidClassificacao->pos_pontos != $posPrev ? htmlspecialchars($cidClassificacao->pos_pontos) . 'º' : ''; ?></span>
					<span class="span2">
						<a href="/jogos/cidades-participantes/<?php echo htmlspecialchars($cidClassificacao->slug); ?>" title="<?php echo htmlspecialchars($cidClassificacao->nome); ?>">
						<img src="/_img/cidade/bandeiras/<?php echo htmlspecialchars($cidClassificacao->bandeira); ?>" alt="<?php echo htmlspecialchars($cidClassificacao->nome); ?>"></a>
					</span>
					<span class="span3"><?php echo htmlspecialchars(number_format($cidClassificacao->pontos, 1, ',', '.')); ?></span>
					<br clear="all" />
				</div>
			<?php $posPrev = $cidClassificacao->pos_pontos; endforeach; ?>
			</div>
    		</div>
    		<p><a href="/resultados/classificacao" title="Ver classificação completa">Ver classificação completa</a></p>
    	</div>
	<?php endif; ?>
    	
	<h3 class="btnFbBlue">Facebook</h3>
	<div id="boxScrollFbFeed">
		<div id="boxFbFeed">&nbsp;</div>
	</div>
	<p class="btnLinkFb"><a href="http://www.facebook.com/pages/Jogos-Abertos-2011/199731200050883" target="_blank" title="Ver Jogos Abertos no Facebook">Ver Jogos Abertos no Facebook</a></p>
	<br clear="all" />
	
    	<div class="prograHome">
		<div class="boxTltHome">
			<h3 class="tltBlue">Programação Extraoficial</h3>
			<span class="bgGray">&nbsp;</span>
		</div>
    		<select id="programacaoHome" name="programacaoHome" autocomplete="off">
    			<option value="1">1ª divisão</option>
    			<option value="2">2ª divisão</option>
			<option value="3">Divisão Especial</option>
			<option value="4">Modalidades Extras</option>
    		</select>
    		<h4>Próximas Provas</h4>
		
		<div class="boxContent">
			<div class="bgLoading">&nbsp;</div>
			<div class="boxLoading" id="loadingProgramacao"><img src="/_img/load.gif" alt="Carregando..." /></div>
			<div class="scroll-pane">
				<ul>
					<?php if($programacao !== FALSE) : foreach($programacao as $itemProg) : ?>
						<li>
							<p>
								<img src="/_img/modalidades/ico_cat<?php echo htmlspecialchars($itemProg->id_modalidade); ?>.png" alt="<?php echo htmlspecialchars($itemProg->modalidade); ?>">
								<span><?php echo htmlspecialchars(date('d\/m - H:i', $itemProg->data)); ?>h: <a href="/modalidade/<?php echo htmlspecialchars($itemProg->slug_modalidade); ?>"><?php echo htmlspecialchars($itemProg->modalidade); ?></a>
								<br><?php echo htmlspecialchars($itemProg->provas); ?>
								<?php if(!empty($itemProg->descricao)) : ?>
									(<?php echo htmlspecialchars($itemProg->descricao); ?>)
								<?php endif; ?>
								</span>
								<br clear="all" />
							</p>
						</li>
					<?php endforeach; else : ?>
						<li class="nenhumaProva">Nenhuma prova cadastrada</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
    		<p><a href="/programacao/programacao-extraoficial" title="Ver programação completa">Ver programação completa</a></p>
		</div>
    	
        <h3 id="sitePrefeitura"><a href="http://www.mogidascruzes.sp.gov.br/index.php" target="_blank">Visite o Site da Prefeitura de Mogi - mogidascruzes.sp.gov.br</a></h3>
	</div>
	<div id="rightHome">
			<?php
				$noticias = Noticia::get_destaque('home');
				$utility = new Utility();
				$cont = 0;
				$idsExibidos = array();
			?>
			<div class="noticiaTop noticiaContent">
				<?php while(isset($noticias[$cont]) && $noticias[$cont]['exibirHome'] <= 2 && $cont <= 1) : $idsExibidos[] = $noticias[$cont]['id']; ?>
					<div class="itemNoticia">
						<a class="img" href="/noticia/<?php echo $noticias[$cont]['permalink']; ?>"><img src="<?php echo $noticias[$cont]['imagemThumb']; ?>" alt="<?php echo htmlspecialchars(stripslashes($noticias[$cont]['titulo'])); ?>" /></a>
						<h3><a href="/noticia/<?php echo $noticias[$cont]['permalink']; ?>"><?php echo stripslashes($noticias[$cont++]['titulo']); ?></a></h3>
					</div>
				<?php endwhile; ?>
				<br clear="all" />
			</div>
			<div class="destaquesSub">
				<div class="noticiaMid noticiaContent">
					<?php while(isset($noticias[$cont]) && $noticias[$cont]['exibirHome'] <= 4 && $cont <= 3) : $idsExibidos[] = $noticias[$cont]['id']; ?>
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
					<?php while(isset($noticias[$cont]) && $noticias[$cont]['exibirHome'] <= 6 && $cont <= 5) : $idsExibidos[] = $noticias[$cont]['id']; ?>
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
					<?php while(isset($noticias[$cont]) && $noticias[$cont]['exibirHome'] <= 8 && $cont <= 7) : $idsExibidos[] = $noticias[$cont]['id']; ?>
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
					<?php while(isset($noticias[$cont]) && $noticias[$cont]['exibirHome'] <= 10 && $cont <= 9) : $idsExibidos[] = $noticias[$cont]['id']; ?>
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
		
		
			<div class="boxHome296">
				<div class="boxTltHome">
					<h3 class="tltYellow">Galeria Multimídia</h3>
					<span class="bgGray">&nbsp;</span>
				</div>
				<?php
					require($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Galeria.class.php');
					$galeriaVideo = Galeria::get_galeria(FALSE, 2, FALSE, TRUE, FALSE, 'limit 0,2');
				?>
				<h5 class="tltYellow">Fotos</h5>
				<div id="boxSideFotos">
					<ul>
						<?php
							$galeriaFoto = Galeria::get_galeria(FALSE, 1, FALSE, FALSE, FALSE, 'limit 0, 32');
							if($galeriaFoto) : foreach($galeriaFoto as $item) :
								$titleAlt = htmlspecialchars($item->titulo);
						?>
							<li><a href="/galeria-multimidia/<?php echo $item->id; ?>" title="<?php echo $titleAlt; ?>"><img src="/upload/galeria/thumb/<?php echo $item->thumb; ?>" alt="<?php echo $titleAlt; ?>" /></a></li>
						<?php endforeach; endif; ?>
					</ul>
					<br clear="all" />
				</div>
				<h5 class="tltYellow">Vídeos</h5>
				<?php if($galeriaVideo) : foreach($galeriaVideo as $item) :
					unset($categ);
					if(isset($item->categorias)){
						$categ = '';
						$modObj = new Modalidade();
						foreach($item->categorias as $itemCateg){
							$baseUrl = (integer) $itemCateg['categ'] !== 0 ? 'modalidade' : 'categoria';
							$categ = '<a href="/' . $baseUrl . '/' . $itemCateg['permalink'] . '">' . $itemCateg['titulo'] . '</a>, ';
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
			</div>
			<div class="boxHome296 boxHome296Last">
				<div class="boxTltHome">
					<h3 class="tltRed">Mais notícias</h3>
					<span class="bgGray">&nbsp;</span>
				</div>
				<?php
					$ultimasNoticias = Noticia::get_ultimas($idsExibidos, 8);
					$dataPrev = '';
					$i = 0;
					if($ultimasNoticias !== FALSE) :
					foreach($ultimasNoticias as $item) :
				?>
				<div class="item">
						<p class="data"><?php echo $item['data']; ?></p>
						<div class="txt">
							<p class="categoria"><?php echo $item['categoria']; ?></p>
							<h3><a href="/noticia/<?php echo $item['permalink']; ?>"><?php echo $utility->limit_string($item['titulo'], 80); ?></a></h3>
						
						</div>
						<br clear="all" />
					</div>
				<?php endforeach; endif; ?>	
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