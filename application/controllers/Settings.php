<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PMS - Settings Controller
 *
 * @author      Assem Bayahi
 */

class Settings extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->config->load('pms_config');
        
        $this->load->model('lovs_model');
        $this->load->model('sequences_model');
		$this->load->helper(array('form', 'url', 'my_date'));
		
		$this->load->library('form_validation');
    }
    
    public function index() {
    	if( $this->require_role('admin') ) {
			$liste_lov_s = array();
			
			foreach ($this->config->item('pms_lov_s') as $group => $label) {
				$liste_lov_s[$group] = array(
    				'label' => $label,
    				'items' => $this->lovs_model->getList($group)
    			);
			}
			
			$sequences = array();
			
    		foreach ($this->config->item('pms_sequences') as $key => $label) {
    			$sequence = $this->sequences_model->getSequenceByKey($key);
    			$sequence->label = $label;
				$sequences[$key] = $sequence;
			}
			
			$data = array(
    			'title' 		=> "Configurations",
    			'content' 		=> 'settings/index',
    			'map_key' 		=> $this->config->item('pms_google_map_key'),
	    		'map_refresh' 	=> $this->config->item('pms_map_refresh_interval'),
    			'idle_time' 	=> $this->config->item('pms_agent_idle_time'),
    			'listes'		=> $liste_lov_s,
				'countries'		=> $this->lovs_model->getList('country'),
				'towns'			=> $this->lovs_model->getListWithParent('town'),
				'sequences'		=> $sequences,
				'data_url'		=> base_url("settings/lov_management/"),
    			'js_to_load' 	=> array('settings.js'),
    		);
	    	$this->load->view('global/layout', $data);
    	}
    }
    
	private function _sequence_getFields($action='edit', $data_values) {
    	$data = array(
			'fields' => array(
				'Préfix' 	=> form_input(array(
					'name'	=> 'prefix',
					'id'	=> 'prefix',
					'value'	=> $data_values['prefix'],
					'maxlength' 	=> '45',
					'class'		=> 'form-control'
					)
				),
				'Remplissage <font color="red">*</font>' 	=> form_input(array(
					'name'	=> 'fillers',
					'id'	=> 'fillers',
					'value'	=> $data_values['fillers'],
					'type'	=> 'number',
					'class'	=> 'form-control'
					)
				),
				'Prochain index <font color="red">*</font>' 	=> form_input(array(
					'name'	=> 'next_index',
					'id'	=> 'next_index',
					'value'	=> $data_values['next_index'],
					'type'	=> 'number',
					'class'	=> 'form-control'
					)
				),
				'Aperçu' 	=> '<span id="preview"></span>'
			)
		);
    	
    	return $data;
    }
    
	private function _setSequencesValidationRules($action='edit', $id=NULL) {
    	$this->form_validation->set_rules('prefix', 'Préfix', 'trim|max_length[45]');
		$this->form_validation->set_rules('fillers', 'Remplissage', 'trim|integer|greater_than_equal_to[0]|required|max_length[10]');
		$this->form_validation->set_rules('next_index', 'Prochain index', 'trim|integer|required|max_length[10]');
    }
    
    public function sequence_edit($id=NULL) {
    	if( $this->require_role('admin') ) {
    		if(!isset($id) || !is_numeric($id)) {
	    		show_404();
	    	}
	    		
	    	$sequence = $this->sequences_model->getRecordByID($id);
    		
    		$data = array(
    			'title' => "Edition d'une séquence",
    			'content' => 'settings/sequence_edit',
    			'sequence' => $sequence,
    			'js_to_load' 	=> array('settings.js')
    		);
    		
    		if($sequence) {
    			$label = $this->config->item('pms_sequences')[$sequence->key];
	    		
	    		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
	    			$data_values = array(
	    				'prefix' 		=> set_value('prefix', ''),
    					'fillers' 		=> set_value('fillers'),
	    				'next_index'	=> set_value('next_index')
	    			);
	    			
	    			$this->_setSequencesValidationRules('edit', $id);
	    			
	    			if ($this->form_validation->run()) {
		    			if($this->sequences_model->update($id, $data_values)){
	    					$this->session->set_flashdata('success', 'Séquence mise à jour!');
	    					
	    					redirect('/settings/index');
	    				} else {
	    					$this->session->set_flashdata('error', 'La mise à jour a échoué!');
	    				}
	                }
	    		} else {
	    			$data_values = array(
	    				'prefix' 	=> $sequence->prefix,
    					'fillers' 	=> $sequence->fillers,
	    				'next_index'=> $sequence->next_index
	    			);
	    		}
	    		
	    		$data['content_data'] = $this->_sequence_getFields('edit', $data_values);
	    		$data['content_data']['label'] = $label;
    		}
    		
    		$this->load->view('global/layout', $data);
    	}
    }
    
    public function lov_management() {
    	$this->output->enable_profiler(FALSE);
    	$result = 'error';
    	
    	if( $this->require_role('admin') ) {
    		
    		switch($this->input->post('action')) {
    			case 'delete':
    				$this->lovs_model->delete($this->input->post('id'));
    				$result = "success";
    				break;
    			case 'edit':
    				$this->lovs_model->update($this->input->post('id'), array('value' => $this->input->post('value')));
    				$result = "success";
    				break;
    			case 'add':
    				$parent = NULL;
    				if($this->input->post('parent_value')) {
    					$parent = $this->input->post('parent_value');
    				}
    				$this->lovs_model->create(
    					array(
    						'id_parent' => $parent,
	    					'value' 	=> $this->input->post('value'),
	    					'group' 	=> $this->input->post('group')
	    				)
    				);
    				$result = "success";
    				break;
    		}
    	}
    	
    	echo json_encode($result);
    }
}
