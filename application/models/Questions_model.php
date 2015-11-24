<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @author      Assem Bayahi
 */

class Questions_model extends Ordered_model {
	
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('pools_model');
		
		$this->table_name = 'questions';
		$this->pk_column = 'id';
		$this->parent_column = 'id_pool';
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
	 * Return all the answers of the question
	 * 
	 * @param int $id_question
	 */
	public function getAnswers($id_question) {
		$answers = $this->getMany2OneRecords('answers', 'id_question', $id_question, 'order asc');
		
		return $answers;
	}

	/**
	 * Delete all the answers of a question
	 * 
	 * @param int $id_question
	 */
	private function deleteAnswers($id_question) {
		$this->db->delete('answers', array('id_question' => $id_question));
	}
	
	/**
	 * If update goes fine then we have to delete all old answers if needed
	 * 
	 * {@inheritDoc}
	 * @see MY_Model::update()
	 */
	public function update($id, $data) {
		$question = $this->getRecordByID($id);
		$res = parent::update($id, $data);
		
		if($res && ($data['type'] != $question->type) && ($data['type'] == 'free_text')) {
			$this->deleteAnswers($id);
		}
		
		return $res;
	}
}