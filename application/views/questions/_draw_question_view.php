<?php
switch ($type) {
	case 'free_text': 
		$answer = $answers[0];
		echo $answer->value;
	break;
	case 'one_choice':
		foreach ($answers as $answer) {
			$img = '<img class="answer_tick" src="'.base_url("assets/img/not_selected.png").'" />';
			if($answer->value == $answer->a_id) {
				$img = '<img class="answer_tick" src="'.base_url("assets/img/selected.png").'" />';
			}
			echo $img.$answer->a_description.'<br>';
		}
	break;
	case 'multiple_choice':
		foreach ($answers as $answer) {
			$choices = explode(',', $answer->value);
			
			$img = '<img class="answer_tick" src="'.base_url("assets/img/not_selected.png").'" />';
			if(in_array($answer->a_id, $choices)) {
				$img = '<img class="answer_tick" src="'.base_url("assets/img/selected.png").'" />';
			}
			echo $img.$answer->a_description.'<br>';
		}
	break;
}