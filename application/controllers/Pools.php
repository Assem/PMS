<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PMS - Pools Controller
 *
 * @author      Assem Bayahi
*/

class Pools extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('pools_model');
	}
	
	/**
	 * List all the pools
	 */
	public function index() {
		if( $this->require_min_level(1) ) {
			if( !$this->is_role('admin') ) { //if not admin, then it's an agent so we redirect him
				redirect( secure_site_url('pools/select') );
			}
			
			$data = array(
				'content' => 'pools/index',
				'title' => "Liste des sondages",
	    		'js_to_load' => array('pools.js'),
	    		'pools' => $this->pools_model->getDataList()
			);
			
			$this->load->view('global/layout', $data);
		}
	}
	
	private function _getFields($action='edit', $data_values) {
    	$data = array(
			'fields' => array(
				'Code interne <font color="red">*</font>' 		=> form_input('code', $data_values['code'], array(
					'maxlength'	=> '30',
					'required' 	=> '1',
					'class'		=> 'form-control'
					)
				),
				'Libellé <font color="red">*</font>' 	=> form_input('label', $data_values['label'], array(
					'maxlength' => '80',
					'required' 	=> '1',
					'class'		=> 'form-control'
					)
				),
				'Date début' 	=> form_input(array(
					'name'	=> 'start_date',
					'id'	=> 'start_date',
					'value'	=> $data_values['start_date'],
					'type'	=> 'date',
					'class'		=> 'form-control'
					)
				),
				'Date fin' 	=> form_input(array(
					'name'	=> 'end_date',
					'id'	=> 'end_date',
					'value'	=> $data_values['end_date'],
					'type'	=> 'date',
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
    	$unique_fct_params = ", user_id, $id";
    	
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
				"trim|valid_date|date_less_than_equal_to[Date de fin,".$this->input->post('end_date')."]", 
				array('date_less_than_equal_to' => 'La date de début doit être inférieur ou égale à la date de fin'));
		$this->form_validation->set_rules(
				'end_date', 
				'Date de fin', 
				"trim|date_greater_than_equal_to[Date de début,".$this->input->post('start_date')."]",
				array('date_greater_than_equal_to' => 'La date de fin doit être supérieur ou égale à la date de début'));
		
		$this->form_validation->set_rules('max_surveys_number', 'Nbr maximum de fiches', 'trim|integer|max_length[11]');
		$this->form_validation->set_rules('code', 'Code interne', "trim|required|max_length[20]|{$unique_fct}[pools.code{$unique_fct_params}]");
    }
	
	/**
     * Add a new survey
     *
     */
    public function add() {
    	$this->output->enable_profiler(TRUE);
    	if( $this->require_role('admin,super-agent') ) {
    		$this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');
    		
    		$data = array(
    			'title' => "Ajouter un sondage",
    			'content' => 'pools/add'
    		);
    		
    		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
    			$data_values = array(
    				'code' 				=> set_value('code'),
    				'label' 			=> set_value('label'),
    				'customer' 			=> set_value('customer'),
    				'start_date' 		=> set_value('start_date'),
    				'end_date' 			=> set_value('end_date'),
    				'max_surveys_number'=> set_value('max_surveys_number'),
    				'actif' 			=> set_value('actif'),
    				'description' 		=> set_value('description'),
    			);
    			
    			$this->_setValidationRules('add');
    			
				if ($this->form_validation->run()) {
					$data_values['created_by']		= $this->auth_user_id;
					$data_values['creation_date']	= date('Y-m-d H:i:s');
					$data_values['update_date']		= date('Y-m-d H:i:s');
					$data_values['actif']	  = (empty($data_values['actif']))? '0' : '1';
		
		            if($this->pools_model->create($data_values)){
    					$this->session->set_flashdata('success', 'Sondage créé avec succès!');
    					
    					redirect('/pools/index');
    				} else {
    					$this->session->set_flashdata('error', 'La création a échoué!');
    				}
                }
    		} else {
    			$data_values = array(
    				'code' 	=> '',
    				'label' 	=> '',
    				'customer' 			=> '',
    				'start_date' 			=> '',
    				'end_date' 			=> '',
    				'max_surveys_number' 		=> '',
    				'actif' 			=> TRUE,
    				'description' 			=> ''
    			);
    		}
	    		
	    	$data['content_data'] = $this->_getFields('add', $data_values);
    		
    		$this->load->view('global/layout', $data);
    	}
    }
    
	/**
     * Check if we have a pool with the passed $id  
     * 
     * @param integer $id
     */
    private function _checkRecord($id) {
    	if(!isset($id) || !is_numeric($id)) {
    		show_404();
    	}
    		
    	$pool = $this->pools_model->getRecordByID($id);
    	
    	return $pool;
    }
    
    /**
     * View a pools detail
     * 
     * @param int $id ID of the pool to view
     */
    public function view($id=NULL) {
    	$this->output->enable_profiler(TRUE);
    	
    	if( $this->require_role('admin,super-agent') ) {
    		$pool = $this->_checkRecord($id);
    		
    		$data = array(
    			'title' => "Detail d'un sondage",
    			'content' => 'pools/view',
    			'pool' => $pool
    		);
    		
    		if($pool){
    			$createdBy = $this->pools_model->getCreatedby($pool);
    			$createdByLink = secure_anchor("users/view/".$createdBy->user_id, 
    					strtoupper($createdBy->pms_user_last_name)." ".ucfirst($createdBy->pms_user_first_name));
    			
    			$data['content_data'] = array(
    				'fields' => array(
	    				'Code interne' 		=> $pool->code,
	    				'Client' 			=> $pool->customer,
    					'Libellé' 			=> $pool->label,
	    				'Description'		=> $pool->description,
    					'Date de début' 	=> date('d/m/Y', strtotime($pool->start_date)),
    					'Date de fin' 		=> date('d/m/Y', strtotime($pool->end_date)),
    					'Nbr maximum de fiches' 		=> $pool->max_surveys_number,
    					'Actif'				=> ($pool->actif)? 'OUI' : 'NON',
    					'Date de création' 	=> date('d/m/Y H:i:s', strtotime($pool->creation_date)),
    					'Dernière modification' => date('d/m/Y H:i:s', strtotime($pool->update_date)),
    					'Créé par' 			=> $createdByLink
    				)
	    		);
    		}
    		
    		$this->load->view('global/layout', $data);
    	}
    }
}