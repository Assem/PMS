$(function() {
	var aaSorting = [[ 0, "desc" ]];
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
	PMS.myDataTable.createDataTable(aaSorting, aoColumnDefs, aoColumnFilterDefs);
});