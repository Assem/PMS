<?php

function drawModelData($fields, $column_per_line, $class='view-form') {
	echo '<table class="'.$class.'">';
	echo '<tr>';
	$i = 1;
	foreach ($fields as $label => $field) {
		echo "<th>$label</th>";
		echo "<td>$field</td>";
		
		if(($i % $column_per_line) == 0) echo '</tr><tr>';
		
		$i++;
	}
	echo '</tr>';
	echo '</table>';
}

/**
 * Create a link with icon
 * 
 * @param string $url link url
 * @param string $icon name of the image file to use
 * @param string $title title of the img
 * @param string $class class added to the <a> tag
 */
function drawActionsMenuItem($url, $icon, $title, $class='') {
	echo secure_anchor($url, '<img class="action-icon '.$class.'" src="'.base_url("assets/img/$icon").'" title="'.$title.'"/>');
}

/**
 * Draw a three links to manage an object (view-edit-delete)
 * 
 * @param string $controller module controller
 * @param string $id id of the object
 */
function drawActionsMenu($controller, $id) {
	echo drawActionsMenuItem("$controller/view/$id", 'view.png', 'Afficher');
	echo drawActionsMenuItem("$controller/edit/$id", 'edit.png', 'Editer');
	echo drawActionsMenuItem("$controller/delete/$id", 'delete.png', 'Supprimer', 'delete-action');
}