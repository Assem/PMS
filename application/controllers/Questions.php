<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PMS - Questions Controller
 *
 * @author      Assem Bayahi
*/

class Questions extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('questions_model', 'main_model');
		$this->load->model('polls_model');
		$this->load->helper(array('form', 'url', 'my_date'));
        $this->load->library('form_validation');
	}
	
	/**
     * Delete a question
     *
     * @param int $id ID of the record to delete
     */
    public function delete($id=NULL) {
    	if( $this->require_role('admin,super-agent') ) {
    		$record = $this->_checkRecord($id);
    		
    		$poll = $this->polls_model->getRecordByID($record->id_poll);
	    	
	    	$this->_can_edit($poll);
    		
    		if($record) {
    			$this->main_model->delete($id);
    			$this->main_model->shiftDown($record->id_poll, $record->order);
    			
    			$this->session->set_flashdata('success', 'Question supprimée avec succès!');
    			
    			redirect('/polls/edit/'.$record->id_poll);
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
				'Type <font color="red">*</font>' 	=> form_dropdown('type', $this->main_model->getTypes(), $data_values['type'], 'class="form-control"'),
				'Obligatoire'		=> form_checkbox('required', '1', $data_values['required'], 'class="form-control"'),
				'Type de réponse'	=> form_dropdown('free_answer_type', $this->main_model->getFreeAnswerTypes(), $data_values['free_answer_type'], 'class="form-control"')
			)
		);
    	
    	return $data;
    }
    
	private function _setValidationRules($action='edit', $id=NULL) {
    	$this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[255]');
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
     * Add a new question to a Poll
     * 
     * @param int $poll_id ID of the Poll to witch the question will be added
     */
    public function add($poll_id) {
    	if( $this->require_role('admin,super-agent') ) {
    		if(!isset($poll_id) || !is_numeric($poll_id)) {
	    		show_404();
	    	}
	    		
	    	$poll = $this->polls_model->getRecordByID($poll_id);
	    	
	    	if(!$poll) {
	    		show_404();
	    	}
	    	
	    	$this->_can_edit($poll);
	    	
	    	$data = array(
    			'title' => "Ajouter une question",
    			'content' => 'questions/add',
	    		'js_to_load' => array('questions.js')
    		);
	    	
	    	$data_values = array(
    			'description' 		=> set_value('description', '', FALSE),
    			'type' 				=> set_value('type', ''),
	    		'free_answer_type' 	=> set_value('free_answer_type', 'alphanumeric'),
    			'required' 			=> set_value('required', FALSE)
    		);
	    	
    		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
    			$this->_setValidationRules('add');
    			
				if ($this->form_validation->run()) {
					$data_values['required']	= (empty($data_values['required']))? '0' : '1';
					$data_values['order'] = $this->main_model->getNextIndex($poll_id);
					$data_values['id_poll'] = $poll->id;
					
					if($id = $this->main_model->create($data_values)){
    					$this->session->set_flashdata('success', 'Question créée avec succès!');
    					
    					// if the question is a choice one, we stay on the question page, so the user can add answers
    					if($data_values['type'] == 'multiple_choice' || $data_values['type'] == 'one_choice') {
    						redirect('/questions/edit/'.$id);
    					}
    					redirect('/polls/edit/'.$poll->id);
    				} else {
    					$this->session->set_flashdata('error', 'La création a échoué!');
    				}
                }
    		}
	    		
	    	$data['content_data'] = $this->_getFields('add', $data_values);
	    	$data['content_data']['poll'] = $poll;
    		
    		$this->load->view('global/layout', $data);
    	}
    }
    
    /**
     * View a question's detail
     * 
     * @param int $id ID of the question to view
     */
    public function view($id=NULL) {
    	if( $this->require_role('admin,super-agent') ) {
    		$question = $this->_checkRecord($id);
    		
    		$data = array(
    			'title' => "Detail d'une question",
    			'content' => 'questions/view',
    			'js_to_load' => array('questions.js'),
    			'question' => $question
    		);
    		
    		if($question){
    			$data['answers'] = $this->main_model->getAnswers($question->id);
    			
    			$poll = $this->main_model->getPoll($question);
    			$poll->sheets_count = $this->polls_model->countSheets($poll);
    			
    			$data['poll'] = $poll;
    			
    			$data['content_data'] = array(
    				'fields' => array(
	    				'Description'		=> $question->description,
    					'Type'				=> $this->main_model->getType($question),
    					'Obligatoire'		=> ($question->required)? 'OUI' : 'NON'
    				)
	    		);
    			
    			if($question->type == 'free_text') {
    				$data['content_data']['fields']['Type de réponse'] = $this->main_model->getFreeAnswerType($question);
    			}
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
    		$question = $this->_checkRecord($id);
    		$poll = $this->main_model->getPoll($question);
    		
    		$this->_can_edit($poll);
    		
    		$data = array(
    			'title' => "Edition d'une question",
    			'content' => 'questions/edit',
    			'js_to_load' => array('questions.js'),
    			'question' => $question,
    			'poll'		=> $poll
    		);
    		
    		if($question) {
    			$answers = $this->main_model->getAnswers($question->id);
    			$data['answers'] = $answers;
    			
	    		$data_values = array(
	    			'description' 			=> set_value('description', $question->description, FALSE),
    				'type' 					=> set_value('type', $question->type),
	    			'free_answer_type' 		=> set_value('free_answer_type', $question->free_answer_type),
    				'required' 				=> set_value('required', $question->required)
	    		);
	    		
	    		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
	    			$this->_setValidationRules('edit', $id);
	    			
	    			if ($this->form_validation->run()) {
		    			$data_values['required']	= (empty($data_values['required']))? '0' : '1';
						
						if($this->main_model->update($id, $data_values)){
							$this->session->set_flashdata('success', 'Question mise à jour avec succès!');
	    					
	    					redirect('/polls/edit/'.$question->id_poll);
	    				} else {
	    					$this->session->set_flashdata('error', 'La mise à jour a échoué!');
	    				}
	                }
	    		}
	    		
	    		$data['content_data'] = $this->_getFields('edit', $data_values);
    		}
    		
    		$this->load->view('global/layout', $data);
    	}
    }
    
    /**
     * Change the rank/order of a question
     * 
     * @param int $id
     * @param string $action
     */
    public function move_rank($id=NULL, $action=NULL) {
    	if( $this->require_role('admin,super-agent') ) {
    		$question = $this->_checkRecord($id);
    		
    		if($question) {
    			switch ($action) {
    				case 'first':
    					$new_rank = 1;
    					$function = 'shiftUp';
    					$order_from = $new_rank - 1;
    					$order_to = $question->order;
    					break;
    				case 'up':
    					$new_rank = $question->order - 1;
    					$function = 'shiftUp';
    					$order_from = $new_rank - 1;
    					$order_to = $question->order;
    					break;
    				case 'down':
    					$new_rank = $question->order + 1;
    					$function = 'shiftDown';
    					$order_from = $new_rank - 1;
    					$order_to = $question->order + 2;
    					break;
    				case 'last':
    					$new_rank = $this->main_model->getNextIndex($question->id_poll) - 1;
    					$function = 'shiftDown';
    					$order_from = $question->order;
    					$order_to = $new_rank + 1;
    					break;
    			}
    					
    			$this->main_model->$function($question->id_poll, $order_from, $order_to);
    					
    			if($this->main_model->update($id, array('order' => $new_rank))) {
    				$this->session->set_flashdata('success', 'Rang mis à jour avec succès!');
    			} else {
    				$this->session->set_flashdata('error', 'La mise à jour a échoué!');
    			}
    					
    			redirect('/polls/edit/'.$question->id_poll);
    		}
    	}
    }
}