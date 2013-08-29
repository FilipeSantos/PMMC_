<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title>Locais de Competição | Jogos Abertos do Interior 2011 </title>
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
<link rel="canonical" href="http://www.jogosabertos2011.com.br<?php echo $_SERVER['REQUEST_URI']; ?>" />
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="/_js/util.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
<script type="text/javascript" src="/_js/functions.js"></script>
<script type="text/javascript">
	var geocoder;
	var infowindow;
  	var map;
	var arquivo; //= 'moredata.xml';
	var coordMap1;// = 37.4419;
	var coordMap2;// = -122.1419;
	var icone; // = 'American-Football_128x128.png';
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
		
      		for (var i = 0; i < markers.length; i++) {
        		var latlng = new google.maps.LatLng(
					parseFloat(markers[i].getAttribute("lat")),
           	 		parseFloat(markers[i].getAttribute("lng"))
				);
				var image = '';
				if(markers[i].getAttribute("img")){
					image = markers[i].getAttribute("img");
					image = '<img src="'+image+'" width="80" height="80" align="left" style="margin:0 10px 0 0;" />';
				}
				var marker = createMarker(
					markers[i].getAttribute("name"),
					markers[i].getAttribute("info"),
					image,
					icone,
					latlng,
					id
				);
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
	function selecionaPins(esporte, id){
		var marca = 0;
		if (arrayMarkers[id]) {
			for (i in arrayMarkers[id]) {
				arrayMarkers[id][i].setMap(null);
			}
		}
		downloadUrl('/mapa/competicao.xml', function(data) {
			var num = 0;
			var markers = data.documentElement.getElementsByTagName('competicao');
			var latlng = 0;
			var centerMap = 0;
			var totalLat = [];
			var totalLang = [];
			
			for (var i = 0; i < markers.length; i++) {
					var latlng = 0;
				var latlng = new google.maps.LatLng(
						parseFloat(markers[i].getAttribute("lat")),
					parseFloat(markers[i].getAttribute("lng"))
					);
					categorias = markers[i].getAttribute("categoria");
					categoria = categorias.split(",");
					for(var j = 0; j < categoria.length; j++){
						if(categoria[j] == esporte){
							arrayMarkers[id][i].setMap(map);
							marca = marca+1;
							centerMap = latlng;
							totalLat.push(parseFloat(markers[i].getAttribute("lat")));
							totalLang.push(parseFloat(markers[i].getAttribute("lng")));
						}
					}
			}
			
			if(totalLat.length > 1 && totalLang.length > 1){
				totalLat.sort();
				totalLang.sort();
				totalLat = (totalLat.pop() + totalLat.shift()) / 2;
				totalLang = (totalLang.pop() + totalLang.shift()) / 2;
			} else {
				totalLat = totalLat.pop();
				totalLang = totalLang.pop();
			}

			centerMap = new google.maps.LatLng(
				totalLat,
				totalLang
			);

			map.setCenter(centerMap);
		});
	}
jQuery(function($) {
	initialize(-23.537991, -46.424904, 10);
	
	createMarkersMaps('/mapa/competicao.xml', '/_img/pin_locais.png', 'competicao');
	$('#esportes li').find('a').click(function(){
		id = $(this).attr('id');
		selecionaPins(id, 'competicao');
		return false;
	});
});
</script>
</head>

<body>
	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/bg.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/header.php') ?>
    <div class="content" id="content">
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/navbar.php') ?>
        <h1 class="title">
          <div class="sombra" style="margin:-4px 0 0 -24px; height:65px;"></div> 
        	Locais de competição 	
        </h1>
        <div class="page locais_comp">
            <div class="mapa" id="mapa">
            	<img src="/_img/mapa_ilustracao.jpg" width="634" height="639" alt="Mapa" />
            </div>
            <div class="info_mapa">
				<h2>Traga a sua torcida</h1>
            	<p>Descubra onde a sua equipe ou atleta favorito disputará um lugar no pódio do maior evento esportivo da América Latina. Consulte o endereço exato de ginásios, piscinas, estádios e todos os locais de competição das modalidades esportivas dos Jogos Abertos do Interior 2011.</p>
				<ul class="esportes" id="esportes">
					<li><a href="#" id="atletismo">Atletismo</a></li>
					<li><a href="#" id="basquete">Basquete</a></li>
					<li><a href="#" id="biribol">Biribol</a></li>
					<li><a href="#" id="bocha">Bocha</a></li>
					<li><a href="#" id="boxe">Boxe</a></li>
					<li><a href="#" id="capoeira">Capoeira</a></li>
					<li><a href="#" id="ciclismo">Ciclismo</a></li>
					<li><a href="#" id="damas">Damas</a></li>
					<li><a href="#" id="futebol">Futebol</a></li>
					<li><a href="#" id="futsal">Futsal</a></li>
					<li><a href="#" id="ginasticaartistica">Ginástica Artística</a></li>
					<li><a href="#" id="ginasticaritmica">Ginástica Rítmica</a></li>
					<li><a href="#" id="handebol">Handebol</a></li>
					<li><a href="#" id="judo">Judô</a></li>
					<li><a href="#" id="karate">Karatê</a></li>
					<li><a href="#" id="kickboxing">Kickboxing</a></li>
					<li><a href="#" id="lutaolimpica">Luta Olímpica</a></li>
					<li><a href="#" id="malha">Malha</a></li>
					<li><a href="#" id="natacao">Natação</a></li>
					<li><a href="#" id="taekwondo">Taekwondo</a></li>
					<li><a href="#" id="tenis">Tênis</a></li>
					<li><a href="#" id="tenisdemesa">Tênis de Mesa</a></li>
					<li><a href="#" id="volei">Vôlei</a></li>
					<li><a href="#" id="voleidepraia">Vôlei de Praia</a></li>
					<li><a href="#" id="xadrez">Xadrez</a></li>
				</ul>
           	</div>
        </div>
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
				<p><a href="/cidade/cidade-sede">Conheça de perto a casa da maior festa esportiva da América Latina.</a></p>
			</div>
			<div class="item">
				<h4><a href="/modalidades">modalidades</a></h4>
				<div class="imagem">
					<a href="/modalidades"><img src="/_img/ft_vejatambem3.jpg" width="210" height="84" alt="Imagem 1" /></a>
				</div>
				<p><a href="/modalidades">27 modalidades vão esquentar as disputas dos Jogos Abertos de 2011. Clique e conheça.</a></p>
			</div>
		</div>
		<br clear="all" />
    </div>
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>
