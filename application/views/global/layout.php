<?php
	$this->load->view ( 'global/page_header' );
	$this->load->view ( $content, isset($content_data)? $content_data : '' );
	$this->load->view ( 'global/page_footer' );
?>