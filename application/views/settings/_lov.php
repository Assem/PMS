<?php $i = 0; ?>
<?php foreach ($listes as $group => $liste): ?>
	<div class="lov-wrapper">
		<h3><?php echo $liste['label']; ?></h3>
		<button class="actions-button" onclick="add_lov('<?php echo $group; ?>');">
			<img class="action-icon mini" src="/assets/img/add.png" title="Ajouter">
		</button>
		<div class="dataTable_wrapper">
		    <table class="table table-striped table-hover responsive dataTable no-footer inline collapsed" id="" cellspacing="0" width="100%"  style="width: 100%;">
				<thead>
					<tr role="row" class="pmsTHeader">
						<th>Valeur</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody id="tbody">
					<?php foreach($liste['items'] as $item): ?>
						<tr style="border-color: gray;">
							<td width="80%">
								<div id="item_value_<?php echo $item->id; ?>">
									<?php echo $item->value; ?>
								</div>
						<input style="display: none" value="<?php echo $item->value; ?>" type="text" id="item_input_<?php echo $item->id; ?>" />
							</td>
							<td>
								<div style="display: none" id="edition_actions_<?php echo $item->id; ?>">
									<button class="actions-button" onclick="save_lov(<?php echo $item->id; ?>);">
										<img class="action-icon " src="/assets/img/save.png" title="Sauvegarder">
									</button>
									<button class="actions-button" onclick="edit_lov(<?php echo $item->id; ?>, '<?php echo $item->value; ?>');">
										<img class="action-icon " src="/assets/img/cancel.png" title="Annuler">
									</button>
								</div>
								<div id="actions_<?php echo $item->id; ?>">
									<button class="actions-button" onclick="edit_lov(<?php echo $item->id; ?>, '<?php echo $item->value; ?>');">
										<img class="action-icon " src="/assets/img/edit.png" title="Editer">
									</button>
									<button class="actions-button" onclick="delete_lov(<?php echo $item->id; ?>);">
										<img class="action-icon " src="/assets/img/delete.png" title="Supprimer">
									</button>
								</div>
							</td>
						</tr>
					<?php endforeach;?>
					<tr style="display: none" id="add_lov_row_<?php echo $group; ?>" style="border-color: gray;">
						<td width="80%">
							<input type="text" id="add_input_<?php echo $group; ?>" />
						</td>
						<td>
							<button class="actions-button" onclick="add_save_lov('<?php echo $group; ?>');">
								<img class="action-icon " src="/assets/img/save.png" title="Sauvegarder">
							</button>
							<button class="actions-button" onclick="add_lov('<?php echo $group; ?>');">
								<img class="action-icon " src="/assets/img/cancel.png" title="Annuler">
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<?php $i++ ?>
	<?php if($i % 2 == 0): ?>
		<hr style="width: 100%"/>
	<?php endif; ?>
<?php endforeach; ?>
<div style="clear: both;"></div>
