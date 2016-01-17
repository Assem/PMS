<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * @author      Assem Bayahi
 */

class Respondents_model extends MY_Model {
	
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->table_name = 'respondents';
		
		$this->load->model('polls_model');
		$this->load->model('lovs_model');
	}
	
	/**
	 * Return the poll to witch this person responded
	 * 
	 * @param Question $question
	 */
	public function getPoll($respondent) {
		return $this->polls_model->getRecordByID($respondent->id_poll);
	}
	
	public function getEducationalLevel_List() {
		return self::_convert_to_array($this->lovs_model->getListe('educational_level'));
	}
	
	public function getEducationalLevel($respondent) {
		return $this->getEducationalLevel_List()[$respondent->educational_level];
	}
	
	public function getMaritalStatus_List() {
		return self::_convert_to_array($this->lovs_model->getListe('marital_status'));
	}
	
	public function getMaritalStatus($respondent) {
		return $this->getMaritalStatus_List()[$respondent->marital_status];
	}
	
	public function getProfessionalStatus_List() {
		return self::_convert_to_array($this->lovs_model->getListe('professional_status'));
	}
	
	public function getProfessionalStatus($respondent) {
		return $this->getProfessionalStatus_List()[$respondent->professional_status];
	}
	
	private static function _convert_to_array($db_records) {
		$results = array(null => '');
		
		foreach ($db_records as $record) {
			$results[$record->id] = $record->value;
		}
		
		return $results;
	}
	
	public function getCompanyType_List() {
		return self::_convert_to_array($this->lovs_model->getListe('company_type'));
	}
	
	public function getCompanyType($respondent) {
		return $this->getCompanyType_List()[$respondent->company_type];
	}
	
	public static function getCountry_List() {
		return array(
			'1'	=> 'Algérie'
		);
	}
	
	public function getCountry($respondent) {
		return $this->getCountry_List()[$respondent->country];
	}
	
	public static function getCity_List($id_country=1) {
		$cities = array(
			'1' => array(
				'1' => 'Alger',
				'2'	=> 'Oran',
				'3'	=> 'Constantine',
				'4'	=> 'Annaba',
				'5'	=> 'Blida'
			)
		);
		
		return $cities[$id_country];
	}
	
	public function getCity($respondent) {
		return $this->getCity_List()[$respondent->city];
	}
}