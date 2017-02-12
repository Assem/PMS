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
		
		if($question->type == 'free_text' && $question->free_answer_type == 'numeric') {
			$options['type'] = 'number';
			$options['step'] = 'any';
			
			echo form_input(array(
				'name'	=> $input_name,
				'id'	=> $input_name,
				'value'	=> set_value($input_name, '', FALSE),
				'type'	=> 'number',
				'maxlength' => '11',
				'step'		=> 'any',
				'class'		=> 'form-control'
			));
		} else {
			echo form_textarea($input_name, set_value($input_name, '', FALSE), $options);
		}
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
	case 'multiple_choice':
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
	case 'ordered_choices':
		echo '<table style="width:100%">';
		echo form_hidden($input_name . '[hidden]');
		foreach ($answers as $answer) {
			$order_input_name = $input_name . '[' . $answer->id . ']';

			echo '<tr>';
				echo '<td class="order-input-column">' . form_input(array(
					'name'	=> $order_input_name,
					'id'	=> $order_input_name,
					'value'	=> set_value($order_input_name, '', FALSE),
					'type'	=> 'number',
					'maxlength' => '3',
					'min'		=> 1,
					'step'		=> 'any',
					'class'		=> 'form-control order-input',
					'data-qid'	=> $question->id
				)) . '</td>';
				echo '<td>'.form_label($answer->description, $order_input_name).'</td>';
			echo '</tr>';
		}
		echo '</table>';
	break;
}