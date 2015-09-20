<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Login Form View
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2015, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

?>

<div id="loginModal" class="modal show" tabindex="-1" role="dialog" aria-hidden="true">
 	<div class="modal-dialog">
		<?php
		if( ! isset( $on_hold_message ) )
		{
		?>
			<!--login modal-->
 			<?php 
				if( isset( $login_error_mesg ) )
				{
			?>
				    <div class="alert alert-danger">
				        <a href="#" class="close" data-dismiss="alert">&times;</a>
				        <strong>Erreur! </strong>Données de connexion invalides.
				    </div>
			<?php 
				}
			?>
 			<?php 
				if($this->input->get('logout') )
				{
			?>
					<div class="alert alert-success">
				        <a href="#" class="close" data-dismiss="alert">&times;</a>
				        Vous vous êtes déconnecté avec succès!
				    </div>
			<?php 
				}
			?>
		  	<div class="modal-content">
	      		<div class="modal-header">
		          	<h1 class="text-center">&nbsp;</h1>
		      	</div>
		      	<div class="modal-body">
		      		<?php 
		      			echo form_open( $login_url, array( 'class' => 'form col-md-12 center-block' ) );
		      		?>
		            	<div class="form-group">
		              		<input type="text" name="login_string" id="login_string" autocomplete="off" maxlength="255" class="form-control input-lg" placeholder="Nom d'utilisateur ou Email">
		            	</div>
		            	<div class="form-group">
		              		<input type="password" name="login_pass" id="login_pass" class="form-control input-lg" placeholder="Mot de passe" autocomplete="off" maxlength="<?php echo config_item('max_chars_for_password'); ?>" />
		            	</div>
		            	<?php
							if( config_item('allow_remember_me') )
							{
						?>
							<div class="checkbox">
								<label>
									<input type="checkbox" id="remember_me" name="remember_me" value="yes" />Se souvenir de moi
								</label>
							</div>
						<?php
							}
						?>
		            	<div class="form-group">
		              		<input type="submit" name="submit" value="Se connecter" id="submit_button" class="btn btn-primary btn-lg btn-block"/>
		            	</div>
		          	</form>
		      	</div>
		      	<div class="modal-footer">
		          	<div class="col-md-12">
		          		<!-- <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button> -->
				  	</div>	
		      	</div>
		  	</div>
		<?php
			}
			else // EXCESSIVE LOGIN ATTEMPTS ERROR MESSAGE
			{
		?>
				<div class="alert alert-danger">
					<h1>Erreur! </h1>
			        <p>
						Tentatives de connexion excessives:
					</p>
					<p>
						Vous avez dépassé le nombre maximum de connexions incorrectes
					<p>
					<p>
						Votre compte sera blocké pendant <?php echo ( (int) config_item('seconds_on_hold') / 60 ) . ' minutes.'?>
					</p>
				</div>
		<?php
			}
		?>
	</div>
</div>

<?php
/* End of file login_form.php */
/* Location: /views/examples/login_form.php */ 