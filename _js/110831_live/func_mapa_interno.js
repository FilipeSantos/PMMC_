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
function createMarkersMaps(id, icone, esporte){
	downloadUrl('mapa/competicao.xml', function(data) {
		var markers = data.documentElement.getElementsByTagName(id);
		for (var i = 0; i < markers.length; i++) {
			categorias = markers[i].getAttribute("categoria");
			categoria = categorias.split(",");
			for(var j = 0; j < categoria.length; j++){
				if(categoria[j] == esporte){
					var latlng = new google.maps.LatLng(
						parseFloat(markers[i].getAttribute("lat")),
						parseFloat(markers[i].getAttribute("lng"))
					);
					var marker = createMarker(
						markers[i].getAttribute("name"),
						markers[i].getAttribute("info"),
						icone,
						latlng,
						id
					);
				}
			}
		}
		if(markers.length < 1){
			map.setCenter(latlng);
		}
	});
	markersArray = new Array;
}
function createMarker(name, info, icone, latlng, id) {
	var marker = new google.maps.Marker({position: latlng, map: map, icon:icone});
	markersArray.push(marker);
	arrayMarkers[id] = markersArray;
	google.maps.event.addListener(marker, "click", function() {
		html = '<h1 class="tit_maps">'+name+'</h1><br /><div class="info_maps">'+info+'</div>';
		if (infowindow) infowindow.close();
		infowindow = new google.maps.InfoWindow({
			content: html
		});
		infowindow.open(map, marker);
	});
	return marker;
}
