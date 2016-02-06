<div id="settings">
	<ul>
		<li><a href="#lov">Listes de valeurs</a></li>
		<li><a href="#countries">Pays/Villes</a></li>
		<li><a href="#dashboard">Dashboard</a></li>
	</ul>
	<div id="lov" data-index="0">
		<?php
			$this->load->view ( 'settings/_lov' );
		?>
	</div>
	<div id="countries" data-index="1">
		<?php
			$this->load->view ( 'settings/_countries' );
		?>
	</div>
	<div id="dashboard" data-index="2">
		
	</div>
</div>

<script type="text/javascript">
var lov_url = '<?php echo $data_url; ?>';
</script>