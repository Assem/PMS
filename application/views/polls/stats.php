<?php if(!$poll): ?>
	<div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Erreur! </strong>Sondage introuvable!
    </div>
<?php else: ?>
	<h1 class="pmsH1">Statistiques du sondage: <?php echo $poll->code; ?></h1>
	<?php echo secure_anchor('sheets/index/poll-'.$poll->id, $poll->sheets_count.' Fiches au total', 
			array(
					'title' => 'Consulter les fiches',
					'class' => 'counter_link'
	)); ?>
	<div class="view-menu">
		<?php echo drawActionsMenuItem('polls/view/'.$poll->id, 'back.png', 'Revenir') ?>
	</div>
	<?php foreach ($graphs_data as $question): ?>
		<div class="question_stats">
			<h4 class="question_tilte">
				<?php 
					echo secure_anchor('questions/view/' . $question['details']->id, $question['details']->order . '.' . $question['details']->description); 
					if($question['details']->required) {
						echo '<font color="red">*</font>';
					}
					//echo drawActionsMenuItem('questions/stats/'.$question['details']->id, 'charts.png', 'Statistiques pour cette question');
				?>
			</h4>
			
			<div class="graph_holder" id="q_<?php echo $question['details']->id; ?>">
			</div>
		</div>
	<?php endforeach; ?>
	
	<script type="text/javascript">
	var data = <?php echo json_encode($graphs_data); ?>;
	var answers_data = <?php echo json_encode($answers_data); ?>;
	var total_fiches = <?php echo $poll->sheets_count; ?>;
	</script>

<?php endif; ?>

