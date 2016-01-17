$(document).ready(function() {
	if (window.PMS === undefined) {
        window.PMS = {};
    }
    var PMS = window.PMS;
    PMS.myGraphs = {
    	getGraphData: function(question) {
    		var label = 'Nombre de répondants par réponse';
    		var color = "#5482FF";
    		
    		if(question['details']['type'] == 'free_text') {
    			label = '';
    			color = "red";
    		} else if (question['details']['type'] == 'mutiple_choice') {
    			color = "green";
    		}
    		
    		return [{
    			data: question['graph']['data'],
    			bars: { show: true },
    			color: color,
    			answers: answers_data[question['details']['id']]['answers'],
    			label: label
    		}];
    	},
    	getGraphTooltip: function(label, xval, yval, flotItem) {
    		if(flotItem.series.answers) {
    			var answer = flotItem.series.answers[xval + 1];
        		
        		return  '<div class="graph_tip">' + (xval+1) + '.' + answer.description + "<br><br>"
        				+ 'Nombre de répondants: ' + yval + ' => ' + yval/total_fiches*100 + '%'
        				+ '</div>';
    		}
    		
    		return '<div class="graph_tip">' + 'Nombre de répondants: ' + yval + ' => ' + yval/total_fiches*100 + '%'
					+ '</div>';
    	},
    	getGraphOptions: function(labels) {
    		var options = {
				grid : {
					hoverable : true,
					backgroundColor: "#f7f7f7",
					labelMargin: 15
				},
				tooltip : true,
				tooltipOpts : {
					content: PMS.myGraphs.getGraphTooltip,
			        defaultTheme : false
				},
				xaxis : {
					ticks: labels
				},
				yaxis : {
					min: 0,
					minTickSize: 1,
					tickDecimals: 0
				},
				bars: {
				    align: "center",
				    barWidth: 0.5
				}
			};
    			
    		return options;
    	}
    }
    
	var i = 0;
	var question;
	
	for(i=0; i<data.length; i++) {
		question = data[i];
		
		$.plot(
			'#q_' + question['details']['id'], 
			PMS.myGraphs.getGraphData(question), 
			PMS.myGraphs.getGraphOptions(question['graph']['labels'])
		);
	}
});