<?php if(!$sheet): ?>
	<div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Erreur! </strong>Fiche introuvable!
    </div>
<?php else: ?>
	<h1 class="pmsH1"><?php echo $title?></h1>
	<?php
		$this->load->view ( 'global/flash_messages', array('title' => $title) );
	?>
	
	<div class="view-menu">
		<?php echo drawActionsMenuItem($back_url, 'back.png', 'Revenir') ?>
		<?php echo drawActionsMenuItem('sheets/delete/'.$sheet->id.(($redirect)?"/$redirect":''), 'delete.png', 'Supprimer', 'delete-action') ?>
	</div>
	<?php drawModelData($sheet_fields, 2, 'view-form'); ?>
	<hr>
	<?php echo $respondent_view; ?>
	<hr>
	<h3>Réponses</h3>
	<?php drawModelDataToggle($answers_fields, 1, 'view-form'); ?>
<?php endif; ?>

