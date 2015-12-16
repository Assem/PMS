var UPDATING = false; //flag indicating if a data update is running
var refreshTimeout = false;

var markers = [];
var maps = [];

//add map api
function loadMapScript() {
  var script = document.createElement("script");
  script.src = "//maps.googleapis.com/maps/api/js?key="+GOOGLE_MAP_KEY+"&callback=initializeMap&language=fr-FR";
  document.body.appendChild(script);
}

//create the maps and start getting data
function initializeMap() {
	var mapOptions = {
		center: new google.maps.LatLng(48.8588589, 2.3470599),
		zoom: 8
	};
	
	for(var i=0; i < mapsids.length; i++) {
		var cc = document.getElementById(mapsids[i].id + '-map');
		maps[mapsids[i].id] = new google.maps.Map(document.getElementById(mapsids[i].id + '-map'), mapOptions);
		
		//create search input
		var drawRouteControlDiv = document.createElement('div');
		new createControl(drawRouteControlDiv, mapsids[i].id);
	
		drawRouteControlDiv.index = 1;
		maps[mapsids[i].id].controls[google.maps.ControlPosition.TOP_CENTER].push(drawRouteControlDiv);
	}
	
	// initialize data (we wait for 1 sec to give the tabs the time to load)
	refreshTimeout = setTimeout(function(){ 
		startDrawingData();
	}, 1000);
}

//we remove all markers and redraw new ones
function startDrawingData() {
	//if no update is running than we proceed
	if(!UPDATING) {
		if(refreshTimeout) {
			clearTimeout(refreshTimeout);
		}
		UPDATING = true;
		clearMap();
		
		var active = $( TABS ).tabs( "option", "active" );
		if(active === parseInt(active, 10)) {
			retrieveData(data_url + '/' + mapsids[active].id, maps[mapsids[active].id], mapsids[active].id);
		}
	}
}

//we use ajax request to the Backend to get data
function retrieveData(url, map, id) {
	$.ajax({
		url: url,
		dataType: 'json',
		success: function(result) {
			if(result && result.length > 0) {
				updateMap(map, result, id);
		    } else {
		    	var searchInputID = "#" + id + 'seach';
		    	if($(searchInputID).hasClass("ui-autocomplete-input")) {
					$(searchInputID).autocomplete( "close" );
					$(searchInputID).autocomplete("option", "source", []);
				}
		    }
			
			//we plan the next update of data
			nextUpdate();
	  	},
	  	error: function(jqXHR, textStatus, errorThrown) {
	  		if(jqXHR.responseText.indexOf("login_string") > -1) { 
	  			// the problem here is that the user was disconnected, so we reload login page
	  			location.href = '/';
	  		}
	  		var d = new Date();
	  		var t = d.getHours() + 'h' + d.getMinutes();
	  		$('#localisations-errors').append(t + " : impossible de récupérer la liste des localisations<br>");
	  		
	  		//we plan the next update of data
	  		nextUpdate();
	  	}
	});
}

// we plan the next update depending on the Update interval config
function nextUpdate() {
	UPDATING = false;
	refreshTimeout = setTimeout(function(){ 
		startDrawingData(); 
	}, UPDATE_INTERVAL * 1000);
}

function updateMap(map, result, id) {
	clearMap();
	
	var searchData = [];
	var bounds = new google.maps.LatLngBounds();
	
	for(var i=0; i<result.length; i++) {
		var vData = result[i];
		bounds.extend(getLatLongObjectFromLocalisation(vData));
		
		drawMarker(vData, map);
		searchData.push({
			label: "[" + vData.pms_user_code + "] " + vData.pms_user_last_name.toUpperCase() + ' ' + vData.pms_user_first_name,
			value: vData.latitude + ':' + vData.longitude
		});
	}
	
	//we centrate the map so that we can see all the markers (only if it's the 1st view)
	if(!map.firstShow) {
		map.firstShow = 1;
		map.fitBounds(bounds);
	}
	
	//update search data
	var searchInputID = "#" + id + 'seach';
	if($(searchInputID).hasClass("ui-autocomplete-input")) {
		$(searchInputID).autocomplete( "close" );
		$(searchInputID).autocomplete("option", "source", searchData);
	} else {
		$(searchInputID).autocomplete({
			source: searchData,
			select: function( event, ui ) {
				//when we select a value, we centrate and zoom the map on the selected marker
				event.preventDefault();
				var val = ui.item.value.split(':');
				$(searchInputID).val(ui.item.label);
				map.setCenter(new google.maps.LatLng(Number(val[0]), Number(val[1])));
				map.setZoom(16);
			}
		});
	}
}

function drawMarker(vhData, map) {
	var icon;
	var al = '<div class="marker-tip">' + vhData.creation_date_formatted + ' => <b>' + vhData.since + "min.</b><br>" + 
			"[" + vhData.pms_user_code + "] " + vhData.pms_user_last_name.toUpperCase() + ' ' + vhData.pms_user_first_name +
			'</div>';
	var moving = '1';
	if(vhData.since > IDLE_TIME) {
		moving = '0';
	}
	
	icon = createPin("agent-"+moving+".png", new google.maps.Size(15, 20));
	
	var marker = new google.maps.Marker({
		position: getLatLongObjectFromLocalisation(vhData),
		icon: icon,
		map: map
	});
	markers.push(marker);
	
	var infoWindow = new google.maps.InfoWindow({
		content: al,
		disableAutoPan: true
	});
	
	google.maps.event.addListener(marker, 'mouseover', function() {
	    infoWindow.open(map, marker);
	});
}

//clear and remove a map object
function clearObject(object) {
	if(object != undefined) {
		object.setMap(null);
		object = null;
	}
}

function createPin(icon, size) {
	var pinImage = new google.maps.MarkerImage(
		"assets/img/" + icon,
        null,
        null,
        null,
        size
    );

    return pinImage;
}

function createPinGoogle(color, letter) {
	var pinImage = new google.maps.MarkerImage(
		"http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld="+ letter +"|" + color,
        null,
        null,
        null,
        new google.maps.Size(30, 50)
    );

    return pinImage;
}

//convert a Localisation to a LatLng object
function getLatLongObjectFromLocalisation(localistion) {
	return new google.maps.LatLng(Number(localistion.latitude), Number(localistion.longitude));
}

function clearMap() {
	//remove old markers
	for (var i = 0; i < markers.length; i++) {
		clearObject(markers[i]);
	}
	markers = [];
}

//create search input
function createControl(controlDiv, id) {
	controlDiv.style.padding = '5px';
	
	var searchInput = document.createElement('input');
	searchInput.type = 'text';
	searchInput.id = id + 'seach';
	searchInput.className = 'search rounded';
	searchInput.placeholder = 'Recherche';
	controlDiv.appendChild(searchInput);
}

window.onload = loadMapScript;