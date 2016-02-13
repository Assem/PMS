<?php if(!$poll): ?>
	<div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Erreur! </strong>Sondage introuvable!
    </div>
<?php else: ?>
	<h1 class="pmsH1">Fiche sondage</h1>
	<?php
		$this->load->view ( 'global/flash_messages', array('title' => $title) );
	?>
	
	<?php if($warning): ?>
		<img class="action-icon" src="/assets/img/warning.png" title="Des questions sans réponses!" />
	<?php endif?>
	
	<?php echo secure_anchor('sheets/index/poll-'.$poll->id, $poll->sheets_count.' Fiches', 
			array(
					'title' => 'Consulter les fiches',
					'class' => 'counter_link'
	)); ?>
	
	<div class="view-menu">
		<?php echo drawActionsMenuItem('polls/edit/'.$poll->id, 'edit.png', 'Editer') ?>
		<?php echo drawActionsMenuItem('polls/stats/'.$poll->id, 'charts.png', 'Statistiques') ?>
		<?php echo drawActionsMenuItem('polls/delete/'.$poll->id, 'delete.png', 'Supprimer', 'delete-action') ?>
		<div style='text-align: left; float:left'>
			<?php echo drawActionsMenuItem('sheets/delete_all/'.$poll->id, 'delete-all.png', 'Supprimer toutes les fiches', 'delete-all-action') ?>
		</div>
	</div>
	<?php drawModelData($fields, 2, 'view-form'); ?>
	
	<?php
		$this->load->view ( 'questions/_list', array('poll' => $poll, 'questions' => $questions, 'action' => 'view') );
	?>

<?php endif; ?>

