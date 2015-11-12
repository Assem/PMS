<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Examples Model
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class Pools_model extends MY_Model {
	
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('users_model');
		
		$this->table_name = 'pools';
		$this->pk_column = 'id';
	}
	
	/**
	 * Delete the user having the passed ID
	 * 
	 * @param integer $id
	 */
	public function delete($id){
		$this->db->delete(config_item('user_table'), array('user_id' => $id));
	}
	
	/**
	 * Update the user having the passed ID with the passed data
	 * 
	 * @param integer $id
	 * @param array $data
	 */
	public function update($id, $data){
		$this->db->update(config_item('user_table'), $data, array('user_id' => $id));
		
		if ($this->db->affected_rows() == 1) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Return the user how created the pool
	 * 
	 * @param integer $id
	 */
	public function getCreatedby($pool) {
		return $this->users_model->getRecordByID($pool->created_by);
	}
}

/* End of file users_model.php */
/* Location: /models/users_model.php */