<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @author      Assem Bayahi
 */

class Geolocations_model extends MY_Model {
	
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->table_name = 'geolocations';
	}
	
	/**
	 * Get the geolocation data of a sheet
	 * 
	 * @param int $sheet_id
	 */
	public function getSheetPosition($sheet_id) {
		$result = $this->db->where('id_sheet', $sheet_id)
			->from($this->table_name)
			->get()
			->row();
		
		return $result;
	}
	
	/**
	 * Get geolocation errors
	 * 
	 * @param int $limit
	 */
	public function getErrors($limit=null) {
		$req = $this->db->select($this->table_name.'.*, 
				users.pms_user_last_name, users.pms_user_first_name', false)
			->order_by($this->table_name.'.creation_date', 'desc')
			->from($this->table_name)
			->join('users', 'users.user_id = id_user')
			->where('error IS NOT NULL AND error != ""');
		
		if($limit) {
			$req->limit($limit);
		}
		
		return $req->get()->result();
	}	
}