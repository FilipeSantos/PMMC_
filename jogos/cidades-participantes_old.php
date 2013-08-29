<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/Cache.class.php');
	$cache = new Cache();
	$cache->set_arquivo('page_' . $_SERVER['REQUEST_URI']);
	$cache->set_tempo(10080);
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
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Conheça a casa dos Jogos Abertos do Interior 2011. Navegue pelo mapa interativo da cidade, e veja toda a infraestrutura preparada para receber o evento." />
<meta name="keywords" content="Mapa, Mogi, Jogos, Abertos, pontos, turísticos, interior, SP, localização, Casa, infraestrutura" />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title><?php echo isset($cidade) && $cidade !== FALSE ? "$cidade->nome | " : ''; ?>Cidades Participantes | Jogos Abertos do Interior 2011 </title>
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
<script type="text/javascript" src="/_js/jquery.scrollpane.js"></script>
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
});

$(document).ready(function(){  });
</script>

</head>

<body>
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
	                        <h2 class="subtitle">Colocação nos Jogos de 2010:</h2>
				<?php if(!$cidade->colocacao_2010 || !$cidade->divisao_2010) : ?>
					<h2 class="subtitle2">(não participou)</h2>
				<?php else : ?>
					<h2 class="subtitle2"><?php echo $cidade->colocacao_2010; ?>º lugar - <?php echo $cidade->divisao_2010; ?>ª divisão</h2>
				<?php endif; ?>
				
				<?php if($cidade->modalidades != NULL) : ?>
					<h2 class="subtitle subtitleMod">Modalidades Participantes:</h2>
					<h2 class="subtitle2"><?php echo $cidade->divisao; ?>ª divisão</h2>			
					<ul id="listParticipantesModalidades" class="listPartModalidades">
						<?php foreach($cidade->modalidades as $mod) : ?>
							<li>
							    <a id="btnPart<?php echo $mod->id; ?>" href="/modalidade/<?php echo $mod->slug; ?>"><?php echo $mod->titulo; ?></a>
							    <div class="tooltip">
								<div class="top">&nbsp;</div>
								<div class="content"><?php echo $mod->titulo; ?></div>
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
							    <a id="btnPart<?php echo $mod->id; ?>" href="/modalidade/<?php echo $mod->slug; ?>"><?php echo $mod->titulo; ?></a>
							    <div class="tooltip">
								<div class="top">&nbsp;</div>
								<div class="content"><?php echo $mod->titulo; ?></div>
							    </div>
							</li>
						<?php endforeach; ?>
					</ul>
					<br clear="all" />
				<?php endif; if($cidade->modalidades_pcd != NULL) : ?>
					<h2 class="subtitle2">PCD</h2>
					<ul id="listParticipantesModalidades2" class="listPartModalidades">
						<?php foreach($cidade->modalidades_pcd as $mod) : ?>
							<li>
							    <a id="btnPart<?php echo $mod->id; ?>" href="/modalidade/<?php echo $mod->slug; ?>"><?php echo $mod->titulo; ?></a>
							    <div class="tooltip">
								<div class="top">&nbsp;</div>
								<div class="content"><?php echo $mod->titulo; ?></div>
							    </div>
							</li>
						<?php endforeach; ?>
					</ul>
					<br clear="all" />
				<?php endif; ?>
	        </div>
			<div class="l">
	            <div class="bgGray"></div>
	            <div class="flag">
	                <h2 class="subtitle subtitleFlag"><img src="/_img/cidade/bandeiras/<?php echo $cidade->bandeira; ?>" alt="<?php echo htmlspecialchars($cidade->nome); ?>"/><?php echo $cidade->nome; ?></h2>
	            </div>
			    <h2 class="subtitle2 subtitle2Aloj">Alojamentos</h2>
		            <div class="mapa" id="mapa">
		                <img src="/_img/mapa_ilustracao.jpg" width="500" height="500" alt="Mapa" />
		            </div>
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
</body>
</html>
<?php endif; $cache->close(); ?>