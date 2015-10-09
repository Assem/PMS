$(function() {
	var aaSorting = [[ 3, "desc" ]];
	var aoColumnDefs = [
         { bSortable: false, aTargets: [ -1 ] },
         { sType: "date-euro", aTargets: [ 3, 4 ] },
	];
	YESSIR.myDataTable.createDataTable(aaSorting, aoColumnDefs);
});