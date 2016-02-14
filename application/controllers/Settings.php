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
        $this->load->model('settings_model');
        
		$this->load->helper(array('form', 'url', 'my_date'));
		
		$this->load->library('form_validation');
    }
    
    public function index() {
    	if( $this->require_role('admin') ) {
    		$settings = array();
    		foreach ($this->settings_model->getDataList() as $setting) {
    			$settings[$setting->key] = $setting->value;
    		}
    		
    		$data_values = array(
	    		'map_update_interval' => set_value('map_update_interval', $settings['map_update_interval']),
    			'map_show_all_sheets' => set_value('map_show_all_sheets', ( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' )? FALSE : $settings['map_show_all_sheets']),
	    		'dashboard_last_sheets_number'	=> set_value('dashboard_last_sheets_number', $settings['dashboard_last_sheets_number']),
    			'dashboard_last_errors_number' => set_value('dashboard_last_errors_number', $settings['dashboard_last_errors_number']),
	    		'map_idle_interval'	=> set_value('map_idle_interval', $settings['map_idle_interval'])
	    	);
    			
	    	if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
	    		$this->_setSettingsValidationRules();
	    		
	    		$data_values['map_show_all_sheets']	  = (empty($data_values['map_show_all_sheets']))? '0' : '1';
	    			
	    		if ($this->form_validation->run()) {
	    			foreach ($data_values as $key => $value) {
	    				$this->settings_model->update($key, array('value' => $value));
	    			}
		    		$this->session->set_flashdata('success', 'Configuration mise à jour!');
	    					
	    			redirect('/settings/index');
				}
	    	}
	    		
	    	$settings_fields = $this->_settings_getFields('edit', $data_values);
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
    			'listes'		=> $liste_lov_s,
				'countries'		=> $this->lovs_model->getList('country'),
				'towns'			=> $this->lovs_model->getListWithParent('town'),
				'sequences'		=> $sequences,
				'settings'		=> $settings_fields,
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
    
	private function _settings_getFields($action='edit', $data_values) {
		$data = array(
			'fields' => array(
				"Afficher toutes les fiches <font color='red'>*</font><span id='helpBlock' class='help-block'>Si cette case n'est pas cochée, on affiche seulement la dernière fiche créée, aujourd'hui, pour chaque agent </span>"	=> form_checkbox('map_show_all_sheets', '1', $data_values['map_show_all_sheets'], 'class="form-control"'),
				"Délai de rafraichissement du dashboard (sec) <font color='red'>*</font>" 	=> form_input(array(
					'name'	=> 'map_update_interval',
					'id'	=> 'map_update_interval',
					'value'	=> $data_values['map_update_interval'],
					'type'	=> 'number',
					'min'	=> 10,
					'class'	=> 'form-control'
					)
				),
				"Délai d'inactivité (min) <font color='red'>*</font><span id='helpBlock' class='help-block'>Si l'âge de la dernière fiche d'un agent dépasse cette valeur (en minute), l'agent s'affichera grisé</span>" 	=> form_input(array(
					'name'	=> 'map_idle_interval',
					'id'	=> 'map_idle_interval',
					'value'	=> $data_values['map_idle_interval'],
					'type'	=> 'number',
					'min'	=> 1,
					'class'	=> 'form-control'
					)
				),
				"Nombre de fiches à afficher dans le dashboard <font color='red'>*</font>" 	=> form_input(array(
					'name'	=> 'dashboard_last_sheets_number',
					'id'	=> 'dashboard_last_sheets_number',
					'value'	=> $data_values['dashboard_last_sheets_number'],
					'type'	=> 'number',
					'min'	=> 1,
					'class'	=> 'form-control'
					)
				),
				"Nombre d'erreurs à afficher dans le dashboard <font color='red'>*</font>" 	=> form_input(array(
					'name'	=> 'dashboard_last_errors_number',
					'id'	=> 'dashboard_last_errors_number',
					'value'	=> $data_values['dashboard_last_errors_number'],
					'type'	=> 'number',
					'min'	=> 1,
					'class'	=> 'form-control'
					)
				)
			)
		);
    	
    	return $data;
    }
    
	private function _setSettingsValidationRules() {
    	$this->form_validation->set_rules('map_update_interval', 'Délai de rafraichissement', 'trim|integer|greater_than_equal_to[10]|required|max_length[10]');
    	$this->form_validation->set_rules('map_idle_interval', 'Délai d\'inactivité', 'trim|integer|greater_than_equal_to[1]|required|max_length[10]');
    	$this->form_validation->set_rules('dashboard_last_errors_number', 'Nombre d\'erreurs', 'trim|integer|greater_than_equal_to[1]|required|max_length[10]');
    	$this->form_validation->set_rules('dashboard_last_sheets_number', 'Nombre de fiches', 'trim|integer|greater_than_equal_to[1]|required|max_length[10]');
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
	    		
    			$data_values = array(
	    			'prefix' 		=> set_value('prefix', $sequence->prefix),
    				'fillers' 		=> set_value('fillers', $sequence->fillers),
	    			'next_index'	=> set_value('next_index', $sequence->next_index)
	    		);
    			
	    		if( strtolower( $_SERVER['REQUEST_METHOD'] ) == 'post' ){
	    			$this->_setSequencesValidationRules('edit', $id);
	    			
	    			if ($this->form_validation->run()) {
		    			if($this->sequences_model->update($id, $data_values)){
	    					$this->session->set_flashdata('success', 'Séquence mise à jour!');
	    					
	    					redirect('/settings/index');
	    				} else {
	    					$this->session->set_flashdata('error', 'La mise à jour a échoué!');
	    				}
	                }
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
