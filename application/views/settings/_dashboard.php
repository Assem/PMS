<?php
	$this->load->view ( 'global/flash_messages', array('title' => 'Configuration du dashboard') );
?>

<?php echo validation_errors('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Erreur! </strong>', '</div>'); ?>

<?php echo form_open('settings/index'); ?>
<?php drawModelData($settings['fields'], 1, 'edit-form'); ?>

<div class="required-notice">* Champ obligatoire</div>
<?php echo form_submit('submit', 'Enregistrer', array('class' => 'submit-button')); ?>
<?php echo form_reset('reset', 'Réinitialiser', array('class' => 'submit-button')); ?>