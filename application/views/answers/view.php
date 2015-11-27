<?php if(!$answer): ?>
	<div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Erreur! </strong>Réponse introuvable!
    </div>
<?php else: ?>
	<h1 class="pmsH1">Sondage <?php echo $pool->label; ?> -> Question <?php echo $question->order; ?>: Détails d'une réponse</h1>
	<?php
		$this->load->view ( 'global/flash_messages', array('title' => $title) );
	?>
	<div class="view-menu">
		<?php echo drawActionsMenuItem('questions/view/'.$question->id, 'back.png', 'Revenir à la question') ?>
		<?php if($pool->sheets_number == 0): ?>
			<?php echo drawActionsMenuItem('answers/edit/'.$answer->id, 'edit.png', 'Editer') ?>
			<?php echo drawActionsMenuItem('answers/delete/'.$answer->id, 'delete.png', 'Supprimer', 'delete-action') ?>
		<?php endif;; ?>
	</div>
	<?php drawModelData($fields, 2, 'view-form'); ?>
<?php endif; ?>

