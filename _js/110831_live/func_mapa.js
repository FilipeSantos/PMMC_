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
			/*if(markers.length < 1){
				map.setCenter(latlng);
			}*/
			if(markers.length < 1){
				map.setCenter(latlng);
			}else{
				map.setCenter(new google.maps.LatLng(-23.526029,-46.188698));				
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
		downloadUrl('mapa/competicao.xml', function(data) {
      		var markers = data.documentElement.getElementsByTagName('competicao');
      		for (var i = 0; i < markers.length; i++) {
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
					}
				}
				/*for(var j = 0; j < categoria.length; j++){
					if(categoria[j] == esporte){
						arrayMarkers[id][i].setMap(map);
					}else{
						arrayMarkers[id][i].setMap(null);
					}
				}*/
       		}
			/*if(marca == 1){
				map.setCenter(latlng);
			}*/
			if(marca == 1){
				map.setCenter(latlng);
			}/*else if (marca == 24 ){
				map.serCenter(new google.maps.LatLng(-23.530877,-46.409225));
				nivelZoom = 6;
			}*/else{
				map.setCenter(new google.maps.LatLng(-23.526029,-46.188698));				
			}
		});
	}
jQuery(function($) {
	initialize('', '', 12);
	
	$('.pins li').click(function(){
		id = $(this).attr('id');
		if($(this).data('acao') == 'falso' || !$(this).data('acao')){
			$(this).addClass('ativo').data('acao','esconder');
			createMarkersMaps('mapa/'+id+'.xml', '_img/maps/pin_'+id+'.png', id);
		}else if($(this).data('acao') == 'esconder'){
			$(this).removeClass('ativo').data('acao','mostrar');
			if (arrayMarkers[id]) {
				for (i in arrayMarkers[id]) {
					arrayMarkers[id][i].setMap(null);
				}
			}
		}else if($(this).data('acao') == 'mostrar'){
			$(this).addClass('ativo').data('acao','esconder');
			if (arrayMarkers[id]) {
				for (i in arrayMarkers[id]) {
					arrayMarkers[id][i].setMap(map);
				}
			}
		}
	});
	$('#competicao').click(function(){
		$('#select-local').toggle('slow');
		var select = $('#local');
		select.val($('options:first', select).val());
	});
	$('#select-local').fadeOut();
	$('#select-local select').animate({opacity:0}).change(function(){
		$('#select-local span').html($(this).find(':selected').text());
		selecionaPins($(this).val(), 'competicao');
	});
});