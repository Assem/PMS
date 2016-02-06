<div class="countries-wrapper">
	<h3>Pays</h3>
	<button class="actions-button" onclick="add_lov('country');">
		<img class="action-icon mini" src="/assets/img/add.png" title="Ajouter">
	</button>
	<div class="dataTable_wrapper">
	    <table class="table table-striped table-hover responsive dataTable no-footer inline collapsed" id="" style="width: 100%;">
			<thead>
				<tr role="row" class="pmsTHeader">
					<th>Pays</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody id="tbody">
				<?php foreach($countries as $country): ?>
					<tr style="border-color: gray;">
						<td width="70%">
							<div id="item_value_<?php echo $country->id; ?>">
								<?php echo $country->value; ?>
							</div>
							<input style="display: none" value="<?php echo $country->value; ?>" type="text" id="item_input_<?php echo $country->id; ?>" />
						</td>
						<td>
							<div style="display: none" id="edition_actions_<?php echo $country->id; ?>">
								<button class="actions-button" onclick="save_lov(<?php echo $country->id; ?>);">
									<img class="action-icon " src="/assets/img/save.png" title="Sauvegarder">
								</button>
								<button class="actions-button" onclick="edit_lov(<?php echo $country->id; ?>, '<?php echo $country->value; ?>');">
									<img class="action-icon " src="/assets/img/cancel.png" title="Annuler">
								</button>
							</div>
							<div id="actions_<?php echo $country->id; ?>">
								<button class="actions-button" onclick="edit_lov(<?php echo $country->id; ?>, '<?php echo $country->value; ?>');">
									<img class="action-icon " src="/assets/img/edit.png" title="Editer">
								</button>
								<button class="actions-button" onclick="delete_lov(<?php echo $country->id; ?>);">
									<img class="action-icon " src="/assets/img/delete.png" title="Supprimer">
								</button>
								<button class="actions-button" onclick="add_town(<?php echo $country->id; ?>);">
									<img class="action-icon " src="/assets/img/add_min.png" title="Ajouter une ville pour ce pays">
								</button>
								<button class="actions-button" onclick="filter_town('<?php echo $country->value; ?>', <?php echo $country->id; ?>);">
									<img class="action-icon " src="/assets/img/search.png" title="Rechercher les villes de ce pays">
								</button>
							</div>
						</td>
					</tr>
				<?php endforeach;?>
				<tr style="display: none" id="add_lov_row_country" style="border-color: gray;">
					<td width="70%">
						<input type="text" id="add_input_country" />
					</td>
					<td>
						<button class="actions-button" onclick="add_save_lov('country');">
							<img class="action-icon " src="/assets/img/save.png" title="Sauvegarder">
						</button>
						<button class="actions-button" onclick="add_lov('country');">
							<img class="action-icon " src="/assets/img/cancel.png" title="Annuler">
						</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div style="clear: both;"></div>

<div class="towns-wrapper">
	<h3>Villes</h3>
	<button class="actions-button" onclick="add_lov('town');">
		<img class="action-icon mini" src="/assets/img/add.png" title="Ajouter">
	</button>
	<div class="dataTable_wrapper">
	    <table class="table table-striped table-hover responsive dataTable no-footer inline collapsed" id="towns_table" style="width: 100%;">
			<thead>
				<tr role="row" class="pmsFilterTHeader">
					<th data-prefix="FILTER" id="country_filter">Pays</th>
					<th>Ville</th>
					<th></th>
				</tr>
				<tr role="row" class="pmsTHeader">
					<th>Pays</th>
					<th>Ville</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody id="tbody">
				<?php foreach($towns as $town): ?>
					<tr style="border-color: gray;">
						<td width="40%">
							<div id="item_parent_value_<?php echo $town->id; ?>">
								<div style="display: none">FILTER<?php echo $town->parent; ?></div>
								<?php echo $town->parent; ?>
							</div>
							<select style="display: none; width:100%" id="item_parent_input_<?php echo $town->id; ?>" >
								<?php foreach($countries as $country): ?>
									<option value="<?php echo $country->id; ?>" <?php echo ($town->parent_id == $country->id)? "selected":""; ?>>
										<?php echo $country->value; ?>
									</option>
								<?php endforeach; ?>
							</select>
						</td>
						<td width="40%">
							<div id="item_value_<?php echo $town->id; ?>">
								<?php echo $town->child; ?>
							</div>
							<input style="display: none; width:100%" value="<?php echo $town->child; ?>" type="text" id="item_input_<?php echo $town->id; ?>" />
						</td>
						<td>
							<div style="display: none" id="edition_actions_<?php echo $town->id; ?>">
								<button class="actions-button" onclick="save_lov(<?php echo $town->id; ?>);">
									<img class="action-icon " src="/assets/img/save.png" title="Sauvegarder">
								</button>
								<button class="actions-button" onclick="edit_lov(<?php echo $town->id; ?>, '<?php echo $town->child; ?>', <?php echo $town->parent_id; ?>);">
									<img class="action-icon " src="/assets/img/cancel.png" title="Annuler">
								</button>
							</div>
							<div id="actions_<?php echo $town->id; ?>">
								<button class="actions-button" onclick="edit_lov(<?php echo $town->id; ?>, '<?php echo $town->child; ?>', <?php echo $town->parent_id; ?>);">
									<img class="action-icon " src="/assets/img/edit.png" title="Editer">
								</button>
								<button class="actions-button" onclick="delete_lov(<?php echo $town->id; ?>);">
									<img class="action-icon " src="/assets/img/delete.png" title="Supprimer">
								</button>
							</div>
						</td>
					</tr>
				<?php endforeach;?>
				<tr style="display: none" id="add_lov_row_town" style="border-color: gray;">
					<td width="40%">
						<select id="add_input_parent_town" style="width:100%" >
							<?php foreach($countries as $country): ?>
								<option value="<?php echo $country->id; ?>">
									<?php echo $country->value; ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
					<td width="40%">
						<input type="text" id="add_input_town" style="width:100%" >
					</td>
					<td>
						<button class="actions-button" onclick="add_save_lov('town', true);">
							<img class="action-icon " src="/assets/img/save.png" title="Sauvegarder">
						</button>
						<button class="actions-button" onclick="add_lov('town', true);">
							<img class="action-icon " src="/assets/img/cancel.png" title="Annuler">
						</button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div style="clear: both;"></div>