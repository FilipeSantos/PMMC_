<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/_inc/Cache.class.php');
	$cache = new Cache();
	$cache->set_arquivo('page_' . $_SERVER['REQUEST_URI']);
	$cache->set_tempo(0);
	$cache->start();
	
	if($cache->cached === FALSE):
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/_lib/Cidade.class.php'); ?>
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
<title>Alojamentos | Jogos Abertos do Interior 2011 </title>
<link rel="SHORTCUT ICON" href="/_img/favicon.ico">
<link rel="stylesheet" type="text/css" href="/_css/reset.css">
<link rel="stylesheet" type="text/css" href="/_css/style.css">
<link rel="stylesheet" type="text/css" href="/_css/style_2.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.countdown.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/_css/jquery.selectmenu.css">
<link rel="canonical" href="http://www.jogosabertos2011.com.br<?php echo $_SERVER['REQUEST_URI']; ?>" />
<script type="text/javascript" src="/_js/jquery.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.core.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="/_js/jquery.ui.selectmenu.js"></script>
<script type="text/javascript" src="/_js/jquery.jparallax.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="/_js/util.js"></script>
<script type="text/javascript" src="/_js/jquery.countdown.js"></script>
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
					parseFloat(markers[i].getAttribute("lat")),
					parseFloat(markers[i].getAttribute("lng"))
				);
				createMarker(markers[i].getAttribute("name"),
					markers[i].getAttribute("info") + "<br />" + markers[i].getAttribute("tel"),
					'',
					'/_img/pin_alojamentos.png',
					latlng,
					markers[i].getAttribute("id")
				)
				num++;
			}

			if(num < 2){
				map.setCenter(latlng);
			}else{
				map.setCenter(new google.maps.LatLng(-23.526029,-46.188698));				
			}
		});
	}
jQuery(function($) {
	initialize(-23.534754, -46.195134, 12);	
	createMarkersMaps('/mapa/alojamento.php', '/_img/pin_alojamentos.png', 'alojamento');	
	$('#alojamentos').selectmenu({ style:'dropdown',width: 300});
	$('select#alojamentos').change(function(){
		var $val = ($(this).val() != '' ? $(this).val() : false);
		selecionaPins($val, 'alojamento');
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
        	Alojamentos	
        </h1>
        <div class="page locais_comp alojamentos">
            <div class="mapa" id="mapa">
            	<img src="/_img/mapa_ilustracao.jpg" width="634" height="639" alt="Mapa" />
            </div>
            <div class="info_mapa">
            	<p>Mogi é grande em território e em hospitalidade. Por isso é bom ficar ligado na hora de procurar o alojamento da delegação de sua cidade. Se você é torcedor, aqui é perfeito para encontrar o destino das suas mensagens de motivação. Se for atleta, melhor ainda. Principalmente se estiver perdido.</p>
		<?php
			$cidades = Cidade::get_list_cidades(TRUE);
			if($cidades !== FALSE) :
		?>
			<form id="frmAlojamentos">                    
			    <select name="alojamentos" id="alojamentos" autocomplete="off">
				<option value="" selected="selected">Todas</option>
				<?php foreach($cidades as $cidade) : ?>
					<option value="<?php echo $cidade->id; ?>"><?php echo $cidade->nome; ?></option>
				<?php endforeach; ?>
			    </select>
			</form>
		<?php endif; ?>
           	</div>
        </div>
           
        <div class="veja_tambem">
            <h3>Veja Também</h3>
            <div class="item">
                <h4><a href="/jogos/dicas">Dicas</a></h4>
                <div class="imagem">
                    <a href="/jogos/dicas"><img src="/_img/ft_veja_dicas.jpg" width="210" height="84" alt="Imagem 1" /></a>
                </div>
                <p><a href="/jogos/dicas">As Informações práticas que você pode precisar para aproveitar melhor os Jogos.</a></p>
            </div>
            <div class="item">
                <h4><a href="/jogos/locais-de-competicao">locais de competição</a></h4>
                <div class="imagem">
                    <a href="/jogos/locais-de-competicao"><img src="/_img/ft_veja_locais.jpg" width="210" height="84" alt="Imagem 1" /></a>
                </div>
                <p><a href="/jogos/locais-de-competicao">Descubra onde serão os palcos das competições de sua modalidade favorita.</a></p>
            </div>
            <div class="item">
				<h4><a href="/jogos/historia-dos-jogos">história dos jogos</a></h4>
				<div class="imagem">
					<a href="/jogos/historia-dos-jogos"><img src="/_img/ft_vejatambem1.jpg" width="210" height="84" alt="Imagem 1" /></a>
				</div>
				<p><a href="/jogos/historia-dos-jogos">De 1936 a 2011, os fatos mais marcantes dos tradicionais Jogos Abertos.</a></p>
			</div>
        </div>
	<br clear="all" />
    </div>
    	<?php include($_SERVER['DOCUMENT_ROOT'] . '/_inc/footer.php'); ?>
    </div>
</body>
</html>
<?php endif; $cache->close(); ?>