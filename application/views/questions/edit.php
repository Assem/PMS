<?php if(!$question): ?>
	<div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Erreur! </strong>Question introuvable!
    </div>
<?php else: ?>
	<h1 class="pmsH1">Sondage <?php echo $pool->label; ?>: Edition d'une question</h1>
	<?php
		$this->load->view ( 'global/flash_messages', array('title' => $title) );
	?>
	
	<?php echo validation_errors('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Erreur! </strong>', '</div>'); ?>
	
	<div class="view-menu">
		<?php echo drawActionsMenuItem('pools/edit/'.$pool->id, 'back.png', 'Revenir au sondage') ?>
		<?php echo drawActionsMenuItem('questions/view/'.$question->id, 'cancel.png', 'Annuler') ?>
		<?php echo drawActionsMenuItem('questions/delete/'.$question->id, 'delete.png', 'Supprimer', 'delete-action') ?>
	</div>
	
	<?php echo form_open('questions/edit/'.$question->id); ?>
	<?php drawModelData($fields, 2, 'edit-form'); ?>
	
	<div class="required-notice">* Champ obligatoire</div>
	<?php echo form_submit('submit', 'Enregistrer', array('class' => 'submit-button')); ?>
	<?php echo form_reset('reset', 'Réinitialiser', array('class' => 'submit-button')); ?>

<?php endif; ?>

