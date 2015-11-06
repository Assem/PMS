<?php if(!$user): ?>
	<div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Erreur! </strong>Utilisateur introuvable!
    </div>
<?php else: ?>
	<h1 class="pmsH1">Edition fiche utilisateur</h1>
	<?php
		$this->load->view ( 'global/flash_messages', array('title' => $title) );
	?>
	
	<?php echo validation_errors('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Erreur! </strong>', '</div>'); ?>
	
	<div class="view-menu">
		<?php echo drawActionsMenuItem('users/view/'.$user->user_id, 'cancel.png', 'Annuler et quitter') ?>
		<?php echo drawActionsMenuItem('users/delete/'.$user->user_id, 'delete.png', 'Supprimer', 'delete-action') ?>
	</div>
	
	<?php echo form_open('users/edit/'.$user->user_id); ?>
	<?php drawModelData($fields, 2, 'edit-form'); ?>
	
	<div class="required-notice">* Champ obligatoire</div>
	<?php echo form_submit('submit', 'Enregistrer', array('class' => 'submit-button')); ?>
	<?php echo form_reset('reset', 'Réinitialiser', array('class' => 'submit-button')); ?>

<?php endif; ?>

