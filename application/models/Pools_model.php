<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      Assem Bayahi
 */

class Pools_model extends MY_Model {
	
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('users_model');
		$this->load->model('questions_model');
		
		$this->table_name = 'pools';
		$this->pk_column = 'id';
	}
	
	/**
	 * Return the user hwo created the pool
	 * 
	 * @param Pools $pool
	 */
	public function getCreatedby($pool) {
		return $this->users_model->getRecordByID($pool->created_by);
	}
	
	/**
	 * Return all the questions of the pool
	 * 
	 * @param Pools $pool
	 */
	public function getQuestions($pool) {
		$questions = $this->getMany2OneRecords('questions', 'id_pool', $pool->id);
		
		foreach ($questions as $question) {
			$question->type_name = $this->questions_model->getType($question);
		}
		
		return $questions;
	}
}

/* End of file users_model.php */
/* Location: /models/users_model.php */