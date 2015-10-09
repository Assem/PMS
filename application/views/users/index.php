<div class="dataTable_wrapper">
    <table class="table table-striped table-hover responsive dataTable no-footer inline collapsed" id="datatable_fixed_column" cellspacing="0" width="100%"  style="width: 100%;">
		<thead>
			<tr role="row">
				<th>ID</th>
				<th>Nom</th>
				<th>Libellé</th>
				<th>De</th>
				<th>A</th>
				<th>Client</th>
				<th>Actif</th>
				<th>Actions</th>
			</tr>
			<tr role="row">
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
				<?php for($i=0; $i<5; $i++): ?>
					<tr style="border-color: gray;">
						<td>{{ event.id }}</td>
						<td>{{ event.name }}</td>
						<td>{{ event.commercialName }}</td>
						<td>{{ event.fromDatetime|date('d/m/Y H:i') }}</td>
						<td>{{ event.toDatetime|date('d/m/Y H:i') }}</td>
						<td>{{ event.broker }}</td>
						<td>
							{% if event.actif %}
								1
							{% else %}
								0
							{% endif %}
						</td>
						<td>
							</td>
					</tr>
				<?php endfor;?>
		</tbody>
	</table>
</div>