<?php if(!$question): ?>
	<div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Erreur! </strong>Question introuvable!
    </div>
<?php else: ?>
	<h1 class="pmsH1">Sondage '<?php echo $poll->label; ?>': Edition d'une question</h1>
	<?php
		$this->load->view ( 'global/flash_messages', array('title' => $title) );
	?>
	
	<?php echo validation_errors('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Erreur! </strong>', '</div>'); ?>
	
	<div class="view-menu">
		<?php echo drawActionsMenuItem('polls/edit/'.$poll->id, 'back.png', 'Revenir au sondage') ?>
		<?php echo drawActionsMenuItem('questions/view/'.$question->id, 'cancel.png', 'Annuler') ?>
		<?php echo drawActionsMenuItem('questions/delete/'.$question->id, 'delete.png', 'Supprimer', 'delete-action') ?>
	</div>
	
	<?php echo form_open('questions/edit/'.$question->id); ?>
	<?php drawModelData($fields, 2, 'edit-form'); ?>
	
	<div id="generate_yes_no" style="display: none; float: right;">
		<?php 
			echo secure_anchor(
					'answers/add_yes_no/'.$question->id, 
					'<input type="button" id="generate_yes_no_button" name="generate_yes_no_button" title="Générer OUI/NON réponses" value="+ OUI/NON" class="submit-button"/>'
				);
		?>
	</div>
	
	<div class="required-notice">* Champ obligatoire</div>
	<?php echo form_submit('submit', 'Enregistrer', array('class' => 'submit-button')); ?>
	<?php echo form_reset('reset', 'Réinitialiser', array('class' => 'submit-button')); ?>
	
	<?php
		if($question->type != 'free_text') {
			$this->load->view ( 'answers/_list', array('question' => $question, 'answers' => $answers, 'action' => 'edit') );
		}
	?>

<?php endif; ?>

