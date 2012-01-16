<?php if(!$content->loggedin): ?>
<?php Plugins::act( 'theme_loginform_before' ); ?>
<form method="post" action="<?php URL::out( 'auth', array( 'page' => 'login' ) ); ?>">
	<input type="hidden" name="blocklogin" value="1">
	<p>
		<label for="habari_username" class="incontent abovecontent"><?php _e('Name'); ?></label><input type="text" name="habari_username" id="habari_username"<?php if (isset( $habari_username )) { ?> value="<?php echo Utils::htmlspecialchars( $habari_username ); ?>"<?php } ?> placeholder="<?php _e('name'); ?>" class="styledformelement" style="width:auto;margin:0px 0px;">
	</p>
	<p>
		<label for="habari_password" class="incontent abovecontent"><?php _e('Password'); ?></label><input type="password" name="habari_password" id="habari_password" placeholder="<?php _e('password'); ?>" class="styledformelement" style="width:auto;margin:0px 0px;">
	</p>
	<?php Plugins::act( 'theme_loginform_controls' ); ?>
	<p>
		<input class="submit" type="submit" name="submit_button" value="<?php _e('Login'); ?>">
	</p>

</form>
<?php Plugins::act( 'theme_loginform_after' ); ?>
<?php endif; ?>