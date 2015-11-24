<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @author      Assem Bayahi
 */

class Geolocations_model extends MY_Model {
	
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->table_name = 'geolocations';
	}
}