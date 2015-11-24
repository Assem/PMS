$(function() {
	if (window.PMS === undefined) {
        window.PMS = {};
    }
    var PMS = window.PMS;
    PMS.geolocation = {
    	onPositionUpdate: function (position) {
    		$('input[name="position"]').val(position.coords.latitude + ',' + position.coords.longitude);
    		$('input[name="geo_error"]').val('');
        },
        showError: function (error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    $('input[name="geo_error"]').val("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    $('input[name="geo_error"]').val("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    $('input[name="geo_error"]').val("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    $('input[name="geo_error"]').val("An unknown error occurred.");
                    break;
            }
            
            $('input[name="position"]').val('');
        }
	};
    
    if(navigator.geolocation)
        navigator.geolocation.getCurrentPosition(PMS.geolocation.onPositionUpdate, PMS.geolocation.showError);
    else
        alert("navigator.geolocation is not available");
    
    $('#sheet_form').submit(function( event ) {
    	return confirm("Êtes-vous sûr de vouloir valider cette fiche (la validation est définitive)?");
	});
    
    $('.back1').click(function( event ) {
    	return confirm("Êtes-vous sûr de vouloir quitter cette fiche et revenir à l'édition du repondant (vous perderez toutes les informations saisies)?");
	});
    
    $('.back2').click(function( event ) {
    	return confirm("Êtes-vous sûr de vouloir quitter cette fiche (ceci supprimera toutes les données saisies)?");
	});
});