<?php if(!$respondent): ?>
	<div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Erreur! </strong>Fiche répondant introuvable!
    </div>
<?php else: ?>
	<h1 class="pmsH1">Sondage <?php echo $poll->label; ?>: Edition informations sur le répondant</h1>
	<?php
		$this->load->view ( 'global/flash_messages', array('title' => $title) );
	?>
	
	<?php echo validation_errors('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Erreur! </strong>', '</div>'); ?>
	
	<div class="view-menu">
		<?php echo drawActionsMenuItem('respondents/delete/'.$respondent->id.'/true', 'delete.png', 'Supprimer et revenir à la sélection des sondages') ?>
	</div>
	
	<?php echo form_open('respondents/edit/'.$respondent->id); ?>
	<?php drawModelData($fields, 2, 'edit-form'); ?>
	
	<div class="required-notice">* Champ obligatoire</div>
	<?php echo form_submit('submit', 'Enregistrer', array('class' => 'submit-button')); ?>
	<?php echo form_reset('reset', 'Réinitialiser', array('class' => 'submit-button')); ?>

<?php endif; ?>

<script type="text/javascript">
var cities = <?php echo json_encode($cities); ?>;
</script>