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
        
        $this->load->model('lovs_model');
		$this->load->helper(array('form', 'url', 'my_date'));
    }
    
    public function index() {
    	if( $this->require_role('admin') ) {
			$this->config->load('pms_config');
			
			$liste_lov_s = array();
			
			foreach ($this->config->item('pms_lov_s') as $group => $label) {
				$liste_lov_s[$group] = array(
    				'label' => $label,
    				'items' => $this->lovs_model->getList($group)
    			);
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
				'data_url'		=> base_url("settings/lov_management/"),
    			'js_to_load' 	=> array('settings.js'),
    		);
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
