<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model to manage Settings values
 * 
 * @author      Assem Bayahi
 */

class Settings_model extends MY_Model {
	
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->table_name = 'settings';
		$this->pk_column = 'key';
	}	
}