<h1 class="pmsH1">Listes des utilisateurs</h1>
<?php
	$this->load->view ( 'global/flash_messages', array('title' => $title) );
?>
<?php echo drawActionsMenuItem('users/add', 'add.png', 'Ajouter'); ?>

<div id="datatable_fixed_column_tableInfo" class="tableInfo"></div>
<div class="dataTable_wrapper">
    <table class="table table-striped table-hover responsive dataTable no-footer inline collapsed" id="datatable_fixed_column" cellspacing="0" width="100%"  style="width: 100%;">
		<thead>
			<tr role="row" class="pmsFilterTHeader">
				<th>Code</th>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Nom d'utilisateur</th>
				<th>Email</th>
				<th>GSM</th>
				<th>Rôle</th>
				<th>Actif</th>
				<th></th>
			</tr>
			<tr role="row" class="pmsTHeader">
				<th>Code</th>
				<th>Nom</th>
				<th>Prénom</th>
				<th>Nom d'utilisateur</th>
				<th>Email</th>
				<th>GSM</th>
				<th>Rôle</th>
				<th>Actif</th>
				<th style="max-width: 70px">Actions</th>
			</tr>
		</thead>
		<tbody id="tbody">
			<?php foreach($users as $user): ?>
				<tr style="border-color: gray;">
					<td><?php echo $user->pms_user_code; ?></td>
					<td><?php echo $user->pms_user_last_name; ?></td>
					<td><?php echo $user->pms_user_first_name; ?></td>
					<td><?php echo $user->user_name; ?></td>
					<td><?php echo $user->user_email; ?></td>
					<td><?php echo $user->pms_user_gsm; ?></td>
					<td><?php echo ucfirst($this->authentication->roles[$user->user_level]); ?></td>
					<td>
						<?php echo ($user->user_banned)? 0:1; ?>
					</td>
					<td>
						<?php drawActionsMenu('users', $user->user_id); ?>
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>