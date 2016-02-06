<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model to manage Listes of values
 * 
 * @author      Assem Bayahi
 */

class Lovs_model extends MY_Model {
	
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->table_name = 'lov';
	}
	
	/**
	 * Get liste of values for the passed group
	 * 
	 * @param string $group
	 */
	public function getList($group) {
		$req = $this->db->order_by('value', 'asc')
			->from($this->table_name)
			->where('group', $group);
		
		return $req->get()->result();
	}	
	
	/**
	 * Get liste of values with their parent for the passed group
	 * 
	 * @param string $group
	 */
	public function getListWithParent($group) {
		$results = $this->db->select('child.id, parent.id as parent_id, parent.value as parent, child.value as child FROM lov child', 
				false)
			->join('lov parent', 'parent.id = child.id_parent', 'LEFT')
			->where('child.group', $group)
			->order_by('parent asc, child asc')
			->get()
			->result();
		
		return $results;
	}	
	
	/**
	 * Get liste of values for the passed group: using the ids as keys
	 * 
	 * @param string $group
	 */
	public function getIDsListe($group) {
		$values = $this->getList($group);
		$result = array();
		
		foreach ($values as $value) {
			$result[$value->id] = $value;
		}
		
		return $result;
	}	
}