<h1 class="pmsH1">Sondage <?php echo $poll->label; ?> -> Question <?php echo $question->order; ?>: Ajouter une réponse</h1>
<?php
	$this->load->view ( 'global/flash_messages', array('title' => $title) );
?>
<?php echo validation_errors('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Erreur! </strong>', '</div>'); ?>

<div class="view-menu">
	<?php echo drawActionsMenuItem('questions/edit/'.$question->id, 'cancel.png', 'Revenir à la question') ?>
</div>

<?php echo form_open('answers/add/'.$question->id); ?>
<?php drawModelData($fields, 2, 'edit-form'); ?>

<div class="required-notice">* Champ obligatoire</div>
<?php echo form_submit('submit', 'Enregistrer', array('class' => 'submit-button')); ?>
<?php echo form_reset('reset', 'Réinitialiser', array('class' => 'submit-button')); ?>

