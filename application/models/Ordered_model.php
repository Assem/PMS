<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * A model containing functions to manage object Order
 * 
 * @author      Assem Bayahi
 */

class Ordered_model extends MY_Model {
	//column used to manage elements orders
	protected $order_column = 'order';
	protected $parent_column;
			
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Get the next index to use as the rank of newly created record
	 * 
	 * @param int $id_parent
	 */
	public function getNextIndex($id_parent) {
		$col = $this->order_column;
		
		$this->db->select_max($this->order_column);
		$this->db->from($this->table_name);
		$this->db->where(array($this->parent_column => $id_parent));
		$result = $this->db->get()->row();
		
		if($result->$col) {
			return $result->$col + 1;
		}
		
		return 1;
	}
	
	/**
	 * Update the ranks of a groups of records starting from a defined rank
	 * (called after deleting a record for example)
	 * 
	 * @param int $id_parent Reoder records of this parent record
	 * @param int $reorder_from Reorder questions having a rank above this
	 * @param string $delta Shifting sense (up or down)
	 */
	private function reorder($id_parent, $reorder_from, $delta, $reorder_to=NULL) {
		$where = array(
			$this->parent_column 		=> $id_parent,
			$this->order_column.' >'	=> $reorder_from
		);
		
		if($reorder_to) {
			$where[$this->order_column.' <'] = $reorder_to;
		}
		
		$this->db->set('`'.$this->order_column.'`', "`".$this->order_column."` $delta", FALSE);
		$this->db->where($where);
		$this->db->update($this->table_name);
	}
	
	/**
	 * Move up a list of records order
	 * 
	 * @param int $id_parent
	 * @param int $reorder_from
	 * @param int $reorder_to
	 */
	public function shiftUp($id_parent, $reorder_from, $reorder_to=NULL) {
		$this->reorder($id_parent, $reorder_from, '+ 1', $reorder_to);
	}
	
	/**
	 * Move down a list of records order
	 * 
	 * @param int $id_parent
	 * @param int $reorder_from
	 * @param int $reorder_to
	 */
	public function shiftDown($id_parent, $reorder_from, $reorder_to=NULL) {
		$this->reorder($id_parent, $reorder_from, '- 1', $reorder_to);
	}
}