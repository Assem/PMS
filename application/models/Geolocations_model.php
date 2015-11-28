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
}