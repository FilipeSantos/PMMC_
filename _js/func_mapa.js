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
	function createMarkersMaps(arquivo, icone, id, callback){
		downloadUrl(arquivo, function(data) {
			var markers = data.documentElement.getElementsByTagName(id);
			var latlng = 0;
			var centerMap = 0;
			var totalLat = [];
			var totalLang = [];
			
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
					
					totalLat.push(parseFloat(markers[i].getAttribute("lat")));
					totalLang.push(parseFloat(markers[i].getAttribute("lng")));
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
			
			if(typeof callback == 'function'){
				callback.call(this, data);
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
      		var markers = data.documentElement.getElementsByTagName('competicao');
      		var latlng = 0;
		var centerMap = 0;
		var totalLat = [];
		var totalLang = [];
		
		for (var i = 0; i < markers.length; i++) {
			latlng = new google.maps.LatLng(
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
				/*for(var j = 0; j < categoria.length; j++){
					if(categoria[j] == esporte){
						arrayMarkers[id][i].setMap(map);
					}else{
						arrayMarkers[id][i].setMap(null);
					}
				}*/
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
	
	$('.pins li').click(function(){
		id = $(this).attr('id');
		if($(this).data('acao') == 'falso' || !$(this).data('acao')){
			$(this).addClass('ativo').data('acao','esconder');
			createMarkersMaps('/mapa/'+id+'.xml', '/_img/maps/pin_'+id+'.png', id);
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
	
	$('#body-mapa-interativo').each(function(){
		var $zoom = 0;
		if($(this).attr('rel')){
			var $rel = $(this).attr('rel');
			var select = $('#local');			
			$zoom = 12;
			$('#competicao').addClass('ativo').data('acao','esconder');
			$('#select-local').toggle('slow');
			createMarkersMaps('/mapa/competicao.xml', '/_img/maps/pin_competicao.png', 'competicao', function(){
				selecionaPins($rel, 'competicao');	
			});
			
		} else {
			$zoom = 10;
		}
		
		initialize('', '', $zoom);
	});
});