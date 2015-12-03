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
	<?php echo secure_anchor('sheets/index/user-'.$user->user_id, $user->sheets_number.' Fiches', 
		array(
			'title' => 'Consulter les fiches',
			'class' => 'counter_link'
	)); ?>
	<div class="view-menu">
		<?php echo drawActionsMenuItem('users/edit/'.$user->user_id, 'edit.png', 'Editer') ?>
		<?php echo drawActionsMenuItem('users/delete/'.$user->user_id, 'delete.png', 'Supprimer', 'delete-action') ?>
	</div>
	<?php drawModelData($fields, 2, 'view-form'); ?>
	
	<div id="positions_map">
		<h3>Dernière position</h3>
		<?php if($user->last_position):?>
			<?php $position = $user->last_position[0]; ?>
			<p>
				<?php echo date('d/m/Y H:i:s', strtotime($position->creation_date)).'<br>Sondage: ['.$position->poll_code.'] '.$position->poll_label.' - ID Fiche: '.$position->sheet_id;?>
			</p>
			<?php if($position->latitude): ?>
				<?php $latlong = $position->latitude.','.$position->longitude; ?>
				<img src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo $latlong?>&zoom=15&size=600x300&maptype=roadmap
							&markers=color:blue|<?php echo $latlong?>"/>
			<?php else: ?>
				<p>Erreur: <?php echo $position->error; ?></p>
			<?php endif; ?>
		<?php else: ?>
			<p>Pas de position enregistrée</p>
		<?php endif; ?>
	</div>

<?php endif; ?>

