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
		
		$this->load->model('pools_model');
	}
	
	/**
	 * Return the pool to witch this person responded
	 * 
	 * @param Question $question
	 */
	public function getPool($respondent) {
		return $this->pools_model->getRecordByID($respondent->id_pool);
	}
	
	public static function getEducationalLevel_List() {
		return array(
			'0' => '',
			'1'	=> 'Aucun',
			'2'	=> 'Primaire',
			'3'	=> 'Secondaire',
			'4'	=> 'Universitaire',
			'5'	=> '3ième Cycle'
		);
	}
	
	public function getEducationalLevel($respondent) {
		return $this->getEducationalLevel_List()[$respondent->educational_level];
	}
	
	public static function getMaritalStatus_List() {
		return array(
			'0' => '',
			'1'	=> 'Célibataire',
			'2'	=> 'Marié',
			'3'	=> 'Divorcé',
			'4'	=> 'Veuf'
		);
	}
	
	public function getMaritalStatus($respondent) {
		return $this->getMaritalStatus_List()[$respondent->marital_status];
	}
	
	public static function getProfessionalStatus_List() {
		return array(
			'0' => '',
			'1'	=> 'Elève',
			'2'	=> 'Etudiant',
			'3'	=> 'Chômeur',
			'4'	=> 'Employé',
			'5'	=> 'Directeur',
			'6' => 'Fondateur'
		);
	}
	
	public function getProfessionalStatus($respondent) {
		return $this->getProfessionalStatus_List()[$respondent->professional_status];
	}
	
	public static function getCompanyType_List() {
		return array(
			'0' => '',
			'1'	=> 'Etatique',
			'2'	=> 'Privé'
		);
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