<h2 class="pmsH1">Liste des questions</h2>
	
<?php echo drawActionsMenuItem('questions/add/'.$pool->id, 'add.png', 'Ajouter'); ?>

<div class="dataTable_wrapper">
    <table class="table table-striped table-hover responsive dataTable no-footer inline collapsed" id="questions_datatable" cellspacing="0" width="100%"  style="width: 100%;">
		<thead>
			<tr role="row" class="pmsFilterTHeader">
				<th>Rang</th>
				<th>Description</th>
				<th>Type</th>
				<th>Obligatoire</th>
				<th></th>
			</tr>
			<tr role="row" class="pmsTHeader">
				<th>Rang</th>
				<th>Description</th>
				<th>Type</th>
				<th>Obligatoire</th>
				<th style="max-width: 80px">Actions</th>
			</tr>
		</thead>
		<tbody id="tbody">
			<?php $max_order = count($questions); ?>
			<?php foreach($questions as $question): ?>
				<tr style="border-color: gray;">
					<td><?php echo $question->order; ?></td>
					<td><?php echo $question->description; ?></td>
					<td><?php echo $question->type_name; ?></td>
					<td><?php echo $question->required; ?></td>
					<td>
						<?php drawActionsMenu('questions', $question->id); ?>
						<?php drawManageRankingMenu('questions', $question->id, $question->order, $max_order); ?>
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>