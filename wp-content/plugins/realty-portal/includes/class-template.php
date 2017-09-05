<?php
/**
 * class-rp-template.php
 *
 * @author  : NooTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_Template' ) ) :

	class RP_Template {

		/**
		 * A reference to an instance of this class.
		 */
		private static $instance;

		/**
		 * Returns an instance of this class.
		 */
		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new RP_Template();
			}

			return self::$instance;
		}

		/**
		 * Initializes the plugin by setting filters and administration functions.
		 */
		protected function __construct() {
			add_action( 'loop_start', array(
				$this,
				'template_single',
			) );
			add_action( 'loop_end', array(
				$this,
				'template_single',
			) );

			add_action( 'loop_start', array(
				$this,
				'template_archive',
			) );

			add_action( 'loop_end', array(
				$this,
				'template_archive',
			) );

			add_action( 'the_post', array(
				$this,
				'the_post',
			) );

			/**
			 * Register page template
			 */
			add_filter( 'theme_page_templates', array(
				__CLASS__,
				'register_templates',
			) );

			/**
			 * Add a filter to the template include to determine if the page has our
			 */
			add_filter( 'template_include', array(
				__CLASS__,
				'views_templates',
			) );

			add_action( 'pre_get_posts', array(
				$this,
				'pre_get_posts',
			) );
		}

		public function pre_get_posts() {
			if ( RP_Property::is_property() ) {
				add_filter( 'navigation_markup_template', '__return_empty_string' );
			}
		}

		public static function list_template() {
			return apply_filters( 'rp_add_page_template', array() );
		}

		public static function register_templates( $post_templates ) {

			return array_merge( $post_templates, self::list_template() );
		}

		/**
		 * Checks if the template is assigned to the page
		 */
		public static function views_templates( $template ) {

			global $post;

			/**
			 * Check is post
			 */
			if ( empty( $post ) || empty( $post->ID ) ) {

				return $template;
			}

			/**
			 * Check is template
			 */
			$template_name = get_post_meta( $post->ID, '_wp_page_template', true );
			$list_template = self::list_template();

			if ( empty( $template_name ) || ! isset( $list_template[ $template_name ] ) || empty( $list_template[ $template_name ] ) ) {
				return $template;
			}

			return self::get_template( $template_name );
		}

		/**
		 * locate_template()
		 *
		 * Locate a template and return the path
		 * for inclusion or load if desired.
		 *
		 * This is the load order:
		 *
		 *  /wp-content/themes/  theme (child) / $template_name
		 *
		 *    /wp-content/themes/  theme (child) / RP_DOMAIN (e.g. realty-portal) / $template_name
		 *
		 *  /wp-content/themes/  theme (parent) / $template_name
		 *
		 *    /wp-content/themes/  theme (parent) / RP_DOMAIN (e.g. realty-portal) / $template_name
		 *
		 *  $template_path (custom path from addon for example) / $template_name
		 *
		 *  /wp-content/plugins/  RP_DOMAIN (e.g. realty-portal) / templates / $template_name
		 *
		 * @param string|array $template_names Template name (incl. file extension like .php)
		 * @param string       $template_path  Custom template path for plugins and addons (default: '')
		 * @param bool         $load           Call load_template() if true or return template path if false
		 *
		 * @uses  trailingslashit()
		 * @uses  get_stylesheet_directory()
		 * @uses  get_template_directory()
		 * @uses  REALTY_PORTAL . '/templates/'
		 * @return string $located Absolute path to template file (if $load is false)
		 */
		public static function locate_template( $template_names, $args = array(), $template_path = '', $load = false, $require_once = false ) {
			global $post, $wp_query, $wpdb;

			if ( $args && is_array( $args ) ) {
				extract( $args );
			}

			// No file found yet
			$located = false;

			// Try to find a template file
			foreach ( (array) $template_names as $template_name ) {

				// Continue if template is empty
				if ( empty( $template_name ) ) {
					continue;
				}

				// Trim off any slashes from the template name
				$template_name = ltrim( $template_name, '/' );

				// Check child theme
				if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $template_name ) ) {
					$located = trailingslashit( get_stylesheet_directory() ) . $template_name;
					break;
					// Check extra folder in child theme
				} elseif ( file_exists( trailingslashit( get_stylesheet_directory() . '/' . RP_DOMAIN ) . $template_name ) ) {
					$located = trailingslashit( get_stylesheet_directory() . '/' . RP_DOMAIN ) . $template_name;
					break;
					// Check parent theme
				} elseif ( file_exists( trailingslashit( get_template_directory() ) . $template_name ) ) {
					$located = trailingslashit( get_template_directory() ) . $template_name;
					break;
					// Check extra folder parent theme
				} elseif ( file_exists( trailingslashit( get_template_directory() . '/' . RP_DOMAIN ) . $template_name ) ) {
					$located = trailingslashit( get_template_directory() . '/' . RP_DOMAIN ) . $template_name;
					break;
					// Check custom path templates (e.g. from addons)
				} elseif ( file_exists( trailingslashit( $template_path ) . $template_name ) ) {
					$located = trailingslashit( $template_path ) . $template_name;
					break;
					// Check plugin templates
				} elseif ( file_exists( rp_get_plugin_path( "templates/{$template_name}" ) ) ) {
					$located = rp_get_plugin_path( "templates/{$template_name}" );
					break;
				}
			}

			$located = apply_filters( 'rp_locate_template', $located, $template_names, $template_path, $load, $require_once );

			// Load found template if required

			if ( ( true == $load ) && ! empty( $located ) ) {

				if ( $require_once ) {
					require_once $located;
				} else {
					require $located;
				}
			}

			// Or return template file path
			return $located;
		}

		/**
		 * get_template_part()
		 *
		 * Load specific template part.
		 *
		 * @param string $slug          The slug name for the generic template
		 * @param string $name          The name of the specialized template
		 * @param string $template_path Custom template path for plugins and addons (default: '')
		 * @param bool   $load          Call load_template() if true or return template path if false
		 *
		 * @uses  self::locate_template()
		 * @return string $located Absolute path to template file (if $load is false)
		 *
		 */
		public static function get_template_part( $slug, $name = null, $args = array(), $template_path = '', $load = true, $require_once = false ) {

			// Execute code for this part
			do_action( 'rp_get_template_part_' . $slug, $slug, $name, $args, $template_path, $load, $require_once );

			// Setup possible parts
			$templates = array();
			if ( isset( $name ) ) {
				$templates[] = $slug . '-' . $name . '.php';
			}
			$templates[] = $slug . '.php';

			// Allow template parts to be filtered
			$templates = apply_filters( 'rp_get_template_part', $templates, $slug, $name, $args, $template_path, $load, $require_once );

			// Return the part that is found
			return self::locate_template( $templates, $args, $template_path, $load, $require_once );
		}

		public static function get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
			if ( ! empty( $args ) && is_array( $args ) ) {
				extract( $args );
			}

			$located = self::locate_template( $template_name, $template_path, $default_path );

			if ( ! file_exists( $located ) ) {
				_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_name ), '2.1' );

				return;
			}

			// Allow 3rd party plugin filter template file from their plugin.
			$located = apply_filters( 'rp_get_template', $located, $template_name, $args, $template_path, $default_path );

			do_action( 'rp_get_template_before', $template_name, $template_path, $located, $args );

			include( $located );

			do_action( 'rp_get_template_after', $template_name, $template_path, $located, $args );
		}

		public function the_post( $post ) {
			unset( $GLOBALS[ 'property' ] );

			if ( is_int( $post ) ) {
				$post = get_post( $post );
			}

			if ( empty( $post->post_type ) || $post->post_type != apply_filters( 'rp_property_post_type', 'rp_property' ) ) {
				return false;
			}

			$GLOBALS[ 'property' ] = rp_get_property( $post );

			return $GLOBALS[ 'property' ];
		}

		public function template_single( $query ) {

			if ( ! $query->is_main_query() ) {
				return false;
			}

			global $post;

			if ( RP_Property::is_single_property() ) {

				if ( 'loop_start' === current_filter() ) {
					ob_start();
				} else {
					ob_end_clean();
				}

				$this->the_post( $post );

				do_action( 'rp_template_single_property_before', $query );

				self::get_template_part( 'property/single-property', 'content' );

				do_action( 'rp_template_single_property_after', $query );
			}
		}

		public function template_archive( $query ) {

			if ( ! $query->is_main_query() ) {
				return false;
			}

			if ( RP_Property::is_property() ) {

				if ( 'loop_start' === current_filter() ) {
					ob_start();
				} else {
					ob_end_clean();
				}

				$property_query = RP_Property::query( $query );

				if ( $property_query->have_posts() ) {

					rp_property_loop_start();

					while ( $property_query->have_posts() ) {

						$property_query->the_post();

						self::get_template( 'property/content-property.php' );
					}

					rp_property_loop_end();

					self::get_template( 'loop/pagination.php', array(
						'query' => $property_query,
					) );
				} else {

					self::get_template( 'loop/property-found.php' );
				}

				wp_reset_query();
			}
		}

	}

	add_action( 'plugins_loaded', array(
		'RP_Template',
		'get_instance',
	) );

endif;