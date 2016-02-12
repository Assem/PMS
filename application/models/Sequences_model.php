<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model to manage sequences used in the application to set default values in some tale columns
 * 
 * @author      Assem Bayahi
 */

class Sequences_model extends MY_Model {
	
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->table_name = 'sequences';
		$this->pk_column = 'id';
	}
	
	/**
	 * Get the sequence using it's key
	 * 
	 * @param string $key
	 */
	public function getSequenceByKey($key) {
		$query = $this->db->get_where($this->table_name, array('key' => $key), 1);
		
		if ($query->num_rows() == 1) {
			return $query->row();
		}
		
		return FALSE;
	}
	
	/**
	 * Get the next sequence using for the passed key sequence
	 * And increase the next_index value
	 * 
	 * @param string $key
	 */
	public function getNextSequenceByKey($key) {
		$query = $this->db->get_where($this->table_name, array('key' => $key), 1);
		
		if ($query->num_rows() == 1) {
			$sequence = $query->row();
			
			// increase sequence index
			$this->update($sequence->id, array('next_index' => $sequence->next_index + 1));
			
			return $this->_get_sequence_value($sequence);
		}
		
		return FALSE;
	}
	
	private function _get_sequence_value($sequence) {
		return  $sequence->prefix . str_pad($sequence->next_index, $sequence->fillers, "0", STR_PAD_LEFT);
	}
}