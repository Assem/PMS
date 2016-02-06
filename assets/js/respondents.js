$(function() {
	$( "#country_input" ).change(function(){
		$('#city_input').empty();
		
		var country_cities = cities[$( "#country_input" ).val()];
		var ids = Object.keys(country_cities);
		var values = ids.map(function (key) {
		    return country_cities[key];
		});
		
		for(var i=0; i<ids.length; i++) {
			$('#city_input').append('<option value="' + ids[i] + '">' + values[i] + '</option>');
		}
	});
});