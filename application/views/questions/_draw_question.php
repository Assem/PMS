<?php
$options = array();
if($question->required) {
	$options['required'] = '1';
}

$input_name = 'answers['.$question->id.']';

switch ($question->type) {
	case 'free_text': 
		$options['maxlength']	= '255';
		$options['class']	= 'form-control';
		
		echo form_textarea($input_name, set_value($input_name, '', FALSE), $options);
	break;
	case 'one_choice':
		echo '<table class="answers">';
		foreach ($answers as $answer) {
			echo '<tr>';
				echo '<td>'.form_radio($input_name, $answer->id, set_radio($input_name, $answer->id)).'</td>';
				echo '<td>'.form_label($answer->description, $input_name).'</td>';
			echo '</tr>';
		}
		echo '</table>';
	break;
	case 'mutiple_choice':
		$input_name .= '[]';
		
		echo '<table style="width:100%">';
		foreach ($answers as $answer) {
			echo '<tr>';
				echo '<td>'.form_checkbox($input_name, $answer->id, set_checkbox($input_name, $answer->id)).'</td>';
				echo '<td>'.form_label($answer->description, $input_name).'</td>';
			echo '</tr>';
		}
		echo '</table>';
	break;
}