<?php if($this->session->flashdata('success')): ?>
	<div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <?php echo $this->session->flashdata('success');?>
    </div>
<?php endif; ?>
<?php if($this->session->flashdata('error')): ?>
	<div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <strong>Erreur! </strong><?php echo $this->session->flashdata('error');?>
    </div>
<?php endif; ?>