<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - MY Model
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class MY_Model extends CI_Model {

	/**
	 * An array specifying the form validation error delimeters.
	 * They can be conveniently set in either the controller or model.
	 * I like to use a list for my errors, and CI default is for 
	 * individual paragraphs, which I think is somewhat retarded.
	 *
	 * @var array
	 * @access public
	 */
	public $error_delimiters = array( '<li>', '</li>' );

	/**
	 * An array specifying which fields to unset from 
	 * the form validation class' protected error array.
	 * This is helpful if you have hidden fields that 
	 * are required, but the user shouldn't see them 
	 * if form validation fails.
	 *
	 * @var string
	 * @access public
	 */
	public $hide_errors = array();

	/**
	 * All form validation errors are stored as a string, 
	 * and can be accessed from the controller or model.
	 *
	 * @var string
	 * @access public
	 */
	public $validation_errors   = '';

	/**
	 * Validation rules are set in the model, since 
	 * the model is aware of what data should be inserted 
	 * or updated. The exception would be when using the 
	 * reauthentication feature, because we can optionally 
	 * pass in our validation rules from the controller.
	 *
	 * @var string
	 * @access public
	 */
	public $validation_rules = array();
	
	protected $table_name;
	
	protected $pk_column = 'id';
	
	// --------------------------------------------------------------

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	// --------------------------------------------------------------

	/**
	 * Form validation consolidation.
	 */
	public function validate()
	{
		// Load the form validation library
		$this->load->library('form_validation');

		// Apply the form validation error delimiters
		$this->_set_form_validation_error_delimiters();

		// Set form validation rules
		$this->form_validation->set_rules( $this->validation_rules );

		// If form validation passes
		if( $this->form_validation->run() !== FALSE )
		{
			// Load var to confirm validation passed
			$this->load->vars( array( 'validation_passed' => 1 ) );

			return TRUE;
		}

		/**
		 * If form validation passes, none of the code below will be processed.
		 */

		// Unset fields from the error array if they are in the hide errors array.
		if( ! empty( $this->hide_errors ) )
		{
			foreach( $this->hide_errors as $field )
			{
				$this->form_validation->unset_error( $field );
			}
		}

		// Load errors into class member for use in model or controller.
		$this->validation_errors = validation_errors();

		// Load var w/ validation errors
		$this->load->vars( array( 'validation_errors' => $this->validation_errors ) );

		/**
		 * Do not repopulate with data that did not validate
		 */

		// Get the errors
		$error_array = $this->form_validation->get_error_array();

		// Loop through the post array
		foreach( $this->input->post() as $k => $v )
		{
			// If a key is in the error array
			if( array_key_exists( $k, $error_array ))
			{
				// kill set_value() for that key
				$this->form_validation->unset_field_data( $k );
			}
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Sometimes, when you have a successful form validation, 
	 * you will not want to repopulate the form, but if you 
	 * don't unset the field data, the form will repopulate.
	 */
	public function kill_set_value()
	{
		$this->form_validation->unset_field_data('*');
	}

	// --------------------------------------------------------------

	/**
	 * Set the form validation error delimiters with an array.
	 */
	private function _set_form_validation_error_delimiters()
	{
		list( $prefix, $suffix ) = $this->error_delimiters;

		$this->form_validation->set_error_delimiters( $prefix, $suffix );
	}

	// --------------------------------------------------------------
	
	public function getDataList() {
		$query = $this->db->get($this->table_name);
		
		return $query->result();
	}
	
	/**
	 * Return a record by it's ID if exists, else return False
	 * 
	 * @param int $id
	 */
	public function getRecordByID($id) {
		$query = $this->db->get_where($this->table_name, array($this->pk_column => $id), 1);
		
		if ($query->num_rows() == 1) {
			return $query->row();
		}
		
		return FALSE;
	}
	
	/**
	 * Insert a new record
	 * 
	 * @param array $data
	 */
	public function create($data) {
		$this->db->set($data)
				->insert($this->table_name);
		
		if ($this->db->affected_rows() == 1) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Delete the record having the passed ID
	 * 
	 * @param integer $id
	 */
	public function delete($id){
		$this->db->delete($this->table_name, array($this->pk_column => $id));
	}
	
	/**
	 * Update the record having the passed ID with the passed data
	 * 
	 * @param integer $id
	 * @param array $data
	 */
	public function update($id, $data){
		$this->db->update($this->table_name, $data, array($this->pk_column => $id));
		
		if ($this->db->affected_rows() == 1) {
			return true;
		}
		
		return false;
	}
}

/* End of file MY_Model.php */
/* Location: /application/libraries/MY_Model.php */