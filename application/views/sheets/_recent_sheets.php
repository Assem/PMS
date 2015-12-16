<div id="sheets">
	<h2>Dernières fiches</h2>
	
	<div class="dataTable_wrapper">
	    <table class="table table-striped table-hover responsive dataTable no-footer inline collapsed" id="" cellspacing="0" width="100%"  style="width: 100%;">
			<thead>
				<tr role="row" class="pmsTHeader">
					<th>Date</th>
					<th>Sondage</th>
					<th>Agent</th>
				</tr>
			</thead>
			<tbody id="tbody">
				<?php foreach($sheets as $sheet): ?>
					<tr style="border-color: gray;">
						<td>
							<?php echo secure_anchor("sheets/view/".$sheet->id, date('d/m/Y H:i:s', strtotime($sheet->creation_date))); ?>
						</td>
						<td>
							<?php echo secure_anchor("polls/view/".$sheet->id_poll, '['.$sheet->poll_code.'] '.$sheet->poll_label); ?>
						</td>
						<td>
							<?php echo secure_anchor("users/view/".$sheet->created_by, strtoupper($sheet->pms_user_last_name)." ".ucfirst($sheet->pms_user_first_name)); ?>
						</td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>