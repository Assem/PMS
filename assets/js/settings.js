$(function() {
    var tabs = $( '#settings' ).tabs();
});

function edit_lov(id, default_value) {
	$('#item_value_' + id).toggle();
	$('#item_input_' + id).toggle();
	$('#actions_' + id).toggle();
	$('#edition_actions_' + id).toggle();
	
	$('#item_input_' + id).val(default_value);
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
}

function add_save_lov(group) {
	var value = $('#add_input_' + group).val().trim();
	if(value == '') {
		alert('Vous ne pouvez pas ajouter une valeur vide!');
	} else {
		sendData(lov_url, {action: 'add', group: group, value: value});
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