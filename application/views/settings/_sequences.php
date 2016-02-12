<h1 class="pmsH1">Listes des séquences</h1>
<?php
	$this->load->view ( 'global/flash_messages', array('title' => $title) );
?>
<div id="sequences_table_tableInfo" class="tableInfo"></div>
<div class="dataTable_wrapper">
    <table class="table table-striped table-hover responsive dataTable no-footer inline collapsed" id="sequences_table" cellspacing="0" width="100%"  style="width: 100%;">
		<thead>
			<tr role="row" class="pmsFilterTHeader">
				<th>Libellé</th>
				<th></th>
				<th>Préfix</th>
				<th></th>
				<th></th>
			</tr>
			<tr role="row" class="pmsTHeader">
				<th>Libellé</th>
				<th>Prochain index</th>
				<th>Préfix</th>
				<th>Remplissage</th>
				<th style="max-width: 70px">Actions</th>
			</tr>
		</thead>
		<tbody id="tbody">
			<?php foreach($sequences as $sequence): ?>
				<tr style="border-color: gray;">
					<td><?php echo $sequence->label; ?></td>
					<td><?php echo $sequence->next_index; ?></td>
					<td><?php echo $sequence->prefix; ?></td>
					<td><?php echo $sequence->fillers; ?></td>
					<td>
						<?php echo drawActionsMenuItem("settings/sequence_edit/" . $sequence->id, 'edit.png', 'Editer'); ?>
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>