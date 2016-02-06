var SETTINGS_TABS;
if (! localStorage.activetab) {
	localStorage.activetab = 0;
}

var towns_table;

$(function() {
	SETTINGS_TABS = $( '#settings' ).tabs({
    	activate: function( event, ui ) {
    		localStorage.activetab = ui.newPanel[0].dataset.index;
        },
        active: localStorage.activetab
    });
	
	if($('#towns_table').length) {
		var aaSorting = [[ 0, "asc" ]];
		var aoColumnDefs = [
	         { bSortable: false, aTargets: [ -1 ] }
		];
		var aoColumnFilterDefs = [		
			{ type: "text" },		
			{ type: "text" }
		];		
		towns_table = PMS.myDataTable.createDataTable($('#towns_table'), aaSorting, aoColumnDefs, aoColumnFilterDefs);
	}
});

function edit_lov(id, default_value, parent_default_value) {
	$('#item_value_' + id).toggle();
	$('#item_input_' + id).toggle();
	
	$('#item_parent_value_' + id).toggle();
	$('#item_parent_input_' + id).toggle();
	
	$('#actions_' + id).toggle();
	$('#edition_actions_' + id).toggle();
	
	$('#item_input_' + id).val(default_value);
	$('#item_input_' + id).focus();
	
	$('#item_parent_input_' + id).val(parent_default_value);
}

function delete_lov(id) {
	if(confirm("Êtes-vous sûr de vouloir supprimer définitivement cette valeur (Ceci peut affecter des fiches)?")) {
		sendData(lov_url, {id: id, action: 'delete'});
	}
}

function save_lov(id) {
	var value = $('#item_input_' + id).val().trim();
	if(value == '') {
		alert('Vous ne pouvez pas saisir une valeur vide!');
	} else {
		sendData(lov_url, {id: id, action: 'edit', value: value});
	}
}

function add_lov(group) {
	$('#add_lov_row_' + group).toggle();
	$('#add_input_' + group).focus();
}

function add_save_lov(group, with_parent) {
	with_parent = typeof with_parent !== 'undefined' ? with_parent : false;
	
	var value = $('#add_input_' + group).val().trim();
	var parent = false;
	if(with_parent) {
		parent = $('#add_input_parent_' + group).val().trim();
	}
	if(value == '') {
		alert('Vous ne pouvez pas ajouter une valeur vide!');
	} else {
		sendData(lov_url, {action: 'add', group: group, value: value, parent_value: parent});
	}
}

function sendData(url, params) {
	$.post( url, params, function(result) {
		if(result == "success"){
			location.reload();
		} else {
			alert("Un problème est survenu, merci d'essayer plus tard!");
		}
	}, 'json')
	.fail(function() {
		alert("Un problème est survenu, merci d'essayer plus tard!");
	});
}

function add_town(country_id) {
	if(!$('#add_lov_row_town').is(":visible")) {
		add_lov('town');
	}
	
	$('#add_input_parent_town').val(country_id);
}

function filter_town(country, id) {
	$('.pmsFilterTHeader input:first').val(country);
	$('.dataTables_filter input').val('');
	
	towns_table.columns().eq(0).each( function ( colIdx ) {
		towns_table.column( colIdx ).search('');
	} );
	
	towns_table.search('');
	towns_table.column( 0 ).search('FILTER' + country).draw();
}