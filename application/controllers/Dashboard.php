<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PMS - Dashboard Controller
 *
 * @author      Assem Bayahi
 */

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('polls_model');
        $this->load->model('sheets_model');
        $this->load->model('geolocations_model');
        $this->load->model('settings_model');
        
		$this->load->helper(array('form', 'url', 'my_date'));
    }
    
    public function index() {
    	if( $this->require_role('admin,super-agent,agent') ) {
    		$settings = array();
    		foreach ($this->settings_model->getDataList() as $setting) {
    			$settings[$setting->key] = $setting->value;
    		}
    		
			if( $this->is_role('agent') ) { //if it's an agent we redirect him to start creating a sheet
				redirect( secure_site_url('polls/select') );
			} elseif ($this->is_role('super-agent')) { //if it's an super-agent we redirect him to the Poll module
				redirect( secure_site_url('polls') );
			}
			
    		$this->config->load('pms_config');
			$polls = $this->polls_model->getActivePolls(false);
			
    		foreach ($polls as $poll) {
    			$poll->sheets_count = $this->polls_model->countSheets($poll);
    			$color = ($poll->sheets_count == $poll->max_surveys_number)? 'red':'green';
    			$poll->show_count = "<span style='color:$color'> (<span id='sheet_number_" . $poll->id . "'>".$poll->sheets_count.'</span>/'.$poll->max_surveys_number.')</span>';
    		}
    		
    		$data = array(
    			'title' 		=> "Fiche du répondant",
    			'content' 		=> 'dashboard/index',
	    		'js_to_load' 	=> array('tracking.js', 'map_utilities.js'),
    			'map_key' 		=> $this->config->item('pms_google_map_key'),
	    		'map_refresh' 	=> $settings['map_update_interval'],
    			'idle_time' 	=> $settings['map_idle_interval'],
    			'polls' 		=> $polls,
    			'sheets'		=> $this->sheets_model->getSheetsWithPollAndUser(10),
    			'geolocations'	=> $this->geolocations_model->getErrors(10),
    			'data_url'		=> base_url("sheets/get_sheets/")
    		);
	    	$this->load->view('global/layout', $data);
    	}
    }
}
