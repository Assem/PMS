<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - MY_Form_validation
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

class MY_Form_validation extends CI_Form_validation {
	private $DATE_TIME_FORMAT = 'd/m/Y H:i:s';
	private $DATE_FORMAT = 'd/m/Y';
	/**
	 * Access to protected $_error_array
	 */
	public function get_error_array()
	{
		return $this->_error_array;
	}

	// --------------------------------------------------------------

	/**
	 * If there are form validation errors, you can unset them so they
	 * don't display when calling validation_errors().
	 *
	 * @param  string  the field name to unset
	 */
	public function unset_error( $field )
	{
		if( isset( $this->_error_array[$field] ) ) 
			unset( $this->_error_array[$field] );
	}

	// --------------------------------------------------------------

	/**
	 * Unset an element in the protected $_field_data
	 *
	 * @param  mixed  either an array of elements to unset or a string with name of element to unset
	 */
	public function unset_field_data( $element )
	{
		if( is_array( $element ) )
		{
			foreach( $element as $x )
			{
				$this->unset_field_data( $x );
			}
		}
		else
		{
			if( $element == '*' )
			{
				unset( $this->_field_data );
			}
			else
			{
				if( isset( $this->_field_data[$element] ) )
				{
					unset( $this->_field_data[$element] );
				}
			}
		}
	}

	// --------------------------------------------------------------

	/**
	 * Generic callback used to call callback methods for form validation.
	 * 
	 * @param  string  the value to be validated
	 * @param  string  a comma separated string that contains the model name, 
	 *                 method name and any optional values to send to the method 
	 *                 as a single parameter.
	 *
	 *                 - First value is the external class type (library,model)
	 *                 - Second value is the name of the class.
	 *                 - Third value is the name of the method.
	 *                 - Any additional values are values to be 
	 *                   sent in an array to the method. 
	 *
	 *      EXAMPLE RULE FOR CALLBACK IN MODEL:
	 *  external_callbacks[model,model_name,some_method,some_string,another_string]
	 *  
	 *      EXAMPLE RULE FOR CALLBACK IN LIBRARY:
	 *  external_callbacks[library,library_name,some_method,some_string,another_string]
	 */
	public function external_callbacks( $postdata, $param )
	{
		$param_values = explode( ',', $param ); 

		// Load the model or library holding the callback
		$class_type = $param_values[0];
		$class_name = $param_values[1];
		$this->CI->load->$class_type( $class_name );

		// Apply method name to variable for easy usage
		$method = $param_values[2];

		// Check to see if there are any additional values to send as an array
		if( count( $param_values ) > 3 )
		{
			// Remove the first three elements in the param_values array
			$argument = array_slice( $param_values, 3 );
		}

		// Do the actual validation in the external callback
		if( isset( $argument ) )
		{
			$callback_result = $this->CI->$class_name->$method( $postdata, $argument );
		}
		else
		{
			$callback_result = $this->CI->$class_name->$method( $postdata );
		}

		return $callback_result;
	}
	
	// --------------------------------------------------------------
	
	/**
	 * Is Unique
	 *
	 * Check if the input value doesn't already exist
	 * in the specified database field, taking in consideration the edited record id
	 *
	 * @param	string	$str
	 * @param	string	$field
	 * @return	bool
	 */
	public function is_unique_exclude($str, $field)
	{
		$params = explode(', ', $field);
		
		$id_column = $params[1];
		$id_to_exclude = $params[2];
		$field = $params[0];
		
		sscanf($field, '%[^.].%[^.]', $table, $field);
		return isset($this->CI->db)
			? ($this->CI->db->limit(1)->where("$id_column !=", $id_to_exclude)->get_where($table, array($field => $str))->num_rows() === 0)
			: FALSE;
	}
	
	/**
	 * Check if the passed string is a valid 'm-d-Y' date
	 * 
	 * @param string $str
	 */
	public function valid_date($str) {
		$d = DateTime::createFromFormat($this->DATE_FORMAT, $str);
		
		return $d && $d->format($this->DATE_FORMAT) == $str;
	}
	
	public function date_less_than_equal_to($str, $field) {
		$first_date = DateTime::createFromFormat($this->DATE_TIME_FORMAT, "$str 00:00:00");
		$second_date = false;
		if($field) {
			$second_date = DateTime::createFromFormat($this->DATE_TIME_FORMAT, $field." 00:00:00");
		}
		
		if($first_date && $second_date){
			return $first_date->gettimestamp() <= $second_date->gettimestamp();
		}
		
		return true;
	}
	
	public function date_greater_than_equal_to($str, $field) {
		$first_date = DateTime::createFromFormat($this->DATE_TIME_FORMAT, "$str 00:00:00");
		$second_date = false;
		if($field) {
			$second_date = DateTime::createFromFormat($this->DATE_TIME_FORMAT, $field." 00:00:00");
		}
		
		if($first_date && $second_date){
			return $first_date->gettimestamp() >= $second_date->gettimestamp();
		}
		
		return true;
	}
}

/* End of file MY_Form_validation.php */
/* Location: /application/libraries/MY_Form_validation.php */ 