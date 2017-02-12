$(function() {
	var sort_index = 3;
	var aoColumnFilterDefs = [	
      null,	
  		{ type: "number" },		
  		{ type: "text" }
  	];
  	
  	if(from == '') {
  		aoColumnFilterDefs.push({ type: "text" });
  		sort_index = 4;
  	}
  	aoColumnFilterDefs.push({ type: "date-range" });
  	
  	var aaSorting = [[ sort_index, "desc" ]];
	var aoColumnDefs = [
         { bSortable: false, aTargets: [ 0, -1 ] },
         { sType: "date-euro", aTargets: [ sort_index ] },
	];
	PMS.myDataTable.createDataTable($('#datatable_fixed_column'), aaSorting, aoColumnDefs, aoColumnFilterDefs);
});