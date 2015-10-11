<h1 class="pmsH1">Listes des utilisateurs</h1>
<div class="dataTable_wrapper">
    <table class="table table-striped table-hover responsive dataTable no-footer inline collapsed" id="datatable_fixed_column" cellspacing="0" width="100%"  style="width: 100%;">
		<thead>
			<tr role="row" class="pmsFilterTHeader">
				<th>ID</th>
				<th>Nom</th>
				<th>Libellé</th>
				<th>De</th>
				<th>A</th>
				<th>Client</th>
				<th>Actif</th>
				<th>Actions</th>
			</tr>
			<tr role="row" class="pmsTHeader">
				<th>ID</th>
				<th>Nom</th>
				<th>Libellé</th>
				<th>De</th>
				<th>A</th>
				<th>Client</th>
				<th>Actif</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody id="tbody">
			<?php $i = 0;?>
				<?php foreach($users as $user): ?>
					<tr style="border-color: gray;">
						<td><?php echo $user->user_id; ?></td>
						<td><?php echo $user->user_name; ?></td>
						<td><?php echo $user->user_email; ?></td>
						<td><?php echo $user->user_last_login; ?></td>
						<td><?php echo $user->user_last_login; ?></td>
						<td><?php echo $user->user_level; ?></td>
						<td>
							<?php echo $user->user_banned; ?>
						</td>
						<td>
							</td>
					</tr>
				<?php endforeach;?>
		</tbody>
	</table>
</div>