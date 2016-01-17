<?php

function get_lov_label($group) {
	$ci =& get_instance();
	// Load config file
	$ci->load->config('pms_config');
	
	return $ci->config->item('pms_lov_s')[$group];
}