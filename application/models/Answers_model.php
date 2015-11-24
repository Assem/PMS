<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @author      Assem Bayahi
 */

class Answers_model extends Ordered_model {
	
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('questions_model');
		
		$this->table_name = 'answers';
		$this->pk_column = 'id';
		$this->parent_column = 'id_question';
	}
	
	/**
	 * Return the Question to witch the Answer was added
	 * 
	 * @param Answers $answer
	 */
	public function getQuestion($answer) {
		return $this->questions_model->getRecordByID($answer->id_question);
	}
}