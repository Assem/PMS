<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PMS - Polls Controller
 *
 * @author      Assem Bayahi
*/

class Polls extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('polls_model', 'main_model');
		$this->load->model('sheets_model');
		$this->load->model('sequences_model');
		
		$this->load->helper(array('form', 'url', 'my_date'));
        $this->load->library('form_validation');
	}
	
	/**
	 * Select a poll to start creating users survey sheet
	 */
	public function select() {
		if( $this->require_role('admin,super-agent,agent') ) {
			$polls = array();
			
			foreach ($this->main_model->getActivePolls() as $poll) {
				$polls[$poll->id] = '['.$poll->code.'] '.$poll->label;
			}
				
			$data = array(
				'content' => 'polls/select',
				'title' => "Sélection de sondage",
	    		'js_to_load' => array('polls.js'),
	    		'content_data' => array(
					'fields' => array(
						'Sondage' 	=> form_dropdown('poll', $polls, null, 'class="form-control"')
					)
				)
			);
			
			if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' && set_value('poll', false)){
				$seleted_poll_id = set_value('poll', false);
				
				redirect('/respondents/add/'.$seleted_poll_id);
			}
			
			$this->load->view('global/layout', $data);
		}
	}
	
	/**
	 * List all the polls
	 */
	public function index() {
		if( $this->require_role('admin,super-agent') ) {
			if( $this->is_role('agent') ) { //if it's an agent so we redirect him
				redirect( secure_site_url('polls/select') );
			}
			
			$polls = array();
			$polls_list = $this->main_model->getPollsWithSheetsNumber();
			
			foreach ($polls_list as $poll) {
				$poll->sheets_count = $poll->sheets_number.'/'.$poll->max_surveys_number;
				$polls[] = $poll;
			}
			
			$data = array(
				'content' => 'polls/index',
				'title' => "Liste des sondages",
	    		'js_to_load' => array('polls.js'),
	    		'polls' => $polls
			);
			
			$this->load->view('global/layout', $data);
		}
	}
	
	/**
     * Delete a poll
     *
     * @param int $id ID of the poll to delete
     */
    public function delete($id=NULL) {
    	if( $this->require_role('admin,super-agent') ) {
    		$record = $this->_checkRecord($id);
    		
    		if($record) {
    			$this->main_model->delete($id);
    			
    			$this->session->set_flashdata('success', 'Sondage supprimé avec succès!');
    			
    			redirect('/polls/index');
    		}
    	}
    }
	
	private function _getFields($action='edit', $data_values) {
    	$data = array(
			'fields' => array(
				'Code interne <font color="red">*</font>'	=> form_input('code', $data_values['code'], array(
					'maxlength'	=> '30',
					'required' 	=> '1',
					'class'		=> 'form-control'
					)
				),
				'Libellé <font color="red">*</font>'	=> form_input('label', $data_values['label'], array(
					'maxlength' => '80',
					'required' 	=> '1',
					'class'		=> 'form-control'
					)
				),
				'Date début' 	=> form_input(array(
					'name'	=> 'start_date',
					'id'	=> 'start_date',
					'value'	=> $data_values['start_date'],
					'class'		=> 'form-control'
					)
				),
				'Date fin' 	=> form_input(array(
					'name'	=> 'end_date',
					'id'	=> 'end_date',
					'value'	=> $data_values['end_date'],
					'class'		=> 'form-control'
					)
				),
				'Client' 	=> form_input('customer', $data_values['customer'], array(
					'maxlength' => '120',
					'class'		=> 'form-control'
					)
				),
				'Nbr maximum de fiches <span id="helpBlock" class="help-block">Vide ou 0 veut dire pas de limite</span>' 	=> form_input(array(
					'name'	=> 'max_surveys_number',
					'id'	=> 'max_surveys_number',
					'value'	=> $data_values['max_surveys_number'],
					'type'	=> 'number',
					'maxlength' => '11',
					'class'		=> 'form-control'
					)
				),
				'Description' 	=> form_textarea('description', $data_values['description'], array(
					'maxlength' => '255',
					'class'		=> 'form-control'
					)
				),
				'Actif'		=> form_checkbox('actif', '1', $data_values['actif'], 'class="form-control"')
			)
		);
    	
    	return $data;
    }
    
	private function _setValidationRules($action='edit', $id=NULL) {
    	$unique_fct = 'is_unique_exclude';
    	$unique_fct_params = ", id, $id";
    	
    	if($action == 'add') {
    		$unique_fct = 'is_unique';
    		$unique_fct_params = "";
		}	
    	
    	$this->form_validation->set_rules('label', 'Libellé', 'trim|required|max_length[80]');
		$this->form_validation->set_rules('description', 'Description', 'trim|max_length[255]');
		$this->form_validation->set_rules('customer', 'Client', 'trim|max_length[120]');
		
		$this->form_validation->set_rules(
				'start_date', 
				'Date de début', 
				"trim|valid_date|date_less_than_equal_to[".$this->input->post('end_date')."]", 
				array('date_less_than_equal_to' => 'La date de début doit être inférieur ou égale à la date de fin'));
		$this->form_validation->set_rules(
				'end_date', 
				'Date de fin', 
				"trim|valid_date|date_greater_than_equal_to[".$this->input->post('start_date')."]",
				array('date_greater_than_equal_to' => 'La date de fin doit être supérieur ou égale à la date de début'));
		
		$this->form_validation->set_rules('max_surveys_number', 'Nbr maximum de fiches', 'trim|integer|max_length[11]');
		$this->form_validation->set_rules('code', 'Code interne', "trim|required|max_length[20]|{$unique_fct}[polls.code{$unique_fct_params}]");
    }
	
	/**
     * Add a new survey
     *
     */
    public function add() {
    	if( $this->require_role('admin,super-agent') ) {
    		$data = array(
    			'title' => "Ajouter un sondage",
    			'js_to_load' => array('polls.js'),
    			'content' => 'polls/add'
    		);
    		
    		$data_values = array(
    			'code' 				=> set_value('code', $this->sequences_model->getNextSequenceByKey('polls_code_seq')),
    			'label' 			=> set_value('label'),
    			'customer' 			=> set_value('customer'),
    			'start_date' 		=> set_value('start_date'),
   				'end_date' 			=> set_value('end_date'),
   				'max_surveys_number'=> set_value('max_surveys_number'),
   				'actif' 			=> set_value('actif', TRUE),
   				'description' 		=> set_value('description', '', FALSE)
    		);
    			
    		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
    			$this->_setValidationRules('add');
    			
				if ($this->form_validation->run()) {
					$data_values['created_by']		= $this->auth_user_id;
					$data_values['creation_date']	= date('Y-m-d H:i:s');
					$data_values['update_date']		= date('Y-m-d H:i:s');
					$data_values['actif']	  = (empty($data_values['actif']))? '0' : '1';
					
					$data_values['start_date'] = prepareDateForSave($data_values['start_date']);
					$data_values['end_date'] = prepareDateForSave($data_values['end_date']);
		
		            if($this->main_model->create($data_values)){
    					$this->session->set_flashdata('success', 'Sondage créé avec succès!');
    					
    					redirect('/polls/index');
    				} else {
    					$this->session->set_flashdata('error', 'La création a échoué!');
    				}
                }
    		}
	    		
	    	$data['content_data'] = $this->_getFields('add', $data_values);
    		
    		$this->load->view('global/layout', $data);
    	}
    }
    
	/**
     * View a polls detail
     * 
     * @param int $id ID of the poll to view
     */
    public function view($id=NULL) {
    	if( $this->require_role('admin,super-agent') ) {
    		$poll = $this->_checkRecord($id);
    		
    		$data = array(
    			'title' => "Detail d'un sondage",
    			'content' => 'polls/view',
    			'js_to_load' => array('polls.js'),
    			'poll' => $poll
    		);
    		
    		if($poll){
    			$poll->sheets_count = $this->main_model->countSheets($poll);
    			$warning = False;
    			
    			$questions = $this->main_model->getQuestionsWithAnswers($poll);
    			$data['questions'] = $questions;
    			
    			foreach ($questions as $question) {
    				$warning = $warning || $question->warning;
    			}
    			
    			$data['warning'] = $warning;
    			
    			$createdBy = $this->main_model->getCreatedby($poll);
    			$createdByLink = secure_anchor("users/view/".$createdBy->user_id, 
    					strtoupper($createdBy->pms_user_last_name)." ".ucfirst($createdBy->pms_user_first_name));
    			
    			$data['content_data'] = array(
    				'fields' => array(
	    				'Code interne' 		=> $poll->code,
	    				'Client' 			=> $poll->customer,
    					'Libellé' 			=> $poll->label,
	    				'Description'		=> $poll->description,
    					'Date de début' 	=> (isset($poll->start_date))? date('d/m/Y', strtotime($poll->start_date)):'',
    					'Date de fin' 		=> (isset($poll->end_date))? date('d/m/Y', strtotime($poll->end_date)):'',
    					'Nbr maximum de fiches' 		=> $poll->max_surveys_number,
    					'Actif'				=> ($poll->actif)? 'OUI' : 'NON',
    					'Date de création' 	=> date('d/m/Y H:i:s', strtotime($poll->creation_date)),
    					'Dernière modification' => date('d/m/Y H:i:s', strtotime($poll->update_date)),
    					'Créé par' 			=> $createdByLink
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
    		$poll = $this->_checkRecord($id);
    		
    		$data = array(
    			'title' => "Edition d'un sondage",
    			'js_to_load' => array('polls.js'),
    			'content' => 'polls/edit',
    			'poll' => $poll
    		);
    		
    		if($poll){
    			$poll->sheets_count = $this->main_model->countSheets($poll);
    			$warning = False;
    			
    			$questions = $this->main_model->getQuestionsWithAnswers($poll);
    			$data['questions'] = $questions;
    			
    			foreach ($questions as $question) {
    				$warning = $warning || $question->warning;
    			}
    			
    			$data['warning'] = $warning;
    			
	    		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
	    			$data_values = array(
	    				'code' 					=> set_value('code'),
	    				'label' 				=> set_value('label'),
	    				'customer' 				=> set_value('customer'),
	    				'start_date' 			=> set_value('start_date'),
	    				'end_date' 				=> set_value('end_date'),
	    				'max_surveys_number' 	=> set_value('max_surveys_number'),
	    				'actif' 				=> set_value('actif'),
	    				'description' 			=> set_value('description')
	    			);
	    			
	    			$this->_setValidationRules('edit', $id);
	    			
	    			if ($this->form_validation->run()) {
	    				$data_values['update_date']	= date('Y-m-d H:i:s');
	    				$data_values['start_date'] = prepareDateForSave($data_values['start_date']);
						$data_values['end_date'] = prepareDateForSave($data_values['end_date']);
						$data_values['actif']	  = (empty($data_values['actif']))? '0' : '1';
	    				
	    			 	if($this->main_model->update($id, $data_values)){
	    					$this->session->set_flashdata('success', 'Sondage mis à jour avec succès!');
	    					
	    					redirect('/polls/view/'.$id);
	    				} else {
	    					$this->session->set_flashdata('error', 'La mise à jour a échoué!');
	    				}
	                }
	    		} else {
	    			$data_values = array(
	    				'code' 					=> $poll->code,
	    				'label' 				=> $poll->label,
	    				'customer' 				=> $poll->customer,
	    				'start_date' 			=> (isset($poll->start_date))? date('d/m/Y', strtotime($poll->start_date)):'',
	    				'end_date' 				=> (isset($poll->end_date))? date('d/m/Y', strtotime($poll->end_date)):'',
	    				'max_surveys_number' 	=> $poll->max_surveys_number,
	    				'actif' 				=> $poll->actif,
	    				'description' 			=> $poll->description,
	    			);
	    		}
	    		
	    		$data['content_data'] = $this->_getFields('edit', $data_values);
    		}
    		
    		$this->load->view('global/layout', $data);
    	}
    }
    
    /**
     * Show a poll's statistics
     * 
     * @param int $id a Poll id
     */
    public function stats($id=NULL) {
    	if( $this->require_role('admin') ) {
    		$poll = $this->_checkRecord($id);
    		
    		$data = array(
    			'title' => "Statistiques d'un sondage",
    			'js_to_load' => array(
    				'plugins/flot/jquery.flot.js', 
    				'plugins/flot/jquery.flot.resize.min.js', 
    				'plugins/flot/jquery.flot.tooltip.min.js', 
    				'plugins/flot/jquery.flot.pie.js', 
    				'graphs.js'),
    			'content' => 'polls/stats',
    			'poll' => $poll
    		);
    		
    		if($poll){
    			$poll->sheets_count = $this->main_model->countSheets($poll);
    			$all_answers = $this->main_model->getAllAnswers($id);
    			$questions_with_answers = $this->main_model->getQuestionsWithAnswers($poll);
    			
    			$counted_answers = array();
    			$stats = array();
    			$count_question_answers = 0;
    			
    			foreach ($all_answers as $answer) {
	    			if(!isset($counted_answers[$answer->id_question])) {
	    				$counted_answers[$answer->id_question] = array('global' => 0, 'details' => array());
	    			}
	    			
	    			if($answer->type == 'free_text') {
	    				if(trim($answer->value)) {
	    					$counted_answers[$answer->id_question]['global'] += 1;
	    				}		
	    			} else {
	    				$counted_answers[$answer->id_question]['global'] += 1;
    					$choices = explode(',', $answer->value);
    					
    					foreach ($choices as $choice) {
    						if(!isset($counted_answers[$answer->id_question]['details'][$choice])) {
	    						$counted_answers[$answer->id_question]['details'][$choice] = 0;
	    					}
	    					$counted_answers[$answer->id_question]['details'][$choice] += 1;
    					}
	    			}
	    		}
	    		
	    		foreach ($questions_with_answers as $question) {
	    			$question_data = array(
	    				'details' => $question
	    			);
	    			
	    			if(!isset($counted_answers[$question->id])) {
	    				$counted_answers[$question->id] = array('global' => 0, 'details' => array());
	    			}
	    			
	    			$question_data['nbr_answers'] = $counted_answers[$question->id]['global'];
	    			$question_data['graph'] = array('data' => array(), 'labels' => array());
	    			$counted_answers[$question->id]['answers'] = array();
	    			
	    			if($question->type != 'free_text') {
	    				$i = 0;
	    				foreach ($question->answers as $answer) {
	    					if(!isset($counted_answers[$question->id]['details'][$answer->id])) {
	    						$question_data['graph']['data'][] = array($i, 0);
	    					} else {
	    						$question_data['graph']['data'][] = array($i, $counted_answers[$question->id]['details'][$answer->id]);
	    					}
	    					
	    					$counted_answers[$question->id]['answers'][$answer->order] = $answer;
	    					$question_data['graph']['labels'][] = array($i, $answer->order);
	    					
	    					$i++;
	    				}
	    			} else {
	    				$question_data['graph']['data'][] = array(0, $counted_answers[$question->id]['global']);
	    				$question_data['graph']['data'][] = array(1, $poll->sheets_count - $counted_answers[$question->id]['global']);
	    				
	    				$question_data['graph']['labels'][] = array(0, 'Ayant répondu');
	    				$question_data['graph']['labels'][] = array(1, 'Pas de réponse');
	    				
	    				$counted_answers[$question->id]['answers'] = false;
	    			}
	    			
	    			$stats[] = $question_data;
	    		}
	    		
	    		$data['graphs_data'] = $stats;
	    		$data['answers_data'] = $counted_answers;
    		}
    		
    		$this->load->view('global/layout', $data);
    	}
    }
}