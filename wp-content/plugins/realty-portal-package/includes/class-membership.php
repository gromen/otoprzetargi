<?php
/**
 * Create class RP_MemberShip
 * This class process data for membership
 */
if ( ! class_exists( 'RP_MemberShip' ) ) :

	class RP_MemberShip {

		/**
		 * A reference to an instance of this class.
		 */
		private static $instance;
		private        $suffix;
		private        $suffix_path;

		/**
		 * Returns an instance of this class.
		 */
		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new RP_MemberShip();
			}

			return self::$instance;
		}

		/**
		 * Initializes the plugin by setting filters and administration functions.
		 */
		private function __construct() {

			$this->suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			$this->suffix_path = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : 'min/';

			/**
			 * Loader action/filter
			 */
			add_action( 'init', array(
				&$this,
				'register_membership',
			) );

			add_action( 'add_meta_boxes', array(
				&$this,
				'register_metabox',
			) );

			add_filter( 'enter_title_here', array(
				&$this,
				'custom_enter_title',
			) );
			add_action( 'add_meta_boxes', array(
				&$this,
				'remove_meta_boxes',
			), 20 );

			add_action( 'save_post', 'RP_MemberShip::rp_create_agent' );

			add_action( 'save_post', 'RP_MemberShip::rp_update_post_to_agent' );
		}

		/**
		 * Get membership type
		 */
		public static function get_membership_type() {
			return Realty_Portal::get_setting( 'agent_setting', 'membership_type', 'free' );
		}

		/**
		 * Check is membership
		 */
		public static function is_membership() {
			return self::get_membership_type() == 'membership';
		}

		/**
		 * Check is submission
		 */
		public static function is_submission() {
			return self::get_membership_type() == 'submission';
		}

		/**
		 * Custom title package
		 */
		public function custom_enter_title( $input ) {
			global $post_type;

			if ( apply_filters( 'rp_membership_post_type', 'rp_membership' ) == $post_type ) {
				return esc_html__( 'Package Title', 'realty-portal-package' );
			}

			return $input;
		}

		/**
		 * Remove metabox
		 */
		public function remove_meta_boxes() {
			remove_meta_box( 'slugdiv', apply_filters( 'rp_membership_post_type', 'rp_membership' ), 'normal' );
			remove_meta_box( 'mymetabox_revslider_0', apply_filters( 'rp_membership_post_type', 'rp_membership' ), 'normal' );
		}

		/**
		 * Create post type rp_membership
		 */
		public function register_membership() {

			/**
			 * Check post type exits
			 */
			if ( post_type_exists( apply_filters( 'rp_membership_post_type', 'rp_membership' ) ) ) {
				return;
			}

			/**
			 * Register post type
			 */
			register_post_type( apply_filters( 'rp_membership_post_type', 'rp_membership' ), array(
				'labels'             => array(
					'name'               => esc_html__( 'Packages', 'realty-portal-package' ),
					'singular_name'      => esc_html__( 'Package', 'realty-portal-package' ),
					'add_new'            => esc_html__( 'Add New', 'realty-portal-package' ),
					'add_new_item'       => esc_html__( 'Add Packages', 'realty-portal-package' ),
					'edit'               => esc_html__( 'Edit', 'realty-portal-package' ),
					'edit_item'          => esc_html__( 'Edit Packages', 'realty-portal-package' ),
					'new_item'           => esc_html__( 'New Packages', 'realty-portal-package' ),
					'view'               => esc_html__( 'View', 'realty-portal-package' ),
					'view_item'          => esc_html__( 'View Packages', 'realty-portal-package' ),
					'search_items'       => esc_html__( 'Search Packages', 'realty-portal-package' ),
					'not_found'          => esc_html__( 'No packages found', 'realty-portal-package' ),
					'not_found_in_trash' => esc_html__( 'No packages found in Trash', 'realty-portal-package' ),
					'parent'             => esc_html__( 'Parent Packages', 'realty-portal-package' ),
				),
				'public'             => true,
				'has_archive'        => false,
				'rewrite'            => array(
					'slug'       => apply_filters( 'rp_membership_post_type', 'rp_membership' ),
					'with_front' => true,
				),
				'menu_icon'          => RP_ADDON_PACKAGE_URL . 'assets/images/package.png',
				'menu_position'      => 52,
				'supports'           => array( 'title' ),
				'can_export'         => true,
				'publicly_queryable' => false,
				'hierarchical'       => false,
			) );
		}

		/**
		 * Create metabox
		 */
		public function register_metabox() {

			/**
			 * VAR
			 */
			$prefix = apply_filters( 'rp_membership_post_type', 'rp_membership' );

			$helper = new RP_Meta_Boxes( $prefix, array(
				'page' => apply_filters( 'rp_membership_post_type', 'rp_membership' ),
			) );

			/**
			 * Create box: Detail Infomation
			 */
			$meta_box = array(
				'id'     => "{$prefix}_package_details",
				'title'  => esc_html__( 'Package Details', 'realty-portal-package' ),
				'fields' => array(
					array(
						'id'       => "{$prefix}_interval",
						'label'    => esc_html__( 'Package Interval', 'realty-portal-package' ),
						'desc'     => esc_html__( 'Duration time of this package.', 'realty-portal-package' ),
						'type'     => 'billing_period',
						'std'      => '0',
						'callback' => 'RP_MemberShip::render_metabox_fields',
					),
					array(
						'id'    => "{$prefix}_price",
						'label' => esc_html__( 'Package Price', 'realty-portal-package' ),
						'desc'  => esc_html__( 'The price of this package.', 'realty-portal-package' ),
						'type'  => 'text',
						'std'   => '20.00',
					),
					array(
						'id'       => "{$prefix}_properties_num",
						'label'    => esc_html__( 'Number of properties', 'realty-portal-package' ),
						'desc'     => esc_html__( 'Number of properties available for this package.', 'realty-portal-package' ),
						'type'     => 'properties_num',
						'std'      => '3',
						'callback' => 'RP_MemberShip::render_metabox_fields',
					),
					array(
						'id'    => "{$prefix}_featured_num",
						'label' => esc_html__( 'Number of Featured properties', 'realty-portal-package' ),
						'desc'  => esc_html__( 'Number of Featured properties can make featured with this package.', 'realty-portal-package' ),
						'type'  => 'number',
						'std'   => '2',
					),
					array(
						'id'       => "{$prefix}_expire",
						'label'    => esc_html__( 'Expire after', 'realty-portal-package' ),
						'desc'     => esc_html__( 'The time after which the package will expire.', 'realty-portal-package' ),
						'type'     => 'billing_period',
						'std'      => '0',
						'callback' => 'RP_MemberShip::render_metabox_fields',
					),
					array(
						'id'    => "{$prefix}_package_highlighted",
						'label' => esc_html__( 'Package highlighted', 'realty-portal-package' ),
						'desc'  => esc_html__( 'If checked, will be marked as a prominent package when using the "Pricing Table" shortcode.', 'realty-portal-package' ),
						'type'  => 'checkbox',
					),
				),
			);

			$helper->add_meta_box( $meta_box );

			/**
			 * Create box: Aditional Information
			 */
			$meta_box = array(
				'id'          => "{$prefix}_additional_info",
				'title'       => esc_html__( 'Aditional Information', 'realty-portal-package' ),
				'context'     => 'normal',
				'priority'    => 'default',
				'description' => '',
				'fields'      => array(
					array(
						'id'       => "{$prefix}_additional_info",
						'label'    => esc_html__( 'Additional Info', 'realty-portal-package' ),
						'desc'     => esc_html__( 'Add more detail for this package.', 'realty-portal-package' ),
						'type'     => 'addable_text',
						'std'      => '',
						'callback' => 'RP_MemberShip::render_metabox_fields',
					),
				),
			);

			$helper->add_meta_box( $meta_box );
		}

		public static function render_metabox_fields( $post, $id, $type, $meta, $std = null, $field = null ) {
			switch ( $type ) {
				case 'billing_period':
					$value = $meta ? ' value="' . $meta . '"' : '';
					$value = empty( $value ) && ( $std != null && $std != '' ) ? ' placeholder="' . $std . '"' : $value;
					$unit  = esc_attr( get_post_meta( $post->ID, $id . '_unit', true ) );
					$unit  = empty( $unit ) ? 'day' : $unit;
					echo '<div class="input-group">';
					echo '<input type="text" name="rp_meta_boxes[' . $id . ']" ' . $value . ' style="width:200px;display: inline-block;float: left;margin: 0;height:28px;"/>';
					echo '<select name="rp_meta_boxes[' . $id . '_unit]" style="width:100px;display: inline-block;float: left;margin: 0;box-shadow: none;background-color:#ddd;">';
					echo '<option value="day" ' . selected( $unit, 'day', false ) . '>' . esc_html__( 'Days', 'realty-portal-package' ) . '</option>';
					echo '<option value="week" ' . selected( $unit, 'week', false ) . '>' . esc_html__( 'Weeks', 'realty-portal-package' ) . '</option>';
					echo '<option value="month" ' . selected( $unit, 'month', false ) . '>' . esc_html__( 'Months', 'realty-portal-package' ) . '</option>';
					echo '<option value="year" ' . selected( $unit, 'year', false ) . '>' . esc_html__( 'Years', 'realty-portal-package' ) . '</option>';
					echo '</select>';
					echo '</div>';
					break;

				case 'properties_num':
					$unlimited = (bool) get_post_meta( $post->ID, $id . '_unlimited', true );
					$value     = $meta ? ' value="' . $meta . '"' : '';
					$value     = empty( $value ) && ( $std != null && $std != '' ) ? ' placeholder="' . $std . '"' : $value;
					echo '<input type="text" name="rp_meta_boxes[' . $id . ']" ' . $value . disabled( $unlimited, true, false ) . '/>';
					echo '<label><input type="checkbox" name="rp_meta_boxes[' . $id . '_unlimited]" ' . checked( $unlimited, true, false ) . 'value="1" />';
					echo esc_html__( 'Unlimited Listing?', 'realty-portal-package' ) . '</label>';

					echo '<script>
                        jQuery( document ).ready( function ( $ ) {
                            $("input[name=\'rp_meta_boxes[' . $id . '_unlimited]\']").click( function() {
                                if( $(this).is(":checked") ) {
                                    $("input[name=\'rp_meta_boxes[' . $id . ']\']").prop("disabled", true);
                                } else {
                                    $("input[name=\'rp_meta_boxes[' . $id . ']\']").prop("disabled", false);
                                }
                            });

                        } );
                    </script>';

					break;

				case 'addable_text':
					$max_fields = 5;
					if ( ! empty( $field[ 'max_fields' ] ) && is_numeric( $field[ 'max_fields' ] ) ) {
						$max_fields = $field[ 'max_fields' ];
					}
					if ( $max_fields == - 1 ) {
						$max_fields = 100;
					}
					$meta = array();
					?>
					<div class="rp-membership-additional" data-max="<?php echo $max_fields; ?>"
					     data-name="<?php echo $id; ?>">
						<?php
						$count = 0;
						for ( $index = 0; $index <= $max_fields; $index ++ ) {
							$meta_i = get_post_meta( get_the_ID(), $id . '_' . $index, true );
							if ( ! empty( $meta_i ) ) {
								$count ++;
								$meta[] = get_post_meta( get_the_ID(), $id . '_' . $index, true );
							}
						}

						foreach ( $meta as $index => $meta_i ) :
							?>
							<div class="additional-field">
								<input class="rp-input" type="text" value="<?php echo $meta_i; ?>"
								       name="rp_meta_boxes[<?php echo $id . '_' . ( $index + 1 ); ?>]" />
								<input class="button button-secondary delete_membership_add_info" type="button"
								       value="<?php echo esc_html__( 'Delete', 'realty-portal-package' ); ?>" />
							</div>
							<?php
						endforeach;
						?>
					</div>
					<input type="button" value="<?php echo esc_html__( 'Add', 'realty-portal-package' ); ?>"
					       class="button button-primary add_membership_add_info" <?php disabled( $count >= $max_fields ); ?>/>
					<?php
					break;
			}
		}

		/**
		 * Get package id
		 *
		 * @author  KENT <tuanlv@vietbrain.com>
		 * @since   1.0
		 */
		public static function get_package_id( $agent_id = null ) {
			if ( empty( $agent_id ) ) {
				$agent_id = RP_Agent::get_id_agent();
			}

			if ( empty( $agent_id ) ) {
				return 0;
			}

			return intval( get_post_meta( $agent_id, '_membership_package', true ) );
		}

		/**
		 * Set agent membership
		 */
		public static function set_agent_membership( $agent_id = null, $package_id = null, $activation_date = null, $is_admin_edit = false ) {
			if ( empty( $agent_id ) ) {
				$agent_id = RP_Agent::get_id_agent();
			}

			if ( empty( $agent_id ) ) {
				return false;
			}

			$agent_package = self::get_package_id( $agent_id );

			if ( empty( $package_id ) ) {
				$freemium_enabled           = (bool) ( esc_attr( Realty_Portal::get_setting( 'agent_setting', 'membership_freemium' ) ) );
				$freemium_properties_num    = $freemium_enabled ? intval( Realty_Portal::get_setting( 'agent_setting', 'membership_freemium_properties_num' ) ) : 0;
				$freemium_listing_unlimited = $freemium_enabled ? (bool) Realty_Portal::get_setting( 'agent_setting', 'membership_freemium_listing_unlimited' ) : false;

				$remaining_properties = $freemium_listing_unlimited ? - 1 : $freemium_properties_num;
				$featured_remain      = $freemium_enabled ? (int) Realty_Portal::get_setting( 'agent_setting', 'membership_freemium_featured_num' ) : 0;

				$interval      = - 1;
				$interval_unit = 'day';
			} else {
				$package_prefix    = apply_filters( 'rp_membership_post_type', 'rp_membership' );
				$properties_num    = intval( get_post_meta( $package_id, "{$package_prefix}_properties_num", true ) );
				$listing_unlimited = (bool) get_post_meta( $package_id, "{$package_prefix}_properties_num_unlimited", true );

				$remaining_properties = $listing_unlimited ? - 1 : $properties_num;
				$featured_remain      = intval( get_post_meta( $package_id, "{$package_prefix}_featured_num", true ) );

				$interval      = intval( get_post_meta( $package_id, "{$package_prefix}_interval", true ) );
				$interval_unit = intval( get_post_meta( $package_id, "{$package_prefix}_interval_unit", 'day' ) );
			}

			$activation_date = empty( $activation_date ) ? time() : $activation_date; // Date down to second

			update_post_meta( $agent_id, '_membership_package', $package_id );
			update_post_meta( $agent_id, '_remaining_properties', $remaining_properties );
			update_post_meta( $agent_id, '_featured_remain', $featured_remain );
			update_post_meta( $agent_id, '_activation_date', $activation_date );
			update_post_meta( $agent_id, '_membership_interval', $interval );
			update_post_meta( $agent_id, '_membership_interval_unit', $interval_unit );

			do_action( 'noo_set_agent_membership', $agent_id, $package_id, $activation_date, $is_admin_edit );
		}

		public static function rp_create_agent( $post_id ) {

			/**
			 * If this is just a revision, don't send the email.
			 */
			if ( wp_is_post_revision( $post_id ) ) {
				return false;
			}

			/**
			 * Check post type
			 */
			global $post;

			if ( empty( $post ) || $post->post_type != apply_filters( 'rp_agent_post_type', 'rp_agent' ) ) {
				return false;
			}

			/**
			 * Validate $_POST
			 */
			$_POST = wp_kses_post_deep( $_POST );

			/**
			 * Membership
			 */
			if ( isset( $_POST[ 'rp_meta_boxes' ][ '_membership_package' ] ) ) {
				self::set_agent_membership( $post_id, intval( $_POST[ 'rp_meta_boxes' ][ '_membership_package' ] ), time(), true );
			}
		}

		public static function rp_update_post_to_agent( $post_id ) {

			/**
			 * If this is just a revision, don't send the email.
			 */
			if ( wp_is_post_revision( $post_id ) ) {
				return;
			}

			/**
			 * Check post type
			 */
			global $post;

			if ( isset( $_POST[ 'rp_meta_boxes' ][ '_membership_package' ] ) ) {
				self::set_agent_membership( $post_id, intval( $_POST[ 'rp_meta_boxes' ][ '_membership_package' ] ), time(), true );
			}
		}

		/**
		 * Get membership info
		 */
		public static function get_membership_info( $agent_id = null ) {
			if ( empty( $agent_id ) ) {
				$user_id = rp_get_current_user( true );
				if ( ! empty( $user_id ) ) {
					$agent_id = RP_Agent::get_id_agent();
				}
			}

			$agent_remaining_properties = ! empty( $agent_id ) ? get_post_meta( $agent_id, '_remaining_properties', true ) : '';
			$agent_featured_remain      = ! empty( $agent_id ) ? get_post_meta( $agent_id, '_featured_remain', true ) : '';

			$return           = array();
			$return[ 'type' ] = self::get_membership_type();

			if ( $return[ 'type' ] == 'membership' ) {
				$freemium_enabled           = (bool) ( esc_attr( Realty_Portal::get_setting( 'agent_setting', 'membership_freemium' ) ) );
				$freemium_properties_num    = $freemium_enabled ? intval( Realty_Portal::get_setting( 'agent_setting', 'membership_freemium_properties_num' ) ) : 0;
				$freemium_listing_unlimited = $freemium_enabled ? (bool) Realty_Portal::get_setting( 'agent_setting', 'membership_freemium_listing_unlimited' ) : false;
				$freemium_featured_num      = $freemium_enabled ? intval( Realty_Portal::get_setting( 'agent_setting', 'membership_freemium_featured_num' ) ) : 0;
				if ( $freemium_listing_unlimited ) {
					$freemium_properties_num = - 1;
				}

				$agent_package = array();
				$package_id    = ! empty( $agent_id ) ? self::get_package_id( $agent_id ) : '';
				if ( empty( $package_id ) ) {
					$agent_package[ 'package_id' ]    = '';
					$agent_package[ 'package_title' ] = esc_html__( 'Free Membership', 'realty-portal-package' );

					$agent_package[ 'number_property' ]      = $freemium_properties_num;
					$agent_package[ 'remaining_properties' ] = ( $agent_remaining_properties === '' || $agent_remaining_properties === null ) ? $freemium_properties_num : intval( $agent_remaining_properties );

					$agent_package[ 'featured_included' ] = $freemium_featured_num;
					$agent_package[ 'featured_remain' ]   = ( $agent_featured_remain === '' || $agent_featured_remain === null ) ? $freemium_featured_num : intval( $agent_featured_remain );
					$agent_package[ 'expired_date' ]      = esc_html__( 'Never', 'realty-portal-package' ); // Never
				} else {
					$agent_package[ 'package_id' ]    = $package_id;
					$agent_package[ 'package_title' ] = get_the_title( $package_id );

					$package_prefix    = apply_filters( 'rp_membership_post_type', 'rp_membership' );
					$properties_num    = intval( get_post_meta( $package_id, "{$package_prefix}_properties_num", true ) );
					$listing_unlimited = (bool) get_post_meta( $package_id, "{$package_prefix}_properties_num_unlimited", true );
					$featured_num      = intval( get_post_meta( $package_id, "{$package_prefix}_featured_num", true ) );

					if ( $listing_unlimited ) {
						$properties_num = - 1;
					}

					$agent_package[ 'number_property' ]   = $properties_num;
					$agent_package[ 'featured_included' ] = $featured_num;

					if ( self::is_expired( $agent_id ) ) {
						$agent_package[ 'remaining_properties' ] = 0;
						$agent_package[ 'featured_remain' ]      = 0;
						$agent_package[ 'expired_date' ]         = - 1; // Expired
					} else {
						$agent_package[ 'remaining_properties' ] = ( $agent_remaining_properties === '' || $agent_remaining_properties === null ) ? $properties_num : $agent_remaining_properties;
						$agent_package[ 'featured_remain' ]      = ( $agent_featured_remain === '' || $agent_featured_remain === null ) ? $featured_num : $agent_featured_remain;

						$expired_date                    = self::get_expired_date( $agent_id );
						$expired_date                    = ( $expired_date == false ) ? esc_html__( 'Never', 'realty-portal-package' ) : date_i18n( get_option( 'date_format' ), $expired_date );
						$agent_package[ 'expired_date' ] = $expired_date;
					}
				}
				$return[ 'data' ] = $agent_package;
			} elseif ( $return[ 'type' ] == 'submission' ) {

				$submission                          = array();
				$submission[ 'listing_price' ]       = floatval( esc_attr( Realty_Portal::get_setting( 'agent_setting', 'membership_submission_listing_price' ) ) );
				$submission[ 'listing_price_text' ]  = RP_Payment::format_price( $submission[ 'listing_price' ] );
				$submission[ 'featured_price' ]      = floatval( esc_attr( Realty_Portal::get_setting( 'agent_setting', 'membership_submission_featured_price' ) ) );
				$submission[ 'featured_price_text' ] = RP_Payment::format_price( $submission[ 'featured_price' ] );

				$return[ 'data' ] = $submission;
			}

			return $return;
		}

		/**
		 * Get expired date
		 */
		public static function get_expired_date( $agent_id = null ) {
			if ( empty( $agent_id ) ) {
				$user_id = rp_get_current_user( true );
				if ( ! empty( $user_id ) ) {
					$agent_id = RP_Agent::get_id_agent();
				}
			}

			if ( empty( $agent_id ) ) {
				return false;
			}

			if ( ! RP_MemberShip::is_membership() ) {
				return false;
			}

			$package_id = intval( get_post_meta( $agent_id, '_membership_package', true ) );
			if ( empty( $package_id ) ) {
				return false;
			}

			$package_prefix  = apply_filters( 'rp_membership_post_type', 'rp_membership' );
			$activation_date = intval( get_post_meta( $agent_id, '_activation_date', true ) );
			$interval        = intval( get_post_meta( $package_id, "{$package_prefix}_interval", true ) );
			if ( $interval == - 1 ) { // Unlimited
				return false;
			}

			$interval_unit = esc_attr( get_post_meta( $package_id, "{$package_prefix}_interval_unit", true ) );

			$unit_seconds = 0;
			switch ( $interval_unit ) {
				case 'day':
					$unit_seconds = 60 * 60 * 24;
					break;
				case 'week':
					$unit_seconds = 60 * 60 * 24 * 7;
					break;
				case 'month':
					$unit_seconds = 60 * 60 * 24 * 30;
					break;
				case 'year':
					$unit_seconds = 60 * 60 * 24 * 365;
					break;
			}

			$expired_date = $activation_date + $interval * $unit_seconds;

			return $expired_date;
		}

		public static function is_expired( $agent_id = null ) {
			$expired_date = self::get_expired_date( $agent_id );

			if ( $expired_date == false ) {
				return false;
			}

			$now = time();

			return ( $expired_date - $now ) < 0;
		}

		public static function getMembershipPaymentURL( $agent_id = null, $package_id = null, $is_recurring = false, $recurring_time = 0 ) {
			if( empty( $agent_id ) ) {
				$user_id = rp_get_current_user(true);
				if( !empty($user_id) ) {
					$agent_id = get_user_meta( $user_id, '_associated_agent_id', true );
				}
			}

			if( empty( $agent_id ) || empty( $package_id ) ) {
				return false;
			}

			$agent      = get_post( $agent_id );
			$package    = get_post( $package_id );
			if( !$agent || !$package ) {
				return false;
			}

			$billing_type           = $is_recurring ? 'recurring' : 'onetime';
			$total_price            = floatval( get_post_meta( $package_id, 'noo_membership_price', true ) );
			$title                  = $agent->post_title . ' - Purchase package: ' . $package->post_title;
			$new_order_ID           = RP_Payment::create_new_order( 'membership', $billing_type, $package_id, $total_price, $agent_id, $title );

			if( !$new_order_ID ) {
				return false;
			}

			$order                  = array( 'ID' => $new_order_ID );
			$order['name']          = $agent->post_title;
			$order['email']         = esc_attr( get_post_meta( $agent_id, 'noo_agent_email', true ) );
			$order['item_name']     = esc_html__( 'Membership Payment', 'noo-landmark-core' );
			$order['item_number']   = $package->post_title;
			$order['amount']        = $total_price;
			$order['return_url']    = get_permalink( noo_get_page_by_template( 'agent-dashboard.php' ) );
			$order['cancel_url']    = get_permalink( noo_get_page_by_template( 'agent-dashboard.php' ) );
			if( $is_recurring ) {
				$order['is_recurring']  = $is_recurring;
				$order['p3']            = intval( get_post_meta( $package_id, '_noo_membership_interval', true ) );
				$order['t3']            = esc_attr( get_post_meta( $package_id, '_noo_membership_interval_unit', true ) );
				switch( $order['t3'] ) {
					case 'day':
						$order['t3'] = 'D';
						break;
					case 'week':
						$order['t3'] = 'W';
						break;
					case 'month':
						$order['t3'] = 'M';
						break;
					case 'year':
						$order['t3'] = 'Y';
						break;
				}

				$order['src']       = 1;
				$order['srt']       = $recurring_time;
				$order['sra']       = 1;
			}

			$RPPayPalFramework = RPPayPalFramework::getInstance();

			return $RPPayPalFramework->getPaymentURL( $order );
		}

		public static function revoke_agent_membership( $agent_id = null, $package_id = null ) {
			if( empty( $agent_id ) ) {
				$user_id = rp_get_current_user(true);
				if( !empty($user_id) ) {
					$agent_id = RP_Agent::get_id_agent();
				}
			}

			if( empty( $agent_id ) || empty( $package_id ) ) {
				return false;
			}

			$agent_package = RP_MemberShip::get_package_id( $agent_id );

			if( $package_id == $agent_package ) {
				$freemium_enabled           = (bool) ( esc_attr( Realty_Portal::get_setting( 'agent_setting', 'membership_freemium' ) ) );
				$freemium_properties_num       = $freemium_enabled ? intval( Realty_Portal::get_setting( 'agent_setting', 'membership_freemium_properties_num' ) ) : 0;
				$freemium_listing_unlimited = $freemium_enabled ? (bool) Realty_Portal::get_setting( 'agent_setting', 'membership_freemium_listing_unlimited' ) : false;

				$remaining_properties             = $freemium_listing_unlimited ? -1 : 0;
				$featured_remain            = 0;

				$interval                   = -1;
				$interval_unit              = 'day';

				$activation_date = time(); // Date down to second

				update_post_meta( $agent_id, '_membership_package','' );
				update_post_meta( $agent_id, '_remaining_properties', $remaining_properties );
				update_post_meta( $agent_id, '_featured_remain', $featured_remain );
				update_post_meta( $agent_id, '_activation_date', $activation_date );
				update_post_meta( $agent_id, '_membership_interval', $interval );
				update_post_meta( $agent_id, '_membership_interval_unit', $interval_unit );
			}
		}

		public static function revoke_property_status( $agent_id = null, $prop_id = null, $status_type = '' ) {
			if( empty( $agent_id ) ) {
				$user_id = rp_get_current_user(true);
				if( !empty($user_id) ) {
					$agent_id = get_user_meta( $user_id, '_associated_agent_id', true );
				}
			}

			if( empty( $agent_id ) || empty( $prop_id ) || empty( $status_type ) ) {
				return false;
			}

			if( !self::is_owner( $agent_id, $prop_id ) ) {
				return false;
			}

			switch( $status_type ) {
				case 'listing':
					update_post_meta( $prop_id, '_paid_listing', '' );
					break;
				case 'featured':
					update_post_meta( $prop_id, '_featured', 'no' );
					break;
				case 'both':
					update_post_meta( $prop_id, '_paid_listing', '' );
					update_post_meta( $prop_id, '_featured', 'no' );
					break;
			}
		}

		/**
		 * Check is owner
		 */
		public static function is_owner( $agent_id = null, $prop_id = null ) {
			if( empty( $agent_id ) ) {
				$user_id = rp_get_current_user(true);
				if( !empty($user_id) ) {
					$agent_id = RP_Agent::get_id_agent();
				}
			}

			if( empty( $agent_id ) || empty( $prop_id ) ) {
				return false;
			}

			return intval( get_post_meta( $prop_id, 'agent_responsible', true ) );
		}

		public static function set_property_status( $agent_id = null, $prop_id = null, $status_type = '' ) {
			if( empty( $agent_id ) ) {
				$user_id = rp_get_current_user(true);
				if( !empty($user_id) ) {
					$agent_id = get_user_meta( $user_id, '_associated_agent_id', true );
				}
			}

			if( empty( $agent_id ) || empty( $prop_id ) || empty( $status_type ) ) {
				return false;
			}

			if( !self::is_owner( $agent_id, $prop_id ) ) {
				return false;
			}

			switch( $status_type ) {
				case 'listing':
					update_post_meta( $prop_id, '_paid_listing', 1 );
					break;
				case 'featured':
					update_post_meta( $prop_id, '_featured', 'yes' );
					break;
				case 'both':
					update_post_meta( $prop_id, '_paid_listing', 1 );
					update_post_meta( $prop_id, '_featured', 'yes' );
					break;
			}
		}

		/**
		 * Decrease featured remain
		 *
		 * @author  KENT <tuanlv@vietbrain.com>
		 * @since   1.0
		 */
		public static function decrease_featured_remain( $agent_id = null ) {
			if( empty( $agent_id ) ) {
				$user_id = noo_get_current_user(true);
				if( !empty($user_id) ) {
					$agent_id = get_user_meta( $user_id, '_associated_agent_id', true );
				}
			}

			if( empty( $agent_id ) ) {
				return false;
			}

			if( !RP_MemberShip::is_membership() ) {
				return false;
			}

			$featured_remain = self::get_featured_remain( $agent_id );
			$new_featured_remain = max( 0, $featured_remain - 1 );
			update_post_meta( $agent_id, '_featured_remain', $new_featured_remain );
		}

		/**
		 * Get featured remain
		 *
		 * @author  KENT <tuanlv@vietbrain.com>
		 * @since   1.0
		 */
		public static function get_featured_remain( $agent_id = null ) {
			if( empty( $agent_id ) ) {
				$user_id = noo_get_current_user(true);
				if( !empty($user_id) ) {
					$agent_id = get_user_meta( $user_id, '_associated_agent_id', true );
				}
			}

			if( !RP_MemberShip::is_membership() ) {
				return 0;
			}

			$featured_remain = !empty( $agent_id ) ? get_post_meta( $agent_id, '_featured_remain', true ) : '';
			if( $featured_remain === '' || $featured_remain === null ) {
				$package_id = !empty( $agent_id ) ? self::get_package_id( $agent_id ) : '';
				if( empty( $package_id ) ) {
					$freemium_enabled = (bool) ( esc_attr( get_option( 'noo_membership_freemium' ) ) );
					$featured_remain  = $freemium_enabled ? intval( get_option( 'noo_membership_freemium_featured_num' ) ) : 0;
				} else {
					$package_prefix  = 'noo_membership';
					$featured_remain = intval( get_post_meta( $package_id, "{$package_prefix}_featured_num", true ) );
				}
			}

			return intval( $featured_remain );
		}

	}

	RP_MemberShip::get_instance();

endif;