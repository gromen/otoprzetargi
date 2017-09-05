<?php
/**
 * RP_Floor_Plan_Process Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'RP_Floor_Plan_Process' ) ) :

	class RP_Floor_Plan_Process {

		/**
		 *    Initialize class
		 */
		public function __construct() {
			add_action( 'wp_ajax_rp_load_floor_plan', 'RP_Floor_Plan_Process::floor_plan' );
			add_action( 'wp_ajax_nopriv_rp_load_floor_plan', 'RP_Floor_Plan_Process::floor_plan' );
			add_action( 'rp_after_single_property_summary', 'RP_Floor_Plan_Process::rp_box_floor_plan' , 30 );
		}

		public static function rp_box_floor_plan() {
			RP_Template::get_template( 'floor-plan.php', '', '', RP_ADDON_FLOOR_PLAN_TEMPLATES );
		}

		public static function floor_plan() {

			/**
			 * Check security
			 */
			check_ajax_referer( 'rp-property', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal-floor-plan' ) );

			/**
			 * Validate $_POST
			 */
			$_POST = wp_kses_post_deep( $_POST );

			/**
			 * Process
			 */
			$index_floor_plan = rp_validate_data( $_POST['index'], 'int' );
			$property_id      = rp_validate_data( $_POST['property_id'], 'int' );

			if ( !empty( $index_floor_plan ) ) {

				rp_list_floor_plan( $property_id, $index_floor_plan-1 );
				die;

			} else {

				$response['status'] = 'error';
				$response['msg']    = esc_html__( 'Not empty index floor plan, please check again!', 'realty-portal-floor-plan' );

			}

			wp_send_json( $response );

		}

	}

	new RP_Floor_Plan_Process();

endif;