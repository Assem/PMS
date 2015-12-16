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
		$this->load->model('polls_model');
		$this->load->model('users_model');
		$this->load->model('respondents_model');
		$this->load->helper(array('form', 'url', 'my_date'));
        $this->load->library('form_validation');
    }
    
    public function index($from=NULL) {
    	if( $this->require_role('admin') ) {
    		$title = 'Liste des fiches';
    		$back_url = '';
    		$columns = '<th>Poll</th><th>Agent</th>';
    		$relation = '';
    		
    		if(isset($from)) {
    			$relation = explode('-', $from)[0];
    			$id = explode('-', $from)[1];
    			
    			switch ($relation) {
    				case 'poll':
    					$poll = $this->polls_model->getRecordByID($id);
    					
    					if(!$poll) {
    						show_404();
    					}
    					$title = "Sondage '".$poll->code."': Liste des fiches";
    					$sheets = $this->main_model->getPollSheets($id);
    					
    					$back_url = 'polls/view/'.$id;
    					$columns = '<th>Agent</th>';
    					$columns_data = '';
    					break;
    				case 'user':
    					$user = $this->users_model->getRecordByID($id);
    					
    					if(!$user) {
    						show_404();
    					}
    					$title = "Agent '".$user->pms_user_code."': Liste des fiches";
    					$sheets = $this->main_model->getUserSheets($id);
    					
    					$back_url = 'users/view/'.$id;
    					$columns = '<th>Poll</th>';
    					break;
    			}
    		} else {
    			$sheets = $this->main_model->getSheetsWithPollAndUser();
    		}
    			
    		$data = array(
	    		'title' 		=> $title,
    			'content' 		=> 'sheets/index',
	    		'js_to_load' 	=> array('sheets.js'),
	    		'sheets' 		=> $sheets,
    			'back_url' 		=> $back_url,
    			'relation'		=> $relation,
    			'columns'		=> $columns,
    			'from'			=> "/$from"
	    	);
	    	
	    	$this->load->view('global/layout', $data);
    	}
    }
    
    /**
     * View a sheet
     * 
     * @param int $id ID of the sheet to view
     */
    public function view($id=NULL, $redirect=NULL) {
    	if( $this->require_role('admin') ) {
    		$sheet = $this->_checkRecord($id);
    		$back_url = 'sheets/index';
    		
    		if(isset($redirect)) {
    			$back_url = "sheets/index/$redirect";
    		}
    		
    		$data = array(
    			'title' 	=> "Fiche N°$id",
    			'content' 	=> 'sheets/view',
    			'sheet' 	=> $sheet,
    			'back_url' 	=> $back_url,
    			'redirect'	=> $redirect
    		);
    		
    		if($sheet){
    			$poll = $this->main_model->getPoll($sheet);
	    		$respondent = $this->main_model->getRespondent($sheet);
	    		$geolocation = $this->main_model->getLocation($sheet);
	    		$answers = $this->main_model->getAnswers($sheet);
	    		
	    		$id_question = false;
	    		$answers_data = array();
	    		$question_answers = array();
	    		$label = $type = false;
	    		
	    		foreach ($answers as $answer) {
	    			if($id_question != $answer->id_question) {
	    				if($id_question) {
		    				$answers_data[$label] = $this->load->view('questions/_draw_question_view', 
			    				array(
			    					'answers' 	=> $question_answers,
			    					'type'		=> $type
			    				),
			    				TRUE
			    			);
	    				}
	    				
	    				$id_question = $answer->id_question;
	    				$question_answers = array();
	    				$label = $answer->q_order.'. '.$answer->q_description;
	    				$type = $answer->q_type;
	    			}
	    			
	    			$question_answers[] = $answer;
	    		}
	    		
    			if($id_question) {
	    			$answers_data[$label] = $this->load->view('questions/_draw_question_view', 
		    			array(
		    				'answers' 	=> $question_answers,
		   					'type'		=> $type
		   				),
		   				TRUE
		   			);
    			}
	    		
	    		$latlong = $geolocation->latitude.','.$geolocation->longitude;
							
	    		$createdBy = $this->main_model->getCreatedby($sheet);
    			$createdByLink = secure_anchor("users/view/".$createdBy->user_id, 
    					strtoupper($createdBy->pms_user_last_name)." ".ucfirst($createdBy->pms_user_first_name));
	    	
	    		$data['content_data'] = array(
    				'sheet_fields' => array(
	    				'Date' 		=> date('d/m/Y H:i:s', strtotime($sheet->creation_date)),
	    				'Agent' 	=> $createdByLink,
	    				'Remarque' 	=> $sheet->notes,
    					'Location'  => "<img src='https://maps.googleapis.com/maps/api/staticmap?center=$latlong&zoom=15&size=400x200&maptype=roadmap
							&markers=color:blue|$latlong'/>"
    				),
	    			'respondent_view' => $this->load->view('respondents/_view_for_sheet', 
	    				array(
	    					'respondent' => $respondent
	    				),
	    				TRUE
	    			),
	    			'answers_fields' => $answers_data
	    		);
	    		
	    		$data['title'] = "Sondage '".$poll->code."' - Fiche N°$id";
    		}
    		
    		$this->load->view('global/layout', $data);
    	}
    }
    
    /**
     * Delete a sheet
     *
     * @param int $id ID of the sheet to delete
     */
    public function delete($id=NULL, $redirect=NULL) {
    	if( $this->require_role('admin') ) {
    		$sheet = $this->_checkRecord($id);
    		
    		if($sheet) {
    			$this->main_model->delete($id);
    			
    			$this->session->set_flashdata('success', 'Fiche supprimée avec succès!');
    			
    			if($redirect) {
    				redirect("/sheets/index/$redirect");
    			}
    			
    			redirect("/sheets/index");
    		}
    	}
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
     * Create a new sheet for a poll
     *
     */
	public function add($poll_id = NULL, $respondent_id = NULL) {
    	if( $this->require_role('admin,super-agent,agent') ) {
    		if(!isset($poll_id) || !is_numeric($poll_id) || !isset($respondent_id) || !is_numeric($respondent_id)) {
	    		show_404();
	    	}
	    		
	    	$poll = $this->polls_model->getRecordByID($poll_id);
	    	$respondent = $this->respondents_model->getRecordByID($respondent_id);
	    	
	    	if(!$poll || !$respondent || $respondent->id_poll != $poll_id) {
	    		show_404();
	    	}
	    	
	    	//if we have reached the maximum sheet number, we don't go further and we delete the respondant record
	    	if($poll->max_surveys_number > 0 && $poll->max_surveys_number <= $this->polls_model->countSheets($poll)) {
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
    		
    		$questions = $this->polls_model->getQuestions($poll);
    		
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
    		
    		$this->form_validation->set_rules('notes', 'Remarques', 'trim');
    		
    		//add hidden inputs to save the agent position
    		$questions_data[''] = form_hidden('geo_error', '').form_hidden('position', '');
    		
    		$data['content_data'] = array(
    			'fields' => $questions_data,
    			'poll' 		=> $poll,
    			'respondent'=> $respondent
    		);
    		
    		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
    			if ($this->form_validation->run()) {
    				$data_values = $this->input->post();
    				
					$data_values['created_by']		= $this->auth_user_id;
					$data_values['creation_date']	= date('Y-m-d H:i:s');
					$data_values['id_poll']			= $poll_id;
					$data_values['id_respondent']	= $respondent_id;
					
		            if($sheet_id = $this->main_model->create($data_values)){
    					$this->session->set_flashdata('success', 'Fiche créée avec succès!');
    					
    					redirect("/polls/select");
    				} else {
    					$this->session->set_flashdata('error', 'La création a échoué!');
    				}
                }
    		}
	    		
	    	$this->load->view('global/layout', $data);
    	}
    }
    
    /**
     * Delete all the sheets of a poll
     * 
     * @param unknown $poll_id
     */
    public function delete_all($poll_id) {
    	if( $this->require_role('admin') ) {
	    	$this->main_model->delete_from_poll($poll_id);
	    			
	    	$this->session->set_flashdata('success', 'Fiches supprimées avec succès!');
	    			
	    	redirect("/polls/view/$poll_id");
    	}
    }
    
    /**
     * Return the sheets created today: we take only the last sheet with valid localisation for each agent
     * Return JSON
     * 
     * @param int $poll_id
     */
    public function today_sheets($poll_id) {
    	$this->output->enable_profiler(FALSE);
    	
    	if( $this->require_role('admin') ) {
    		$result = array();
    		
	    	foreach($this->main_model->getPollTodaySheets($poll_id) as $sheet) {
	    		if(!isset($result[$sheet->user_id])) {
	    			$sheet->since = floor(((new \DateTime("now"))->getTimestamp() - strtotime($sheet->creation_date)) / 60);
	    			$sheet->creation_date_formatted = date('d/m/Y H:i:s', strtotime($sheet->creation_date));
	    			$result[$sheet->user_id] = $sheet;
	    		}
	    	}
	    	
	    	$result = array_values($result);
	    	
	    	echo json_encode($result);
    	}
    }
    
    public function recent_sheets() {
    	$this->output->enable_profiler(FALSE);
    	
    	if( $this->require_role('admin') ) {
	    	$lastSheets = $this->main_model->getSheetsWithPollAndUser(10);
	    	
	    	$this->load->view('sheets/_recent_sheets', array('sheets' => $lastSheets));
    	}
    }
}
