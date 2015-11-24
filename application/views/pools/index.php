<h1 class="pmsH1">Listes des sondages</h1>
<?php
	$this->load->view ( 'global/flash_messages', array('title' => $title) );
?>
<?php echo drawActionsMenuItem('pools/add', 'add.png', 'Ajouter'); ?>
<div id="tableInfo"></div>
<div class="dataTable_wrapper">
    <table class="table table-striped table-hover responsive dataTable no-footer inline collapsed" id="datatable_fixed_column" cellspacing="0" width="100%"  style="width: 100%;">
		<thead>
			<tr role="row" class="pmsFilterTHeader">
				<th>ID</th>
				<th>Code</th>
				<th>Libellé</th>
				<th>Client</th>
				<th>Date Début</th>
				<th>Date fin</th>
				<th></th>
				<th>Actif</th>
				<th></th>
			</tr>
			<tr role="row" class="pmsTHeader">
				<th>ID</th>
				<th>Code</th>
				<th>Libellé</th>
				<th>Client</th>
				<th>Date Début</th>
				<th>Date fin</th>
				<th>Nbre Fiches</th>
				<th>Actif</th>
				<th style="max-width: 70px">Actions</th>
			</tr>
		</thead>
		<tbody id="tbody">
			<?php foreach($pools as $pool): ?>
				<tr style="border-color: gray;">
					<td><?php echo $pool->id; ?></td>
					<td><?php echo $pool->code; ?></td>
					<td><?php echo $pool->label; ?></td>
					<td><?php echo $pool->customer; ?></td>
					<td><?php drawDate($pool->start_date); ?></td>
					<td><?php drawDate($pool->end_date); ?></td>
					<td><?php echo $pool->sheets_count ?></td>
					<td>
						<?php echo ($pool->actif)? 1:0; ?>
					</td>
					<td>
						<?php drawActionsMenu('pools', $pool->id); ?>
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>