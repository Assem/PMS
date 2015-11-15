<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @author      Assem Bayahi
 */

class Questions_model extends MY_Model {
	
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('pools_model');
		
		$this->table_name = 'questions';
		$this->pk_column = 'id';
	}
	
	/**
	 * Return the pool of the question
	 * 
	 * @param Question $question
	 */
	public function getPool($question) {
		return $this->pools_model->getRecordByID($question->id_pool);
	}
	
	/**
	 * Return all the questions of the pool
	 * 
	 * @param Pools $pool
	 */
	public function getQuestions($pool) {
		return $this->getMany2OneRecords('questions', $pool->id);
	}
	
	/**
	 * Get the list of available question types
	 * 
	 */
	public function getTypes() {
		return array(
			'mutiple_choice' => 'Choix mutiple',
			'one_choice' => 'Choix unique',
			'free_text' => 'Réponse libre'
		);
	}
	
	public function getType($question) {
		$types = $this->getTypes();
		
		return $types[$question->type];
	}
	
	/**
	 * Get the next index to use as the rank of newly created question
	 * 
	 * @param int $id_pool
	 */
	public function getNextIndex($id_pool) {
		$this->db->select_max('order');
		$this->db->from($this->table_name);
		$this->db->where(array('id_pool' => $id_pool));
		$result = $this->db->get()->row();
		
		if($result->order) {
			return $result->order + 1;
		}
		
		return 1;
	}
	
	/**
	 * Update the ranks of a pool's questions starting from a defined rank
	 * (called after deleting a question for example)
	 * 
	 * @param int $id_pool Reoder questions of this pool
	 * @param int $reorder_from Reorder questions having a rank above this
	 * @param string $delta Shifting sense (up or down)
	 */
	private function reorder($id_pool, $reorder_from, $delta, $reorder_to=NULL) {
		
		
		
		$where = array(
			'id_pool' 	=> $id_pool,
			'order >'	=> $reorder_from
		);
		
		if($reorder_to) {
			$where['order <'] = $reorder_to;
		}
		
		$this->db->set('`order`', "`order` $delta", FALSE);
		$this->db->where($where);
		$this->db->update($this->table_name);
	}
	
	/**
	 * Move up a list of questions order
	 * 
	 * @param int $id_pool
	 * @param int $reorder_from
	 * @param int $reorder_to
	 */
	public function shiftUp($id_pool, $reorder_from, $reorder_to=NULL) {
		$this->reorder($id_pool, $reorder_from, '+ 1', $reorder_to);
	}
	
	/**
	 * Move down a list of questions order
	 * 
	 * @param int $id_pool
	 * @param int $reorder_from
	 * @param int $reorder_to
	 */
	public function shiftDown($id_pool, $reorder_from, $reorder_to=NULL) {
		$this->reorder($id_pool, $reorder_from, '- 1', $reorder_to);
	}
}

/* End of file users_model.php */
/* Location: /models/users_model.php */