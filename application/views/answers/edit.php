<?php if(!$answer): ?>
	<div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Erreur! </strong>Réponse introuvable!
    </div>
<?php else: ?>
	<h1 class="pmsH1">Sondage <?php echo $pool->label; ?> -> Question <?php echo $question->order; ?>: Edition d'une réponse</h1>
	<?php
		$this->load->view ( 'global/flash_messages', array('title' => $title) );
	?>
	
	<?php echo validation_errors('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Erreur! </strong>', '</div>'); ?>
	
	<div class="view-menu">
		<?php echo drawActionsMenuItem('questions/edit/'.$question->id, 'back.png', 'Revenir à la question') ?>
		<?php echo drawActionsMenuItem('answers/view/'.$answer->id, 'cancel.png', 'Annuler') ?>
		<?php echo drawActionsMenuItem('answers/delete/'.$answer->id, 'delete.png', 'Supprimer', 'delete-action') ?>
	</div>
	
	<?php echo form_open('answers/edit/'.$answer->id); ?>
	<?php drawModelData($fields, 2, 'edit-form'); ?>
	
	<div class="required-notice">* Champ obligatoire</div>
	<?php echo form_submit('submit', 'Enregistrer', array('class' => 'submit-button')); ?>
	<?php echo form_reset('reset', 'Réinitialiser', array('class' => 'submit-button')); ?>

<?php endif; ?>

