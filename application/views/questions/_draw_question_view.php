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
	case 'ordered_choices':
		// parse answers
		$user_answers = array();
		$last = 1000;
		foreach (explode('*', $answers[0]->value) as $u_answer) {
			if($u_answer) {
				$u_answer = explode('|', $u_answer);
				$order = $u_answer[1];

				if(!$order) {
					$order = $last;
					$last++;
				}

				$user_answers[$u_answer[0]] = $order;
			}
		}

		asort($user_answers);

		// Order answers by the user order
		foreach ($user_answers as $id => $order) {
			foreach ($answers as $answer) {
				if($id == $answer->a_id) {
					if($order >= 1000) {
						$order = 0;
					}
					echo '<b>' . $order . '.&nbsp;&nbsp;</b>' .$answer->a_description.'<br>';
				}
			}
		}
	break;
}