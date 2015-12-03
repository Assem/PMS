<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      Assem Bayahi
 */

class Polls_model extends MY_Model {
	
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('users_model');
		$this->load->model('questions_model');
		$this->load->model('sheets_model');
		
		$this->table_name = 'polls';
		$this->pk_column = 'id';
	}
	
	/**
	 * Return the user hwo created the poll
	 * 
	 * @param Polls $poll
	 */
	public function getCreatedby($poll) {
		return $this->users_model->getRecordByID($poll->created_by);
	}
	
	/**
	 * Return all the questions of the poll
	 * 
	 * @param Polls $poll
	 */
	public function getQuestions($poll) {
		$questions = $this->getMany2OneRecords('questions', 'id_poll', $poll->id, 'order asc');
		
		foreach ($questions as $question) {
			$question->type_name = $this->questions_model->getType($question);
		}
		
		return $questions;
	}
	
	/**
	 * Count all the sheets of the poll
	 * 
	 * @param Polls $poll
	 */
	public function countSheets($poll) {
		$this->db->where('id_poll', $poll->id);
		$this->db->from('sheets');
		return $this->db->count_all_results();
	}
	
	/**
	 * Return only active polls: Active = True and start_date <= NOW <= end_date (if dates are defined)
	 */
	public function getActivePolls() {
		$results = $this->db->select('*')
			->from($this->table_name)
			->where('actif', 1)
			->where('(SELECT count(*) FROM sheets WHERE sheets.id_poll = polls.id) < polls.max_surveys_number', NULL, FALSE)
			->group_start()
				->where('start_date =', NULL)
				->or_where('start_date <= NOW()')
			->group_end()
			->group_start()
				->where('end_date =', NULL)
				->or_where('end_date >= NOW()')
			->group_end()
			->order_by('label', 'asc')
		->get()->result();
		
		return $results;
	}
	
	public function getPollsWithSheetsNumber() {
		$results = $this->db->select($this->table_name.'.*, (SELECT count(*) FROM sheets WHERE sheets.id_poll = polls.id) as sheets_number')
			->from($this->table_name)
			->get()->result();
		
		return $results;
	}
}