<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PMS - Users Controller
 *
 * @author      Assem Bayahi
 */

class Users extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('users_model', 'main_model');
    }
    
    public function index() {
    	if( $this->require_role('admin') ) {
    		$data = array(
	    		'title' => 'Liste des utilisateurs',
    			'content' => 'users/index',
	    		'js_to_load' => array('users.js'),
	    		'users' => $this->main_model->getDataList()
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
    	if( $this->require_role('admin') ) {
    		$user = $this->_checkRecord($id);
    		
    		$data = array(
    			'title' => "Detail d'un utilisateur",
    			'content' => 'users/view',
    			'user' => $user
    		);
    		
    		if($user){
    			$user->sheets_number = $this->main_model->countSheets($user);
    			$user->last_position = $this->main_model->getLastPositions($user, 1);
    			
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
    	}
    }
    
    /**
     * Delete a user profile
     *
     * @param int $id ID of the user to delete
     */
    public function delete($id=NULL) {
    	if( $this->require_role('admin') ) {
    		$user = $this->_checkRecord($id);
    		
    		if($user) {
    			$this->main_model->delete($id);
    			
    			$this->session->set_flashdata('success', 'Utilisateur supprimé avec succès!');
    			
    			redirect('/users/index');
    		}
    	}
    }
    
    /**
     * Edit a user profile
     *
     * @param int $id ID of the user to edit
     */
    public function edit($id=NULL) {
    	if( $this->require_role('admin') ) {
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
    	}
    }
    
    private function _setValidationRules($action='edit', $id=NULL) {
    	$unique_fct = 'is_unique_exclude';
    	$unique_fct_params = ", user_id, $id";
    	
    	if($action == 'add') {
    		$unique_fct = 'is_unique';
    		$unique_fct_params = "";
    	
			$this->form_validation->set_rules('user_pass', 'Mot de passe', 'trim|required|external_callbacks[model,formval_callbacks,_check_password_strength,TRUE]');
			$this->form_validation->set_rules('user_pass_conf', 'Confirmation mot de passe', 'required|matches[user_pass]');
		}	
    	
    	$this->form_validation->set_rules('pms_user_last_name', 'Nom', 'trim|required|max_length[80]');
		$this->form_validation->set_rules('pms_user_first_name', 'Prénom', 'trim|required|max_length[80]');
		$this->form_validation->set_rules('user_email', 'Email', "trim|required|valid_email|{$unique_fct}[users.user_email{$unique_fct_params}]");
		$this->form_validation->set_rules('pms_user_gsm', 'GSM', 'trim|required|max_length[30]');
		$this->form_validation->set_rules('user_name', "Nom d'utilisateur", "trim|max_length[12]|{$unique_fct}[users.user_name{$unique_fct_params}]");
		$this->form_validation->set_rules('pms_user_code', 'Code interne', "trim|required|max_length[20]|{$unique_fct}[users.pms_user_code{$unique_fct_params}]");
    }
    
    private function _getFields($action='edit', $data_values) {
    	$data = array(
			'fields' => array(
				'Nom <font color="red">*</font>' 		=> form_input('pms_user_last_name', $data_values['pms_user_last_name'], array(
					'maxlength'	=> '80',
					'required' 	=> '1',
					'class'		=> 'form-control'
					)
				),
				'Prénom <font color="red">*</font>' 	=> form_input('pms_user_first_name', $data_values['pms_user_first_name'], array(
					'maxlength' => '80',
					'required' 	=> '1',
					'class'		=> 'form-control'
					)
				),
				'Email <font color="red">*</font>' 	=> form_input(array(
					'name'	=> 'user_email',
					'id'	=> 'user_email',
					'value'	=> $data_values['user_email'],
					'type'	=> 'email',
					'maxlength' => '150',
					'required' 	=> '1',
					'class'		=> 'form-control'
					)
				),
				'GSM <font color="red">*</font>' 	=> form_input(array(
					'name'	=> 'pms_user_gsm',
					'id'	=> 'pms_user_gsm',
					'value'	=> $data_values['pms_user_gsm'],
					'type'	=> 'number',
					'maxlength' => '30',
					'required' 	=> '1',
					'class'		=> 'form-control'
					)
				),
				'Nom d\'utilisateur'	=> form_input('user_name', $data_values['user_name'], array(
					'maxlength'	=> '12',
					'style'		=> 'width:50%;',
					'class'		=> 'form-control'
					)
				),
				'Code interne <font color="red">*</font>'	=> form_input('pms_user_code', $data_values['pms_user_code'], array(
					'maxlength'	=> '20',
					'style'		=> 'width:50%;',
					'required' 	=> '1',
					'class'		=> 'form-control'
					)
				),
				'Rôle <font color="red">*</font>'		=> form_dropdown('user_level', $this->authentication->roles_for_select, $data_values['user_level'], 'class="form-control"'),
				'Actif'		=> form_checkbox('user_banned', '1', $data_values['user_banned'], 'class="form-control"')
			)
		);
    	
    	if($action == 'edit') {
    		$data['fields']['Mot de passe <span id="helpBlock" class="help-block">Ne saisir que pour mettre à jour</span>'] = form_password('user_pass', '', array(
				'maxlength'	=> '60',
				'class'		=> 'form-control'
				)
			);
    		
    		$data['fields']['Confirmation mot de passe'] = form_password('user_pass_conf', '', array(
				'maxlength'	=> '60',
				'class'		=> 'form-control'
				)
			);
    	} else {
    		$data['fields']['Mot de passe <font color="red">*</font><span id="helpBlock" class="help-block">Ne saisir que pour mettre à jour</span>'] = form_password('user_pass', '', array(
				'maxlength'	=> '60',
				'class'		=> 'form-control',
				'required'	=> '1'
				)
			);
    		
    		$data['fields']['Confirmation mot de passe <font color="red">*</font>'] = form_password('user_pass_conf', '', array(
				'maxlength'	=> '60',
				'class'		=> 'form-control',
				'required'	=> '1'
				)
			);
    	}
    	
    	return $data;
    }
    
	/**
     * Add a new user
     *
     */
    public function add() {
    	if( $this->require_role('admin') ) {
    		$this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
    		
    		$data = array(
    			'title' => "Ajouter un utilisateur",
    			'content' => 'users/add'
    		);
    		
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
    			
    			$this->_setValidationRules('add');
    			
				if ($this->form_validation->run()) {
					$data_values['user_banned']	  = (empty($data_values['user_banned']))? '1' : '0';
					$data_values['user_salt']     = $this->authentication->random_salt();
					$data_values['user_pass']     = $this->authentication->hash_passwd($this->input->post('user_pass'), $data_values['user_salt']);
					$data_values['user_id']       = $this->_get_unused_id();
					$data_values['user_date']     = date('Y-m-d H:i:s');
					$data_values['user_modified'] = date('Y-m-d H:i:s');
		
		            // If username is not used, it must be entered into the record as NULL
		            if( empty( $data_values['user_name'] ) )
		            {
		                $data_values['user_name'] = NULL;
		            }
		            
					if($this->main_model->create($data_values)){
    					$this->session->set_flashdata('success', 'Utilisateur créé avec succès!');
    					
    					redirect('/users/view/'.$data_values['user_id']);
    				} else {
    					$this->session->set_flashdata('error', 'La création a échoué!');
    				}
                }
    		} else {
    			$data_values = array(
    				'pms_user_last_name' 	=> '',
    				'pms_user_first_name' 	=> '',
    				'user_email' 			=> '',
    				'pms_user_gsm' 			=> '',
    				'user_name' 			=> '',
    				'pms_user_code' 		=> '',
    				'user_level' 			=> '',
    				'user_banned' 			=> TRUE
    			);
    		}
	    		
	    	$data['content_data'] = $this->_getFields('add', $data_values);
    		
    		$this->load->view('global/layout', $data);
    	}
    }

    /**
     * This login method only serves to redirect a user to a 
     * location once they have successfully logged in. It does
     * not attempt to confirm that the user has permission to 
     * be on the page they are being redirected to.
     */
    public function login()
    {
        // Method should not be directly accessible
        if( $this->uri->uri_string() == 'users/login')
        {
            show_404();
        }

        if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' )
        {
            $this->require_min_level(1);
        }
        
        $this->setup_login_form();
        
        $data = array(
        	'content' => 'users/login_form',
        	'title' => 'PMS Login'
        );
        
        $this->load->view('global/layout', $data);
    }

    // --------------------------------------------------------------

    /**
     * Log out
     */
    public function logout()
    {
        $this->authentication->logout();

        redirect( secure_site_url( LOGIN_PAGE . '?logout=1') );
    }
    
    // --------------------------------------------------------------
    
    /**
     * Get an unused ID for user creation
     *
     * @return  int between 1200 and 4294967295
     */
    private function _get_unused_id()
    {
        // Create a random user id
        $random_unique_int = 2147483648 + mt_rand( -2147482447, 2147483647 );

        // Make sure the random user_id isn't already in use
        $query = $this->db->where('user_id', $random_unique_int)
            ->get_where(config_item('user_table'));

        if ($query->num_rows() > 0) {
            $query->free_result();

            // If the random user_id is already in use, get a new number
            return $this->_get_unused_id();
        }

        return $random_unique_int;
    }

    // --------------------------------------------------------------
}

/* End of file Examples.php */
/* Location: /application/controllers/Examples.php */
