$(function() {
	var aaSorting = [[ 2, "desc" ]];
	var aoColumnDefs = [
         { bSortable: false, aTargets: [ -1 ] },
         //{ sType: "date-euro", aTargets: [ 3, 4 ] },
	];
	var aoColumnFilterDefs = [		
		{ type: "number" },		
		{ type: "text" },		
		{ type: "date-range" }
	];		
	PMS.myDataTable.createDataTable($('#datatable_fixed_column'), aaSorting, aoColumnDefs, aoColumnFilterDefs);
});