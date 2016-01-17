$(function() {
	var aaSorting = [[ 0, "asc" ]];
	var aoColumnDefs = [
         { bSortable: false, aTargets: [ -1 ] }
	];
	var aoColumnFilterDefs = [		
		{ type: "text" },		
		{ type: "text" },
		{ type: "text" },
		{ type: "text" },		
		{ type: "text" },		
		{ type: "text" },		
		{ type: "text" },		
		{ type: "number" }	
	];		
	PMS.myDataTable.createDataTable($('#datatable_fixed_column'), aaSorting, aoColumnDefs, aoColumnFilterDefs);
});