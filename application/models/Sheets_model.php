<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @author      Assem Bayahi
 */

class Sheets_model extends MY_Model {
	
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->table_name = 'sheets';
		
		$this->load->model('geolocations_model');
		$this->load->model('sheet_answers_model');
		$this->load->model('polls_model');
		$this->load->model('respondents_model');
		$this->load->model('users_model');
	}
	
	/**
	 * Insert a new record
	 * 
	 * @param array $data
	 */
	public function create($data) {
		$this->db->trans_start();
		
		$sheet_data = array(
			'id_poll' 		=> $data['id_poll'],
			'id_respondent' => $data['id_respondent'],
			'created_by' 	=> $data['created_by'],
			'creation_date' => $data['creation_date'],
			'notes' 		=> $data['notes'],
		);
		
		$this->db->set($sheet_data)->insert($this->table_name);
		
		if ($this->db->affected_rows() != 1) {
			return FALSE;
		}
		
		$sheet_id = $this->db->insert_id();
		
		//save geolocations data
		$lat = $long = NULL;
		if(!empty($data['position'])) {
			$lat = explode(',', $data['position'])[0];
			$long = explode(',', $data['position'])[1];
		}
		
		$geolocation_data = array(
			'id_user' 		=> $data['created_by'],
			'id_sheet' 		=> $sheet_id,
			'creation_date' => $data['creation_date'],
			'error' 		=> $data['geo_error'],
			'latitude' 		=> $lat,
			'longitude' 	=> $long
		);
		
		$this->geolocations_model->create($geolocation_data);
		
		//save answers
		$this->sheet_answers_model->createFromSheet($sheet_id, $data['answers']);
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE) {
		    return FALSE;
		}
		
		return $sheet_id;
	}
	
	/**
	 * Return all the sheets of a poll
	 * 
	 * @param int $poll_id
	 */
	function getPollSheets($poll_id) {
		$results = $this->db->where('id_poll', $poll_id)
			->order_by('creation_date', 'desc')
			->from($this->table_name)
			->join('users', 'users.user_id = sheets.created_by')
			->get()
			->result();
		
		return $results;
	}
	
	/**
	 * Return all the sheets created by a user
	 * 
	 * @param int $user_id
	 */
	function getUserSheets($user_id) {
		$results = $this->db->select($this->table_name.'.*, polls.code as poll_code, polls.label as poll_label', false)
			->where($this->table_name.'.created_by', $user_id)
			->order_by($this->table_name.'.creation_date', 'desc')
			->from($this->table_name)
			->join('polls', 'polls.id = sheets.id_poll')
			->get()
			->result();
		
		return $results;
	}
	
	/**
	 * Return all sheets with info on the Poll and Agent
	 * 
	 */
	function getSheetsWithPollAndUser() {
		$results = $this->db->select($this->table_name.'.*, 
				polls.code as poll_code, polls.label as poll_label, 
				users.pms_user_last_name, users.pms_user_first_name', false)
			->order_by($this->table_name.'.creation_date', 'desc')
			->from($this->table_name)
			->join('polls', 'polls.id = sheets.id_poll')
			->join('users', 'users.user_id = sheets.created_by')
			->get()
			->result();
		
		return $results;
	}
	
	/**
	 * Return the poll of the sheet
	 * 
	 * @param sheet $sheet
	 */
	function getPoll($sheet) {
		return $this->polls_model->getRecordByID($sheet->id_poll);
	}
	
	/**
	 * Return the respondent data of the sheet
	 * 
	 * @param sheet $sheet
	 */
	function getRespondent($sheet) {
		return $this->respondents_model->getRecordByID($sheet->id_respondent);
	}
	
	/**
	 * Return the user hwo created the sheet
	 * 
	 * @param sheet $sheet
	 */
	public function getCreatedby($sheet) {
		return $this->users_model->getRecordByID($sheet->created_by);
	}
	
	/**
	 * Return the geolocation data of the sheet
	 * 
	 * @param sheet $sheet
	 */
	public function getLocation($sheet) {
		return $this->geolocations_model->getSheetPosition($sheet->id);
	}
	
	/**
	 * Return all the answers of the sheet
	 * 
	 * @param sheet $sheet
	 */
	public function getAnswers($sheet) {
		$results = $this->db->select('sheet_answers.*, 
				questions.description as q_description,
				questions.id as q_id,
				questions.type as q_type,
				questions.order as q_order,
				answers.id as a_id,
				answers.description as a_description,
				answers.order as a_order', 
				false)
			->where('sheet_answers.id_sheet', $sheet->id)
			->from('sheet_answers')
			->join('questions', 'questions.id = sheet_answers.id_question')
			->join('answers', 'answers.id_question = questions.id', 'LEFT')
			->order_by('questions.order asc, answers.order asc')
			->get()
			->result();
		
		return $results;
	}
	
	/**
	 * Delete all sheets of a poll
	 * 
	 * @param int $poll_id
	 */
	public function delete_from_poll($poll_id) {
		$this->db->delete($this->table_name, array('id_poll' => $poll_id));
	}
}