<?php

class LoginRedirectGroup extends Plugin
{
	public function action_init()
	{
		$this->add_template( 'block.lrglogin', dirname( __FILE__ ) . '/block.lrglogin.php' );
	}

	/**
	 * Simple plugin configuration
	 * @return FormUI The configuration form
	 **/
	public function configure()
	{
		$form = new FormUI( 'loginredirectgroup' );
		$groups = array();
		foreach(UserGroups::get_all() as $group) {
			$groups[$group->id] = $group->name;
		}
		$form->append( new FormControlCheckboxes('group', 'lrg__group', _t("If you're any of these groups:"), $groups));
		$form->append( new FormControlText('url', 'lrg__url', _t('Redirect to this URL after login:')));
		$form->append( new FormControlSubmit('save', _t( 'Save' )));

		return $form;
	}

	public function filter_login_redirect_dest( $login_dest, User $user, $login_session )
	{
		$login_dest_start = $login_dest;
		if(isset($login_session) && $user->info->login_redirect != '') {
			$login_dest = $user->info->login_redirect;
		}
		if($login_dest == $login_dest_start) {
			if((isset($login_session) || $_POST['blocklogin']) && Options::get('lrg__url') != '') {
				$in_group = false;
				foreach((array)Options::get('lrg__group') as $group_id) {
					if($user->in_group($group_id)) {
						$in_group = true;
						break;
					}
				}
				if($in_group) {
					$login_dest = Options::get('lrg__url');
				}
			}
		}
		return $login_dest;
	}

	/**
	 * Add the configuration to the user page
	 * @param FormUI $form The user form
	 * @param User $user User object
	 */
	public function action_form_user( $form, $user )
	{
		$fieldset = $form->append( 'wrapper', 'login_redirect_fieldset', 'Login Redirect' );
		$fieldset->class = 'container settings';
		$fieldset->append( 'static', 'login_redirect_title', '<h2>Login Redirect</h2>' );
	
		$activate = $fieldset->append( 'text', 'login_redirect', 'null:null', _t('Redirect to this URL after login:') );
		$activate->class[] = 'item clear';
		$activate->value = $user->info->login_redirect;
		
		$form->move_before( $fieldset, $form->page_controls );
	}
	
	/**
	 * Save authentication fields
	 **/
	public function filter_adminhandler_post_user_fields( $fields )
	{
		$fields[] = 'login_redirect';
	
		return $fields;
	}

	public function filter_block_list($block_list)
	{
		$block_list['lrglogin'] = _t( 'Login');
		return $block_list;
	}

	public function action_block_content_lrglogin($block, $theme)
	{
		$block->loggedin = User::identify()->loggedin;
	}

}

?>