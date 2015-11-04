<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PMS - Pools Controller
 *
 * @author      Assem Bayahi
*/

class Pool extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index() {
		if( $this->require_min_level(1) ) {
			if( !$this->is_role('admin') ) { //if not admin, then it's an agent so we redirect him
				redirect( secure_site_url('pools/select') );
			}
			
			$data = array(
				'content' => 'pools/index',
				'title' => "Liste des sondages"
			);
			
			$this->load->view('global/layout', $data);
		}
	}
}