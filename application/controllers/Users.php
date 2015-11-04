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
        
        $this->load->model('users_model');
    }
    
    public function index() {
    	if( $this->require_role('admin') ) {
    		
    		$query = $this->db->get(config_item('user_table'), 10);
    		//print_r($query->result());
    		//exit;
    		$tt = $query->result();
    		//echo $this->authentication->roles[$tt[0]->user_level];
    		//exit;
    		//
    		
	    	$data = array(
	    		'title' => 'Liste des utilisateurs',
    			'content' => 'users/index',
	    		'js_to_load' => array('users.js'),
	    		'users' => $query->result()
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
    		if(!isset($id)) {
    			show_404();
    		}
    		
    		$user = $this->users_model->getUserByID($id);
    		
    		$data = array(
    			'content_data' => array(
    				'fields' => array(
	    				'Nom' 		=> $user->pms_user_last_name,
	    				'Prénom' 	=> $user->pms_user_first_name,
	    				'Email' 	=> $user->user_email,
	    				'GSM' 		=> $user->pms_user_gsm,
    					'Nom d\'utilisateur' => $user->user_name,
    					'Code interne' => $user->pms_user_code,
    					'Rôle'		=> ucfirst($this->authentication->roles[$user->user_level]),
    					'Actif'		=> ($user->user_banned)? 'OUI' : 'NON',
    					'Date de création' => date('d/m/Y H:i:s', strtotime($user->user_date)),
    					'Dernière modification' => date('d/m/Y H:i:s', strtotime($user->user_modified)),
    					'Dernière connexion' => date('d/m/Y H:i:s', strtotime($user->user_last_login))
    						
    				)
    			),
    			'title' => "Detail d'un utilisateur",
    			'content' => 'users/view',
    			'user' => $user
    		);
    		
    		$this->load->view('global/layout', $data);
    	}
    }
    
    /**
     * Edit a user profile
     *
     * @param int $id ID of the user to edit
     */
    public function edit($id=NULL) {
    	$this->output->enable_profiler(TRUE);
    	if( $this->require_role('admin') ) {
    		if(!isset($id)) {
    			show_404();
    		}
    		
    		$this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
    		
    		$user = $this->users_model->getUserByID($id);
    		
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
    			
    			$this->form_validation->set_rules('pms_user_last_name', 'Nom', 'trim|required|max_length[80]');
				$this->form_validation->set_rules('pms_user_first_name', 'Prénom', 'trim|required|max_length[80]');
				$this->form_validation->set_rules('user_email', 'Email', "trim|required|valid_email|is_unique_exclude[users.user_email, user_id, $id]");
				
				$this->form_validation->set_rules('user_pass', 'Mot de passe', 'trim|required|external_callbacks[model,formval_callbacks,_check_password_strength,TRUE]');
				$this->form_validation->set_rules('user_pass_conf', 'Confirmation mot de passe', 'required|matches[user_pass]');
    			
    			if ($this->form_validation->run()) {
                       echo "TRUE";
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
    		
    		$data = array(
    			'content_data' => array(
    				'fields' => array(
	    				'Nom' 		=> form_input('pms_user_last_name', $data_values['pms_user_last_name'], array(
	    					'maxlength'	=> '80',
	    					'required' 	=> '1'
	    					)
	    				),
	    				'Prénom' 	=> form_input('pms_user_first_name', $data_values['pms_user_first_name'], array(
	    					'maxlength' => '80',
	    					'required' 	=> '1'
	    					)
	    				),
	    				'Email' 	=> form_input(array(
	    					'name'	=> 'user_email',
	    					'id'	=> 'user_email',
	    					'value'	=> $data_values['user_email'],
	    					'type'	=> 'email',
	    					'maxlength' => '150',
	    					'required' 	=> '1'
	    					)
	    				),
	    				'GSM' 	=> form_input(array(
	    					'name'	=> 'pms_user_gsm',
	    					'id'	=> 'pms_user_gsm',
	    					'value'	=> $data_values['pms_user_gsm'],
	    					'type'	=> 'number',
	    					'maxlength' => '30',
	    					'required' 	=> '1'
	    					)
	    				),
    					'Nom d\'utilisateur'	=> form_input('user_name', $data_values['user_name'], array(
	    					'maxlength'	=> '12',
    						'style'		=> 'width:50%;'
	    					)
	    				),
    					'Code interne'	=> form_input('pms_user_code', $data_values['pms_user_code'], array(
	    					'maxlength'	=> '20',
    						'style'		=> 'width:50%;',
	    					'required' 	=> '1'
	    					)
	    				),
    					'Rôle'		=> form_dropdown('user_level', $this->authentication->roles_for_select, $data_values['user_level']),
    					'Actif'		=> form_checkbox('user_banned', '1', $data_values['user_banned']),
    					'Mot de passe'	=> form_password('user_pass', substr($user->user_pass, 0, 5), array(
	    					'maxlength'	=> '60',
	    					'required' 	=> '1'
	    					)
	    				),
    					'Confirmation mot de passe'	=> form_password('user_pass_conf', substr($user->user_pass, 0, 5), array(
	    					'maxlength'	=> '60',
	    					'required' 	=> '1'
	    					)
	    				)
    				)
    			),
    			'title' => "Edition d'un utilisateur",
    			'content' => 'users/edit',
    			'user' => $user
    		);
    		
    		$this->load->view('global/layout', $data);
    	}
    	
    	
    }

    /**
     * Most minimal user creation. You will of course make your
     * own interface for adding users, and you may even let users
     * register and create their own accounts.
     *
     * The password used in the $user_data array needs to meet the
     * following default strength requirements:
     *   - Must be at least 8 characters long
     *   - Must have at least one digit
     *   - Must have at least one lower case letter
     *   - Must have at least one upper case letter
     *   - Must not have any space, tab, or other whitespace characters
     *   - No backslash, apostrophe or quote chars are allowed
     */
    public function create_user()
    {
        // Customize this array for your user
        $user_data = array(
            'user_name'     => 'aba',
            'user_pass'     => 'Comp253A1',
            'user_email'    => 'bayahiassem@yahoo.fr',
            'user_level'    => '9', // 9 if you want to login @ examples/index.
        );

        $this->load->library('form_validation');

        $this->form_validation->set_data( $user_data );

        $validation_rules = array(
			array(
				'field' => 'user_name',
				'label' => 'user_name',
				'rules' => 'max_length[12]'
			),
			array(
				'field' => 'user_pass',
				'label' => 'user_pass',
				'rules' => 'trim|required|external_callbacks[model,formval_callbacks,_check_password_strength,TRUE]',
			),
			array(
				'field' => 'user_email',
				'label' => 'user_email',
				'rules' => 'required|valid_email'
			),
			array(
				'field' => 'user_level',
				'label' => 'user_level',
				'rules' => 'required|integer|in_list[1,6,9]'
			)
		);

		$this->form_validation->set_rules( $validation_rules );

		if( $this->form_validation->run() )
		{
			$user_data['user_salt']     = $this->authentication->random_salt();
			$user_data['user_pass']     = $this->authentication->hash_passwd($user_data['user_pass'], $user_data['user_salt']);
			$user_data['user_id']       = $this->_get_unused_id();
			$user_data['user_date']     = date('Y-m-d H:i:s');
			$user_data['user_modified'] = date('Y-m-d H:i:s');

            // If username is not used, it must be entered into the record as NULL
            if( empty( $user_data['user_name'] ) )
            {
                $user_data['user_name'] = NULL;
            }

			$this->db->set($user_data)
				->insert(config_item('user_table'));

			if ($this->db->affected_rows() == 1) {
				echo '<h1>Congratulations</h1>' . '<p>User ' . $user_data['user_name'] . ' was created.</p>';
			}
		}
		else
		{
			echo '<h1>User Creation Error(s)</h1>' . validation_errors();
		}
    }
    
    // -----------------------------------------------------------------------

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
