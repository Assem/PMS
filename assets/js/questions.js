$(function() {
	if($('#answers_datatable').length) {
		var q_aaSorting = [[ 0, "asc" ]];
		var q_aoColumnDefs = [
	         { bSortable: false, aTargets: [ -1 ] },
		];
		var q_aoColumnFilterDefs = [		
			{ type: "number" },		
			{ type: "text" },		
			{ type: "number" }
		];	
		PMS.myDataTable.createDataTable($('#answers_datatable'), q_aaSorting, q_aoColumnDefs, q_aoColumnFilterDefs);
	}
	
	$('select[name="type"]').change(toggle_answer_type);
	toggle_answer_type();
});

function toggle_answer_type() {
	var free_answer_type = $('select[name="free_answer_type"]');
	var type = $('select[name="type"]');
	if(type.length && type.val() == 'free_text') {
		free_answer_type.show();
		free_answer_type.closest('td').prev('th').show();
	} else {
		free_answer_type.hide();
		free_answer_type.closest('td').prev('th').hide();
	}
}