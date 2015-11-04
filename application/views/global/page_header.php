<!DOCTYPE html>
<html lang="fr" >
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="generator" content="Bootply" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
	<title><?php echo $title; ?></title>
	
	<link rel="stylesheet" href="<?php echo base_url("assets/css/plugins/bootstrap.css"); ?>" />
	<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="stylesheet" href="<?php echo base_url("assets/css/main.css"); ?>" />
	
	<link rel="stylesheet" href="<?php echo base_url("assets/css/plugins/dataTables.bootstrap.css"); ?>" />
	<link rel="stylesheet" href="<?php echo base_url("assets/css/plugins/dataTables.responsive.css"); ?>" />
	
	<link rel="stylesheet" href="<?php echo base_url("assets/css/plugins/jquery-ui.css"); ?>" />
	<link rel="stylesheet" href="<?php echo base_url("assets/css/plugins/jquery-ui-timepicker-addon.css"); ?>" />
			
	<style>
    body {
        padding-top: 50px;
        /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
    }
    </style>
</head>
<body>
	<?php 
	if(isset($auth_role) && $auth_role == 'admin') {
		$this->load->view ( 'global/menu' );
	}
	?>
	<?php

/* End of file page_header.php */
/* Location: /views/examples/page_header.php */