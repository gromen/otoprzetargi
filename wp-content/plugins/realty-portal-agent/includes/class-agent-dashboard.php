<?php
/**
 * RP_Agent_Config_Dashboard_Setting Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_Agent_Config_Dashboard_Setting' ) ) :

	class RP_Agent_Config_Dashboard_Setting {

		/**
		 *    Initialize class
		 */
		public function __construct() {
			add_filter( 'RP_Tab_Setting/Config', 'RP_Agent_Config_Dashboard_Setting::setting_agent', 10 );
			add_action( 'RP_Tab_Setting_Content/Config_After', 'RP_Agent_Config_Dashboard_Setting::form_setting', 25 );
		}

		/**
		 * Show html setting agent
		 *
		 * @param $list_tab
		 *
		 * @return array
		 */
		public static function setting_agent( $list_tab ) {

			$list_tab[] = array(
				'name'     => esc_html__( 'Agents', 'realty-portal-agent' ),
				'id'       => 'tab-setting-agent',
				'position' => 10,
			);

			return $list_tab;
		}

		/**
		 * Show form setting
		 */
		public static function form_setting() {

			rp_render_form_setting( array(
				'title'   => esc_html__( 'Agent Setting', 'realty-portal-agent' ),
				'name'    => 'agent_setting',
				'id_form' => 'tab-setting-agent',
				'fields'  => apply_filters( 'rp_agent_form_setting', array(
					array(
						'title'  => esc_html__( 'Agent Archive Base (slug)', 'realty-portal-agent' ),
						'name'   => 'archive_slug',
						'type'   => 'text',
						'std'    => 'agent',
						'notice' => sprintf( __( 'This option will affect the URL structure on your site. If you made change on it and see an 404 Error, you will have to go to <a target="_blank" href="%s">Permalink Settings</a> and click "<strong>Save Changes</strong>" button for reseting WordPress link structure.', 'realty-portal-agent' ), esc_url( admin_url( '/options-permalink.php' ) ) ),
					),
					array(
						'title' => esc_html__( 'Agent Category Base (slug)', 'realty-portal-agent' ),
						'name'  => 'agent_category_slug',
						'type'  => 'text',
						'std'   => 'agent_category',
					),
					array(
						'name' => 'agent_linebreak',
						'type' => 'line',
					),
					array(
						'title' => esc_html__( 'Only Show Agent with Property', 'realty-portal-agent' ),
						'name'  => 'agent_must_has_property',
						'type'  => 'checkbox',
						'label' => esc_html__( 'If selected, only agent with at least one property can be show on Agent listing.', 'realty-portal-agent' ),
					),
					array(
						'title' => '',
						'name'  => 'line',
						'type'  => 'line',
					),
					array(
						'title' => esc_html__( 'Saved Search', 'realty-portal-agent' ),
						'name'  => 'page_saved_search',
						'type'  => 'pages',
					),
				) ),
			) );
		}

	}

	new RP_Agent_Config_Dashboard_Setting();

endif;