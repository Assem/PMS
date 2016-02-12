<?php if(!$sequence): ?>
	<div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Erreur! </strong>Séquence introuvable!
    </div>
<?php else: ?>
	<h1 class="pmsH1">Séquence: <?php echo $label; ?></h1>
	<?php
		$this->load->view ( 'global/flash_messages', array('title' => $title) );
	?>
	
	<?php echo validation_errors('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Erreur! </strong>', '</div>'); ?>
	
	<div class="view-menu">
		<?php echo drawActionsMenuItem('settings/index', 'cancel.png', 'Annuler') ?>
	</div>
	
	<?php echo form_open('settings/sequence_edit/'.$sequence->id); ?>
	<?php drawModelData($fields, 2, 'edit-form'); ?>
	
	<div class="required-notice">* Champ obligatoire</div>
	<?php echo form_submit('submit', 'Enregistrer', array('class' => 'submit-button')); ?>
	<?php echo form_reset('reset', 'Réinitialiser', array('class' => 'submit-button')); ?>

<?php endif; ?>

