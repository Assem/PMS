$(function() {
	if (window.PMS === undefined) {
        window.PMS = {};
    }
    var PMS = window.PMS;
    PMS.myDataTable = {
    	_dataTableLanguage: {
        	"sProcessing":     "Traitement en cours...",
    	    "sSearch":         "Rechercher&nbsp;:",
    	    "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
    	    "sInfo":           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
    	    "sInfoEmpty":      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
    	    "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
    	    "sInfoPostFix":    "",
    	    "sLoadingRecords": "Chargement en cours...",
    	    "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
    	    "sEmptyTable":     "Aucun enregistrement disponible",
    	    "oPaginate": {
    	        "sFirst":      "Premier",
    	        "sPrevious":   "Pr&eacute;c&eacute;dent",
    	        "sNext":       "Suivant",
    	        "sLast":       "Dernier"
    	    },
    	    "oAria": {
    	        "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
    	        "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
    	    }
        },

        createDataTable: function (container, aaSorting, aoColumnDefs, aoColumnsFilter) {
        	var otable = container.DataTable({
    			"aaSorting": aaSorting,
    			"bAutoWidth": true,
    			"aoColumnDefs": aoColumnDefs,
    	    	"bInfo": false,
    	    	"oLanguage": PMS.myDataTable._dataTableLanguage,
    	    	"lengthMenu": [ 5, 10, 25, 50, 75, 100 ]
    	    });
        	
        	container.dataTable().columnFilter({
    	    	sRangeFormat: "De {from} à {to}",		
        	   	sPlaceHolder: "head:before",		
    			aoColumns: aoColumnsFilter
    		});
        	
        	PMS.myDataTable.showTableInfos(otable);
        	
        	otable.on( 'draw.dt', function () {
        		PMS.myDataTable.showTableInfos(otable);
        	});
        	
        	return otable;
        },
        
        showTableInfos: function(otable) {
        	var info = otable.page.info();
        	
        	$('#tableInfo').html(
			    'Nombre total: '+info.recordsTotal+' | Nombre filtré: '+info.recordsDisplay
			);
        },
        
        validateDeletion: function () {
        	return confirm("Êtes-vous sûr de vouloir supprimer définitivement cet enregistrement?");
        }
	};
    
    $('.delete-action').click(function(){
    	return PMS.myDataTable.validateDeletion();
    });
});