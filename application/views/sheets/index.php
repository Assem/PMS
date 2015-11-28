<?php 
	function getData($relation, $sheet) {
		switch ($relation) {
			case 'pool':
				echo '<td>'.strtoupper($sheet->pms_user_last_name).' '.ucfirst($sheet->pms_user_first_name).'</td>' ;
    			break;
    		case 'user':
    			echo '<td>['.$sheet->pool_code.'] '.$sheet->pool_label.'</td>' ;
    			break;
    		default:
    			echo '<td>['.$sheet->pool_code.'] '.$sheet->pool_label.'</td>' ;
    			echo '<td>'.strtoupper($sheet->pms_user_last_name).' '.ucfirst($sheet->pms_user_first_name).'</td>' ;
    			break;
		}
	}
?>
<script type="text/javascript">
var from = '<?php echo $relation; ?>';
</script>
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
				<?php echo $columns; ?>
				<th>Date</th>
				<th></th>
			</tr>
			<tr role="row" class="pmsTHeader">
				<th>ID</th>
				<?php echo $columns; ?>
				<th>Date</th>
				<th style="max-width: 70px">Actions</th>
			</tr>
		</thead>
		<tbody id="tbody">
			<?php foreach($sheets as $sheet): ?>
				<tr style="border-color: gray;">
					<td><?php echo $sheet->id; ?></td>
					<?php getData($relation, $sheet); ?>
					<td><?php echo date('d/m/Y H:i:s', strtotime($sheet->creation_date)); ?></td>
					<td>
						<?php 
						echo drawActionsMenuItem("sheets/view/".$sheet->id.$from, 'view.png', 'Afficher');
						echo drawActionsMenuItem("sheets/delete/".$sheet->id.$from, 'delete.png', 'Supprimer', 'delete-action');
						?>
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>