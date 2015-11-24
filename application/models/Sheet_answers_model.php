<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @author      Assem Bayahi
 */

class Sheet_answers_model extends MY_Model {
	
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->table_name = 'sheet_answers';
	}
	
	/**
	 * Insert all answers for a sheet
	 * 
	 * @param int $id_sheet
	 * @param array $data
	 */
	public function createFromSheet($id_sheet, $data) {
		$result = TRUE;
		
		foreach ($data as $id_question => $value) {
			if(is_array($value)) {
				$value = implode(',', $value);
			}
			
			$result = $result && $this->create(array(
				'id_question' 	=> $id_question,
				'id_sheet'		=> $id_sheet,
				'value'			=> $value
			));
		}
		
		return $result;
	}
}