		<script type="text/javascript" src="<?php echo base_url("assets/js/plugins/jquery.js"); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url("assets/js/plugins/jquery-ui.min.js"); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url("assets/js/plugins/jquery-ui-timepicker-addon.js"); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url("assets/js/plugins/datepicker-fr.js"); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url("assets/js/plugins/jquery-ui-timepicker-fr.js"); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url("assets/js/plugins/bootstrap.js"); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url("assets/js/main.js"); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url("assets/js/plugins/jquery.dataTables.min.js"); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url("assets/js/plugins/dataTables.responsive.js"); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url("assets/js/plugins/dataTables.bootstrap.min.js"); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url("assets/js/plugins/jquery.dataTables.columnFilter.js"); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url("assets/js/plugins/date-euro.js"); ?>"></script>
		
		<?php if(isset($js_to_load)): ?>
			<?php foreach ($js_to_load as $js): ?>
			    <script type="text/javascript" src="<?php echo base_url("assets/js/$js"); ?>"></script>
			<?php endforeach; ?>
		<?php endif; ?>
	</body>
</html>

<?php

/* End of file page_footer.php */
/* Location: /views/examples/page_footer.php */