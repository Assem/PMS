<?php
	$this->load->view ( 'global/page_header', array('title' => $title) );
?>
<!-- Page Content -->
<div class="container" style="padding-top: 10px;">
	<div class="row">
		<div class="col-lg-12 text-center">
			<?php
				$this->load->view ( $content, isset($content_data)? $content_data : '' );
			?>
		</div>
	</div>
	<!-- /.row -->
</div>
<?php
	$this->load->view ( 'global/page_footer' );
?>