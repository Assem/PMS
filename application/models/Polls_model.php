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
		$this->load->model('sheet_answers_model');
		
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
	 * Return all the questions of the poll with their answers
	 * 
	 * @param Polls $poll
	 */
	public function getQuestionsWithAnswers($poll) {
		$questions = $this->getMany2OneRecords('questions', 'id_poll', $poll->id, 'order asc');
		
		foreach ($questions as $question) {
			$question->type_name = $this->questions_model->getType($question);
			$question->answers = $this->questions_model->getAnswers($question->id);
			
			// if it's a choice question, it must contains at least two answers
			if(($question->type == 'multiple_choice' || $question->type == 'one_choice') && count($question->answers) < 2) {
				$question->warning = TRUE;
			} else {
				$question->warning = FALSE;
			}
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
	 * 
	 * @param bool $check_max If True, we return only polls not reaching their max sheet number
	 */
	public function getActivePolls($check_max=TRUE) {
		$request = $this->db->select('*')
			->from($this->table_name)
			->where('actif', 1)
			->group_start()
				->where('start_date =', NULL)
				->or_where('start_date <= NOW()')
			->group_end()
			->group_start()
				->where('end_date =', NULL)
				->or_where('end_date >= NOW()')
			->group_end()
			->order_by('label', 'asc');
		
		if($check_max) {
			$request->where('(polls.max_surveys_number = 0 OR (SELECT count(*) FROM sheets WHERE sheets.id_poll = polls.id) < polls.max_surveys_number)', NULL, FALSE);
		}
		
		return $request->get()->result();
	}
	
	/**
	 * Select all Polls with number of saved sheets
	 * 
	 * @return list
	 */
	public function getPollsWithSheetsNumber() {
		$results = $this->db->select($this->table_name.'.*, 
				(SELECT count(*) FROM sheets WHERE sheets.id_poll = polls.id) as sheets_number, 
				(SELECT count(*) FROM questions WHERE questions.id_poll = polls.id) as questions_number,
				(SELECT count(*) FROM questions q WHERE 
					q.id_poll = polls.id AND 
					q.type in ("multiple_choice", "one_choice") AND
					NOT EXISTS(SELECT count(id) as nbr_a FROM answers WHERE answers.id_question = q.id HAVING nbr_a > 1)) as questions_without_answers_number')
			->from($this->table_name)
			->get()->result();
		
		return $results;
	}
	
	/**
	 * Get all question's answers for a poll
	 * 
	 * @param int $poll_id
	 */
	public function getAllAnswers($poll_id) {
		return $this->sheet_answers_model->getPollAnswers($poll_id);
	}
}