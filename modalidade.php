<?php
	$slug = isset($_GET['slug']) && !empty($_GET['slug']) ? $_GET['slug'] : FALSE;
	
	if($slug === FALSE){
		Header("HTTP/1.0 404 Not Found");
		@include_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/404.php');
		die();
	}
	
	require($_SERVER['DOCUMENT_ROOT'] . '/admin/_inc/config.php');
	
	$modObj = new Modalidade();
	$progObj = new Programacao();
	$notObj = new Noticia();
	$galObj = new Galeria();
	
	$listModalidades = $modObj->get_modalidades();
	$modalidade = $modObj->get_modalidade(0, $slug);
	$resultados = $progObj->get_resultados_programacao(0, 0, $modalidade->id, 0, NULL, 0);
	$dataProva = $progObj->get_max_data_item($modalidade->id);
	$programacao = $progObj->get_programacao(FALSE, FALSE, FALSE, $modalidade->id, FALSE, date('d-m-Y', $dataProva));
	$noticias = $notObj->get_noticias_categoria($modalidade->titulo, 6);
	$galeriaVideos = $galObj->get_galeria_categoria($modalidade->titulo, 2, 2);
	$galeriaFotos = $galObj->get_galeria_categoria($modalidade->titulo, 1, 21);
	
	$metaDescription = '';
	$metaKeywords = '';
	
	if($modalidade !== FALSE){
		if(!empty($modalidade->meta_description)) {
			$metaDescription = htmlspecialchars($modalidade->meta_description);
		}
		if(!empty($modalidade->meta_keywords)){
			$metaKeywords = htmlspecialchars($modalidade->meta_keywords);
		}
	}
			
	if(empty($metaDescription)){
		$metaDescription = 'Informações, fotos e histórias sobre as 27 modalidades que serão disputadas em Mogi das Cruzes, durante os Jogos Abertos do Interior 2011.';
	}
	if(empty($metaKeywords)){
		$metaKeywords = 'Modalidades, esportivas, disputas, categorias, futebol, vôlei, natação, basquete, tênis, atletismo, ciclismo, luta.';
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="<?php echo $metaDescription; ?>" />
<meta name="keywords" content="<?php echo $metaKeywords; ?>" />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title><?php echo ($modalidade !== FALSE) ? htmlspecialchars($modalidade->titulo) . ' | ' : ''; ?>Jogos Abertos do Interior 2011</title>
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/style_2.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.scrollpane.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.selectmenu.css">
<link rel="canonical" href="http://www.jogosabertos2011.com.br<?php echo $_SERVER['REQUEST_URI']; ?>" />
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="/_js/util.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.core.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.selectmenu.js"></script>
<script type="text/javascript" src="/_js/jquery.jscrollpane.js"></script>
<script type="text/javascript" src="/_js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/_js/jquery.em.js"></script>
<script type="text/javascript" src="/_js/jcarousellite_1.0.1.min.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
<script type="text/javascript" src="/_js/functions.js"></script>
<script type="text/javascript" src="/_js/func_mapa_interno.js"></script>
<?php if($modalidade !== FALSE) : ?>
	<script type="text/javascript">
	jQuery(function($) {
		initialize('', '', 12);
		createMarkersMaps('competicao', '/_img/maps/pin_competicao.png', '<?php echo str_replace('-', '', $modalidade->slug); ?>');
		if($('#carousel').length){
			$("#carousel").jCarouselLite({
				btnNext: ".next",
				btnPrev: ".prev",
				visible: 11,
				start: -8
			});
		}
		$('select#modalidade').selectmenu({ style:'dropdown',width: 300});
		$('.scroll-pane').jScrollPane({ 'animateScroll': true, 'verticalGutter': 0, 'autoReinitialise': true, 'scrollbarWidth': 16  });
	});
	</script>
<?php endif; ?>
</head>

<body class="modalidades-interna">
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/bg.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/header.php') ?>
    <div class="content p_modalidade" id="content">
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/navbar.php') ?>
        <h1 class="title">
        	<div class="sombra" style="margin:-4px 0 0 -24px; height:65px;"></div> 
        	Modalidades
        </h1>
	
	<?php if($modalidade !== FALSE) : ?>
	
		<div class="modTitle">
			<img src="/_img/modalidades/ico_cat<?php echo htmlspecialchars($modalidade->id); ?>.png" alt="<?php echo htmlspecialchars($modalidade->titulo); ?>">
			<h2><?php echo htmlspecialchars($modalidade->titulo); ?></h2>
		</div>
		
		<?php if($listModalidades !== FALSE) : ?>
			<div id="boxSelect">
				<label id="lblCidPart">Selecione a modalidade:</label>
				<div class="sombra" style="margin:6px 0 0 6px; width: 300px; height:30px;"></div> 
				<select name="modalidade" id="modalidade" autocomplete="off">
					<?php foreach($listModalidades as $mod) : ?>
					    <option value="<?php echo htmlspecialchars($mod->slug); ?>"<?php echo ($mod->id === $modalidade->id) ? ' selected="selected"' : ''; ?>><?php echo htmlspecialchars($mod->titulo); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php endif; ?>
			
		<div id="content_mod">
			<div class="column_left column_left_modalidade">
				
				<?php if($resultados !== FALSE) : ?>
					<div class="modResult">
						<h2>Últimos resultados</h2>
						<ul class="scroll-pane">
							
						<?php foreach($resultados as $key=>$itemProg) : ?>
							
							<?php
								if(is_array($itemProg->provas) && !empty($itemProg->provas)) {
									$txt = '';
									foreach($itemProg->provas as $prova){
											$txt .= "{$prova->titulo}, ";
									}
									$posLast = strrpos($txt = substr($txt, 0, -2), ',');
									if($posLast !== FALSE){
											$itemProg->provas = substr_replace($txt, ' e', $posLast, 1);
									} else {
											$itemProg->provas = $txt;
									}
								}
								
								$divFormat = explode(',', $itemProg->divisao);
								$txt = '';
								foreach($divFormat as $divItem){
									switch($divItem){
										case '3':
											$txt .= 'Divisão Especial, ';
											break;
										case '4':
											$txt .= 'Modalidades Extras, ';
											break;
										default:
											$txt .= ($divItem . 'ª Divisão, ');
									}
								}
								$posLast = strrpos($txt = substr($txt, 0, -2), ',');
								if($posLast !== FALSE){
									$itemProg->divisao = substr_replace($txt, ' e', $posLast, 1);
								} else {
									$itemProg->divisao = $txt;
								}
								
								$sexo = explode(',', $itemProg->sexo);
								$txt = '';
								foreach($sexo as $itemSexo){
									switch($itemSexo){
										case '1':
										  $txt .= 'Masculino, ';
										  break;
										case '2':
										  $txt .= 'Feminino, ';
										  break;
										case '3':
										  $txt .= 'Misto, ';
									}
								}
								$posLast = strrpos($txt = substr($txt, 0, -2), ',');
								if($posLast !== FALSE){
									$itemProg->sexo = substr_replace($txt, ' e', $posLast, 1);
								} else {
									$itemProg->sexo = $txt;
								}
								
								$categoria = explode(',', $itemProg->categoria);
								$txt = '';
								foreach($categoria as $cat){
										switch($cat){
										  case '0':
											$txt .= 'Livre, ';
											break;
										  default:
											$txt .= "Até $cat anos, ";
										}
								}
								$posLast = strrpos($txt = substr($txt, 0, -2), ',');
								if($posLast !== FALSE){
									$itemProg->categoria = substr_replace($txt, ' e', $posLast, 1);
								} else {
									$itemProg->categoria = $txt;
								}
								
								if(is_array($itemProg->cidades) && !empty($itemProg->cidades)) {
									$txt = '';
									foreach($itemProg->cidades as $cid){
										$txt .= "{$cid->nome}, ";
									}
									$posLast = strrpos($txt = substr($txt, 0, -2), ',');
									if($posLast !== FALSE){
										$txtCidade = substr_replace($txt, ' e', $posLast, 1);
									} else {
										$txtCidade = $txt;
									}
								}
								
								if((integer) $itemProg->resultado_layout_tipo === 1){
									if(!empty($itemProg->cidades[0]->nome_atleta)){
										$tipoResult = '3';
									} else {
										$tipoResult = '1';
									}
								} elseif((integer) $itemProg->resultado_layout_tipo === 2){
									$tipoResult = '2';
								} else {
									$tipoResult = '4';
								}

							?>
							
							<li class="res resultado_<?php echo $tipoResult; ?> item<?php echo ($key % 2 == 0) ? '1' : '2'; ?>">
							<?php if(!empty($itemProg->descricao) || !empty($itemProg->provas)) : ?>
								<h3><?php echo !empty($itemProg->descricao) ? htmlspecialchars($itemProg->descricao) . '<br />' : ''; ?><?php echo isset($itemProg->provas) ? htmlspecialchars($itemProg->provas) : ''; ?></h3>
							<?php endif; ?>
								<h4><?php echo htmlspecialchars($itemProg->divisao . ' - ' . $itemProg->sexo . ' - ' . $itemProg->categoria); ?></h4>
								
							<?php if($tipoResult === '1') : ?>
							
								<p class="<?php echo $itemProg->cidades[0]->resultado === 'v' ? 'vitoria' : ''; ?>">
									<span class="pts"><?php echo $itemProg->cidades[0]->resultado_total; ?></span>
									<img src="/_img/cidade/bandeiras/<?php echo $itemProg->cidades[0]->bandeira; ?>" />
									<span class="nome spaceLine"><?php echo $itemProg->cidades[0]->nome . ($itemProg->cidades[1]->resultado === 'w' ? ' (Venceu por W.O.)' : ''); ?></span>
								</p>
								<span class="x">X</span>
								<p class="<?php echo $itemProg->cidades[1]->resultado === 'v' ? 'vitoria' : ''; ?>">
									<span class="pts"><?php echo $itemProg->cidades[1]->resultado_total; ?></span>
									<img src="/_img/cidade/bandeiras/<?php echo $itemProg->cidades[1]->bandeira; ?>" />
									<span class="nome spaceLine"><?php echo $itemProg->cidades[1]->nome . ($itemProg->cidades[0]->resultado === 'w' ? ' (Venceu por W.O.)' : ''); ?></span>
								</p>
							
							<?php elseif($tipoResult === '2') :
							
								require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Utility.class.php');
								$arrOrdCid = Utility::object2Array($itemProg->cidades);
								$arrOrdCidPrint = array();
								$i = 0;
								$j = 0;

								do{
									if(is_array($arrOrdCid[$i]['resultado_colocacao'])){
										if(count($arrOrdCid[$i]['resultado_colocacao']) === 1){
											$arrOrdCidPrint[$j] = $arrOrdCid[$i];
											$arrOrdCidPrint[$j]['resultado_colocacao'] = array_shift($arrOrdCidPrint[$j]['resultado_colocacao']);
											if(is_array($arrOrdCidPrint[$j]['nome_atleta']) && !empty($arrOrdCidPrint[$j]['nome_atleta'])){
												$arrOrdCidPrint[$j]['nome_atleta'] = array_shift($arrOrdCidPrint[$j]['nome_atleta']);
											}
											$j++;
										} else {
											$itemColTemp = array_shift($arrOrdCid[$i]['resultado_colocacao']);
											if(is_array($arrOrdCid[$i]['nome_atleta']) && !empty($arrOrdCid[$i]['nome_atleta'])){
												$itemAltTemp = array_shift($arrOrdCid[$i]['nome_atleta']);
											}
											$arrOrdCid[] = $arrOrdCidPrint[$j] = $arrOrdCid[$i];
											$arrOrdCidPrint[$j]['resultado_colocacao'] = $itemColTemp;
											$arrOrdCidPrint[$j]['nome_atleta'] = isset($itemAltTemp) ? $itemAltTemp : NULL;
											$j++;
										}
									}
									$i++;
								} while($i < count($arrOrdCid));
								
								for($i = 0; $i < 3; $i++){
									for($j = $i; $j < 3; $j++){
										if($arrOrdCidPrint[$i]['resultado_colocacao'] > $arrOrdCidPrint[$j]['resultado_colocacao']){
											list($arrOrdCidPrint[$i], $arrOrdCidPrint[$j]) = array($arrOrdCidPrint[$j], $arrOrdCidPrint[$i]);
										}
									}
								}
							
								$arrOrdCidPrint = array_map('Utility::array2Object', $arrOrdCidPrint);
								
							?>
								
								<?php for($i = 0; $i < 3; $i++) : ?>
									<p class="<?php echo $i === 2 ? 'last' : ''; ?><?php echo (integer) $arrOrdCidPrint[$i]->resultado_colocacao === 1 ? ' vencedor' : ''; ?>">
										<img class="imgBand" src="/_img/cidade/bandeiras/<?php echo $arrOrdCidPrint[$i]->bandeira; ?>" title="<?php echo htmlspecialchars($arrOrdCidPrint[$i]->nome); ?>" />
										<span class="pts"><?php echo $arrOrdCidPrint[$i]->resultado_colocacao; ?>º</span>
									<?php if(!empty($arrOrdCidPrint[$i]->nome_atleta)) : ?>
										<span class="nome"><b><?php echo $arrOrdCidPrint[$i]->nome_atleta; ?></b><br />
										(<?php echo $arrOrdCidPrint[$i]->nome; ?>)</span>										
									<?php else : ?>
										<span class="nome spaceLine">
											<strong><?php echo $arrOrdCidPrint[$i]->nome; ?></strong>
										</span>
									<?php endif; ?>
										<br clear="all" />
									</p>
								<?php endfor; ?>
							
							<?php elseif($tipoResult === '3') : ?>
							
								<p class="<?php echo $itemProg->cidades[0]->resultado === 'v' ? 'vitoria' : ''; ?>">
									<span class="pts"><?php echo $itemProg->cidades[0]->resultado_total; ?></span>
									<img src="/_img/cidade/bandeiras/<?php echo $itemProg->cidades[0]->bandeira; ?>" title="<?php echo htmlspecialchars($itemProg->cidades[0]->nome); ?>" />
									<span class="nome"><b><?php echo $itemProg->cidades[0]->nome_atleta[0]; ?></b><br />
									(<?php echo $itemProg->cidades[0]->nome . ($itemProg->cidades[1]->resultado === 'w' ? ' [Venceu por W.O.]' : ''); ?>)</span>
								</p>
								<span class="x">X</span>
								<p class="<?php echo $itemProg->cidades[1]->resultado === 'v' ? 'vitoria' : ''; ?>">
									<span class="pts"><?php echo $itemProg->cidades[1]->resultado_total; ?></span>
									<img src="/_img/cidade/bandeiras/<?php echo $itemProg->cidades[1]->bandeira; ?>" title="<?php echo htmlspecialchars($itemProg->cidades[1]->nome); ?>" />
									<span class="nome"><b><?php echo $itemProg->cidades[1]->nome_atleta[0]; ?></b><br />
									(<?php echo $itemProg->cidades[1]->nome . ($itemProg->cidades[0]->resultado === 'w' ? ' [Venceu por W.O.]' : ''); ?>)</span>
								</p>
							
							<?php elseif($tipoResult === '4') : ?>
								
								<a href="/upload/programacao_resultado/<?php echo $itemProg->resultado_link_pdf; ?>" target="_blank" class="ver_res">ver resultado</a>
								
							<?php endif; ?>	
							
							</li>
							
						<?php endforeach; ?>
						
						</ul>
						<a class="mais-result" href="/resultados/">mais resultados</a>
					</div>
				
				<?php endif; ?>
				
					<div class="modProg">
						<h2>
							<div class="sombra" style="width: 320px; height:40px; top: 0px; left: 0px;"></div> 
							Programação Extraoficial
						</h2>
						
						<div class="bgDisabledContent">&nbsp;</div>
						<div class="boxLoading" id="loadingContent"><img src="/_img/load.gif" alt="Carregando..." /></div>
						
						<div class="prog-title prog-title-cidades">
							<a href="#<?php echo date('d-m-Y', strtotime('-1 day', $dataProva)); ?>" class="prog-prev<?php echo strtotime('-1 day', $dataProva) < mktime(0,0,0,11,7,11) ? ' hide' : '' ; ?>">&nbsp;</a>
							<span class="data dataFilter" rel="<?php echo date('d-m-Y', $dataProva); ?>"><?php echo date('d\/m', $dataProva) . ' - ' . Utility::data_extenso_texto(date('N', $dataProva)); ?></span>
							<a href="#<?php echo date('d-m-Y', strtotime('+1 day', $dataProva)); ?>" class="prog-next<?php echo strtotime('+1 day', $dataProva) > mktime(23,59,59,11,19,11) ? ' hide' : '' ; ?>">&nbsp;</a>
						</div>
						
						<div class="scroll-pane">
						<ul id="containerProgramacao">
						
						<?php if($programacao === FALSE) : ?>
						
							<li class="item itemNenhumaProva">
								<p>Nenhuma prova cadastrada.</p>
							</li>
						
						<?php else : ?>
						
							<?php foreach($programacao as $key=>$itemProg) : ?>
								<?php
									$divFormat = explode(',', $itemProg->divisao);
									$txt = '';
									foreach($divFormat as $divItem){
										switch($divItem){
											case '3':
												$txt .= 'Divisão Especial, ';
												break;
											case '4':
												$txt .= 'Modalidades Extras, ';
												break;
											default:
												$txt .= ($divItem . 'ª Divisão, ');
										}
									}

									$posLast = strrpos($txt = substr($txt, 0, -2), ',');
									if($posLast !== FALSE){
										$itemProg->divisao = substr_replace($txt, ' e', $posLast, 1);
									} else {
										$itemProg->divisao = $txt;
									}
									
									if(is_array($itemProg->provas) && !empty($itemProg->provas)) {
										$txt = '';
										foreach($itemProg->provas as $prova){
											$txt .= "{$prova->titulo}, ";
										}
										$posLast = strrpos($txt = substr($txt, 0, -2), ',');
										if($posLast !== FALSE){
											$itemProg->provas = substr_replace($txt, ' e', $posLast, 1);
										} else {
											$itemProg->provas = $txt;
										}
									}
									      
									$sexo = explode(',', $itemProg->sexo);
									$txt = '';
									foreach($sexo as $itemSexo){
										switch($itemSexo){
										    case '1':
										      $txt .= 'Masculino, ';
										      break;
										    case '2':
										      $txt .= 'Feminino, ';
										      break;
										    case '3':
										      $txt .= 'Misto, ';
										}
									}
									$posLast = strrpos($txt = substr($txt, 0, -2), ',');
									if($posLast !== FALSE){
										$itemProg->sexo = substr_replace($txt, ' e', $posLast, 1);
									} else {
										$itemProg->sexo = $txt;
									}
									      
									$categoria = explode(',', $itemProg->categoria);
									$txt = '';
									foreach($categoria as $cat){
										switch($cat){
											case '0':
												$txt .= 'Livre, ';
												break;
											default:
												$txt .= "Até $cat anos, ";
										}
									}
									$posLast = strrpos($txt = substr($txt, 0, -2), ',');
									if($posLast !== FALSE){
										$itemProg->categoria = substr_replace($txt, ' e', $posLast, 1);
									} else {
										$itemProg->categoria = $txt;
									}
									      
									if(is_array($itemProg->cidades) && !empty($itemProg->cidades)) {
										$txt = '';
										
										$txt = '<strong>Cidades:</strong> ';
										foreach($itemProg->cidades as $cid){
										    $txt .= ('<a href="/jogos/cidades-participantes/' . htmlspecialchars($cid->slug) . '">' . htmlspecialchars($cid->nome) . '</a>, ');
										}
										$posLast = strrpos($txt = substr($txt, 0, -2), ',');
										if($posLast !== FALSE){
										    $txtCidade = substr_replace($txt, ' e', $posLast, 1);
										} else {
										    $txtCidade = $txt;
										}

										$itemProg->cidades = $txtCidade;
									}
								?>
								
									<li class="item itemProgMod<?php echo ($key % 2) + 1; ?>">
										<b><?php echo date('H:i', htmlspecialchars($itemProg->data)); ?>h: <?php echo isset($itemProg->provas) ? htmlspecialchars($itemProg->provas) : ''; ?>
											<?php echo !empty($itemProg->descricao) ? '(' . htmlspecialchars($itemProg->descricao) . ')' : ''; ?></b><br>
										<b>Divisão:</b> <?php echo htmlspecialchars($itemProg->divisao); ?><br />
										<b>Categoria:</b> <?php echo htmlspecialchars($itemProg->sexo); ?> - <?php echo htmlspecialchars($itemProg->categoria); ?><br>
										<b>Local:</b> <?php echo htmlspecialchars($itemProg->local); ?><br>
										<?php echo $itemProg->cidades; ?>
									</li>
									
							<?php endforeach; ?>
						
						<?php endif; ?>
						</ul>
						</div>
					</div>
					<a class="mais-prog" href="/programacao/" title="Ver programacao completa">Ver programacao completa</a>
			</div>
			<div class="column_right column_right_modalidade">
				
				<?php if($noticias !== FALSE) : ?>
				
					<div class="modNoti">
						<h2>
							<div class="sombra" style="width: 620px; height:40px; top: 4px; left: 4px;"></div> 
							notícias
						</h2>
					
						<div class="noticiaTop noticiaContent">							
						<?php for($i = 0; $i < 2; $i++) : ?>
							<?php
								if(empty($noticias)){
									break;
								}
								$itemNoticia = array_shift($noticias);
							?>
							<div class="itemNoticia">
								<a class="img" href="/noticia/<?php echo htmlspecialchars($itemNoticia->permalink); ?>"><img src="<?php echo htmlspecialchars($itemNoticia->imagemThumb); ?>" alt="<?php echo htmlspecialchars(stripslashes($itemNoticia->titulo)); ?>" /></a>
								<h3><a href="/noticia/<?php echo htmlspecialchars($itemNoticia->permalink); ?>"><?php echo Utility::limit_string(htmlspecialchars(stripslashes($itemNoticia->titulo)), 80); ?></a></h3>
							</div>
						<?php endfor; ?>
							<br clear="all" />
						</div>
						
					<?php if(!empty($noticias)) : ?>
						<div class="destaquesSub">
							
							<div class="noticiaMid noticiaContent">
							<?php for($i = 0; $i < 2; $i++) : ?>
							<?php
								if(empty($noticias)){
									break;
								}
								$itemNoticia = array_shift($noticias);
							?>
								<div class="itemNoticia">
									<a class="img" href="/noticia/<?php echo htmlspecialchars($itemNoticia->permalink); ?>"><img src="<?php echo htmlspecialchars(str_replace('/upload/', '/upload/thumbMid/', $itemNoticia->imagemThumb)); ?>" alt="<?php echo htmlspecialchars(stripslashes($itemNoticia->titulo)); ?>" /></a>
									<div class="txt">
										<h5><a href="/categoria/<?php echo htmlspecialchars($itemNoticia->categoria_permalink); ?>"><?php echo htmlspecialchars($itemNoticia->categoria_titulo); ?></a></h5>
										<h3><a href="/noticia/<?php echo htmlspecialchars($itemNoticia->permalink); ?>" title="<?php echo htmlspecialchars($itemNoticia->titulo); ?>"><?php echo Utility::limit_string(htmlspecialchars(stripslashes($itemNoticia->titulo)), 70); ?></a></h3>
									</div>
								</div>
							<?php endfor; ?>
								<br clear="all" />
							</div>
							
						<?php if(!empty($noticias)) : ?>
							<div class="noticiaMid noticiaContent">
							<?php for($i = 0; $i < 2; $i++) : ?>
							<?php
								if(empty($noticias)){
									break;
								}
								$itemNoticia = array_shift($noticias);
							?>
								<div class="itemNoticia">
									<a class="img" href="/noticia/<?php echo htmlspecialchars($itemNoticia->permalink); ?>"><img src="<?php echo htmlspecialchars(str_replace('/upload/', '/upload/thumbMid/', $itemNoticia->imagemThumb)); ?>" alt="<?php echo htmlspecialchars(stripslashes($itemNoticia->titulo)); ?>" /></a>
									<div class="txt">
										<h5><a href="/categoria/<?php echo htmlspecialchars($itemNoticia->categoria_permalink); ?>"><?php echo htmlspecialchars($itemNoticia->categoria_titulo); ?></a></h5>
										<h3><a href="/noticia/<?php echo htmlspecialchars($itemNoticia->permalink); ?>" title="<?php echo htmlspecialchars($itemNoticia->titulo); ?>"><?php echo Utility::limit_string(htmlspecialchars(stripslashes($itemNoticia->titulo)), 70); ?></a></h3>
									</div>
								</div>
							<?php endfor; ?>
								<br clear="all" />
							</div>						
						<?php endif; ?>
							
						</div>
					<?php endif; ?>

					</div>
					
				<?php endif; ?>
			
			<?php if($galeriaFotos !== FALSE || $galeriaVideos !== FALSE) : ?>
			
				<div class="modGale">
					<h2>
						<div class="sombra" style="width: 620px; height:40px; top: 0; left: 0;"></div> 
						galeria multimídia
					</h2>
					
				<?php if($galeriaFotos !== FALSE) : ?>
				
					<div class="galeria_f">
						<h3>Fotos</h3>
						<ul>
						
						<?php foreach($galeriaFotos as $itemFoto) : ?>
							<li>
								<a href="/galeria-multimidia/<?php echo htmlspecialchars($itemFoto->id); ?>" title="<?php echo htmlspecialchars($itemFoto->titulo); ?>">
									<img src="/upload/galeria/thumb/<?php echo htmlspecialchars($itemFoto->thumb); ?>" alt="<?php echo htmlspecialchars($itemFoto->titulo); ?>" />
								</a>
							</li>
						<?php endforeach; ?>

						</ul>
						<br clear="all" />
					</div>
					
				<?php endif; ?>
				
				<?php if($galeriaVideos !== FALSE) : ?>
				
					<div class="galeria_v">
							<h3>Vídeos</h3>
						<ul>
						
						<?php foreach($galeriaVideos as $itemVideo) : ?>
							<li>
								<a class="img" href="/galeria-multimidia/<?php echo htmlspecialchars($itemVideo->id); ?>" title="<?php echo htmlspecialchars($itemVideo->titulo); ?>">
									<img src="<?php echo htmlspecialchars($itemVideo->thumb); ?>" alt="<?php echo htmlspecialchars($itemVideo->titulo); ?>">
								</a>
								<span class="categoria"><a class="linkCateg" href="/categoria/<?php echo htmlspecialchars($itemVideo->categoria_slug); ?>"><?php echo htmlspecialchars($itemVideo->categoria_titulo); ?></a></span>
								<span class="titulo"><a class="linkTlt" href="/galeria-multimidia/<?php echo htmlspecialchars($itemVideo->id); ?>" title="<?php echo htmlspecialchars($itemVideo->titulo); ?>"><?php echo Utility::limit_string(htmlspecialchars($itemVideo->titulo), 60); ?></a></span>
							</li>
						<?php endforeach; ?>

						</ul>
					</div>
				
				<?php endif; ?>
					
					<a href="/galeria-multimidia" class="mais">Galeria completa</a>
				</div>
				
			<?php endif; ?>
			
			<h2 class="tit_mapa">locais de competição</h2>
			<div class="mapa" id="mapa">
				<img src="/_img/modalidades/ft_mapa.jpg" width="469" height="286" alt="" title="Mapa não foi carregado completamente." />
			</div>
		    </div>
		    
		    <div class="info">
			<h2>Saiba mais sobre <?php echo htmlspecialchars($modalidade->titulo); ?></h2>
			<img src="/_img/modalidades/<?php echo htmlspecialchars($modalidade->thumb); ?>" align="left" alt="<?php echo htmlspecialchars($modalidade->titulo); ?>" />
			<?php echo strip_tags($modalidade->descricao, '<p><a><br><h3><h4><h5><h6><span><strong><em>'); ?>
		    </div>
		</div>
	
		<input type="hidden" id="nomeModalidade" value="<?php echo htmlspecialchars($modalidade->titulo); ?>" />
		<input type="hidden" id="idModalidade" value="<?php echo htmlspecialchars($modalidade->id); ?>" />
	
	<?php endif; ?>
	<br clear="all" />
    </div>
		
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>
