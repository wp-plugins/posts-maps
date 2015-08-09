var geocoder;
var map;
var markers = [];
function initialize(pmMap) {
	var Latlng = new google.maps.LatLng(pmMap.lat,pmMap.lng);
	var mapOptions = {
		zoom: 8,
		center: Latlng
	}
	var map = new google.maps.Map(document.getElementById('pm-map'), mapOptions);
	var contentString = '<h2>' + pmMap.title + '</h2>';
	if(pmMap.img) {
		contentString += '<img alt="' + pmMap.title + '" title="' + pmMap.title + '" src="' + pmMap.img + '"/>';
	}
	var infowindow = new google.maps.InfoWindow({
		content: contentString
	});
	var marker = new google.maps.Marker({
		position: Latlng,
		map: map,
		title: pmMap.title,
		icon: pmMap.markerIcon
	});
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
}

function initializeMultiple(pmMap) {
	var Settings = getMapSettings(pmMap);
	console.log(Settings);
	if(!Settings) {
		Settings = {};
		Settings.zoom = 12;
		Settings.lat = pmMap[0].lat;
		Settings.lng = pmMap[0].lng;
	}
	var Latlng = new google.maps.LatLng(Settings.lat,Settings.lng);
	var mapOptions = {
		zoom: Settings.zoom,
		center: Latlng
	}
	var map = new google.maps.Map(document.getElementById('pm-map'), mapOptions);

	var infowindow = new google.maps.InfoWindow();
	var windowСontent = [];
	var marker, i;
	for(i = 0; i < pmMap.length; i++) {
		var Latlng = new google.maps.LatLng(pmMap[i].lat,pmMap[i].lng);
		windowСontent[i] = '<h2><a href="' + pmMap[i].url + '" title="' + pmMap[i].title + '">' + pmMap[i].title + '</a></h2>';
		if(pmMap[i].thumbUrl) {
			windowСontent[i] += '<img alt="' + pmMap[i].title + '" title="' + pmMap[i].title + '" src="' + pmMap[i].thumbUrl + '"/>';
		}
		marker = new google.maps.Marker({
			position: Latlng,
			map: map,
			icon: pmMap[i].postMarkerIcon
		});

	    google.maps.event.addListener(marker, 'click', (function(marker, i) {
	        return function() {
	          infowindow.setContent(windowСontent[i]);
	          infowindow.open(map, marker);
	        }
	    })(marker, i));
	}
}

function getMapSettings(pmMap) {
	var points = {};
	var settings = {};
	if(pmMap.length && pmMap.length > 0) {
		var GLOBE_WIDTH = 256;
		points.lat = [];
		points.lng = [];
		points.lat['min'] = parseFloat(pmMap[0].lat);
		points.lat['max'] = parseFloat(pmMap[0].lat);
		points.lng['min'] = parseFloat(pmMap[0].lng);
		points.lng['max'] = parseFloat(pmMap[0].lng);
		for(var i = 0; i < pmMap.length; i++) {
			if(parseFloat(pmMap[i].lat) < points.lat['min']) {
				points.lat['min'] = parseFloat(pmMap[i].lat);
			}
			if(parseFloat(pmMap[i].lat) > points.lat['max']) {
				points.lat['max'] = parseFloat(pmMap[i].lat);
			}
			if(parseFloat(pmMap[i].lng) < points.lng['min']) {
				points.lng['min'] = parseFloat(pmMap[i].lng);
			}
			if(parseFloat(pmMap[i].lng) > points.lng['max']) {
				points.lng['max'] = parseFloat(pmMap[i].lng);
			}
		}
		settings.lat = parseFloat(((points.lat['max'] + 1) + (points.lat['min'] - 1)) / 2);
		settings.lng = parseFloat(((points.lng['max'] + 1) + (points.lng['min'] - 1)) / 2);
		var angle = points.lng['max'] - points.lng['min'];
		if(angle < 0) {
			angle += 360;
		}
		settings.zoom = Math.round(Math.log(parseInt(jQuery('#pm-map').width()) * 360 / angle / GLOBE_WIDTH) / Math.LN2) - 2;
		if(settings.zoom == 'Infinity')
			settings.zoom = 8;
		return settings;
	}
	return;
}

function adminInitialize(pmMap) {
	geocoder = new google.maps.Geocoder();
	var Latlng = new google.maps.LatLng(pmMap.lat,pmMap.lng);
	var mapOptions = {
		zoom: 8,
		center: Latlng
	}
	map = new google.maps.Map(document.getElementById('pm-map'), mapOptions);
	var marker = new google.maps.Marker({
		position: Latlng,
		map: map
	});
    markers.push(marker);

	google.maps.event.addListener(map, "rightclick", function(event) {
	    var lat = event.latLng.lat();
	    var lng = event.latLng.lng();
	    if(jQuery('#pm_lat').val() == '' && jQuery('#pm_lng').val() == '') {
	    	adminSetPoint(lat, lng);
		}
		else {
			if(confirm(pmMap.changeText + ' (' + lat + ',' + lng + ')')) {
				adminSetPoint(lat, lng);
			}
		}
	});
	google.maps.event.addListener(marker, "rightclick", function(event) {
	    var lat = event.latLng.lat();
	    var lng = event.latLng.lng();
	    if(jQuery('#pm_lat').val() == '' && jQuery('#pm_lng').val() == '') {
	    	adminSetPoint(lat, lng);
		}
		else {
			if(confirm(pmMap.changeText + ' (' + lat + ',' + lng + ')')) {
				adminSetPoint(lat, lng);
			}
		}
	});

	if(jQuery('#pm_lat').val() == '' && jQuery('#pm_lng').val() == '') {
		var defaultBounds = new google.maps.LatLngBounds(
	    	new google.maps.LatLng(-33.8902, 151.1759),
	    	new google.maps.LatLng(-33.8474, 151.2631));
		map.fitBounds(defaultBounds);
	}

	var input = (document.getElementById('search-input'));
	map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
	var searchBox = new google.maps.places.SearchBox((input));

	google.maps.event.addListener(searchBox, 'places_changed', function() {
	    var places = searchBox.getPlaces();
	    if (places.length == 0) {
	      return;
	    }

	    for (var i = 0, marker; marker = markers[i]; i++) {
	      marker.setMap(null);
	    }

	    markers = [];
	    var bounds = new google.maps.LatLngBounds();
	    for (var i = 0, place; place = places[i]; i++) {
	      var marker = new google.maps.Marker({
	        map: map,
	        title: place.name,
	        position: place.geometry.location
	      });
	      markers.push(marker);

		  google.maps.event.addListener(marker, "rightclick", function(event) {
		    var lat = event.latLng.lat();
		    var lng = event.latLng.lng();
		    if(jQuery('#pm_lat').val() == '' && jQuery('#pm_lng').val() == '') {
		    	adminSetPoint(lat, lng);
			}
			else {
				if(confirm(pmMap.changeText + ' (' + lat + ',' + lng + ')')) {
					adminSetPoint(lat, lng);
				}
			}
		  });

	      bounds.extend(place.geometry.location);
	    }
	    map.fitBounds(bounds);
	});

	google.maps.event.addListener(map, 'bounds_changed', function() {
	    var bounds = map.getBounds();
	    searchBox.setBounds(bounds);
	});
}

function adminSetPoint(lat, lng) {
	jQuery('#pm_lat').val(lat);
	jQuery('#pm_lng').val(lng);
	for (var i = 0, marker; marker = markers[i]; i++) {
		marker.setMap(null);
	}

	jQuery('#pm-map').after('<input type="text" name="search-input" id="search-input" value="" style="margin: 10px 10px 0px;border:0 none;line-height: 28px;border-radius: 2px;width: 25%;min-width: 100px;"/>');

	var mapObj = {};
	mapObj.lat = lat;
	mapObj.lng = lng;
	adminInitialize(mapObj);
}

jQuery(function($) {
	$(document).on('keydown', '#search-input', function(event) {
		if(event.which == 13) {
			event.preventDefault();
		}
	});

	$(document).on('click', '#pm_plus_button', function(event) {
		var numEl = parseInt($('#pm_count_icons').val());
		var html = 	'<tr>' +
					'<th scope="row"><input type="text" name="marker_icon_name_' + numEl + '" value="" /></th>' +
					'<td>' +
						'<fieldset>' +
							'<input type="file" name="marker_icon_img_' + numEl + '" value="" />' +
						'</fieldset>' + 
					'</td>' +
					'</tr>';
		$('.form-table tbody').append(html);
		$('#pm_count_icons').val(++numEl);
	});
});