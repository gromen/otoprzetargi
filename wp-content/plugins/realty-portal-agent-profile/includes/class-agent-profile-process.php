<?php
/**
 * RP_Agent_Profile_Process Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'RP_Agent_Profile_Process' ) ) :

	class RP_Agent_Profile_Process {

		/**
		 *    Initialize class
		 */
		public function __construct() {
			add_action( 'nre_nav_menu_profile_before', 'RP_Agent_Profile_Process::add_menu_item', 10 );
		}

		/**
		 * Add button submit property
		 */
		public static function add_menu_item() {
			?>
			<li id="menu-item-submit-property" class="menu-item-submit-property">
				<a href="<?php echo RP_AddOn_Agent_Profile::get_url_agent_profile(); ?>"><?php echo esc_html__( 'Agent Profile', 'realty-portal-agent-profile' ); ?></a>
			</li>
			<?php
		}

	}

	new RP_Agent_Profile_Process();

endif;