<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PMS - Answers Controller
 *
 * @author      Assem Bayahi
*/

class Answers extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('answers_model', 'main_model');
		$this->load->model('questions_model');
		$this->load->model('polls_model');
		$this->load->helper(array('form', 'url', 'my_date'));
        $this->load->library('form_validation');
	}
	
	/**
     * Delete an answser
     *
     * @param int $id ID of the record to delete
     */
    public function delete($id=NULL) {
    	if( $this->require_role('admin,super-agent') ) {
    		$record = $this->_checkRecord($id);
    		
    		if($record) {
    			$question = $this->questions_model->getRecordByID($record->id_question);
	    		$poll = $this->questions_model->getPoll($question);
		    	$this->_can_edit($poll);
	    	
    			$this->main_model->delete($id);
    			$this->main_model->shiftDown($record->id_question, $record->order);
    			
    			$this->session->set_flashdata('success', 'Réponse supprimée avec succès!');
    			
    			redirect('/questions/edit/'.$record->id_question);
    		}
    	}
    }
	
	private function _getFields($action='edit', $data_values) {
    	$data = array(
			'fields' => array(
				'Description <font color="red">*</font>' 	=> form_textarea('description', $data_values['description'], array(
					'maxlength'	=> '255',
					'required' 	=> '1',
					'class'		=> 'form-control'
					)
				),
				'Valeur' 	=> form_input(array(
					'name'	=> 'value',
					'id'	=> 'value',
					'value'	=> $data_values['value'],
					'type'	=> 'number',
					'max' 	=> '99',
					'class'		=> 'form-control'
					)
				)
			)
		);
    	
    	return $data;
    }
    
	private function _setValidationRules($action='edit', $id=NULL) {
    	$this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('value', 'Valeur', 'trim|integer|max_length[2]');
    }
	
	/**
     * Check if the poll have already some sheets, if yes, we can not add, edit or delete question
     * 
     * @param poll $poll
     */
    private function _can_edit($poll) {
    	//we can not add a question to poll having already some sheets (otherwise, we will have a problem with the stats)
    	if($this->polls_model->countSheets($poll) > 0) {
    		$this->session->set_flashdata('error', 'Vous ne pouvez pas ajouter, éditer ou supprimer une question d\'un sondage qui possède des fiches!');
    		
    		redirect('/polls/view/'.$poll->id);
    	}
    }
	
	/**
     * Add a new answer to a question
     * 
     * @param int $id_question ID of the Question to witch the answer will be added
     */
    public function add($id_question) {
    	if( $this->require_role('admin,super-agent') ) {
    		if(!isset($id_question) || !is_numeric($id_question)) {
	    		show_404();
	    	}
	    		
	    	$question = $this->questions_model->getRecordByID($id_question);
	    	
	    	if(!$question) {
	    		show_404();
	    	}
	    	
	    	$poll = $this->questions_model->getPoll($question);
	    	$this->_can_edit($poll);
    	
    		$data = array(
    			'title' => "Ajouter une réponse",
    			'content' => 'answers/add'
    		);
    		
    		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
    			$data_values = array(
    				'description' 	=> set_value('description', '', FALSE),
    				'value' 		=> set_value('value')
    			);
    			
    			$this->_setValidationRules('add');
    			
				if ($this->form_validation->run()) {
					$data_values['order'] = $this->main_model->getNextIndex($id_question);
					$data_values['id_question'] = $id_question;
					
					if($this->main_model->create($data_values)){
    					$this->session->set_flashdata('success', 'Réponse créée avec succès!');
    					
    					redirect('/questions/edit/'.$id_question);
    				} else {
    					$this->session->set_flashdata('error', 'La création a échoué!');
    				}
                }
    		} else {
    			$data_values = array(
    				'description' 	=> '',
    				'value' 		=> ''
    			);
    		}
	    		
	    	$data['content_data'] = $this->_getFields('add', $data_values);
	    	$data['content_data']['question'] = $question;
	    	$data['content_data']['poll'] = $poll;
    		
    		$this->load->view('global/layout', $data);
    	}
    }
    
	/**
     * View a answer's detail
     * 
     * @param int $id ID of the answer to view
     */
    public function view($id=NULL) {
    	if( $this->require_role('admin,super-agent') ) {
    		$answer = $this->_checkRecord($id);
    		
    		$data = array(
    			'title' => "Detail d'une réponse",
    			'content' => 'answers/view',
    			'answer' => $answer
    		);
    		
    		if($answer){
    			$question = $this->questions_model->getRecordByID($answer->id_question);
	    		$poll = $this->questions_model->getPoll($question);
    			$poll->sheets_number = $this->polls_model->countSheets($poll);
    			
    			$data['content_data'] = array(
    				'question' 	=> $question,
    				'poll'		=> $poll,
    				'fields' 	=> array(
	    				'Description'		=> $answer->description,
    					'Valeur'			=> $answer->value
    				)
	    		);
    		}
    		
    		$this->load->view('global/layout', $data);
    	}
    }
    
	/**
     * Edit a poll
     *
     * @param int $id ID of the poll to edit
     */
    public function edit($id=NULL) {
    	if( $this->require_role('admin,super-agent') ) {
    		$answer = $this->_checkRecord($id);
    		
    		$data = array(
    			'title' => "Edition d'une réponse",
    			'content' => 'answers/edit',
    			'answer' => $answer
    		);
    		
    		if($answer) {
    			$question = $this->questions_model->getRecordByID($answer->id_question);
	    		$poll = $this->questions_model->getPoll($question);
	    		
	    		$this->_can_edit($poll);
	    		
	    		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
	    			$data_values = array(
	    				'description' 		=> set_value('description', '', FALSE),
    					'value' 			=> set_value('value')
	    			);
	    			
	    			$this->_setValidationRules('edit', $id);
	    			
	    			if ($this->form_validation->run()) {
		    			if($this->main_model->update($id, $data_values)){
	    					$this->session->set_flashdata('success', 'Réponse mise à jour avec succès!');
	    					
	    					redirect('/questions/edit/'.$question->id);
	    				} else {
	    					$this->session->set_flashdata('error', 'La mise à jour a échoué!');
	    				}
	                }
	    		} else {
	    			$data_values = array(
	    				'description' 	=> $answer->description,
    					'value' 		=> $answer->value
	    			);
	    		}
	    		
	    		$data['content_data'] = $this->_getFields('edit', $data_values);
	    		$data['content_data']['question'] = $question;
	    		$data['content_data']['poll'] = $poll;
    		}
    		
    		$this->load->view('global/layout', $data);
    	}
    }
    
    /**
     * Change the rank/order of an answer
     * 
     * @param int $id
     * @param string $action
     */
    public function move_rank($id=NULL, $action=NULL) {
    	if( $this->require_role('admin,super-agent') ) {
    		$answer = $this->_checkRecord($id);
    		
    		if($answer) {
    			switch ($action) {
    				case 'first':
    					$new_rank = 1;
    					$function = 'shiftUp';
    					$order_from = $new_rank - 1;
    					$order_to = $answer->order;
    					break;
    				case 'up':
    					$new_rank = $answer->order - 1;
    					$function = 'shiftUp';
    					$order_from = $new_rank - 1;
    					$order_to = $answer->order;
    					break;
    				case 'down':
    					$new_rank = $answer->order + 1;
    					$function = 'shiftDown';
    					$order_from = $new_rank - 1;
    					$order_to = $answer->order + 2;
    					break;
    				case 'last':
    					$new_rank = $this->main_model->getNextIndex($answer->id_question) - 1;
    					$function = 'shiftDown';
    					$order_from = $answer->order;
    					$order_to = $new_rank + 1;
    					break;
    			}
    					
    			$this->main_model->$function($answer->id_question, $order_from, $order_to);
    					
    			if($this->main_model->update($id, array('order' => $new_rank))) {
    				$this->session->set_flashdata('success', 'Rang mis à jour avec succès!');
    			} else {
    				$this->session->set_flashdata('error', 'La mise à jour a échoué!');
    			}
    					
    			redirect('/questions/edit/'.$answer->id_question);
    		}
    	}
    }
}