<div id="geolocations">
	<h2>Erreurs de localisations</h2>
	
	<div class="dataTable_wrapper">
	    <table class="table table-striped table-hover responsive dataTable no-footer inline collapsed" id="" cellspacing="0" width="100%"  style="width: 100%;">
			<thead>
				<tr role="row" class="pmsTHeader">
					<th>Date</th>
					<th>Agent</th>
					<th>ID Fiche</th>
					<th>Erreur</th>
				</tr>
			</thead>
			<tbody id="tbody">
				<?php foreach($geolocations as $geolocation): ?>
					<tr style="border-color: gray;">
						<td>
							<?php echo date('d/m/Y H:i:s', strtotime($geolocation->creation_date)); ?>
						</td>
						<td>
							<?php echo secure_anchor("users/view/".$geolocation->id_user, strtoupper($geolocation->pms_user_last_name)." ".ucfirst($geolocation->pms_user_first_name)); ?>
						</td>
						<td>
							<?php echo secure_anchor("sheets/view/".$geolocation->id_sheet, $geolocation->id_sheet); ?>
						</td>
						<td>
							<?php echo $geolocation->error; ?>
						</td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>