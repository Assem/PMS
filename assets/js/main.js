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
        	var container_id = container.attr('id');
        	var otable = container.DataTable({
    			"aaSorting": aaSorting,
    			"bAutoWidth": true,
    			"aoColumnDefs": aoColumnDefs,
    	    	"bInfo": false,
    	    	"oLanguage": PMS.myDataTable._dataTableLanguage,
    	    	"lengthMenu": [[ 5, 20, 50, -1 ], [ 5, 20, 50, 'Tout' ]]
    	    });
        	
        	container.dataTable().columnFilter({
    	    	sRangeFormat: "De {from} à {to}",		
        	   	sPlaceHolder: "head:before",		
    			aoColumns: aoColumnsFilter
    		});
        	
        	PMS.myDataTable.showTableInfos(otable, container_id);
        	
        	otable.on( 'draw.dt', function () {
        		PMS.myDataTable.showTableInfos(otable, container_id);
        	});
        	
        	$( "#" + container_id + "_filter" ).parent().parent().append('<button class="actions-button" id="' + container_id + '-clear"><img height="36" class="action-icon " src="/assets/img/reset.png" title="Re-initialiser filtre"></button>');
        	$('#' + container_id + '-clear').click(function(){
    			PMS.myDataTable.resetAllFilters(otable, container_id);
    	    });
        	
        	return otable;
        },
        
        showTableInfos: function(otable, container_id) {
        	var info = otable.page.info();
        	
        	$('#' + container_id + '_tableInfo').html(
			    'Nombre total: '+info.recordsTotal+' | Nombre filtré: '+info.recordsDisplay
			);
        },
        
        resetAllFilters: function(oTable, container_id) {
        	$("#" + container_id + "_filter").val('');
        	$('#' + container_id + ' .pmsFilterTHeader input').val('');
        	
        	oTable.columns().eq(0).each( function ( colIdx ) {
        		oTable.column( colIdx ).search('');
        	} );
        	oTable.search('');
            oTable.draw();
        },
        
        validateDeletion: function () {
        	return confirm("Êtes-vous sûr de vouloir supprimer définitivement cet enregistrement?");
        }
	};
    
    $('.delete-action').click(function(){
    	return PMS.myDataTable.validateDeletion();
    });
    
    $.fn.dataTableExt.oApi.fnResetAllFilters = function (oSettings, bDraw/*default true*/) {
        for(iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                oSettings.aoPreSearchCols[ iCol ].sSearch = '';
        }
        oSettings.oPreviousSearch.sSearch = '';
 
        if(typeof bDraw === 'undefined') bDraw = true;
        if(bDraw) this.fnDraw();
    }
    
    
});