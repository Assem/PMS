$(function() {
	var aaSorting = [[ 4, "desc" ]];
	var aoColumnDefs = [
         { bSortable: false, aTargets: [ -1 ] },
         { sType: "date-euro", aTargets: [ 4, 5 ] },
	];
	var aoColumnFilterDefs = [		
		{ type: "number" },		
		{ type: "text" },		
		{ type: "text" },		
		{ type: "text" },		
		{ type: "date-range" },		
		{ type: "date-range" },		
		{ type: "number" }	
	];		
	PMS.myDataTable.createDataTable($('#datatable_fixed_column'), aaSorting, aoColumnDefs, aoColumnFilterDefs);
	
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
		{ type: "text" },		
		{ type: "number" }
	];	
	PMS.myDataTable.createDataTable($('#questions_datatable'), q_aaSorting, q_aoColumnDefs, q_aoColumnFilterDefs);
    
    $( "#start_date, #end_date" ).datepicker({
    	dateFormat: "dd/mm/yy"
    });
});