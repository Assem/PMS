<h2 class="pmsH1">Liste des réponses</h2>

<?php if($action == 'edit'): ?>
	<?php echo drawActionsMenuItem('answers/add/'.$question->id, 'add.png', 'Ajouter'); ?>
<?php endif; ?>

<div class="dataTable_wrapper">
    <table class="table table-striped table-hover responsive dataTable no-footer inline collapsed" id="answers_datatable" cellspacing="0" width="100%"  style="width: 100%;">
		<thead>
			
			<tr role="row" class="pmsFilterTHeader">
				<th>Rang</th>
				<th>Description</th>
				<th>Valeur</th>
				<?php if($action == 'edit'): ?>
					<th></th>
				<?php endif; ?>
			</tr>
			
			<tr role="row" class="pmsTHeader">
				<th>Rang</th>
				<th>Description</th>
				<th>Valeur</th>
				<?php if($action == 'edit'): ?>
					<th style="max-width: 80px">Actions</th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody id="tbody">
			<?php $max_order = count($answers); ?>
			<?php foreach($answers as $answer): ?>
				<tr style="border-color: gray;">
					<td><?php echo $answer->order; ?></td>
					<td><?php echo $answer->description; ?></td>
					<td><?php echo $answer->value; ?></td>
					<?php if($action == 'edit'): ?>
						<td>
							<?php drawActionsMenu('answers', $answer->id); ?>
							<?php drawManageRankingMenu('answers', $answer->id, $answer->order, $max_order); ?>
						</td>
					<?php endif; ?>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>

<script type="text/javascript">
var action = '<?php echo $action ?>';
</script>