<?php
/**
 * RP_Agent_Templates Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_Agent_Templates' ) ) :

	class RP_Agent_Templates extends RP_Template {

		/**
		 *    Initialize class
		 */
		public function __construct() {
			parent::__construct();

			add_action( 'rp_after_single_property_summary', 'RP_Agent_Templates::box_agent_contact', 45 );
			add_action( 'rp_after_agent_contact', 'RP_Agent_Templates::box_contact_property', 5 );

			add_action( 'rp_single_agent_summary', 'RP_Agent_Templates::box_agent_description_single', 5 );
			add_action( 'rp_single_agent_summary', 'RP_Agent_Templates::box_agent_contact_single', 10 );

			add_action( 'rp_before_single_agent_summary', 'RP_Agent_Templates::box_agent_meta_single', 5 );

			add_action( 'rp_before_agent_meta_content', 'RP_Agent_Templates::agent_meta_content_title', 5 );
			add_action( 'rp_before_agent_meta_content', 'RP_Agent_Templates::box_agent_info_single', 10 );

			add_action( 'rp_before_agent_loop_properties', 'RP_Agent_Templates::agent_properties', 5 );

			add_action( 'rp_after_single_agent_summary', 'RP_Agent_Templates::box_agent_property_single', 5 );

		}

		/**
		 * Create global data agent
		 *
		 * @param $post
		 *
		 * @return bool|RP_Agents_Factory
		 */
		public function the_post( $post ) {

			unset( $GLOBALS[ 'agent' ] );

			if ( is_int( $post ) ) {
				$post = get_post( $post );
			}

			if ( empty( $post->post_type ) || $post->post_type != apply_filters( 'rp_agent_post_type', 'rp_agent' ) ) {
				return false;
			}

			$GLOBALS[ 'agent' ] = new RP_Agents_Factory( $post );

			return $GLOBALS[ 'agent' ];
		}

		public function pre_get_posts() {
			if ( RP_Agent::is_archive_agent() ) {
				add_filter( 'navigation_markup_template', '__return_empty_string' );
			}
		}

		public function template_single( $query ) {

			if ( ! $query->is_main_query() ) {
				return false;
			}

			global $post;

			if ( RP_Agent::is_single_agent() ) {

				if ( 'loop_start' === current_filter() ) {
					ob_start();
				} else {
					ob_end_clean();
				}

				$this->the_post( $post );

				do_action( 'rp_template_single_agent_before', $query );

				RP_Template::get_template( 'single-agent-content.php', '', '', RP_AGENT_TEMPLATES );

				do_action( 'rp_template_single_agent_after', $query );
			}
		}

		public function template_archive( $query ) {

			if ( ! $query->is_main_query() ) {
				return false;
			}

			if ( RP_Agent::is_archive_agent() ) {

				if ( 'loop_start' === current_filter() ) {
					ob_start();
				} else {
					ob_end_clean();
				}

				$agent_query = RP_Agent::query( $query );

				if ( $agent_query->have_posts() ) {

					rp_agent_loop_start();

					while ( $agent_query->have_posts() ) {

						$agent_query->the_post();

						RP_Template::get_template( 'content-agent.php', '', '', RP_AGENT_TEMPLATES );
					}

					rp_agent_loop_end();

				} else {

					RP_Template::get_template( 'loop/agent-found.php', '', '', RP_AGENT_TEMPLATES );
				}

				wp_reset_query();
			}
		}

		public static function box_agent_contact() {
			RP_Template::get_template( 'box-agent-contact.php', '', '', RP_AGENT_TEMPLATES );
		}

		public static function box_contact_property() {
			RP_Template::get_template( 'contact-property.php', '', '', RP_AGENT_TEMPLATES );
		}

		public static function box_agent_description_single() {
			global $agent;
			echo '<div class="agent-description">';
			echo apply_filters( 'rp_agent_description', $agent->agent_info( 'about' ) );
			echo '</div>';
		}

		public static function box_agent_contact_single() {
			ob_start();
			RP_Template::get_template( 'single-agent/agent-contact.php', '', '', RP_AGENT_TEMPLATES );
			echo ob_get_clean();
		}

		public static function box_agent_meta_single() {
			ob_start();
			RP_Template::get_template( 'single-agent/agent-meta.php', '', '', RP_AGENT_TEMPLATES );
			echo ob_get_clean();
		}

		public static function box_agent_info_single() {
			ob_start();
			RP_Template::get_template( 'single-agent/agent-info.php', '', '', RP_AGENT_TEMPLATES );
			echo ob_get_clean();
		}

		public static function box_agent_property_single() {
			ob_start();
			RP_Template::get_template( 'single-agent/property.php', '', '', RP_AGENT_TEMPLATES );
			echo ob_get_clean();
		}

		public static function agent_meta_content_title() {
			global $agent;
			echo apply_filters( 'rp_agent_meta_content_title', '<h1 class="agent-title">' . $agent->title() . '</h1>' );
		}

		public static function agent_properties() {
			global $agent;
			echo apply_filters( 'rp_agent_properties', "<h3 class='agent-properties'><span>{$agent->title()}</span> Properties</h3>" );
		}

	}

	new RP_Agent_Templates();

endif;