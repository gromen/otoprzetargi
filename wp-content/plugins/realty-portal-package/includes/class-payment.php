<?php
/**
 * Create class RP_Payment
 * This class process data when payment
 */
if ( ! class_exists( 'RP_Payment' ) ) :

	class RP_Payment {

		/**
		 * A reference to an instance of this class.
		 */
		private static $instance;

		/**
		 * Returns an instance of this class.
		 */
		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new RP_Payment();
			}

			return self::$instance;
		}

		/**
		 * Initializes the plugin by setting filters and administration functions.
		 */
		private function __construct() {

			/**
			 * Loader action/filter
			 */
			add_action( 'init', array(
				&$this,
				'register_payment',
			) );

			add_action( 'init', array(
				&$this,
				'load_library',
			) );

			add_action( 'add_meta_boxes', array(
				&$this,
				'register_metabox',
			) );

			add_action( 'admin_enqueue_scripts', array(
				&$this,
				'enqueue_script_admin',
			) );

			add_action( 'wp_enqueue_scripts', array(
				&$this,
				'enqueue_script_site',
			) );

//			add_action( 'pre_get_posts', array(
//				&$this,
//				'pre_get_posts',
//			) );

			add_action( 'add_meta_boxes', array(
				&$this,
				'remove_meta_boxes',
			), 20 );

			add_filter( 'manage_edit-' . apply_filters( 'rp_payment_post_type', 'rp_payment' ) . '_columns', array(
				&$this,
				'manage_edit_columns',
			) );
			add_action( 'manage_posts_custom_column', array(
				&$this,
				'manage_posts_custom_column',
			) );

			add_action( 'rp-paypal-ipn', array(
				&$this,
				'paypal_ipn',
			) );
		}

		public function enqueue_script_admin() {
		}

		public function enqueue_script_site() {
		}

		/**
		 * Set query pre_get_posts
		 */
		public function pre_get_posts( $query ) {
		}

		/**
		 * Get value setting payment
		 */
		public static function get_setting( $name = '', $value = '', $default = '' ) {

			if ( empty( $name ) ) {
				return false;
			}

			$payment_setting = (array) get_option( esc_attr( $name ), array() );

			if ( array_key_exists( $value, $payment_setting ) ) {

				if ( ! empty( $value ) && ! empty( $payment_setting[ $value ] ) ) {
					return $payment_setting[ $value ];
				}
			}

			if ( empty( $value ) && ! empty( $payment_setting ) ) {
				return $payment_setting;
			}

			return $default;
		}

		/**
		 * Get path payment
		 */
		public static function get_path( $name = '' ) {

			$payment_path = untrailingslashit( plugin_dir_path( __FILE__ ) . 'payment/' . $name );

			return $payment_path;
		}

		/**
		 * Remove metabox
		 */
		public function remove_meta_boxes() {
			remove_meta_box( 'slugdiv', apply_filters( 'rp_payment_post_type', 'rp_payment' ), 'normal' );
			remove_meta_box( 'mymetabox_revslider_0', apply_filters( 'rp_payment_post_type', 'rp_payment' ), 'normal' );
		}

		/**
		 * Loader function
		 */
		public static function load_library() {

			/**
			 * Load library
			 */
			foreach ( glob( self::get_path( 'library/*.php' ) ) as $filename ) {
				require $filename;
			}

			foreach ( glob( self::get_path( 'process/*.php' ) ) as $filename ) {
				require $filename;
			}
		}

		/**
		 * Create post type rp_payment
		 */
		public function register_payment() {

			/**
			 * Check post type exits
			 */
			if ( post_type_exists( apply_filters( 'rp_payment_post_type', 'rp_payment' ) ) ) {
				return;
			}

			/**
			 * Register post type
			 */
			register_post_type( apply_filters( 'rp_payment_post_type', 'rp_payment' ), array(
				'labels'             => array(
					'name'               => esc_html__( 'Orders', 'realty-portal-package' ),
					'singular_name'      => esc_html__( 'Order', 'realty-portal-package' ),
					'menu_name'          => esc_html__( 'Payment', 'realty-portal-package' ),
					'all_items'          => esc_html__( 'Orders', 'realty-portal-package' ),
					'add_new'            => esc_html__( 'Add New', 'realty-portal-package' ),
					'add_new_item'       => esc_html__( 'Add Order', 'realty-portal-package' ),
					'edit'               => esc_html__( 'Edit', 'realty-portal-package' ),
					'edit_item'          => esc_html__( 'Edit Order', 'realty-portal-package' ),
					'new_item'           => esc_html__( 'New Order', 'realty-portal-package' ),
					'view'               => esc_html__( 'View', 'realty-portal-package' ),
					'view_item'          => esc_html__( 'View Order', 'realty-portal-package' ),
					'search_items'       => esc_html__( 'Search Order', 'realty-portal-package' ),
					'not_found'          => esc_html__( 'No payment packages found', 'realty-portal-package' ),
					'not_found_in_trash' => esc_html__( 'No payment packages found in Trash', 'realty-portal-package' ),
					'parent'             => esc_html__( 'Parent Order', 'realty-portal-package' ),
				),
				'public'             => true,
				'has_archive'        => false,
				'rewrite'            => array(
					'slug'       => apply_filters( 'rp_payment_post_type', 'rp_payment' ),
					'with_front' => true,
				),
				'show_in_menu'       => 'edit.php?post_type=' . apply_filters( 'rp_membership_post_type', 'rp_membership' ),
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
			$prefix = apply_filters( 'rp_payment_post_type', 'rp_payment' );

			$helper = new RP_Meta_Boxes( $prefix, array(
				'page' => apply_filters( 'rp_payment_post_type', 'rp_payment' ),
			) );

			/**
			 * Create box: Order Details
			 */
			$meta_box = array(
				'id'     => "{$prefix}_order_details",
				'title'  => esc_html__( 'Order Details', 'realty-portal-package' ),
				'fields' => array(
					array(
						'id'    => '_order_id',
						'label' => esc_html__( 'Order ID', 'realty-portal-package' ),
						'desc'  => '',
						'type'  => 'label',
						'std'   => '<strong>' . get_the_ID() . '</strong>',
					),
				),
			);

			if ( RP_MemberShip::is_membership() ) {
				$meta_box[ 'fields' ][] = array(
					'id'       => '_payment_type',
					'label'    => esc_html__( 'Payment Type', 'realty-portal-package' ),
					'desc'     => '',
					'type'     => 'membership_label',
					'std'      => esc_html__( 'Membership Package', 'realty-portal-package' ),
					'callback' => 'RP_Payment::render_metabox_fields',
				);
				$meta_box[ 'fields' ][] = array(
					'id'      => '_billing_type',
					'label'   => esc_html__( 'Billing Type', 'realty-portal-package' ),
					'desc'    => '',
					'type'    => 'select',
					'std'     => 'onetime',
					'options' => array(
						array(
							'value' => 'onetime',
							'label' => esc_html__( 'One Time', 'realty-portal-package' ),
						),
						array(
							'value' => 'recurring',
							'label' => esc_html__( 'Recurring', 'realty-portal-package' ),
						),
					),
				);
				$meta_box[ 'fields' ][] = array(
					'id'       => '_item_id',
					'label'    => esc_html__( 'Package', 'realty-portal-package' ),
					'desc'     => '',
					'type'     => 'packages',
					'std'      => '',
					'callback' => 'RP_Payment::render_metabox_fields',
				);
				$meta_box[ 'fields' ][] = array(
					'id'    => '_total_price',
					'label' => esc_html__( 'Package Price', 'realty-portal-package' ),
					'desc'  => '',
					'type'  => 'text',
					'std'   => '',
				);
			} elseif ( RP_MemberShip::is_submission() ) {
				$meta_box[ 'fields' ][] = array(
					'id'      => '_payment_type',
					'label'   => esc_html__( 'Payment Type', 'realty-portal-package' ),
					'desc'    => '',
					'type'    => 'select',
					'std'     => 'listing',
					'options' => array(
						array(
							'value' => 'listing',
							'label' => esc_html__( 'Publish Listing', 'realty-portal-package' ),
						),
						array(
							'value' => 'featured',
							'label' => esc_html__( 'Upgrade to Featured', 'realty-portal-package' ),
						),
						array(
							'value' => 'both',
							'label' => esc_html__( 'Publish Listing with Featured', 'realty-portal-package' ),
						),
					),
				);
				$meta_box[ 'fields' ][] = array(
					'id'    => '_item_id',
					'label' => esc_html__( 'Property ID', 'realty-portal-package' ),
					'desc'  => '',
					'type'  => 'text',
					'std'   => '',
				);
				$meta_box[ 'fields' ][] = array(
					'id'    => '_total_price',
					'label' => esc_html__( 'Total Price', 'realty-portal-package' ),
					'desc'  => '',
					'type'  => 'text',
					'std'   => '',
				);
			}

			$meta_box[ 'fields' ][] = array(
				'id'      => '_payment_status',
				'label'   => esc_html__( 'Payment Status', 'realty-portal-package' ),
				'desc'    => '',
				'type'    => 'select',
				'std'     => 'pending',
				'options' => array(
					array(
						'value' => 'pending',
						'label' => esc_html__( 'Pending', 'realty-portal-package' ),
					),
					array(
						'value' => 'canceled',
						'label' => esc_html__( 'Canceled', 'realty-portal-package' ),
					),
					array(
						'value' => 'failed',
						'label' => esc_html__( 'Failed', 'realty-portal-package' ),
					),
					array(
						'value' => 'completed',
						'label' => esc_html__( 'Completed', 'realty-portal-package' ),
					),
					array(
						'value' => 'reversed',
						'label' => esc_html__( 'Reversed', 'realty-portal-package' ),
					),
				),
			);
			$meta_box[ 'fields' ][] = array(
				'id'       => '_purchase_date',
				'label'    => esc_html__( 'Purchase Date', 'realty-portal-package' ),
				'desc'     => '',
				'type'     => 'data_package',
				'std'      => '',
				'callback' => 'RP_Payment::render_metabox_fields',
			);
			$meta_box[ 'fields' ][] = array(
				'id'       => '_agent_id',
				'label'    => esc_html__( 'Agent', 'realty-portal-package' ),
				'desc'     => '',
				'type'     => 'agents',
				'std'      => '',
				'callback' => 'RP_Payment::render_metabox_fields',
			);
			//			$meta_box[ 'fields' ][] = array(
			//				'id'    => '_txn_id',
			//				'label' => esc_html__( 'Transaction ID', 'realty-portal-package' ),
			//				'desc'  => '',
			//				'type'  => 'label',
			//				'std'   => '',
			//			);

			$helper->add_meta_box( $meta_box );
		}

		public static function render_metabox_fields( $post, $id, $type, $meta, $std = null, $field = null ) {
			switch ( $type ) {
				case 'membership_label':
					$value = empty( $meta ) && ( $std != null && $std != '' ) ? $std : $meta;
					$value == 'membership' ? esc_html__( 'Membership Package', 'realty-portal-package' ) : '';
					echo '<label id=' . esc_attr( $id ) . ' >' . esc_html( $value ) . '</label>';
					break;
				case 'data_package':
					$date_format = get_option('date_format', true);
					$value = $meta ? date( $date_format, absint( $meta ) ) : get_the_date( $date_format, $post->ID);
					echo '<input id="' . $id . '_show" type="text" readonly name="" value="' . $value . '">';
					echo '<input id="' . $id . '" type="hidden" name="noo_meta_boxes[' . $id . ']" value="' . absint( $value ) . '">';
					break;
				case 'agents':

					$value = $meta ? $meta : $std;
					$html = array();
					$html[] = '<select name="noo_meta_boxes[' . $id . ']" class="noo_agents_select" >';
					$html[] = '<option value=""' . selected( $value, '', false ) . '>' . esc_html__( '- No Agent -', 'noo-landmark-core' ) . '</option>';

					$args = array(
						'post_type'        => 'noo_agent',
						'posts_per_page'   => -1,
						'post_status'      => 'publish',
						'suppress_filters' => 0
					);

					$agents = get_posts($args); //new WP_Query($args);
					if(!empty($agents)){
						foreach ($agents as $agent){
							$html[] ='<option value="'.$agent->ID.'"' . selected( $value, $agent->ID, false ) . '>'.$agent->post_title.'</option>';
						}
					}
					$html[] = '</select>';
					echo implode( "\n", $html);
					break;

				case 'packages':

					$value = $meta ? $meta : $std;
					$html = array();
					$html[] = '<select name="noo_meta_boxes[' . $id . ']" class="noo_packages_select" >';
					$html[] = '<option value="" ' . selected( $value, '', false ) . '></option>';

					$args = array(
						'post_type'        => 'noo_membership',
						'posts_per_page'   => -1,
						'post_status'      => 'publish',
						'suppress_filters' => 0
					);

					$packages = get_posts($args); //new WP_Query($args);
					if(!empty($packages)){
						foreach ($packages as $package){
							$html[] ='<option value="'.$package->ID.'"' . selected( $value, $package->ID, false ) . '>'.$package->post_title.'</option>';
						}
					}
					$html[] = '</select>';
					echo implode( "\n", $html);
					break;

				case 'membership_packages':
					if( !Noo_MemberShip::is_membership() ) {
						return;
					}

					$value = $meta ? $meta : $std;
					$html = array();
					if( $value != '' ) {
						$html[] = '<p>' . esc_html__( 'If you change agent\'s package, all the package information will be reset.','noo-landmark-core') . '</p>';
					}
					$html[] = '<select name="noo_meta_boxes[' . $id . ']" class="noo_package_select" >';
					if( Noo_Agent::get_setting( 'agent_setting', 'membership_free', true) ) {
						$html[] = '<option value=""' . selected( $value, '', false ) . '> ' . esc_html__( 'Free Membership', 'noo-landmark-core' ) . '</option>';
					}

					$args = array(
						'post_type'        => 'noo_membership',
						'posts_per_page'   => -1,
						'post_status'      => 'publish',
						'suppress_filters' => 0
					);
					$packages = get_posts($args);
					if(!empty($packages)){
						foreach ($packages as $package){
							$html[] ='<option value="'.$package->ID.'"' . selected( $value, $package->ID, false ) . '>'.$package->post_title.'</option>';
						}
					}

					$html[] = '</select>';

					$html[] = '<div id="noo-membership-packages-adder" class="noo-add-parent wp-hidden-children">';
					$html[] = '<h4> <a href="#noo-membership-packages-add" class="noo-add-toggle hide-if-no-js">';
					$html[] = esc_html__( '+ Add new Membership Package', 'noo-landmark-core' );
					$html[] = '</a></h4>';
					$html[] = '<p id="noo-membership-packages-add" class="category-add wp-hidden-child">';

					$html[] = '<label class="screen-reader-text" for="noo-membership-packages-title">' . esc_html__( 'Package Title', 'noo-landmark-core' ) . '</label>';
					$html[] = '<input type="text" name="noo-membership-packages-title" id="noo-membership-packages-title" class="form-required form-input-tip" placeholder="'.esc_html__( 'Package Title', 'noo-landmark-core' ) .'" aria-required="true"/>';
					$html[] = '<label class="screen-reader-text" for="noo-membership-packages-interval">' . esc_html__( 'Package Interval', 'noo-landmark-core' ) . '</label>';
					$html[] = '<input type="text" name="noo-membership-packages-interval" id="noo-membership-packages-interval" placeholder="'.esc_html__( 'Package Interval', 'noo-landmark-core' ) .'" style="width:64%;display: inline-block;float: left;margin-left: 0; margin-right: 0;height:28px;"/>';
					$html[] = '<select name="noo-membership-packages-interval_unit" id="noo-membership-packages-interval_unit" style="width:36%;display: inline-block;float: left; margin-left: 0; margin-right: 0;box-shadow: none;background-color:#ddd;">';
					$html[] = '<option value="day" selected="selected">' . esc_html__( 'Days', 'noo-landmark-core') . '</option>';
					$html[] = '<option value="week">' . esc_html__( 'Weeks', 'noo-landmark-core') . '</option>';
					$html[] = '<option value="month">' . esc_html__( 'Months', 'noo-landmark-core') . '</option>';
					$html[] = '<option value="year">' . esc_html__( 'Years', 'noo-landmark-core') . '</option>';
					$html[] = '</select>';
					$html[] = '<label class="screen-reader-text" for="noo-membership-packages-price">' . esc_html__( 'Package Price', 'noo-landmark-core' ) . '</label>';
					$html[] = '<input type="text" name="noo-membership-packages-price" id="noo-membership-packages-price" class="form-input-tip" placeholder="'.esc_html__( 'Package Price', 'noo-landmark-core' ) .'" aria-required="true"/>';
					$html[] = '<label class="screen-reader-text" for="noo-membership-packages-properties_num">' . esc_html__( 'Number of Listing', 'noo-landmark-core' ) . '</label>';
					$html[] = '<input type="text" name="noo-membership-packages-properties_num" id="noo-membership-packages-properties_num" class="form-input-tip" placeholder="'.esc_html__( 'Number of Listing', 'noo-landmark-core' ) .'" aria-required="true" style="width:64%;display: inline-block;" />';
					$html[] = '<label style="width:34%;display: inline-block;" for="noo-membership-packages-properties_num_unlimited"><input type="checkbox" name="noo-membership-packages-properties_num_unlimited" id="noo-membership-packages-properties_num_unlimited"/>' . esc_html__( 'Unlimited?', 'noo-landmark-core' ) . '</label>';
					$html[] = '<label class="screen-reader-text" for="noo-membership-packages-featured_num">' . esc_html__( 'Number of Featured', 'noo-landmark-core' ) . '</label>';
					$html[] = '<input type="text" name="noo-membership-packages-featured_num" id="noo-membership-packages-featured_num" class="form-input-tip" placeholder="'.esc_html__( 'Number of Featured', 'noo-landmark-core' ) .'" aria-required="true"/>';
					$html[] = '<input type="button" id="noo-membership-packages-add-submit" class="button" value="' . esc_html__( 'Add Membership Package', 'noo-landmark-core' ) . '" />';
					// $html[] = wp_nonce_field( 'noo-membership-packages_ajax_nonce', false );
					$html[] = '<span id="noo-membership-packages-ajax-response"></span>';
					$html[] = '</p>';

					$html[] = '</div>';

					echo implode( "\n", $html);
					break;
			}
		}

		/**
		 * Edit column
		 *
		 * @author  NooTeam <suppport@nootheme.com>
		 * @since   1.0
		 */
		public function manage_edit_columns( $columns ) {
			$before = array_slice( $columns, 1, 1 );
			$after  = array_slice( $columns, 2 );

			$order_columns = array(
				'payment_type'   => esc_html__( 'Payment Type', 'realty-portal-package' ),
				'total_price'    => esc_html__( 'Total Price', 'realty-portal-package' ),
				'payment_status' => esc_html__( 'Payment Status', 'realty-portal-package' ),
			);

			if ( RP_MemberShip::is_membership() ) {
				$order_columns[ 'billing_type' ] = esc_html__( 'Billing Type', 'realty-portal-package' );
			}

			$order_columns[ 'agent' ] = esc_html__( 'by Agent', 'realty-portal-package' );

			$columns = array_merge( $before, $order_columns, $after );

			return $columns;
		}

		/**
		 * Custom column
		 */
		public function manage_posts_custom_column( $column ) {
			GLOBAL $post;
			$post_id = get_the_ID();

			if ( $column == 'payment_type' ) {
				$payment_type = esc_attr( get_post_meta( $post_id, '_payment_type', true ) );

				switch ( $payment_type ) {
					case 'membership':
						echo esc_html__( 'Membership Package', 'realty-portal-package' );
						break;
					case 'listing':
						echo esc_html__( 'Publish Listing', 'realty-portal-package' );
						break;
					case 'featured':
						echo esc_html__( 'Upgrade to Featured', 'realty-portal-package' );
						break;
					case 'both':
						echo esc_html__( 'Publish Listing with Featured', 'realty-portal-package' );
						break;
				}
			}

			if ( $column == 'total_price' ) {
				$total_price = floatval( get_post_meta( $post_id, '_total_price', true ) );
				echo $total_price;
			}

			if ( $column == 'payment_status' ) {
				$payment_status = esc_attr( get_post_meta( $post_id, '_payment_status', true ) );
				switch ( $payment_status ) {
					case 'pending':
						echo esc_html__( 'Pending', 'realty-portal-package' );
						break;
					case 'canceled':
						echo esc_html__( 'Canceled', 'realty-portal-package' );
						break;
					case 'failed':
						echo esc_html__( 'Failed', 'realty-portal-package' );
						break;
					case 'completed':
						echo esc_html__( 'Completed', 'realty-portal-package' );
						break;
					case 'reversed':
						echo esc_html__( 'Reversed', 'realty-portal-package' );
						break;
				}
			}

			if ( $column == 'billing_type' ) {
				$billing_type = esc_attr( get_post_meta( $post_id, '_billing_type', true ) );
				switch ( $billing_type ) {
					case 'onetime':
						echo esc_html__( 'Onetime Payment', 'realty-portal-package' );
						break;
					case 'recurring':
						$recurring_count = intval( get_post_meta( $post_id, '_recurring_count', true ) );
						if ( $recurring_count == 0 ) {
							echo esc_html__( 'Recurring', 'realty-portal-package' );
						} elseif ( $recurring_count == 1 ) {
							echo esc_html__( 'Recurring 1 time', 'realty-portal-package' );
						} elseif ( $recurring_count > 1 ) {
							echo sprintf( esc_html__( 'Recurring %s times', 'realty-portal-package' ), $recurring_count );
						}
						break;
				}
			}

			if ( $column == 'agent' ) {
				$agent_id = esc_attr( get_post_meta( $post_id, '_agent_id', true ) );
				echo get_the_title( $agent_id );
			}
		}

		/**
		 * Get payment type
		 */
		public static function get_payment_type() {
			return self::get_setting( 'payment_setting', 'payment_type', 'paypal' );
		}

		/**
		 * Format the price with a currency symbol.
		 */
		public static function format_price( $price, $html = true ) {
			$type_payment = self::get_payment_type();
			if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && $type_payment == 'woocommerce' ) :

				$decimal_separator  = wc_get_price_decimal_separator();
				$thousand_separator = wc_get_price_thousand_separator();
				$decimals           = wc_get_price_decimals();
				$price_format       = get_woocommerce_price_format();

				$price = apply_filters( 'raw_woocommerce_price', $price );
				$price = apply_filters( 'formatted_woocommerce_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );

				if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $decimals > 0 ) {
					$price = wc_trim_zeros( $price );
				}

				$formatted_price = sprintf( $price_format, get_woocommerce_currency_symbol( '' ), $price );

				if ( 'text' === $html ) {
					return $formatted_price;
				}

				if ( 'number' === $html ) {
					return $price;
				}

				return $formatted_price = '<span class="amount">' . $formatted_price . '</span>';
			else :

				return rp_format_price( $price, $html );

			endif;
		}

		/**
		 * Create new order
		 */
		public static function create_new_order(
			$payment_type = '', $billing_type = '', $item_id = '', $total_price = '', $agent_id = '', $title = ''
		) {
			if ( empty( $payment_type ) || empty( $item_id ) || empty( $total_price ) || empty( $agent_id ) ) {
				return 0;
			}

			if ( $payment_type == 'membership' && empty( $billing_type ) ) {
				return 0;
			}

			if ( ! is_numeric( $item_id ) || ! is_numeric( $agent_id ) ) {
				return 0;
			}

			$total_price = floatval( $total_price );

			if ( $total_price == 0 ) {
				return 0;
			}

			$order    = array(
				'post_title'  => $title,
				'post_type'   => apply_filters( 'rp_payment_post_type', 'rp_payment' ),
				'post_status' => 'publish',
			);
			$order_ID = wp_insert_post( $order );

			if ( ! $order_ID ) {
				return 0;
			}

			update_post_meta( $order_ID, '_payment_status', 'pending' );
			update_post_meta( $order_ID, '_currency_code', RP_Property::get_setting( 'property_setting', 'property_currency' ) );
			update_post_meta( $order_ID, '_payment_type', $payment_type );
			if ( ! empty( $billing_type ) ) {
				update_post_meta( $order_ID, '_billing_type', $billing_type );
			}
			update_post_meta( $order_ID, '_item_id', intval( $item_id ) );
			update_post_meta( $order_ID, '_total_price', $total_price );
			update_post_meta( $order_ID, '_agent_id', intval( $agent_id ) );

			return $order_ID;
		}

		public function paypal_ipn( $POST ) {
			$has_err     = false;
			$err_message = array();

			$order_id       = intval( $POST[ 'custom' ] );
			$txn_id         = esc_attr( $POST[ 'txn_id' ] );
			$txn_type       = esc_attr( $POST[ 'txn_type' ] );
			$payment_status = esc_attr( $POST[ 'payment_status' ] );

			$receiver_id    = esc_attr( $POST[ 'receiver_id' ] );
			$receiver_email = esc_attr( $POST[ 'receiver_email' ] );
			$mc_gross       = floatval( $POST[ 'mc_gross' ] );
			$mc_currency    = esc_attr( $POST[ 'mc_currency' ] );

			if ( $receiver_email != RP_Payment::get_setting( 'payment_setting', 'notify_email', '' ) && $receiver_id != RP_Payment::get_setting( 'payment_setting', 'notify_email', '' ) ) {
				$has_err       = true;
				$err_message[] = esc_html__( 'Different Receiver', 'realty-portal-package' );
			}
			if ( empty( $order_id ) ) {
				$has_err       = true;
				$err_message[] = esc_html__( 'Empty Order ID', 'realty-portal-package' );
			}

			$order               = array();
			$order[ 'agent_id' ] = intval( get_post_meta( $order_id, '_agent_id', true ) );
			if ( empty( $order[ 'agent_id' ] ) ) {
				$has_err       = true;
				$err_message[] = esc_html__( 'Order does not have Agent ID', 'realty-portal-package' );
			}

			$order[ 'item_id' ] = intval( get_post_meta( $order_id, '_item_id', true ) );
			if ( empty( $order[ 'item_id' ] ) ) {
				$has_err       = true;
				$err_message[] = esc_html__( 'Order does not have Item ID', 'realty-portal-package' );
			}
			$order[ 'total_price' ]   = floatval( get_post_meta( $order_id, '_total_price', true ) );
			$order[ 'currency_code' ] = esc_attr( get_post_meta( $order_id, '_currency_code', true ) );
			if ( $mc_gross != round( $order[ 'total_price' ], 2 ) || strtoupper( $mc_currency ) != strtoupper( $order[ 'currency_code' ] ) ) {
				$has_err       = true;
				$err_message[] = esc_html__( 'Price or Currency does not match', 'realty-portal-package' );
			}
			$order[ 'total_price' ] = RP_Payment::format_price( $order[ 'total_price' ], 'text' );

			$order[ 'recurring' ] = esc_attr( get_post_meta( $order_id, '_billing_type', true ) ) == 'recurring';
			$order[ 'status' ]    = esc_attr( get_post_meta( $order_id, '_payment_status', true ) );
			$order_status         = '';

			if ( ! $order[ 'recurring' ] ) {
				$order_status = self::payment_status( $payment_status );
			} else {
				if ( preg_match( "#(subscr_payment)#i", $txn_type ) ) {
					$order_status = self::payment_status( $payment_status );
				} elseif ( preg_match( "#(subscr_signup)#i", $txn_type ) ) {
					$order_status = "pending";
				} elseif ( preg_match( "#(subscr_cancel)#i", $txn_type ) ) {
					$order_status = "canceled";
				} elseif ( preg_match( "#(subscr_failed)#i", $txn_type ) ) {
					$order_status = "failed";
				}
			}

			if ( empty( $order_status ) ) {
				$has_err       = true;
				$err_message[] = esc_html__( 'Unknown order status!', 'realty-portal-package' );
			}

			if ( $has_err ) {
				// echo implode('<br/>', $err_message );
				rp_mail( RP_Payment::get_setting( 'payment_setting', 'notify_email', '' ), esc_html__( 'Error when processing Order', 'realty-portal-package' ), implode( '<br/>', $err_message ) );

				return false;
			}

			if ( $order[ 'status' ] != $order_status ) {

				update_post_meta( $order_id, '_payment_status', $order_status );

				if ( $order[ 'status' ] == 'completed' ) {

					if ( RP_Membership::is_membership() ) {
						// Check if current membership is activated by this order
						$activation_date = get_post_meta( $order[ 'agent_id' ], '_activation_date', true );
						$purchase_date   = get_post_meta( $order_id, '_purchase_date', true );
						if ( $activation_date == $purchase_date ) {
							RP_MemberShip::revoke_agent_membership( $order[ 'agent_id' ], $order[ 'item_id' ] );
						}

						if ( $order[ 'recurring' ] ) {
							$recurring_count = intval( get_post_meta( $order_id, '_recurring_count', true ) );
							$recurring_count = max( 0, $recurring_count - 1 );
							update_post_meta( $order_id, '_recurring_count', $recurring_count );
						}
					} elseif ( RP_Membership::is_submission() ) {
						$order[ 'payment_type' ] = esc_attr( get_post_meta( $order_id, '_payment_type', true ) );
						RP_MemberShip::revoke_property_status( $order[ 'agent_id' ], $order[ 'item_id' ], $order[ 'payment_type' ] );
					}
				}

				if ( $order_status == 'completed' ) {
					$purchase_date = time();
					update_post_meta( $order_id, '_purchase_date', $purchase_date );
					update_post_meta( $order_id, '_txn_id', $txn_id );

					if ( RP_Membership::is_membership() ) {
						RP_MemberShip::set_agent_membership( $order[ 'agent_id' ], $order[ 'item_id' ], $purchase_date );

						if ( $order[ 'recurring' ] ) {
							$recurring_count = intval( get_post_meta( $order_id, '_recurring_count', true ) );
							update_post_meta( $order_id, '_recurring_count', $recurring_count + 1 );
						}

						// Email
						$admin_email = RP_Payment::get_setting( 'payment_setting', 'notify_email', '' );
						if ( empty( $admin_email ) ) {
							$admin_email = get_option( 'admin_email' );
						}

						$user_name    = get_the_title( $order[ 'agent_id' ] );
						$user_email   = get_post_meta( $order[ 'agent_id' ], "rp_agent_email", true );
						$package_name = get_the_title( $order[ 'item_id' ] );
						$site_name    = get_option( 'blogname' );

						// Admin email
						$message = sprintf( esc_html__( "You have received a new payment for membership on %s", 'realty-portal-package' ), $site_name ) . "<br/><br/>";
						$message .= esc_html__( "----------------------------------------------", 'realty-portal-package' ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "User's name: %s", 'realty-portal-package' ), $user_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Email address: %s", 'realty-portal-package' ), $user_email ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Package: %s", 'realty-portal-package' ), $package_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Amount: %s", 'realty-portal-package' ), $order[ 'total_price' ] ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Transaction #: %s", 'realty-portal-package' ), $txn_id ) . "<br/><br/>";
						$message .= esc_html__( "----------------------------------------------", 'realty-portal-package' ) . "<br/><br/>";
						$message .= esc_html__( "You may review your invoice history at any time by logging in to backend.", 'realty-portal-package' ) . "<br/><br/>";
						rp_mail( $admin_email, sprintf( __( '[%s] New Payment received for Membership purchase', 'realty-portal-package' ), $site_name ), $message );

						// Agent email
						$message = sprintf( esc_html__( "Hi %s,", 'realty-portal-package' ), $user_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "You have paid %s for %s membership on %s.", 'realty-portal-package' ), $order[ 'total_price' ], $package_name, $site_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Your transaction ID is: %s,", 'realty-portal-package' ), $txn_id ) . "<br/><br/>";
						$message .= esc_html__( "Thank you and enjoy listing,", 'realty-portal-package' ) . "<br/><br/>";
						rp_mail( $user_email, sprintf( __( '[%s] Payment for membership successfully processed', 'realty-portal-package' ), $site_name ), $message );
					} elseif ( RP_Membership::is_submission() ) {
						$order[ 'payment_type' ] = esc_attr( get_post_meta( $order_id, '_payment_type', true ) );
						RP_MemberShip::set_property_status( $order[ 'agent_id' ], $order[ 'item_id' ], $order[ 'payment_type' ] );

						// Email
						$admin_email = RP_Payment::get_setting( 'payment_setting', 'notify_email', '' );
						if ( empty( $admin_email ) ) {
							$admin_email = get_option( 'admin_email' );
						}
						$property_link       = get_permalink( $order[ 'item_id' ] );
						$property_admin_link = admin_url( 'post.php?post=' . $order[ 'item_id' ] ) . '&action=edit';

						$user_name      = get_the_title( $order[ 'agent_id' ] );
						$user_email     = get_post_meta( $order[ 'agent_id' ], "rp_agent_email", true );
						$property_title = get_the_title( $order[ 'item_id' ] );
						$site_name      = get_option( 'blogname' );

						// Admin email
						$message = '';
						$title   = '';
						if ( $order[ 'payment_type' ] == 'listing' ) {
							$message .= sprintf( esc_html__( "You have received a new payment for Paid Submission on %s", 'realty-portal-package' ), $site_name ) . "<br/><br/>";
							$title   .= sprintf( __( '[%s] New Payment received for Paid Property Submission', 'realty-portal-package' ), $site_name );
						} elseif ( $order[ 'payment_type' ] == 'featured' ) {
							$message .= sprintf( esc_html__( "You have received a new payment for Featured property on %s", 'realty-portal-package' ), $site_name ) . "<br/><br/>";
							$title   .= sprintf( __( '[%s] New Payment received for Featured property', 'realty-portal-package' ), $site_name );
						} elseif ( $order[ 'payment_type' ] == 'both' ) {
							$message .= sprintf( esc_html__( "You have received a new payment for Paid Submission and Featured property on %s", 'realty-portal-package' ), $site_name ) . "<br/><br/>";
							$title   .= sprintf( __( '[%s] New Payment received for Paid Submission and Featured property', 'realty-portal-package' ), $site_name );
						}
						$message .= esc_html__( "----------------------------------------------", 'realty-portal-package' ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "User's name: %s", 'realty-portal-package' ), $user_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Email address: %s", 'realty-portal-package' ), $user_email ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Amount: %s", 'realty-portal-package' ), $order[ 'total_price' ] ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Transaction #: %s", 'realty-portal-package' ), $txn_id ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Property link: %s", 'realty-portal-package' ), $property_admin_link ) . "<br/><br/>";
						$message .= esc_html__( "----------------------------------------------", 'realty-portal-package' ) . "<br/><br/>";
						$message .= esc_html__( "You may review your invoice history at any time by logging in to backend.", 'realty-portal-package' ) . "<br/><br/>";
						rp_mail( $admin_email, $title, $message );

						// Agent email
						$message = sprintf( esc_html__( "Hi %s,", 'realty-portal-package' ), $user_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "You have paid %s for %s property on %s. This is the link to the listing: %s", 'realty-portal-package' ), $order[ 'total_price' ], $property_title, $site_name, $property_link ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Your transaction ID is: %s,", 'realty-portal-package' ), $txn_id ) . "<br/><br/>";
						$message .= esc_html__( "Thank you and best regards,", 'realty-portal-package' ) . "<br/><br/>";
						rp_mail( $user_email, sprintf( __( '[%s] Payment for Property listing successfully processed', 'realty-portal-package' ), $site_name ), $message );
					}
				}
			} else {

				if ( $order[ 'recurring' ] && $order_status == 'completed' ) {
					$purchase_date   = time();
					$recurring_count = intval( get_post_meta( $order_id, '_recurring_count', true ) );
					update_post_meta( $order_id, '_purchase_date', $purchase_date );
					update_post_meta( $order_id, '_txn_id', $txn_id );
					update_post_meta( $order_id, '_recurring_count', $recurring_count + 1 );

					if ( RP_Membership::is_membership() ) {
						RP_MemberShip::set_agent_membership( $order[ 'agent_id' ], $order[ 'item_id' ], $purchase_date );

						// Email
						$admin_email = RP_Payment::get_setting( 'payment_setting', 'notify_email', '' );
						if ( empty( $admin_email ) ) {
							$admin_email = get_option( 'admin_email' );
						}

						$user_name    = get_the_title( $order[ 'agent_id' ] );
						$user_email   = get_post_meta( $order[ 'agent_id' ], "rp_agent_email", true );
						$package_name = get_the_title( $order[ 'item_id' ] );
						$site_name    = get_option( 'blogname' );

						// Admin email
						$message = sprintf( esc_html__( "You have received a new recurring payment for membership on %s", 'realty-portal-package' ), $site_name ) . "<br/><br/>";
						$message .= esc_html__( "----------------------------------------------", 'realty-portal-package' ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "User's name: %s", 'realty-portal-package' ), $user_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Email address: %s", 'realty-portal-package' ), $user_email ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Package: %s", 'realty-portal-package' ), $package_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Amount: %s", 'realty-portal-package' ), $order[ 'total_price' ] ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Transaction #: %s", 'realty-portal-package' ), $txn_id ) . "<br/><br/>";
						$message .= esc_html__( "----------------------------------------------", 'realty-portal-package' ) . "<br/><br/>";
						$message .= esc_html__( "You may review your invoice history at any time by logging in to backend.", 'realty-portal-package' ) . "<br/><br/>";
						rp_mail( $admin_email, sprintf( __( '[%s] New Payment received for Membership purchase', 'realty-portal-package' ), $site_name ), $message );

						// Agent email
						$message = sprintf( esc_html__( "Hi %s,", 'realty-portal-package' ), $user_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "You have paid %s for %s membership on %s.", 'realty-portal-package' ), $order[ 'total_price' ], $package_name, $site_name ) . "<br/><br/>";
						$message .= sprintf( esc_html__( "Your transaction ID is: %s,", 'realty-portal-package' ), $txn_id ) . "<br/><br/>";
						$message .= esc_html__( "Thank you and enjoy listing,", 'realty-portal-package' ) . "<br/><br/>";
						rp_mail( $user_email, sprintf( __( '[%s] Payment for membership successfully processed', 'realty-portal-package' ), $site_name ), $message );
					}
				}
			}
		}

		/**
		 * Set payment status
		 */
		public static function payment_status( $payment_status ) {
			if ( preg_match( "#(canceled_reversal|completed)#i", $payment_status ) ) {
				return "completed";
			} elseif ( preg_match( "#(created|processed|pending)#i", $payment_status ) ) {
				return "pending";
			} elseif ( preg_match( "#(canceled|denied)#i", $payment_status ) ) {
				return "canceled";
			} elseif ( preg_match( "#(failed|expired|voided)#i", $payment_status ) ) {
				return "failed";
			} elseif ( preg_match( "#(refunded|reversed)#i", $payment_status ) ) {
				return "reversed";
			}

			return '';
		}

	}

	RP_Payment::get_instance();

endif;