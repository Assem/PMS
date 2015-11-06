<?php if(!$user): ?>
	<div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Erreur! </strong>Utilisateur introuvable!
    </div>
<?php else: ?>
	<h1 class="pmsH1">Fiche utilisateur</h1>
	<?php
		$this->load->view ( 'global/flash_messages', array('title' => $title) );
	?>
	<div class="view-menu">
		<?php echo drawActionsMenuItem('users/edit/'.$user->user_id, 'edit.png', 'Editer') ?>
		<?php echo drawActionsMenuItem('users/delete/'.$user->user_id, 'delete.png', 'Supprimer', 'delete-action') ?>
	</div>
	<?php drawModelData($fields, 2, 'view-form'); ?>

<?php endif; ?>

