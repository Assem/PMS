<h1 class="pmsH1">Sélection de sondage</h1>
<?php
	$this->load->view ( 'global/flash_messages', array('title' => $title) );
?>
<?php echo form_open('polls/select'); ?>
<?php drawModelData($fields, 1, 'edit-form'); ?>

<?php echo form_submit('submit', 'Valider', array('class' => 'submit-button')); ?>