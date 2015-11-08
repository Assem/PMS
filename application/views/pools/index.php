<h1 class="pmsH1">Listes des sondages</h1>
<?php
	$this->load->view ( 'global/flash_messages', array('title' => $title) );
?>
<?php echo drawActionsMenuItem('pools/add', 'add.png', 'Ajouter'); ?>
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
				<th>Actif</th>
				<th style="max-width: 70px">Actions</th>
			</tr>
		</thead>
		<tbody id="tbody">
			<?php $i = 0;?>
				<?php foreach($pools as $pool): ?>
					<tr style="border-color: gray;">
						<td><?php echo $pool->id; ?></td>
						<td><?php echo $pool->code; ?></td>
						<td><?php echo $pool->label; ?></td>
						<td><?php echo $pool->customer; ?></td>
						<td><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $pool->start_date." 00:00:00")->format('m/d/Y'); ?></td>
						<td><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $pool->end_date." 00:00:00")->format('m/d/Y'); ?></td>
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