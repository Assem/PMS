//Il semble que quand on place des map dans des tabs, la carte ne se resize pas automatiquement
//donc il faut forcer le resize à chaque fois qu'un tab est sélectionné.
//Puis-que le resize ne se fait pas automatiquement, la carte ne se met pas aussi au bon centre,
//donc on force ça aussi juste la première fois car on veut pas recentrer la carte si l'utilisateur
//à déjà changé la vue (ceci n'est pas nécessaire pour le premier tab car affiché par défaut)
$(function() {
    var tabs = $( TABS ).tabs({
    	activate: function( event, ui ) {
    		var tabMap = maps[ui.newPanel[0].id];
    		var currCenter = tabMap.getCenter();
	    	google.maps.event.trigger( tabMap, 'resize' );
	    	if((firstTabId != ui.newPanel[0].id) && !tabMap.first) {
	    		tabMap.first = 1;
	    		tabMap.setCenter(currCenter);
	    	}
	    	
	    	startDrawingData();
        }
    });
    
    var firstTabId = $('.ui-tabs-panel:not(.ui-tabs-hide)').attr('id');
    
    //we update the list of recent sheets and geolocation errors
    setInterval(function() {
    	$( "#recent_sheets" ).load( "sheets/recent_sheets" );
    	$( "#locations_error" ).load( "geolocations/recent_errors" );
    }, UPDATE_INTERVAL * 1000);
});

var WindowsSize = function(){
	var h=$(window).height() - 230;
	$('.map-canvas').height(h+'px');
};

$(document).ready(function(){WindowsSize();}); 
$(window).resize(function(){WindowsSize();}); 