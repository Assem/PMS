<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PMS - Respondents Controller : a person that answer a survey
 *
 * @author      Assem Bayahi
 */

class Respondents extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('respondents_model', 'main_model');
		$this->load->model('polls_model');
		$this->load->helper(array('form', 'url', 'my_date', 'my_config'));
        $this->load->library('form_validation');
    }
    
    /**
     * Delete a respondent (if we cancel sheet creation for example)
     *
     * @param int $id ID of the user to delete
     */
    public function delete($id=NULL, $redirect_to_selection=NULL) {
    	$this->session->keep_flashdata('success');
    	
    	if( $this->require_role('admin,super-agent,agent') ) {
    		$respondent = $this->_checkRecord($id);
    		
    		if($respondent) {
    			$this->main_model->delete($id);
    			
    			if($redirect_to_selection) {
    				redirect('/polls/select');
    			}
    			redirect('/sheets/index');
    		}
    	}
    }
    
    private function _setValueToNull($data_array) {
    	$fields_list = array('age', 'childs_nbr', 'brothers_nbr', 'sisters_nbr', 
    			'gsm', 'educational_level', 'professional_status', 'company_type', 'marital_status');
    	
    	foreach ($fields_list as $field) {
    		if(empty($data_array[$field])) {
    			$data_array[$field] = NULL;
    		}
    	}
    	
    	return $data_array;
    }
    
    /**
     * Edit a respondent profile
     *
     * @param int $id ID of the respondent to edit
     */
    public function edit($id=NULL) {
    	if( $this->require_role('admin,super-agent,agent') ) {
    		$respondent = $this->_checkRecord($id);
    		
    		$this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
    		
    		$data = array(
    			'title' => "Fiche du répondant",
    			'content' => 'respondents/edit',
    			'respondent' => $respondent
    		);
    		
    		if($respondent){
    			$poll = $this->polls_model->getRecordByID($respondent->id_poll);
    			
    			$data['poll'] = $poll;
    			
    			$data_values = array(
	    			'age' 					=> set_value('age', $respondent->age),
	    			'country' 				=> set_value('country', $respondent->country),
	    			'city' 					=> set_value('city', $respondent->city),
	    			'email' 				=> set_value('email', $respondent->email),
	    			'sexe' 					=> set_value('sexe', $respondent->sexe),
	    			'educational_level' 	=> set_value('educational_level', $respondent->educational_level),
	    			'marital_status' 		=> set_value('marital_status', $respondent->marital_status),
	    			'professional_status' 	=> set_value('professional_status', $respondent->professional_status),
	    			'childs_nbr' 			=> set_value('childs_nbr', $respondent->childs_nbr),
	    			'brothers_nbr' 			=> set_value('brothers_nbr', $respondent->brothers_nbr),
	    			'sisters_nbr' 			=> set_value('sisters_nbr', $respondent->sisters_nbr),
	    			'gsm' 					=> set_value('gsm', $respondent->gsm),
	    			'company_type' 			=> set_value('company_type', $respondent->company_type)
	    		);
    			
	    		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
	    			$this->_setValidationRules();
						
	    			if ($this->form_validation->run()) {
	    				$data_values = $this->_setValueToNull($data_values);
	    				
	    				if($this->main_model->update($id, $data_values)){
	    					$this->session->set_flashdata('success', 'Répondant mis à jour avec succès!');
	    					
	    					redirect("/sheets/add/" . $respondent->id_poll . "/$id");
	    				} else {
	    					$this->session->set_flashdata('error', 'La mise à jour a échoué!');
	    				}
	                }
	    		}
	    		
	    		$data['content_data'] = $this->_getFields($data_values);
	    		$data['content_data']['cities'] = $this->main_model->getCity_List();
	    		$data['js_to_load'] = array('respondents.js');
    		}
    		
    		$this->load->view('global/layout', $data);
    	}
    }
    
    private function _setValidationRules() {
    	$this->form_validation->set_rules('age', 'Âge', 'trim|integer|greater_than[0]');
		$this->form_validation->set_rules('email', 'Email', "trim|valid_email|max_length[150]");
		$this->form_validation->set_rules('childs_nbr', "Nombre d'enfants", 'trim|integer|greater_than_equal_to[0]');
		$this->form_validation->set_rules('brothers_nbr', "Nombre de frères", 'trim|integer|greater_than_equal_to[0]');
		$this->form_validation->set_rules('sisters_nbr', 'Nombre de soeur', 'trim|integer|greater_than_equal_to[0]');
		$this->form_validation->set_rules('gsm', 'GSM', 'trim|max_length[30]');
    }
    
    private function _getFields($data_values) {
    	$cities = [null => ''];
    	if(!empty($data_values['country'])) {
    		$cities = $this->main_model->getCity_List($data_values['country']);
    	}
    	
    	$data = array(
			'fields' => array(
				'Pays'		=> form_dropdown(
					'country', 
					$this->main_model->getCountry_List(), 
					$data_values['country'], 
					'class="form-control" id="country_input"'
				),
				'Ville'		=> form_dropdown(
					'city', 
					$cities, 
					$data_values['city'], 
					'class="form-control" id="city_input"'
				),
				'Sexe'		=> form_dropdown(
					'sexe', 
					array('H' => 'Homme', 'F' => 'Femme'), 
					$data_values['sexe'], 
					'class="form-control"'
				),
				'Âge' 	=> form_input(array(
					'name'	=> 'age',
					'id'	=> 'age',
					'value'	=> $data_values['age'],
					'type'	=> 'number',
					'max' => '200',
					'min' => '1',
					'class'		=> 'form-control'
					)
				),
				'GSM' 	=> form_input(array(
					'name'	=> 'gsm',
					'id'	=> 'gsm',
					'value'	=> $data_values['gsm'],
					'type'	=> 'number',
					'class'		=> 'form-control'
					)
				),
				'Email' 	=> form_input(array(
					'name'	=> 'email',
					'id'	=> 'email',
					'value'	=> $data_values['email'],
					'type'	=> 'email',
					'maxlength' => '150',
					'class'		=> 'form-control'
					)
				),
				get_lov_label('marital_status')		=> form_dropdown(
					'marital_status', 
					$this->main_model->getMaritalStatus_List(), 
					$data_values['marital_status'], 
					'class="form-control"'
				),
				'Nombre d\'enfants' 	=> form_input(array(
					'name'	=> 'childs_nbr',
					'id'	=> 'childs_nbr',
					'value'	=> $data_values['childs_nbr'],
					'type'	=> 'number',
					'max' => '100',
					'min' => '0',
					'class'		=> 'form-control'
					)
				),
				'Nombre de soeurs' 	=> form_input(array(
					'name'	=> 'sisters_nbr',
					'id'	=> 'sisters_nbr',
					'value'	=> $data_values['sisters_nbr'],
					'type'	=> 'number',
					'max' => '100',
					'min' => '0',
					'class'		=> 'form-control'
					)
				),
				'Nombre de frères' 	=> form_input(array(
					'name'	=> 'brothers_nbr',
					'id'	=> 'brothers_nbr',
					'value'	=> $data_values['brothers_nbr'],
					'type'	=> 'number',
					'max' => '100',
					'min' => '0',
					'class'		=> 'form-control'
					)
				),
				get_lov_label('educational_level')		=> form_dropdown(
					'educational_level', 
					$this->main_model->getEducationalLevel_List(), 
					$data_values['educational_level'], 
					'class="form-control"'
				),
				get_lov_label('professional_status')		=> form_dropdown(
					'professional_status', 
					$this->main_model->getProfessionalStatus_List(), 
					$data_values['professional_status'], 
					'class="form-control"'
				),
				get_lov_label('company_type')	=> form_dropdown(
					'company_type', 
					$this->main_model->getCompanyType_List(), 
					$data_values['company_type'], 
					'class="form-control"'
				)
			)
		);
    	
    	return $data;
    }
    
	/**
     * Add a new respondents
     *
     */
    public function add($poll_id = NULL) {
    	if( $this->require_role('admin,super-agent,agent') ) {
    		if(!isset($poll_id) || !is_numeric($poll_id)) {
	    		show_404();
	    	}
	    		
	    	$poll = $this->polls_model->getRecordByID($poll_id);
	    	
	    	if(!$poll) {
	    		show_404();
	    	}
	    	
    		$data = array(
    			'title' => "Fiche du répondant",
    			'content' => 'respondents/add'
    		);
    		
    		$data_values = array(
    			'age' 					=> set_value('age'),
    			'country' 				=> set_value('country'),
    			'city' 					=> set_value('city'),
    			'email' 				=> set_value('email'),
    			'sexe' 					=> set_value('sexe'),
    			'educational_level' 	=> set_value('educational_level'),
    			'marital_status' 		=> set_value('marital_status'),
    			'professional_status' 	=> set_value('professional_status'),
    			'childs_nbr' 			=> set_value('childs_nbr'),
    			'brothers_nbr' 			=> set_value('brothers_nbr'),
    			'sisters_nbr' 			=> set_value('sisters_nbr'),
    			'gsm' 					=> set_value('gsm'),
    			'company_type' 			=> set_value('company_type')
    		);
    			
    		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
    			$this->_setValidationRules();
    			
				if ($this->form_validation->run()) {
					$data_values = $this->_setValueToNull($data_values);
					
					$data_values['created_by']		= $this->auth_user_id;
					$data_values['creation_date']	= date('Y-m-d H:i:s');
					$data_values['id_poll']	= $poll_id;
					
		            if($respondent_id = $this->main_model->create($data_values)){
    					$this->session->set_flashdata('success', 'Fiche répondant créée avec succès!');
    					
    					redirect("/sheets/add/$poll_id/$respondent_id");
    				} else {
    					$this->session->set_flashdata('error', 'La création a échoué!');
    				}
                }
    		}
	    		
	    	$data['content_data'] = $this->_getFields($data_values);
	    	$data['content_data']['cities'] = $this->main_model->getCity_List();
	    	$data['content_data']['poll'] = $poll;
	    	$data['js_to_load'] = array('respondents.js');
    		
    		$this->load->view('global/layout', $data);
    	}
    }
}
