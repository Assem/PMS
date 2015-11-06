<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse"
				data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span> <span
					class="icon-bar"></span> <span class="icon-bar"></span> <span
					class="icon-bar"></span>
			</button>
			<a class="navbar-brand" rel="home" href="/">
				<img style="max-height:35px; margin-top: -7px;"
             		src="/assets/img/yessir-background.jpg">
			</a>
		</div>
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse"
			id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<?php $active_controller = $this->router->fetch_class();?>
				<li <?php echo(($active_controller == 'pool')?'class="active"':''); ?>><?php echo secure_anchor('users/index', 'Sondages'); ?></li>
				<li <?php echo(($active_controller == 'questionnaire')?'class="active"':''); ?>><?php echo secure_anchor('users/index', 'Questionnaires'); ?></li>
				<li <?php echo(($active_controller == 'users')?'class="active"':''); ?>><?php echo secure_anchor('users/index', 'Utilisateurs'); ?></li>
				<li class="logout"><?php echo secure_anchor('users/logout', 'Déconnexion'); ?></li>
			</ul>
			<span class="welcome-span"><?php echo "Bonjour $auth_last_name $auth_first_name" ?></span>
		</div>
		<!-- /.navbar-collapse -->
	</div>
	<!-- /.container -->
</nav>