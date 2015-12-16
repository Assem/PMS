<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - MY Controller
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

require_once APPPATH . 'third_party/community_auth/core/Auth_Controller.php';

class MY_Controller extends Auth_Controller
{
	public $main_model;
	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->output->enable_profiler(TRUE);
	}
	
	/**
     * Check if we have a record with the passed $id  
     * 
     * @param integer $id
     */
    protected function _checkRecord($id) {
    	if(!isset($id) || !is_numeric($id)) {
    		show_404();
    	}
    		
    	$record = $this->main_model->getRecordByID($id);
    	
    	return $record;
    }
}

/* End of file MY_Controller.php */
/* Location: /application/libraries/MY_Controller.php */