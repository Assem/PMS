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
	}
	
	/**
	 * Insert a new record
	 * 
	 * @param array $data
	 */
	public function create($data) {
		$this->db->trans_start();
		
		$sheet_data = array(
			'id_pool' 		=> $data['id_pool'],
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
	 * Return all the sheets of a pool
	 * 
	 * @param int $pool_id
	 */
	function getPoolSheets($pool_id) {
		$results = $this->db->where('id_pool', $pool_id)
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
		$results = $this->db->select($this->table_name.'.*, pools.code as pool_code, pools.label as pool_label', false)
			->where($this->table_name.'.created_by', $user_id)
			->order_by($this->table_name.'.creation_date', 'desc')
			->from($this->table_name)
			->join('pools', 'pools.id = sheets.id_pool')
			->get()
			->result();
		
		return $results;
	}
	
	/**
	 * Return all sheets with info on the Pool and Agent
	 * 
	 */
	function getSheetsWithPoolAndUser() {
		$results = $this->db->select($this->table_name.'.*, 
				pools.code as pool_code, pools.label as pool_label, 
				users.pms_user_last_name, users.pms_user_first_name', false)
			->order_by($this->table_name.'.creation_date', 'desc')
			->from($this->table_name)
			->join('pools', 'pools.id = sheets.id_pool')
			->join('users', 'users.user_id = sheets.created_by')
			->get()
			->result();
		
		return $results;
	}
}