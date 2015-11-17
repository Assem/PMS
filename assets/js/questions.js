$(function() {
	var q_aaSorting = [[ 0, "asc" ]];
	var q_aoColumnDefs = [];
	
	if(action == 'edit') {
		q_aoColumnDefs = [
              { bSortable: false, aTargets: [ -1 ] },
     	];
	}
	var q_aoColumnFilterDefs = [		
		{ type: "number" },		
		{ type: "text" },		
		{ type: "number" }
	];	
	PMS.myDataTable.createDataTable($('#answers_datatable'), q_aaSorting, q_aoColumnDefs, q_aoColumnFilterDefs);
});