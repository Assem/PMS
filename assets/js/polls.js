$(function() {
	if($('#datatable_fixed_column').length) {
		var aaSorting = [[ 4, "desc" ]];
		var aoColumnDefs = [
	         { bSortable: false, aTargets: [ 0, -1, -3 ] },
	         { sType: "date-euro", aTargets: [ 4, 5 ] },
		];
		var aoColumnFilterDefs = [		
			null,		
			{ type: "text" },		
			{ type: "text" },		
			{ type: "text" },		
			{ type: "date-range" },		
			{ type: "date-range" },	
			null,
			{ type: "number" }	
		];		
		PMS.myDataTable.createDataTable($('#datatable_fixed_column'), aaSorting, aoColumnDefs, aoColumnFilterDefs);
	}
	
	if($('#questions_datatable').length) {
		var q_aaSorting = [[ 2, "asc" ]];
		var q_aoColumnDefs = [
	         { bSortable: false, aTargets: [ 0, 1, -1 ] },
		];
		var q_aoColumnFilterDefs = [	
		    null,
		    null,
			{ type: "number" },		
			{ type: "text" },		
			{ type: "text" },		
			{ type: "number" }
		];	
		
		PMS.myDataTable.createDataTable($('#questions_datatable'), q_aaSorting, q_aoColumnDefs, q_aoColumnFilterDefs);
	}
    
    $( "#start_date, #end_date" ).datepicker({
    	dateFormat: "dd/mm/yy"
    });
    
    $('#datatable_fixed_column img.delete-action, .delete_poll').click(function(){
    	return confirm("Êtes-vous sûr de vouloir supprimer définitivement ce sondage; ceci supprimera toutes les fiches liées?");
    });
    
    $('.delete-all-action').click(function(){
    	return confirm("Êtes-vous sûr de vouloir supprimer définitivement toutes les fiches du sondage?");
    });
});