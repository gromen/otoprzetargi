<?php
/**
 * RP_Compare_Process Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( 'RP_Compare_Process' ) ) :

	class RP_Compare_Process {

		/**
		 *    Initialize class
		 */
		public function __construct() {
			add_action( 'rp_property_list_more_action', 'RP_Compare_Process::add_button_compare', 10 );

			if ( ! isset( $_SESSION ) ) {
				session_start();
			}

			add_action( 'wp_footer', array(
				__CLASS__,
				'compare_footer',
			) );

			add_action( 'init', array(
				__CLASS__,
				'rp_open_session',
			), 1 );
			add_action( 'wp_logout', array(
				__CLASS__,
				'rp_close_session',
			) );

			add_action( 'rp_show_compare', array(
				__CLASS__,
				'output_compare_basket',
			), 5 );

			add_action( 'wp_ajax_rp_compare_add_property', array(
				__CLASS__,
				'rp_compare_add_property',
			) );
			add_action( 'wp_ajax_nopriv_rp_compare_add_property', array(
				__CLASS__,
				'rp_compare_add_property',
			) );
			add_action( 'wp_ajax_rp_compare_update_basket', array(
				__CLASS__,
				'update_compare_basket',
			) );
			add_action( 'wp_ajax_nopriv_rp_compare_update_basket', array(
				__CLASS__,
				'update_compare_basket',
			) );
		}

		/**
		 * Get url favorites page
		 *
		 * @return string
		 */
		public static function get_url_compare() {
			$url = Realty_Portal::get_setting( 'agent_setting', 'page_compare_properties', '' );

			return apply_filters( 'get_url_compare_properties', get_permalink( $url ) );
		}

		public static function add_button_compare( $property ) {
			?>
			<span class="compare">
			<i class="rp-event compare rp-icon-exchange" aria-hidden="true"
			   data-id="<?php echo $property->ID; ?>" data-user="<?php echo RP_Agent::is_user(); ?>"
			   data-process="compare"
			   data-thumbnail="<?php echo $property->thumbnail( 'rp-agent-avatar', false ); ?>"></i>
			</span>
			<?php
		}

		public static function compare_footer() {
			?>
			<div id="compare-controller" class="compare-panel">
				<div class="compare-panel-header">
					<h4 class="title">
						<?php esc_html_e( 'Compare Listings', 'realty-portal-compare' ); ?>
					</h4>
				</div>
				<?php do_action( 'rp_show_compare', $args = '' ); ?>
			</div>
			<?php
		}

		public static function rp_compare_add_property() {
			$property_id    = (int) $_POST[ 'property_id' ];
			$max_items      = 4;
			$current_number = ( isset( $_SESSION[ 'rp_compare_properties' ] ) && is_array( $_SESSION[ 'rp_compare_properties' ] ) ) ? count( $_SESSION[ 'rp_compare_properties' ] ) : 0;

			if ( is_array( $_SESSION[ 'rp_compare_properties' ] ) && in_array( $property_id, $_SESSION[ 'rp_compare_properties' ] ) ) {
				unset( $_SESSION[ 'rp_compare_properties' ][ array_search( $property_id, $_SESSION[ 'rp_compare_properties' ] ) ] );
			} elseif ( $current_number < $max_items ) {

				$_SESSION[ 'rp_compare_properties' ][] = $property_id;
			}

			if ( ( $key = array_search( 0, $_SESSION[ 'rp_compare_properties' ] ) ) !== false ) {
				unset( $_SESSION[ 'rp_compare_properties' ][ $key ] );
			}
			$_SESSION[ 'rp_compare_properties' ] = array_unique( $_SESSION[ 'rp_compare_properties' ] );

			die();
		}

		public static function rp_open_session() {

			if ( ! session_id() ) {
				session_start();
				if ( ! isset( $_SESSION[ 'rp_compare_starttime' ] ) ) {
					$_SESSION[ 'rp_compare_starttime' ] = time();
				}
				if ( ! isset( $_SESSION[ 'rp_compare_properties' ] ) ) {
					$_SESSION[ 'rp_compare_properties' ] = array();
				}
			}
			if ( isset( $_SESSION[ 'rp_compare_starttime' ] ) ) {
				//if session has been alive for more than 24 hours, empty compare basket
				if ( (int) $_SESSION[ 'rp_compare_starttime' ] > time() + 86400 ) {
					unset( $_SESSION[ 'rp_compare_properties' ] );
				}
			}
		}

		public static function output_compare_basket() {
			$max_set = 4;
			$current = 0;
			if ( isset( $_SESSION[ 'rp_compare_properties' ] ) ) {
				$current = count( $_SESSION[ 'rp_compare_properties' ] );
			}
			?>
			<div id="compare-properties-basket">
				<?php if ( isset( $_SESSION[ 'rp_compare_properties' ] ) && count( $_SESSION[ 'rp_compare_properties' ] ) ): ?>
					<div class="compare-panel-body">
						<div class="compare-thumb-main row">

							<?php foreach ( $_SESSION[ 'rp_compare_properties' ] as $key ) : ?>
								<?php if ( $key != 0 ) : ?>
									<figure class="compare-thumb compare-property" property-id="<?php echo $key; ?>"">
									<?php echo get_the_post_thumbnail( (double) $key, 'thumbnail', array( 'class' => 'compare-property-img' ) ); ?>
									<button class="btn-trash compare-property-remove">
										<i class="rp-icon-trash"></i>
									</button>
									</figure>
								<?php endif; ?>
							<?php endforeach; ?>

							<?php if ( $current < $max_set ) : ?>
								<?php for ( $i = $current; $i < $max_set; $i ++ ) : ?>
									<figure class="compare-thumb">
										<div class="thumb-inner-empty"></div>
									</figure>
								<?php endfor; ?>
							<?php endif; ?>

						</div>
						<button type="button" class="btn btn-primary btn-block compare-properties-button basket">
							<?php esc_html_e( 'Compare', 'realty-portal-compare' ); ?>
						</button>
					</div>
					<button class="btn btn-primary panel-btn compare-open"><i class="rp-icon-angle-left"></i></button>
				<?php endif; ?>
			</div>
			<?php
		}

		public static function update_compare_basket() {
			self::output_compare_basket();
			die();
		}

		public static function rp_close_session() {
			if ( isset( $_SESSION ) ) {
				session_destroy();
			}
		}

	}

	new RP_Compare_Process();

endif;