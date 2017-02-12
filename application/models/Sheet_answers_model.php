<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @author      Assem Bayahi
 */

class Sheet_answers_model extends MY_Model {
	
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->table_name = 'sheet_answers';
	}
	
	/**
	 * Insert all answers for a sheet
	 * 
	 * @param int $id_sheet
	 * @param array $data
	 */
	public function createFromSheet($id_sheet, $data) {
		$result = TRUE;
		
		foreach ($data as $id_question => $value) {
			if(is_array($value)) {
				if(array_key_exists('hidden', $value)) { // order question
					unset($value['hidden']);
					$val = '';
					foreach ($value as $answer_id => $order) {
						$val .= $answer_id . '|' . $order . '*';
					}

					$value = $val;
				} else { // multiple choice
					$value = implode(',', $value);
				}
			}
			
			$result = $result && $this->create(array(
				'id_question' 	=> $id_question,
				'id_sheet'		=> $id_sheet,
				'value'			=> $value
			));
		}
		
		return $result;
	}
	
	/**
	 * Get all the answers for a poll
	 * 
	 * @param int $id_poll
	 */
	public function getPollAnswers($id_poll) {
		$results = $this->db->select($this->table_name.'.*, questions.type', 
				false)
			->join('sheets', 'sheets.id = sheet_answers.id_sheet', 'LEFT')
			->join('questions', 'questions.id = sheet_answers.id_question', 'LEFT')
			->where('sheets.id_poll', $id_poll)
			->from($this->table_name)
			->order_by('id_question asc')
			->get()
			->result();
		
		return $results;
	}
}