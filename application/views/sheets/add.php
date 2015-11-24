<h1 class="pmsH1-small">Sondage <?php echo $pool->label; ?>: Création d'une fiche</h1>
<?php
	$this->load->view ( 'global/flash_messages', array('title' => $title) );
?>
<?php echo validation_errors('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Erreur! </strong>', '</div>'); ?>

<div class="view-menu">
	<?php echo drawActionsMenuItem('respondents/edit/'.$respondent->id, 'back.png', 'Revenir aux détails du répondant', ' back1') ?>
	<?php echo drawActionsMenuItem('respondents/delete/'.$respondent->id.'/true', 'cancel.png', 'Annuler et revenir à la sélection des sondages', ' back2') ?>
</div>

<?php echo form_open('sheets/add/'.$pool->id.'/'.$respondent->id, array('id' => 'sheet_form')); ?>
<?php drawModelData($fields, 1, 'edit-form'); ?>

<div class="required-notice">* Question obligatoire</div>
<?php echo form_submit('submit', 'Enregistrer', array('class' => 'submit-button')); ?>
<?php echo form_reset('reset', 'Réinitialiser', array('class' => 'submit-button')); ?>