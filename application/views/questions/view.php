<?php if(!$question): ?>
	<div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Erreur! </strong>Question introuvable!
    </div>
<?php else: ?>
	<h1 class="pmsH1">Sondage <?php echo $pool->label; ?>: Détails d'une question</h1>
	<?php
		$this->load->view ( 'global/flash_messages', array('title' => $title) );
	?>
	<div class="view-menu">
		<?php echo drawActionsMenuItem('pools/view/'.$pool->id, 'back.png', 'Revenir au sondage') ?>
		<?php if($pool->sheets_count == 0): ?>
			<?php echo drawActionsMenuItem('questions/edit/'.$question->id, 'edit.png', 'Editer') ?>
			<?php echo drawActionsMenuItem('questions/delete/'.$question->id, 'delete.png', 'Supprimer', 'delete-action') ?>
		<?php endif; ?>
	</div>
	<?php drawModelData($fields, 2, 'view-form'); ?>
	
	<?php
		if($question->type != 'free_text') {
			$this->load->view ( 'answers/_list', array('question' => $question, 'answers' => $answers, 'action' => 'view'));
		}
	?>

<?php endif; ?>

