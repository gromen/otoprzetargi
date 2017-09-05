<?php
/**
 *
 * @link              https://nootheme.com
 * @package           RP_AddOn_Floor_Plan
 *
 * Plugin Name:       Realty Portal: Floor Plan
 * Plugin URI:        https://nootheme.com
 * Description:       An add-on to display detailed information of properties. Easy to add as many plans as you want. Work with any theme!
 * Version:           0.3.1
 * Author:            NooTeam
 * Author URI:        https://nootheme.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       realty-portal-floor-plan
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_AddOn_Floor_Plan' ) ) :

	class RP_AddOn_Floor_Plan {

		/**
		 * Constructor
		 */
		public function __construct() {

			if ( defined( 'RP_RUNNING' ) ) {

				add_action( 'wp_enqueue_scripts', 'RP_AddOn_Floor_Plan::frontend_script' );

				add_action( 'init', array(
					&$this,
					'load_plugin_textdomain',
				) );

				add_filter( 'rp_metabox_property', array(
					&$this,
					'add_metabox',
				), 3, 10 );

				add_action( 'rp_render_metabox_fields', array(
					&$this,
					'add_field_floor_plan',
				), 6, 10 );

				self::setup_constants();

				self::includes();
			} else {
				if ( is_multisite() ) {
					add_action( 'network_admin_notices', array(
						$this,
						'notice',
					) );
				} else {
					add_action( 'admin_notices', array(
						$this,
						'notice',
					) );
				}
			}
		}

		/**
		 * Initialize the plugin when Realty_Portal is loaded
		 *
		 * @param  object $rp_init
		 *
		 * @uses     do_action_ref_array()
		 * @return object
		 */
		public static function init( $rp_init ) {

			if ( ! isset( $rp_init->floor_plan ) ) {
				$rp_init->floor_plan = new self();
			}
			do_action_ref_array( 'rp_init_floor_plan', array( &$rp_init ) );

			return $rp_init->floor_plan;
		}

		public static function notice() {
			echo '<div class="error"><p>' . __( 'The <strong>Realty Portal: Floor Plan</strong> only works after <strong>Realty Portal: Realty Portal</strong> is activated, please activate it', 'realty-portal-floor-plan' ) . '</p></div>';
		}

		/**
		 *  Set up localization for this plugin
		 *  loading the text domain.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'realty-portal-floor-plan', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Load script frontend
		 *
		 */
		public static function frontend_script() {

			wp_enqueue_script( 'realty-portal-floor-plan', RP_ADDON_FLOOR_PLAN_ASSETS . '/js/realty-portal-floor-plan.js', array( 'rp-core', 'rp-property' ), '0.1', true );

			wp_localize_script( 'realty-portal-floor-plan', 'RP_Floor_Plan', apply_filters( 'rp_floor_plan_frontend_scripts_localize', array(
				'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ),
				'security' => wp_create_nonce( 'realty-portal-floor-plan' ),
			) ) );

		}

		/**
		 * Callback for register_activation_hook
		 * to create some default options to be
		 * used by this plugin.
		 */
		public static function activation() {
		}

		/**
		 * Setup plugin constants
		 *
		 * @access   private
		 */
		private static function setup_constants() {

			// Plugin File
			if ( ! defined( 'RP_ADDON_FLOOR_PLAN_FILE' ) ) {
				define( 'RP_ADDON_FLOOR_PLAN_FILE', __FILE__ );
			}

			// Plugin Folder URL
			if ( ! defined( 'RP_ADDON_FLOOR_PLAN_URL' ) ) {
				define( 'RP_ADDON_FLOOR_PLAN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Folder Path
			if ( ! defined( 'RP_ADDON_FLOOR_PLAN_PATH' ) ) {
				define( 'RP_ADDON_FLOOR_PLAN_PATH', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Sub-Directory Paths
			if ( ! defined( 'RP_ADDON_FLOOR_PLAN_PATH_INCLUDES' ) ) {
				define( 'RP_ADDON_FLOOR_PLAN_PATH_INCLUDES', RP_ADDON_FLOOR_PLAN_PATH . 'includes/' );
			}

			// Plugin Templates
			if ( ! defined( 'RP_ADDON_FLOOR_PLAN_TEMPLATES' ) ) {
				define( 'RP_ADDON_FLOOR_PLAN_TEMPLATES', RP_ADDON_FLOOR_PLAN_PATH . 'templates' );
			}

			if ( ! defined( 'RP_ADDON_FLOOR_PLAN_ASSETS' ) ) {
				define( 'RP_ADDON_FLOOR_PLAN_ASSETS', RP_ADDON_FLOOR_PLAN_URL . 'assets' );
			}
		}

		/**
		 * Include required files
		 *
		 * @access private
		 * @return void
		 */
		private static function includes() {
			// Include functions
			include( dirname( __FILE__ ) . '/realty-portal-floor-plan-functions.php' );
			include( dirname( __FILE__ ) . '/includes/class-floor-plan-process.php' );

			// Include new fields
			include( dirname( __FILE__ ) . '/fields/floor_plans/floor_plans.php' );
		}

		/**
		 * Create metabox Floor Plan
		 *
		 * @param $helper
		 * @param $prefix
		 *
		 * @return mixed
		 */
		public static function add_metabox( $helper, $prefix ) {
			/**
			 * Create box: Floor Plan
			 */
			$meta_box = array(
				'id'     => "{$prefix}_floor_plan",
				'title'  => esc_html__( 'Floor Plan', 'realty-portal-floor-plan' ),
				'fields' => array(
					array(
						'label' => '',
						'id'    => 'floor_plans',
						'type'  => 'floor_plans',
					),
				),
			);

			$helper->add_meta_box( $meta_box );

			return $helper;
		}

		public static function add_field_floor_plan( $post, $id, $type, $meta, $std, $field ) {
			switch ( $type ) {
				case 'floor_plans':
					$floor_plan_default = array(
						'plan_title'       => '',
						'plan_bedrooms'    => '',
						'plan_bathrooms'   => '',
						'plan_price'       => '',
						'plan_size'        => '',
						'plan_description' => '',
						'floor_plan'       => '',
					);

					$floor_plans              = get_post_meta( $post->ID, 'floor_plans', true );
					$floor_plans              = ! empty( $floor_plans ) ? array_merge( $floor_plans ) : array_merge( array( $floor_plan_default ), array( $floor_plan_default ) );
					if ( is_array( $floor_plans ) ) :
						echo '<div id="rp-item-floor_plan_wrap-wrap">';
						foreach ( $floor_plans as $index => $floor_plan ) :
							$plan_title = ( array_key_exists( 'plan_title', $floor_plan ) && $index > 0 ) ? $floor_plan[ 'plan_title' ] : '';
							$plan_bedrooms    = ( array_key_exists( 'plan_bedrooms', $floor_plan ) && $index > 0 ) ? $floor_plan[ 'plan_bedrooms' ] : '';
							$plan_bathrooms   = ( array_key_exists( 'plan_bathrooms', $floor_plan ) && $index > 0 ) ? $floor_plan[ 'plan_bathrooms' ] : '';
							$plan_price       = ( array_key_exists( 'plan_price', $floor_plan ) && $index > 0 ) ? $floor_plan[ 'plan_price' ] : '';
							$plan_size        = ( array_key_exists( 'plan_size', $floor_plan ) && $index > 0 ) ? $floor_plan[ 'plan_size' ] : '';
							$plan_description = ( array_key_exists( 'plan_description', $floor_plan ) && $index > 0 ) ? $floor_plan[ 'plan_description' ] : '';
							$plan_image       = ( array_key_exists( 'plan_image', $floor_plan ) && $index > 0 ) ? $floor_plan[ 'plan_image' ] : '';

							?>
							<div <?php echo ( $index === 0 ) ? 'id="clone_element" style="display: none;" ' : '' ?> class="rp-floor-plans-wrap rp-md-12 <?php echo ( $index > 0 ) ? ' floor-item' : '' ?>">
								<i class="remove-floor-plan rp-icon-remove <?php echo ( $index != 1 ) ? 'show-remove' : '' ?> "></i>
								<div class="rp-form-group">
									<label><strong><?php echo esc_html__( 'Plan Title', 'realty-portal-floor-plan' ); ?></strong></label>
									<div class="rp-control">
										<input type="text" name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_title]" value="<?php echo esc_attr( $plan_title ) ?>" />
									</div>
								</div>


								<div class="rp-form-group">
									<label><strong><?php echo esc_html__( 'Plan Bedrooms', 'realty-portal-floor-plan' ); ?></strong></label>
									<div class="rp-control">
										<input type="text" name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_bedrooms]" value="<?php echo esc_attr( $plan_bedrooms ) ?>" />
									</div>
								</div>

								<div class="rp-form-group">
									<label><strong><?php echo esc_html__( 'Plan Bathrooms', 'realty-portal-floor-plan' ); ?></strong></label>
									<div class="rp-control">
										<input type="text" name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_bathrooms]" value="<?php echo esc_attr( $plan_bathrooms ) ?>" />
									</div>
								</div>

								<div class="rp-form-group">
									<label><strong><?php echo esc_html__( 'Plan Price', 'realty-portal-floor-plan' ); ?></strong></label>
									<div class="rp-control">
										<input type="text" name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_price]" value="<?php echo esc_attr( $plan_price ) ?>" />
									</div>
								</div>

								<div class="rp-form-group">
									<label><strong><?php echo esc_html__( 'Plan Size', 'realty-portal-floor-plan' ); ?></strong></label>
									<div class="rp-control">
										<input type="text" name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_size]" value="<?php echo esc_attr( $plan_size ) ?>" />
									</div>
								</div>

								<div class="rp-form-group">
									<label><strong><?php echo esc_html__( 'Plan Description', 'realty-portal-floor-plan' ); ?></strong></label>
									<div class="rp-control">
										<textarea name="floor_plans[<?php echo esc_attr( $index ) ?>][plan_description]"><?php echo esc_attr( $plan_description ) ?></textarea>
									</div>
								</div>
								<div class="rp-form-group">
									<label><strong><?php echo esc_html__( 'Plan Images', 'realty-portal-floor-plan' ); ?></strong></label>
									<div class="rp-control">
										<?php
										$output = '';
										if ( ! empty( $plan_image ) && is_array( $plan_image ) ) {
											foreach ( $plan_image as $image_id ) {
												$output .= wp_get_attachment_image( $image_id, 'thumbnail' );
												$output .= '<input type="hidden" name="floor_plans[' . esc_attr( $index ) . '][plan_image][]" value="' . esc_attr( $image_id ) . '" />';
											}
										} else {
											$plan_image = array();
										}

										$btn_text = ! empty( $plan_image ) ? esc_html__( 'Edit Gallery', 'realty-portal-floor-plan' ) : esc_html__( 'Add Images', 'realty-portal-floor-plan' );
										echo '<input type="button" class="button button-primary ' . $id . '_upload" name="' . $id . '_button_upload" value="' . $btn_text . '" />';
										echo '<input type="button" class="button ' . $id . '_clear" name="' . $id . '_button_clear" value="' . esc_html__( 'Clear Gallery', 'realty-portal-floor-plan' ) . '" />';
										echo '<div class="rp-thumb-wrapper" data-index="' . $index . '">' . $output . '</div>';
										?>
									</div>
								</div>
								<?php
								/**
								 * Get info data floor plan
								 */
								$gallery_state = empty ( $plan_image ) ? 'gallery-library' : 'gallery-edit';
								$plan_image    = empty ( $plan_image ) ? false : true;
								?>
								<span class="floor_plan_data" data-gallery-state="<?php echo esc_attr( $gallery_state ); ?>" data-plan-image="<?php echo esc_attr( $plan_image ); ?>" data-plan-id="<?php echo $id; ?>" data-index="[<?php echo esc_attr( $index ); ?>]"></span>
							</div>
							<?php
						endforeach;
						?>
						<div class="rp-clone-floor-plan">
							<div class="content-clone"></div>
							<button class="rp-button add-floor-plan" data-total="<?php echo count( $floor_plans ) ?>" data-id="<?php echo $id; ?>">
								<?php echo esc_html__( 'Add More', 'realty-portal-floor-plan' ) ?>
							</button>
						</div>
						</div>
						<?php
					endif;
					break;
			}
		}

	}

	// Run activation hook
	register_activation_hook( __FILE__, array(
		'RP_AddOn_Floor_Plan',
		'activation',
	) );

	// Initialize plugin on rp_init
	add_action( 'rp_init', array(
		'RP_AddOn_Floor_Plan',
		'init',
	) );

endif;