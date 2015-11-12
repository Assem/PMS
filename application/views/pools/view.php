<?php if(!$pool): ?>
	<div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Erreur! </strong>Sondage introuvable!
    </div>
<?php else: ?>
	<h1 class="pmsH1">Fiche sondage</h1>
	<?php
		$this->load->view ( 'global/flash_messages', array('title' => $title) );
	?>
	<div class="view-menu">
		<?php echo drawActionsMenuItem('pools/edit/'.$pool->id, 'edit.png', 'Editer') ?>
		<?php echo drawActionsMenuItem('pools/delete/'.$pool->id, 'delete.png', 'Supprimer', 'delete-action') ?>
	</div>
	<?php drawModelData($fields, 2, 'view-form'); ?>

<?php endif; ?>

