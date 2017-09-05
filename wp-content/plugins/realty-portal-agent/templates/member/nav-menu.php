<?php
/**
 * Nav Menu
 *
 * This template can be overridden by copying it to yourtheme/realty-portal/nav-menu.php.
 *
 * HOWEVER, on occasion NooTheme will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author        NooTheme
 * @version       0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php if ( is_user_logged_in() ) : ?>
	<li id="menu-item-profile" class="menu-item-profile menu-item-has-children">
		<a id="thumb-info" href="<?php echo RP_AddOn_Agent_Dashboard::get_url_agent_dashboard(); ?>" class="sf-with-ul">
			<span class="profile-name"><?php echo RP_Agent::get_name() ?></span>
			<span class="profile-avatar">
				<img class="rp-avatar" src="<?php echo RP_Agent::get_avatar() ?>" alt="<?php echo RP_Agent::get_name() ?>" />
			</span>
		</a>
		<ul class="sub-menu">
			<?php do_action('nre_nav_menu_profile_before'); ?>
			<li id="menu-item-logout" class="menu-item-logout">
				<a href="<?php echo wp_logout_url( apply_filters( 'nre_logout_redirect', home_url() ) ); ?>"><?php echo esc_html__( 'Logout', 'realty-portal-agent' ); ?></a>
			</li>
			<?php do_action('nre_nav_menu_profile_after'); ?>
		</ul>
	</li>
<?php else : ?>
	<li id="menu-item-login" class="menu-item-login">
		<a href="<?php echo RP_AddOn_Agent::get_url_login(); ?>" title="<?php echo esc_html__( 'Login', 'realty-portal-agent' ); ?>">
			<?php echo esc_html__( 'Login', 'realty-portal-agent' ); ?>
		</a>
	</li>

	<li id="menu-item-register" class="menu-item-register">
		<a href="<?php echo RP_AddOn_Agent::get_url_register(); ?>" title="<?php echo esc_html__( 'Register', 'realty-portal-agent' ); ?>">
			<?php echo esc_html__( 'Register', 'realty-portal-agent' ); ?>
		</a>
	</li>
<?php endif; ?>