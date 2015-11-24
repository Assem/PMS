<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PMS - Respondents Controller : a person that answer a survey
 *
 * @author      Assem Bayahi
 */

class Sheets extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('sheets_model', 'main_model');
		$this->load->model('pools_model');
		$this->load->model('respondents_model');
		$this->load->helper(array('form', 'url', 'my_date'));
        $this->load->library('form_validation');
    }
    
    public function index($from=NULL) {
    	if( $this->require_role('admin') ) {
    		$title = 'Liste des fiches';
    		$back_url = '';
    		
    		if(isset($from)) {
    			$relation = explode('-', $from)[0];
    			$id = explode('-', $from)[1];
    			
    			switch ($relation) {
    				case 'pool':
    					$pool = $this->pools_model->getRecordByID($id);
    					
    					if(!$pool) {
    						show_404();
    					}
    					$title = "Sondage '".$pool->code."': Liste des fiches";
    					$sheets = $this->main_model->getPoolSheets($id);
    					
    					$back_url = 'pools/view/'.$pool->id;
    					break;
    			}
    		} else {
    			$sheets = $this->main_model->getDataList();
    		}
    			
    		$data = array(
	    		'title' 		=> $title,
    			'content' 		=> 'sheets/index',
	    		'js_to_load' 	=> array('sheets.js'),
	    		'sheets' 		=> $sheets,
    			'pool' 			=> $pool,
    			'back_url' 		=> $back_url
	    	);
	    	
	    	$this->load->view('global/layout', $data);
    	}
    }
    
    /**
     * View a user profile
     * 
     * @param int $id ID of the user to view
     */
    public function view($id=NULL) {
    	/*if( $this->require_role('admin') ) {
    		$user = $this->_checkRecord($id);
    		
    		$data = array(
    			'title' => "Detail d'un utilisateur",
    			'content' => 'users/view',
    			'user' => $user
    		);
    		
    		if($user){
	    		$data['content_data'] = array(
    				'fields' => array(
	    				'Nom' 		=> $user->pms_user_last_name,
	    				'Prénom' 	=> $user->pms_user_first_name,
	    				'Email' 	=> $user->user_email,
	    				'GSM' 		=> $user->pms_user_gsm,
    					'Nom d\'utilisateur' => $user->user_name,
    					'Code interne' => $user->pms_user_code,
    					'Rôle'		=> ucfirst($this->authentication->roles[$user->user_level]),
    					'Actif'		=> ($user->user_banned)? 'NON' : 'OUI',
    					'Date de création' => date('d/m/Y H:i:s', strtotime($user->user_date)),
    					'Dernière modification' => date('d/m/Y H:i:s', strtotime($user->user_modified)),
    					'Dernière connexion' => isset($user->user_last_login)?date('d/m/Y H:i:s', strtotime($user->user_last_login)):''
    				)
	    		);
    		}
    		
    		$this->load->view('global/layout', $data);
    	}*/
    }
    
    /**
     * Delete a user profile
     *
     * @param int $id ID of the user to delete
     */
    public function delete($id=NULL) {
    	/*if( $this->require_role('admin') ) {
    		$user = $this->_checkRecord($id);
    		
    		if($user) {
    			$this->main_model->delete($id);
    			
    			$this->session->set_flashdata('success', 'Utilisateur supprimé avec succès!');
    			
    			redirect('/users/index');
    		}
    	}*/
    }
    
    /**
     * Edit a user profile
     *
     * @param int $id ID of the user to edit
     */
    public function edit($id=NULL) {
    	/*if( $this->require_role('admin') ) {
    		$user = $this->_checkRecord($id);
    		
    		$this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
    		
    		$data = array(
    			'title' => "Edition d'un utilisateur",
    			'content' => 'users/edit',
    			'user' => $user
    		);
    		
    		if($user){
	    		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
	    			$data_values = array(
	    				'pms_user_last_name' 	=> set_value('pms_user_last_name'),
	    				'pms_user_first_name' 	=> set_value('pms_user_first_name'),
	    				'user_email' 			=> set_value('user_email'),
	    				'pms_user_gsm' 			=> set_value('pms_user_gsm'),
	    				'user_name' 			=> set_value('user_name'),
	    				'pms_user_code' 		=> set_value('pms_user_code'),
	    				'user_level' 			=> set_value('user_level'),
	    				'user_banned' 			=> set_value('user_banned')
	    			);
	    			
	    			$this->_setValidationRules('edit', $id);
					
					if(!empty(trim(set_value('user_pass')))) {
						$data_values['user_salt']     = $this->authentication->random_salt();
						$data_values['user_pass']     = $this->authentication->hash_passwd(trim(set_value('user_pass')), $data_values['user_salt']);
						
						$this->form_validation->set_rules('user_pass', 'Mot de passe', 'trim|required|external_callbacks[model,formval_callbacks,_check_password_strength,TRUE]');
						$this->form_validation->set_rules('user_pass_conf', 'Confirmation mot de passe', 'required|matches[user_pass]');
					}
						
	    			if ($this->form_validation->run()) {
	    				$data_values['user_banned'] = (empty($data_values['user_banned']))? '1' : '0';
	    				$data_values['user_modified'] = date('Y-m-d H:i:s');
	    				
	    			 	// If username is not used, it must be entered into the record as NULL
			            if( empty( $data_values['user_name'] ) ) {
			                $data_values['user_name'] = NULL;
			            }
	    				
	    				if($this->main_model->update($id, $data_values)){
	    					$this->session->set_flashdata('success', 'Utilisateur mis à jour avec succès!');
	    					
	    					redirect('/users/view/'.$id);
	    				} else {
	    					$this->session->set_flashdata('error', 'La mise à jour a échoué!');
	    				}
	                }
	    		} else {
	    			$data_values = array(
	    				'pms_user_last_name' 	=> $user->pms_user_last_name,
	    				'pms_user_first_name' 	=> $user->pms_user_first_name,
	    				'user_email' 			=> $user->user_email,
	    				'pms_user_gsm' 			=> $user->pms_user_gsm,
	    				'user_name' 			=> $user->user_name,
	    				'pms_user_code' 		=> $user->pms_user_code,
	    				'user_level' 			=> $user->user_level,
	    				'user_banned' 			=> ($user->user_banned != 1)
	    			);
	    		}
	    		
	    		$data['content_data'] = $this->_getFields('edit', $data_values);
    		}
    		
    		$this->load->view('global/layout', $data);
    	}*/
    }
    
    private function _getFields($data_values) {
    	$data = array(
			'fields' => array(
				'Pays'		=> form_dropdown(
					'country', 
					$this->main_model->getCountry_List(), 
					$data_values['country'], 
					'class="form-control"'
				),
				'Ville'		=> form_dropdown(
					'city', 
					$this->main_model->getCity_List(), 
					$data_values['city'], 
					'class="form-control"'
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
				'Status conjugale'		=> form_dropdown(
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
				'Niveau éducatif'		=> form_dropdown(
					'educational_level', 
					$this->main_model->getEducationalLevel_List(), 
					$data_values['educational_level'], 
					'class="form-control"'
				),
				'Status professionnel'		=> form_dropdown(
					'professional_status', 
					$this->main_model->getProfessionalStatus_List(), 
					$data_values['professional_status'], 
					'class="form-control"'
				),
				'Nature de société'		=> form_dropdown(
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
     * Create a new sheet for a pool
     *
     */
	public function add($pool_id = NULL, $respondent_id = NULL) {
    	if( $this->require_role('admin,super-agent,agent') ) {
    		if(!isset($pool_id) || !is_numeric($pool_id) || !isset($respondent_id) || !is_numeric($respondent_id)) {
	    		show_404();
	    	}
	    		
	    	$pool = $this->pools_model->getRecordByID($pool_id);
	    	$respondent = $this->respondents_model->getRecordByID($respondent_id);
	    	
	    	if(!$pool || !$respondent || $respondent->id_pool != $pool_id) {
	    		show_404();
	    	}
	    	
	    	//if we have reached the maximum sheet number, we don't go further and we delete the respondant record
	    	if($pool->max_surveys_number > 0 && $pool->max_surveys_number <= $this->pools_model->countSheets($pool)) {
	    		$this->session->set_flashdata('success', 'Nombre de fiches maximum atteint; fiche annulée!', TRUE);
    					
    			redirect('respondents/delete/'.$respondent_id.'/true');
	    	}
	    	
    		$data = array(
    			'title' => "Création d'une fiche",
    			'content' => 'sheets/add',
    			'js_to_load' => array('location_detection.js'),
    		);
    		
    		$data_values = array();
    		$questions_data = array();
    		
    		$questions = $this->pools_model->getQuestions($pool);
    		
    		foreach ($questions as $question) {
    			$answers = $this->questions_model->getAnswers($question->id);
    			$rules = 'trim';
    			$input_name = 'answers['.$question->id.']';
    			$label = $question->order.'. '.$question->description;
    			
    			if($question->required) {
    				$label .= ' <font color="red">*</font>';
    				$rules .= '|required';
    			}
    			
    			if($question->type == 'mutiple_choice') {
    				$input_name .= '[]';
    			}
    			
    			$questions_data[$label] = $this->load->view('questions/_draw_question', 
    				array(
    					'question' => $question,
    					'answers' => $answers
    				),
    				TRUE
    			);
    			
    			$this->form_validation->set_rules($input_name, 'Question N°'.$question->order, $rules,
    					array('required' => 'La {field} est obligatoire'));
    		}
    		
    		$questions_data['Remarques'] = form_textarea('notes', set_value('notes', '', FALSE), array(
    			'maxlength'	=> '255',
				'class'	=> 'form-control'
    		));
    		
    		//add hidden inputs to save the agent position
    		$questions_data[''] = form_hidden('geo_error', '').form_hidden('position', '');
    		
    		$data['content_data'] = array(
    			'fields' => $questions_data,
    			'pool' 		=> $pool,
    			'respondent'=> $respondent
    		);
    		
    		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
    			if ($this->form_validation->run()) {
    				$data_values = $this->input->post();
    				
					$data_values['created_by']		= $this->auth_user_id;
					$data_values['creation_date']	= date('Y-m-d H:i:s');
					$data_values['id_pool']			= $pool_id;
					$data_values['id_respondent']	= $respondent_id;
					
		            if($sheet_id = $this->main_model->create($data_values)){
    					$this->session->set_flashdata('success', 'Fiche créée avec succès!');
    					
    					redirect("/pools/select");
    				} else {
    					$this->session->set_flashdata('error', 'La création a échoué!');
    				}
                }
    		}
	    		
	    	$this->load->view('global/layout', $data);
    	}
    }
}
