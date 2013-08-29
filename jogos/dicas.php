<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="Aproveite sua estadia em Mogi das Cruzes, cidade-sede dos Jogos Abertos do Interior 2011. Tenha em mãos números de telefones, sites úteis e descubra locais para visitar." />
<meta name="keywords" content="JAI, Jogos, Abertos, Mogi, interior, olimpíadas, esporte, 2011, Sites, telefones, dicas, locais, visitar, guia, viagem" />
<meta name="robots" content="index,follow" />
<meta http-equiv="content-language" content="pt-br" />
<meta name="author" content="Tboom Interactive"  />
<title>Dicas | Jogos Abertos do Interior 2011 </title>
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
	createItem = function(nome, categoria){
		$('#esportes').append('<li><a href="#" class="'+categoria+'">'+nome+'</a></li>');
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
					image = '<img src="/'+image+'" width="80" height="80" align="left" style="margin:0 10px 0 0;" />';
				}
				var marker = createMarker(
					markers[i].getAttribute("name"),
					markers[i].getAttribute("info"),
					image,
					icone,
					latlng,
					id
				);
				createItem(markers[i].getAttribute("name"), id);
       		}
			if(markers.length < 1){
				map.setCenter(latlng);
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
	function selecionaPins(id, nome, ponto){
		var marca = 0;
		if (arrayMarkers[id]) {
			for (i in arrayMarkers[id]) {
				arrayMarkers[id][i].setMap(null);
			}
		}
		downloadUrl('/mapa/'+id+'.xml', function(data) {
			var markers = data.documentElement.getElementsByTagName(id);
			for (var i = 0; i < markers.length; i++) {
				var latlng = new google.maps.LatLng(
						parseFloat(markers[i].getAttribute("lat")),
					parseFloat(markers[i].getAttribute("lng"))
					);
					pontoSel = markers[i].getAttribute('id');
					if(pontoSel == ponto){
						arrayMarkers[id][i].setMap(map);
						marca = marca+1;
					}
			}
			if(num < 2){
				map.setCenter(latlng);
			}else{
				map.setCenter(new google.maps.LatLng(-23.526029,-46.188698));				
			}
		});
	}

	teste = function(){
		$('#dicas li').find('a').click(function(){
			id = $(this).attr('class');
			nome = $(this).html();
			ponto = $(this).attr('id');
			selecionaPins(id, nome, ponto);
			return false;
		});
	}
jQuery(function($) {
	initialize(-23.519734, -46.201057, 11);
	//createMarkersMaps('/mapa/parque.xml', '/_img/pin_dicas.png', 'parque');
	createMarkersMaps('/mapa/turismo.xml', '/_img/pin_dicas.png', 'turismo');
	window.setTimeout(teste, 1000);
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
        	Dicas 	
        </h1>
        <div class="page locais_comp">
            <div class="mapa" id="mapa">
            	<img src="/_img/mapa_ilustracao.jpg" width="634" height="639" alt="Mapa" />
            </div>
            <div class="info_mapa">
				<h2 class="guia_dicas">Dicas</h1>
            	<p>Tudo do que você precisa saber para aproveitar ao máximo sua estadia em Mogi das Cruzes para os Jogos Abertos 2011. Tenha em mãos telefones e sites úteis, além de uma lista com locais superinteressantes para visitar. Uma mão na roda pra você acertar sem precisar escolher no chute.</p>
				<ul class="esportes dicas" id="dicas">
					<li><a href="#" class="turismo" id="pq_01">Parque Centenário</a></li>
					<li><a href="#" class="turismo" id="pq_02">Parque Leon Feffer</a></li>
					<li><a href="#" class="turismo" id="pq_03">Parque Municipal Botyra</a></li>
					<li><a href="#" class="turismo" id="tur_01">Igreja do Carmo</a></li>
					<li><a href="#" class="turismo" id="tur_02">Igreja São Benedito</a></li>
					<li><a href="#" class="turismo" id="tur_03">Igreja Matriz de Sant'Anna</a></li>
					<li><a href="#" class="turismo" id="tur_04">Theatro Vasques</a></li>
					<li><a href="#" class="turismo" id="tur_05">Feira Mogi Feito à Mão</a></li>
					<li><a href="#" class="turismo" id="tur_06">Ilha Marabá</a></li>
					<li><a href="#" class="turismo" id="tur_07">Mercado Municipal</a></li>
					<li><a href="#" class="turismo" id="tur_08">Mercado do Produtor</a></li>
					<li><a href="#" class="turismo" id="tur_09">Casarão do Carmo</a></li>
					<li><a href="#" class="turismo" id="tur_10">Museu Histórico</a></li>
					<li><a href="#" class="turismo" id="tur_11">Corporação Musical Santa Cecília</a></li>
					<li><a href="#" class="turismo" id="tur_12">Centro de Cultura e Memória</a></li>
					<li><a href="#" class="turismo" id="tur_13">Casarão do Chá</a></li>
					<li><a href="#" class="turismo" id="tur_14">Pico do Urubu</a></li>
					<li><a href="#" class="turismo" id="tur_15">Distrito de Sabaúna</a></li>
					<li><a href="#" class="turismo" id="tur_16">Distrito de Taiaçupeba</a></li>
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
		<br clear="all" />
    </div>
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>
