<?php if(!$question): ?>
	<div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Erreur! </strong>Question introuvable!
    </div>
<?php else: ?>
	<h1 class="pmsH1">Statistiques de la question n°<?php echo $question->order; ?></h1>
	<p><?php echo $question->description; ?></p>
	<div class="view-menu">
		<?php echo drawActionsMenuItem('polls/stats/'.$question->id_poll, 'back.png', 'Revenir') ?>
	</div>
	
	<script type="text/javascript">
	//var data = <?php echo json_encode($graphs_data); ?>;
	//var answers_data = <?php echo json_encode($answers_data); ?>;
	//var total_fiches = <?php echo $question->sheets_count; ?>;
	</script>

<?php endif; ?>

