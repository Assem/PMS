<h1 class="pmsH1"><?php echo $title; ?></h1>
<?php
	$this->load->view ( 'global/flash_messages', array('title' => $title) );
?>
<div id="tableInfo"></div>

<div class="view-menu">
	<?php echo drawActionsMenuItem($back_url, 'back.png', 'Revenir') ?>
</div>

<div class="dataTable_wrapper">
    <table class="table table-striped table-hover responsive dataTable no-footer inline collapsed" id="datatable_fixed_column" cellspacing="0" width="100%"  style="width: 100%;">
		<thead>
			<tr role="row" class="pmsFilterTHeader">
				<th>ID</th>
				<th>Agent</th>
				<th>Date</th>
				<th></th>
			</tr>
			<tr role="row" class="pmsTHeader">
				<th>ID</th>
				<th>Agent</th>
				<th>Date</th>
				<th style="max-width: 70px">Actions</th>
			</tr>
		</thead>
		<tbody id="tbody">
			<?php foreach($sheets as $sheet): ?>
				<tr style="border-color: gray;">
					<td><?php echo $sheet->id; ?></td>
					<td><?php echo strtoupper($sheet->pms_user_last_name).' '.ucfirst($sheet->pms_user_first_name) ; ?></td>
					<td><?php echo date('d/m/Y H:i:s', strtotime($sheet->creation_date)); ?></td>
					<td>
						<?php drawActionsMenu('sheets', $sheet->id); ?>
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>