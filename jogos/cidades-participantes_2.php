<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/Cache.class.php');
	$cache = new Cache();
	$cache->set_arquivo('page_' . $_SERVER['REQUEST_URI']);
	$cache->set_tempo(0);
	$cache->start();
	
	if($cache->cached === FALSE):

		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Cidade.class.php');
		$slug = isset($_GET['slug']) && !empty($_GET['slug']) ? addslashes($_GET['slug']) : FALSE;
		if($slug !== FALSE){
			$cidade = Cidade::get_cidade(FALSE, $slug);
		} else {
			$cidade = Cidade::get_cidade(3);
		}

		if($cidade === FALSE){
			Header("HTTP/1.0 404 Not Found");
			@include_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/404.php');
			die();
		}
		
		$totalModMedalhasCid = 0;
		$medalhasCid = Cidade::get_cidades_medalhas_cidade($cidade->id);
		if($medalhasCid !== FALSE){
			$totalModMedalhasCid = count($medalhasCid->modalidades);
		}
		$medalhasAtualizacao = Medalha::data_atualizacao();
		
		$classificacaoCid = Cidade::get_cidades_classificacao_cidade($cidade->id);
		$classificacaoCid = $classificacaoCid[0];
		$classificacaoAtualizacao = Classificacao::data_atualizacao();
		
		$notObj = new Noticia();
		$noticias = $notObj->get_noticias_tag($cidade->nome, 4);
		
		$divisaoCidade = Cidade::get_divisao_provas($cidade->id);
		$progObj = new Programacao();
		$programacao = $progObj->get_programacao(FALSE, FALSE, $cidade->id, FALSE, FALSE, date('d-m-Y'));

		if($programacao !== FALSE){
			$arrDivPrincipal = array();
			$arrDivEsp = array();
			$arrDivExt = array();
			
			foreach($programacao as $item){
				if(is_array($item->provas) && !empty($item->provas)) {
					$txt = '';
					foreach($item->provas as $prova){
					        $txt .= "{$prova->titulo}, ";
					}
					$posLast = strrpos($txt = substr($txt, 0, -2), ',');
					if($posLast !== FALSE){
					        $item->provas = substr_replace($txt, ' e', $posLast, 1);
					} else {
					        $item->provas = $txt;
					}
				      }
				      
				      $sexo = explode(',', $item->sexo);
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
					      $txt .= 'Misto';
					}
				      }
				      $posLast = strrpos($txt = substr($txt, 0, -2), ',');
				      if($posLast !== FALSE){
					$item->sexo = substr_replace($txt, ' e', $posLast, 1);
				      } else {
					$item->sexo = $txt;
				      }
				      
				      $categoria = explode(',', $item->categoria);
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
					$item->categoria = substr_replace($txt, ' e', $posLast, 1);
				      } else {
					$item->categoria = $txt;
				      }
				      
				      if(is_array($item->cidades) && !empty($item->cidades)) {
					$txt = '';
					
					if(count($item->cidades) === 2){
					    $txtCidade = '<strong>' . $item->cidades[0]->nome . ' x ' . $item->cidades[1]->nome . '</strong>';
					} else {
					    $txt = '<strong>Cidades:</strong> ';
					    foreach($item->cidades as $cid){
					        $txt .= (htmlspecialchars($cid->nome) . ", ");
					    }
					    $posLast = strrpos($txt = substr($txt, 0, -2), ',');
					    if($posLast !== FALSE){
					        $txtCidade = substr_replace($txt, ' e', $posLast, 1);
					    } else {
					        $txtCidade = $txt;
					    }
					}
					$item->cidades = $txtCidade;
				      }
				
				if($item->divisao === '1' || $item->divisao === '2'){
					$arrDivPrincipal[] = $item;
				} elseif($item->divisao === '3'){
					$arrDivEsp[] = $item;
				} elseif($item->divisao === '4'){
					$arrDivExt[] = $item;
				}
			}
		}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="Conheça a casa dos Jogos Abertos do Interior 2011. Navegue pelo mapa interativo da cidade, e veja toda a infraestrutura preparada para receber o evento." />
<meta name="keywords" content="Mapa, Mogi, Jogos, Abertos, pontos, turísticos, interior, SP, localização, Casa, infraestrutura" />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title><?php echo isset($cidade) && $cidade !== FALSE ? "$cidade->nome | " : ''; ?>Cidades Participantes | Jogos Abertos do Interior 2011</title>
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/style_2.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.scrollpane.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.selectmenu.css">
<link rel="canonical" href="http://www.jogosabertos2011.com.br<?php echo $_SERVER['REQUEST_URI']; ?>" />
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.core.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.selectmenu.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="/_js/jcarousellite_1.0.1.min.js"></script>
<script type="text/javascript" src="/_js/jquery.jscrollpane.js"></script>
<script type="text/javascript" src="/_js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/_js/jquery.em.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
<script type="text/javascript" src="/_js/util.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="/_js/functions.js"></script>
<script type="text/javascript">
	var geocoder;
	var infowindow;
  	var map;
	var arquivo;
	var coordMap1;
	var coordMap2;
	var icone;
	var nivelZoom;
	var markersArray = new Array;
	var arrayMarkers = new Array;
	var cont = 0;
	function initialize(coordMap1, coordMap2, nivelZoom) {
		if(!coordMap1){
			coordMap1 = -23.524754;
		}
		if(!coordMap2){
			coordMap2 = -46.187134;
		}
		var myLatlng = new google.maps.LatLng(coordMap1, coordMap2);
		if(!nivelZoom){
			nivelZoom = 8;
		}
		var myOptions = {
			zoom: nivelZoom,
			center: myLatlng,
			icon: icone,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		map = new google.maps.Map(document.getElementById("mapa"), myOptions);
	}
	function createMarkersMaps(arquivo, icone, id){
		downloadUrl(arquivo, function(data) {
			if(data != null){
				var markers = data.documentElement.getElementsByTagName(id);
				var latlng = 0;
				for (var i = 0; i < markers.length; i++) {
					var latlng = new google.maps.LatLng(
							markers[i].getAttribute("lat"),
							markers[i].getAttribute("lng")
						);
						var image = '';
						if(markers[i].getAttribute("img")){
							image = markers[i].getAttribute("img");
							image = '<img src="'+image+'" width="80" height="80" align="left" style="margin:0 10px 0 0;" />';
						}
						var marker = createMarker(
							markers[i].getAttribute("name"),
							markers[i].getAttribute("info") + '<br />' + markers[i].getAttribute("tel"),
							image,
							icone,
							latlng,
							id
						);
				}
				if(latlng){
					map.setCenter(latlng, 12);
				} else {
					map.setCenter(-23.534754, -46.195134, 12);
				}
			}

		});
		markersArray = new Array;
	}
  	function createMarker(name, info, image, icone, latlng, id) {
		var marker = new google.maps.Marker({position: latlng, map: map, icon:icone});
		markersArray.push(marker);
		arrayMarkers[id] = markersArray;
		google.maps.event.addListener(marker, "click", function() {
			html = '<h1 class="tit_maps">'+name+'</h1><br /><div class="info_maps">'+image+info+'</div>';
			if (infowindow) infowindow.close();
			infowindow = new google.maps.InfoWindow({
				content: html
			});
			infowindow.open(map, marker);
		});
		return marker;
  	}
	function selecionaPins(id, marks){
		var marca = 0;
		if (arrayMarkers[marks]) {
			for (i in arrayMarkers[marks]) {
				arrayMarkers[marks][i].setMap(null);
			}
		}
		var $url = (id ? ('/mapa/' + marks + '.php?id=' + id) : ('/mapa/' + marks + '.php'));		
		downloadUrl($url, function(data) {
			var num = 0;
			var markers = data.documentElement.getElementsByTagName(marks);
			for (var i = 0; i < markers.length; i++) {
				var latlng = 0;
				var latlng = new google.maps.LatLng(
					markers[i].getAttribute("lat"),
					markers[i].getAttribute("lng")
				);
				arrayMarkers[marks][i].setMap(map);
				num++;
			}

			if(num < 2){
				map.setCenter(latlng, 12);
			}else{
				map.setCenter(new google.maps.LatLng(-23.526029,-46.188698));				
			}
		});
	}
jQuery(function($) {
	initialize(-23.534754, -46.195134, 12);
	createMarkersMaps('/mapa/alojamento.php?id=<?php echo $cidade->id; ?>', '/_img/pin_alojamentos.png', 'alojamento');
	$('#participantes').selectmenu({ style:'dropdown',width: 300});
	$('select#participantes').change(function(){
		window.location = '/jogos/cidades-participantes/' + $(this).val();
	})
	$('.scroll-pane').jScrollPane({ 'animateScroll': true, 'verticalGutter': 0, 'autoReinitialise': true, 'scrollbarWidth': 16  });
});

$(document).ready(function(){  });
</script>

</head>

<body class="body_cidades_participantes">
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/bg.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/header.php') ?>
    <div class="content" id="content">
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/navbar.php') ?>
        <h1 class="title">
        	<div class="sombra" style="margin:-4px 0 0 -24px; height:65px;"></div> 
        	Cidades Participantes
        </h1>
		<br clear="all" />
		<?php
			$listCidades = Cidade::get_list_cidades();
			if($listCidades !== FALSE) : 
		?>
			<div id="boxSelectCid">
				<label id="lblCidPart">Selecione a cidade e veja as informações:</label>
				<select name="participantes" id="participantes" autocomplete="off">
					<?php foreach($listCidades as $listCidade) : ?>
						<option value="<?php echo $listCidade->slug; ?>"<?php echo $listCidade->id == $cidade->id ? ' selected="selected"' : ''; ?>><?php echo $listCidade->nome; ?></option>
					<?php endforeach; ?>
				</select>
				<div class="bgGray"></div>
			</div>
		<?php endif; ?>
        <div id="content_participantes">
			<div class="box">
				<form id="frmParticipantes">
					<div class="r">
						<div class="partQuadro">
							<h2>Quadro de Medalhas</h2>
							<ul>
								<li>
									<span class=""></span>
									<span class="ouro" title="Ouro"></span>
									<span class="prata" title="Prata"></span>
									<span class="bronze" title="Bronze"></span>
									<span class="total itemLast" title="Total"></span>
									
								</li>
								
								<?php if($medalhasCid === FALSE) : ?>						
									<li class="total">
										<span>total</span>
										<span>0</span>
										<span>0</span>
										<span>0</span>
										<span class="itemLast">0</span>
									</li>
								<?php else : ?>
									<li class="total">
										<span>total</span>
										<span><?php echo htmlspecialchars($medalhasCid->medalha_total_ouro); ?></span>
										<span><?php echo htmlspecialchars($medalhasCid->medalha_total_prata); ?></span>
										<span><?php echo htmlspecialchars($medalhasCid->medalha_total_bronze); ?></span>
										<span class="itemLast"><?php echo htmlspecialchars($medalhasCid->medalha_total); ?></span>
									</li>
									<div class="scroll-pane" style="height: <?php echo ($totalModMedalhasCid <= 4) ? $totalModMedalhasCid * 40 : 160 ; ?>px;">
										<?php foreach($medalhasCid->modalidades as $modalidadeCid) : ?>
											<li>
												<span><?php echo htmlspecialchars($modalidadeCid->titulo); ?></span>
												<span><?php echo htmlspecialchars($modalidadeCid->medalha_ouro); ?></span>
												<span><?php echo htmlspecialchars($modalidadeCid->medalha_prata); ?></span>
												<span><?php echo htmlspecialchars($modalidadeCid->medalha_bronze); ?></span>
												<span class="itemLast"><?php echo htmlspecialchars($modalidadeCid->total); ?></span>
											</li>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							</ul>
							<p>
								<a class="veja" href="">Veja o quadro completo</a>
								<span class="update">Última atualização: <?php echo htmlspecialchars($medalhasAtualizacao); ?></span>
							</p>
						</div>
				
						<div class="partClassif ">
							<h2>CLASSIFICAÇÃO</h2>
							<span class="divisao"><?php echo htmlspecialchars($cidade->divisao); ?>ª Divisao</span>
							<ul>
								<li>
									<span>COLOCAÇÃO:</span>
									<span><?php echo htmlspecialchars($classificacaoCid->pos_pontos); ?>º LUGAR</span>
								</li>
								<li>
									<span>PONTUAÇÃO:</span>
									<span><?php echo htmlspecialchars(str_replace('.', ',', round($classificacaoCid->pontos, 1))); ?> pts</span>
								</li>
							</ul>
							<p>
								<a class="veja" href="/classificacao">Veja a classificação completa</a>
								<span class="update">Última atualização: <?php echo htmlspecialchars($classificacaoAtualizacao); ?></span>
							</p>
						</div>
				
						<?php if($cidade->modalidades != NULL) : ?>
						
							<h2 class="subtitle subtitleMod">Modalidades Participantes:</h2>
							<h2 class="subtitle2"><?php echo htmlspecialchars($cidade->divisao); ?>ª divisão</h2>			
							<ul id="listParticipantesModalidades" class="listPartModalidades">
								<?php foreach($cidade->modalidades as $mod) : ?>
									<li>
										<a href="/modalidade/<?php echo htmlspecialchars($mod->slug); ?>">
											<img src="/_img/modalidades/ico_med<?php echo htmlspecialchars($mod->id); ?>.png" alt="<?php echo htmlspecialchars($mod->titulo); ?>" />
										</a>
										<div class="tooltip">
											<div class="top">&nbsp;</div>
											<div class="content"><?php echo htmlspecialchars($mod->titulo); ?></div>
										</div>
									</li>
								<?php endforeach; ?>
							</ul>
							<br clear="all" />
							
						<?php endif; if($cidade->modalidades_esp != NULL) : ?>
						
							<h2 class="subtitle2">Divisão Especial</h2>
							<ul id="listParticipantesModalidades2" class="listPartModalidades">
								<?php foreach($cidade->modalidades_esp as $mod) : ?>
									<li>
										<a href="/modalidade/<?php echo htmlspecialchars($mod->slug); ?>">
											<img src="/_img/modalidades/ico_med<?php echo htmlspecialchars($mod->id); ?>.png" alt="<?php echo htmlspecialchars($mod->titulo); ?>" />
										</a>
										<div class="tooltip">
										<div class="top">&nbsp;</div>
										<div class="content"><?php echo htmlspecialchars($mod->titulo); ?></div>
										</div>
									</li>
								<?php endforeach; ?>
							</ul>
							<br clear="all" />
				
						<?php endif; if($cidade->modalidades_ext != NULL) : ?>
						
							<h2 class="subtitle2">Modalidades Extras</h2>
							<ul id="listParticipantesModalidades2" class="listPartModalidades">
								<?php foreach($cidade->modalidades_ext as $mod) : ?>
									<li>
										<a href="/modalidade/<?php echo htmlspecialchars($mod->slug); ?>">
										<img src="/_img/modalidades/ico_med<?php echo htmlspecialchars($mod->id); ?>.png" alt="<?php echo htmlspecialchars($mod->titulo); ?>" />
										</a>
										<div class="tooltip">
										<div class="top">&nbsp;</div>
										<div class="content"><?php echo htmlspecialchars($mod->titulo); ?></div>
										</div>
									</li>
								<?php endforeach; ?>
							</ul>
							<br clear="all" />
							
						<?php endif; ?>
						
						<h2 class="subtitle2 subtitle2Aloj">Alojamentos</h2>
						<div class="mapa" id="mapa">
							<img src="/_img/mapa_ilustracao.jpg" width="500" height="500" alt="Mapa" />
						</div>
					</div>
					
					<div class="l">
						<div class="flag">
							<h2 class="subtitle subtitleFlag">
								<img src="/_img/cidade/bandeiras/<?php echo $cidade->bandeira; ?>" alt="<?php echo htmlspecialchars($cidade->nome); ?>"/><?php echo $cidade->nome; ?>
								<span class="colocacao">Colocação nos Jogos de 2010:</span>
								<?php if(!$cidade->colocacao_2010 || !$cidade->divisao_2010) : ?>
									<span class="colocacao2">(não participou)</span>
								<?php else : ?>
									<span class="colocacao2"><?php echo $cidade->colocacao_2010; ?>º lugar - <?php echo $cidade->divisao_2010; ?>ª divisão</span>
								<?php endif; ?>
							</h2>
						</div>
	            
						<div class="partProg">
						<?php if($divisaoCidade !== FALSE) : ?>
							<h2>PROGRAMAÇÃO</h2>
							<ul id="listTab">
								
								<?php if(in_array('1', $divisaoCidade)) : ?>
									<li><a href="#box1a" rel="1">1ª Divisão</a></li>
								<?php endif; if(in_array('2', $divisaoCidade)) : ?>
									<li><a href="#box1a" rel="2">2ª Divisão</a></li>
								<?php endif; if(in_array('3', $divisaoCidade)) : ?>
									<li><a href="#box3a" rel="3">Divisao Especial</a></li>
								<?php endif; if(in_array('4', $divisaoCidade)) : ?>
									<li><a href="#box4a" rel="4">Modalidades Extras</a></li>
								<?php endif; ?>
								
							</ul>
			
							<div id="containerTab" class="participantes">
								<div class="bgDisabledContent">&nbsp;</div>
								<div class="boxLoading" id="loadingContent"><img src="/_img/load.gif" alt="Carregando..." /></div>
								
								<?php if(in_array('1', $divisaoCidade) || in_array('2', $divisaoCidade)) : ?>
									<div class="tab" id="box1a">
										<div class="prog-title-cidades prog-title">
											<a href="#<?php echo date('d-m-Y', strtotime('-1 day')); ?>" class="prog-prev<?php echo strtotime('-1 day') < mktime(0,0,0,10,24,11) ? ' hide' : '' ; ?>">&nbsp;</a>
											<span id="dataFilter" class="data dataFilter" rel="<?php echo date('d-m-Y'); ?>"><?php echo date('d\/m') . ' - ' . Utility::data_extenso_texto(date('N')); ?></span>
											<a href="#<?php echo date('d-m-Y', strtotime('+1 day')); ?>" class="prog-next<?php echo strtotime('+1 day') > mktime(23,59,59,11,19,11) ? ' hide' : '' ; ?>">&nbsp;</a>
										</div>
										
										<div id="containerDivPrincipal" class="scroll-pane">
											
											<?php if(empty($arrDivPrincipal)) : ?>
												<div class="item itemNenhumaProva">
													<p>Nenhuma prova cadastrada.</p>
												</div>
								
											<?php else : foreach($arrDivPrincipal as $i=>$item) : ?>
												<div class="item">
													<span class="icon"><img src="/_img/modalidades/home_cat<?php echo htmlspecialchars($item->id_modalidade); ?>.png" alt="<?php echo htmlspecialchars($item->modalidade); ?>" /></span>
													<p>
														<span><b><?php echo date('H:i', htmlspecialchars($item->data)); ?>h: <?php echo htmlspecialchars($item->modalidade); ?> -
															<?php echo isset($item->provas) ? htmlspecialchars($item->provas) : ''; ?> (<?php echo htmlspecialchars($item->descricao); ?>)</b></span>
														<span><b>Categoria:</b> <?php echo htmlspecialchars($item->sexo); ?> / <?php echo htmlspecialchars($item->categoria); ?></span>
														<span><b>Local:</b> <?php echo htmlspecialchars($item->local); ?></span>
														<span><?php echo str_replace($cidade->nome, "<strong>{$cidade->nome}</strong>", $item->cidades); ?></span>
													</p>
													<br clear="all" />
												</div>
											<?php endforeach; endif; ?>
										</div>
									</div>
					
								<?php endif; if(in_array('3', $divisaoCidade)) : ?>
					
									<div class="tab" id="box3a">
										<div class="prog-title-cidades prog-title">
											<a href="#<?php echo date('d-m-Y', strtotime('-1 day')); ?>" class="prog-prev<?php echo strtotime('-1 day') < mktime(0,0,0,10,24,11) ? ' hide' : '' ; ?>">&nbsp;</a>
											<span id="dataFilter" class="data dataFilter" rel="<?php echo date('d-m-Y'); ?>"><?php echo date('d\/m') . ' - ' . Utility::data_extenso_texto(date('N')); ?></span>
											<a href="#<?php echo date('d-m-Y', strtotime('+1 day')); ?>" class="prog-next<?php echo strtotime('+1 day') > mktime(23,59,59,11,19,11) ? ' hide' : '' ; ?>">&nbsp;</a>
										</div>
										<div id="containerDivEsp" class="scroll-pane">
											<?php if(empty($arrDivEsp)) : ?>
												<div class="item itemNenhumaProva">
													<p>Nenhuma prova cadastrada.</p>
												</div>
											
											<?php else : foreach($arrDivEsp as $i=>$item) : ?>
												<div class="item">
													<span class="icon"><img src="/_img/modalidades/home_cat<?php echo htmlspecialchars($item->id_modalidade); ?>.png" alt="<?php echo htmlspecialchars($item->modalidade); ?>" /></span>
													<p>
														<span><b><?php echo date('H:i', htmlspecialchars($item->data)); ?>h: <?php echo htmlspecialchars($item->modalidade); ?> -
															<?php echo isset($item->provas) ? htmlspecialchars($item->provas) : ''; ?> (<?php echo htmlspecialchars($item->descricao); ?>)</b></span>
														<span><b>Categoria:</b> <?php echo htmlspecialchars($item->sexo); ?> / <?php echo htmlspecialchars($item->categoria); ?></span>
														<span><b>Local:</b> <?php echo htmlspecialchars($item->local); ?></span>
														<span><?php echo str_replace($cidade->nome, "<strong>{$cidade->nome}</strong>", $item->cidades); ?></span>
													</p>
													<br clear="all" />
												</div>
											<?php endforeach; endif; ?>
										</div>
									</div>
									
								<?php endif; if(in_array('4', $divisaoCidade)) : ?>
									<div class="tab" id="box4a">
										<div class="prog-title-cidades prog-title">
											<a href="#<?php echo date('d-m-Y', strtotime('-1 day')); ?>" class="prog-prev<?php echo strtotime('-1 day') < mktime(0,0,0,10,24,11) ? ' hide' : '' ; ?>">&nbsp;</a>
											<span id="dataFilter" class="data dataFilter" rel="<?php echo date('d-m-Y'); ?>"><?php echo date('d\/m') . ' - ' . Utility::data_extenso_texto(date('N')); ?></span>
											<a href="#<?php echo date('d-m-Y', strtotime('+1 day')); ?>" class="prog-next<?php echo strtotime('+1 day') > mktime(23,59,59,11,19,11) ? ' hide' : '' ; ?>">&nbsp;</a>
										</div>
										<div id="containerDivEsp" class="scroll-pane">
											<?php if(empty($arrDivExt)) : ?>
												<div class="item itemNenhumaProva">
													<p>Nenhuma prova cadastrada.</p>
												</div>
											
											<?php else : foreach($arrDivExt as $i=>$item) : ?>
												<div class="item">
													<span class="icon"><img src="/_img/modalidades/home_cat<?php echo htmlspecialchars($item->id_modalidade); ?>.png" alt="<?php echo htmlspecialchars($item->modalidade); ?>" /></span>
													<p>
														<span><b><?php echo date('H:i', htmlspecialchars($item->data)); ?>h: <?php echo htmlspecialchars($item->modalidade); ?> -
															<?php echo isset($item->provas) ? htmlspecialchars($item->provas) : ''; ?> (<?php echo htmlspecialchars($item->descricao); ?>)</b></span>
														<span><b>Categoria:</b> <?php echo htmlspecialchars($item->sexo); ?> / <?php echo htmlspecialchars($item->categoria); ?></span>
														<span><b>Local:</b> <?php echo htmlspecialchars($item->local); ?></span>
														<span><?php echo str_replace($cidade->nome, "<strong>{$cidade->nome}</strong>", $item->cidades); ?></span>
													</p>
													<br clear="all" />
												</div>
											<?php endforeach; endif; ?>
										</div>
									</div>
								<?php endif; ?>
							</div>
							
							<a class="mais-prog" href="/programacao/programacao/" title="Ver programacao completa">Ver programacao completa</a>
				
						<?php endif; ?>
					</div>
		  
					<?php if($noticias !== FALSE) : ?>
						<div class="partNoticias">
							<h2>Notícias Relacionadas
								<div class="sombra"></div>
							</h2>
							<ul>
								<?php foreach($noticias as $noticia) : ?>
									<li>
										<span class="data"><?php echo htmlspecialchars($noticia->data); ?></span>
										<a href="/noticia/<?php echo htmlspecialchars($noticia->permalink); ?>" class="img">
											<img src="<?php echo htmlspecialchars(str_replace('/upload/', '/upload/thumbMid/', $noticia->imagemThumb)); ?>" alt="<?php echo htmlspecialchars($noticia->titulo); ?>" />
										</a>
										<div class="boxTxt">
											<span class="categoria"><a href="/categoria/<?php echo htmlspecialchars($noticia->categoria_permalink); ?>" title=""><?php echo htmlspecialchars($noticia->categoria_titulo); ?></a></span>
											<span class="titulo">
												<a href="/noticia/<?php echo htmlspecialchars($noticia->permalink); ?>" title="<?php echo htmlspecialchars($noticia->titulo); ?>"><?php echo htmlspecialchars(Utility::limit_string($noticia->titulo, 120)); ?></a>
											</span>
										</div>
										<br clear="all" />
									</li>
								<?php endforeach; ?>
							</ul>
							<br clear="all" />
							<a href="/busca/<?php echo htmlspecialchars($cidade->nome); ?>" class="mais" title="mais notícias de <?php echo htmlspecialchars($cidade->nome); ?>">mais notícias de <?php echo htmlspecialchars($cidade->nome); ?></a>
						</div>
					<?php endif; ?>
					</div>
				<br clear="all" />
			</form>
		</div>
    </div>
        
        <div class="veja_tambem">
            <h3>Veja Também</h3>
            <div class="item">
                <h4><a href="/jogos/alojamentos">Alojamentos</a></h4>
                <div class="imagem">
                    <a href="/jogos/alojamentos"><img src="/_img/ft_veja_alojamentos.jpg" width="210" height="84" alt="Imagem 1" /></a>
                </div>
                <p><a href="/jogos/alojamentos">Encontre o alojamento em que a delegação de sua cidade ficará hospedada.</a></p>
            </div>
            <div class="item">
                <h4><a href="/jogos/o-evento">O Evento</a></h4>
                <div class="imagem">
                    <a href="/jogos/o-evento"><img src="/_img/ft_veja_evento.jpg" width="210" height="84" alt="Imagem 1" /></a>
                </div>
                <p><a href="/jogos/o-evento">Os Jogos Abertos do Interior chegaram a Mogi. Saiba mais aqui</a></p>
            </div>
            <div class="item">
                <h4><a href="/noticias">notícias</a></h4>
                <div class="imagem">
                    <a href="/noticias"><img src="/_img/ft_veja_noticias.jpg" width="210" height="84" alt="Imagem 1" /></a>
                </div>
                <p><a href="/noticias">Saiba tudo o que acontece na maior festa esportiva da América Latina.</a></p>
            </div>
            
        </div>
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
	<br clear="all" />
    </div>
    <input type="hidden" id="nomeCidade" value="<?php echo $cidade->nome; ?>" />
    <input type="hidden" id="idCidade" value="<?php echo $cidade->id; ?>" />
</body>
</html>
<?php endif; $cache->close(); ?>