<?php
/**
 * Create widget: Noo - Payment Calculator
 *
 * @package       LandMark
 * @subpackage    Widget
 * @author        James <luyentv@gmail.com>
 * @version       1.0
 */

if ( ! class_exists( 'Noo_Widget_Payment_Calculator' ) ):
	class Noo_Widget_Payment_Calculator extends WP_Widget {

		public function __construct() {
			parent::__construct( 'noo_mortgage_payment', esc_html__( 'Noo - Mortgage Payment Calculator', 'noo-landmark-core' ), array(
				'description',
				esc_html__( 'Noo - Mortgage Payment Calculator', 'noo-landmark-core' ),
			) );
		}

		public function widget( $args, $instance ) {
			extract( $args );

			$title                   = apply_filters( 'widget_title', $instance[ 'title' ] );
			$max_price               = $instance[ 'price' ] ? $instance[ 'price' ] : '22500';
			$default_deposit         = $instance[ 'deposit' ] ? $instance[ 'deposit' ] : '15000';
			$default_annual_interest = $instance[ 'annual_interest' ] ? $instance[ 'annual_interest' ] : '10';
			$default_year            = $instance[ 'year' ] ? $instance[ 'year' ] : '2';
			$currency                = rp_currency_symbol();
			echo $before_widget;
			if ( $title ) :
				echo $before_title . $title . $after_title;
			endif;
			?>
			<div class="noo-mortgage-paymant-calculator">
				<div class="pament_result">
					<h5 data-currency="<?php echo esc_attr( $currency ); ?>"><?php echo esc_html__( 'Monthly Payment: ', 'noo-landmark-core' ) ?>
						<b><?php echo esc_html( $currency ); ?><span></span></b></h5>
				</div>
				<form action="#" method id="noo-mortgage-payment">
					<div class="group">
						<label><?php echo esc_attr__( 'Property price:', 'noo-landmark-core' ) ?></label>
						<div class="noo-item-wrap">
							<input id="cl_price" name="price" type="text" data-customize-setting-link="noo_header_nav_height" class="noo-slider" value="<?php echo esc_attr( $max_price ) ?>" data-min="0" data-max="<?php echo esc_attr( $max_price ) ?>" />
							<span class="field-icon"><i><?php echo esc_html( $currency ); ?></i></span>
						</div>
					</div>
					<div class="group">
						<label><?php echo esc_attr__( 'Deposit:', 'noo-landmark-core' ) ?></label>
						<div class="noo-item-wrap">
							<input id="cl_deposit" name="deposit" type="text" data-customize-setting-link="noo_header_nav_height" class="noo-slider" value="<?php echo esc_attr( $default_deposit ) ?>" data-min="0" data-max="<?php echo esc_attr( $max_price ) ?>" />
							<span class="field-icon"><i><?php echo esc_html( $currency ); ?></i></span>
						</div>
					</div>
					<div class="group">
						<label><?php echo esc_attr__( 'Annual Interest:', 'noo-landmark-core' ) ?></label>
						<div class="noo-item-wrap">
							<input id="cl_annual_interest" name="annual_interest" type="text" data-customize-setting-link="noo_header_nav_height" class="noo-slider" value="<?php echo esc_attr( $default_annual_interest ) ?>" data-min="0" data-max="100" />
							<span class="field-icon"><i>%</i></span>
						</div>
					</div>
					<div class="group">
						<label><?php echo esc_attr__( 'Years:', 'noo-landmark-core' ) ?></label>
						<div class="noo-item-wrap">
							<input id="cl_year" name="year" type="text" data-customize-setting-link="noo_header_nav_height" class="noo-slider" value="<?php echo esc_attr( $default_year ) ?>" data-min="0" data-max="30" />
							<span class="field-icon"><i class="fa fa-calendar"></i></span>
						</div>
					</div>
					<span class="noo-button" id="noo_mortgage_calculate"><?php echo esc_html( 'Calculate', 'noo-landmark-core' ) ?></span>
				</form>
			</div><!-- /.noo-mortgage-paymant-calculator -->
			<?php
			// Call noo_calculator.js
			wp_enqueue_script( 'noo-calculator' );

			echo $after_widget;
		}

		public function form( $instance ) {

			$instrance = wp_parse_args( $instance, array(
				'title'           => 'Mortgage Payment Calculator',
				'price'           => '22500',
				'deposit'         => '1500',
				'annual_interest' => '10',
				'year'            => '2',
			) );

			extract( $instance );

			/**
			 * Create element title
			 */
			$title_args = array(
				'id'    => $this->get_field_name( 'title' ),
				'name'  => $this->get_field_name( 'title' ),
				'type'  => 'text',
				'title' => esc_html__( 'Title', 'noo-landmark-core' ),
			);
			echo rp_create_element( $title_args, $title );

			/**
			 * Create element price
			 */
			$price_args = array(
				'id'    => $this->get_field_name( 'price' ),
				'name'  => $this->get_field_name( 'price' ),
				'type'  => 'text',
				'title' => esc_html__( 'Max property price', 'noo-landmark-core' ),
			);
			echo rp_create_element( $price_args, $price );

			/**
			 * Create element deposit
			 */
			$deposit_args = array(
				'id'    => $this->get_field_name( 'deposit' ),
				'name'  => $this->get_field_name( 'deposit' ),
				'type'  => 'text',
				'title' => esc_html__( 'Default deposit', 'noo-landmark-core' ),
			);
			echo rp_create_element( $deposit_args, $deposit );

			/**
			 * Create element annual interest
			 */
			$annual_interest_args = array(
				'id'    => $this->get_field_name( 'annual_interest' ),
				'name'  => $this->get_field_name( 'annual_interest' ),
				'type'  => 'text',
				'title' => esc_html__( 'Default annual interest (%)', 'noo-landmark-core' ),
			);
			echo rp_create_element( $annual_interest_args, $annual_interest );

			/**
			 * Create element year
			 */
			$year_args = array(
				'id'    => $this->get_field_name( 'year' ),
				'name'  => $this->get_field_name( 'year' ),
				'type'  => 'text',
				'title' => esc_html__( 'Default year', 'noo-landmark-core' ),
			);
			echo rp_create_element( $year_args, $year );
		}

		// method update
		public function update( $new_instance, $old_instance ) {
			$instance                      = array();
			$instance[ 'title' ]           = ( ! empty( $new_instance[ 'title' ] ) ) ? strip_tags( $new_instance[ 'title' ] ) : '';
			$instance[ 'price' ]           = ( ! empty( $new_instance[ 'price' ] ) ) ? strip_tags( $new_instance[ 'price' ] ) : '';
			$instance[ 'deposit' ]         = ( ! empty( $new_instance[ 'deposit' ] ) ) ? strip_tags( $new_instance[ 'deposit' ] ) : '';
			$instance[ 'annual_interest' ] = ( ! empty( $new_instance[ 'annual_interest' ] ) ) ? strip_tags( $new_instance[ 'annual_interest' ] ) : '';
			$instance[ 'year' ]            = ( ! empty( $new_instance[ 'year' ] ) ) ? strip_tags( $new_instance[ 'year' ] ) : '';

			return $instance;
		}
	}
endif;