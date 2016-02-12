<h1 class="pmsH1">Listes des sondages</h1>
<?php
	$this->load->view ( 'global/flash_messages', array('title' => $title) );
?>
<?php echo drawActionsMenuItem('polls/add', 'add.png', 'Ajouter'); ?>
<div id="datatable_fixed_column_tableInfo" class="tableInfo"></div>
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
			<?php foreach($polls as $poll): ?>
				<tr style="border-color: gray;">
					<td><?php echo $poll->id; ?></td>
					<td><?php echo $poll->code; ?></td>
					<td><?php echo $poll->label; ?></td>
					<td><?php echo $poll->customer; ?></td>
					<td><?php drawDate($poll->start_date); ?></td>
					<td><?php drawDate($poll->end_date); ?></td>
					<td><?php echo $poll->sheets_count ?></td>
					<td>
						<?php echo ($poll->actif)? 1:0; ?>
					</td>
					<td>
						<?php drawActionsMenu('polls', $poll->id); ?>
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>