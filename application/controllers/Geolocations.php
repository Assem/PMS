<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PMS - Geolocation Controller
 *
 * @author      Assem Bayahi
 */

class Geolocations extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('geolocations_model', 'main_model');
    }
    
    /**
     * List recent geolocations errors
     * 
     */
    public function recent_errors() {
    	$this->output->enable_profiler(FALSE);
    	
    	if( $this->require_role('admin') ) {
	    	$errors = $this->main_model->getErrors(10);
	    	
	    	$this->load->view('geolocations/_recent_errors', array('geolocations' => $errors));
    	}
    }
}
