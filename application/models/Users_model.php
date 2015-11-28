<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      Assem Bayahi
 */

class Users_model extends MY_Model {
	
	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->table_name = config_item('user_table');
		$this->pk_column = 'user_id';
	}
	
	// --------------------------------------------------------------

	/**
	 * Get data for a recovery
	 * 
	 * @param   string  the email address
	 * @return  mixed   either query data or FALSE
	 */
	public function get_recovery_data( $email )
	{
		$query = $this->db->select('u.user_id, u.user_salt, u.user_email, u.user_banned')
			->from( config_item('user_table') . ' u')
			->where('u.user_email', $email)
			->limit(1)
			->get();

		if( $query->num_rows() == 1 )
		{
			return $query->row();
		}

		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Get the user name, user salt, and hashed recovery code,
	 * but only if the recovery code hasn't expired.
	 */
	public function get_recovery_verification_data( $user_id )
	{
		$recovery_code_expiration = date('Y-m-d H:i:s', time() - config_item('recovery_code_expiration') );

		$query = $this->db->select('user_name,user_salt,passwd_recovery_code')
			->from( config_item('user_table') )
			->where( 'user_id', $user_id )
			->where( 'passwd_recovery_date >', $recovery_code_expiration )
			->limit(1)
			->get();

		if ( $query->num_rows() == 1 )
		{
			return $query->row();
		}
		
		return FALSE;
	}

	// --------------------------------------------------------------

	/**
	 * Validation and processing for password change during account recovery
	 */
	public function recovery_password_change()
	{
		// The form validation class doesn't allow for multiple config files, so we do it the old fashion way
		$this->config->load( 'form_validation/examples/recovery_verification' );
		$this->validation_rules = config_item('recovery_verification');

		if( $this->validate() )
		{
			$this->_change_password(
				set_value('user_pass'),
				set_value('user_pass_confirm'),
				$this->input->post('user_identification'),
				$this->input->post('recovery_code')
			);
		}
	}

	// --------------------------------------------------------------

	/**
	 * Change a user's password
	 * 
	 * @param  string  the form token
	 * @param  string  the flash token to match the form token
	 * @param  string  the new password
	 * @param  string  the new password confirmed
	 * @param  string  the user ID
	 * @param  string  the special string
	 */
	protected function _change_password( $password, $password2, $user_id, $special_string )
	{
		// User ID check
		if( isset( $user_id ) && $user_id !== FALSE )
		{
			$this->db->select('user_id');

			// If special string is the CI encryption key, this is a self update or user update.
			if( $special_string == config_item('encryption_key') )
			{
				$this->db->where( 'user_id', $user_id );
			}

			// If the special string was not present, this is a password recovery
			else
			{
				$this->db->where( 'user_id', $user_id );
				$this->db->where( 'passwd_recovery_code', $special_string );
			}

			$query = $this->db->get_where( config_item('user_table') );

			// If above query indicates a match, change the password
			if( $query->num_rows() == 1 )
			{
				$user_data = $query->row();

				// Generate a new random user salt
				$new_salt = $this->authentication->random_salt();

				$data = array(
					'user_pass' => $this->authentication->hash_passwd( $password, $new_salt ),
					'user_salt' => $new_salt
				);

				$this->db->where( 'user_id', $user_data->user_id )
					->update( config_item('user_table'), $data );
			}
		}
	}

	// --------------------------------------------------------------

	/**
	 * Get an unused ID for user creation
	 * 
	 * @param   bool  whether to generate a user Id or registration ID
	 * @return  int
	 */
	public function get_unused_id( $temp = FALSE )
	{
		// Create a random user id
		$random_unique_int = mt_rand(1200,999999999);

		// Make sure the random user_id isn't already in use
		if($temp === FALSE)
		{
			// Generate unused user ID
			$query = $this->db->where('user_id', $random_unique_int)
								->get_where( config_item('user_table'));
		}
		else
		{
			// Generate unused registration ID
			$query = $this->db->where('reg_id', $random_unique_int)
								->get_where( config_item('temp_reg_data_table'));
		}

		if ($query->num_rows() > 0)
		{
			$query->free_result();

			// If the random user_id is already in use, get a new number
			$this->get_unused_id($temp);
		}

		return $random_unique_int;
	}

	// --------------------------------------------------------------
	
	/**
	 * Count all the sheets created by this user
	 * 
	 * @param user $user
	 */
	public function countSheets($user) {
		$this->db->where('created_by', $user->user_id);
		$this->db->from('sheets');
		return $this->db->count_all_results();
	}
	
	/**
	 * Return the most recent positions of the user
	 * 
	 * @return geolocation record
	 */
	public function getLastPositions($user, $limit=0) {
		$this->db->select('geolocations.*, 
				pools.code as pool_code, pools.label as pool_label, 
				sheets.id sheet_id', false)
			->where('id_user', $user->user_id)
			->order_by('geolocations.creation_date', 'desc')
			->from('geolocations')
			->join('sheets', 'sheets.id = geolocations.id_sheet', 'LEFT')
			->join('pools', 'pools.id = sheets.id_pool', 'LEFT');
		
		if($limit) {
			$this->db->limit($limit);
		}
		
		return $this->db->get()->result();
	}
}

/* End of file users_model.php */
/* Location: /models/users_model.php */