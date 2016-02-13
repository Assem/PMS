<?php if(!$poll): ?>
	<div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Erreur! </strong>Sondage introuvable!
    </div>
<?php else: ?>
	<h1 class="pmsH1">Edition d'un sondage</h1>
	<?php
		$this->load->view ( 'global/flash_messages', array('title' => $title) );
	?>
	<?php if($warning): ?>
		<img class="action-icon" src="/assets/img/warning.png" title="Des questions sans réponses!" />
	<?php endif?>
	<?php echo validation_errors('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Erreur! </strong>', '</div>'); ?>
	
	<div class="view-menu">
		<?php echo drawActionsMenuItem('polls/view/'.$poll->id, 'cancel.png', 'Annuler et quitter') ?>
		<?php echo drawActionsMenuItem('polls/delete/'.$poll->id, 'delete.png', 'Supprimer', 'delete-action delete_poll') ?>
	</div>
	
	<?php echo form_open('polls/edit/'.$poll->id); ?>
	<?php drawModelData($fields, 2, 'edit-form'); ?>
	
	<div class="required-notice">* Champ obligatoire</div>
	<?php echo form_submit('submit', 'Enregistrer', array('class' => 'submit-button')); ?>
	<?php echo form_reset('reset', 'Réinitialiser', array('class' => 'submit-button')); ?>
	
	<?php
		$action = 'edit';
		if($poll->sheets_count > 0) {
			$action = 'view';
		}
		$this->load->view ( 'questions/_list', array('poll' => $poll, 'questions' => $questions, 'action' => $action) );
	?>

<?php endif; ?>

