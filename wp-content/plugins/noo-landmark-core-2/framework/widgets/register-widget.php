<?php
/**
 * Register widget
 *
 * @package       LandMark
 * @author        KENT <tuanlv@vietbrain.com>
 * @version       1.0
 */
if ( ! function_exists( 'noo_register_widget' ) ) :

	function noo_register_widget() {
		register_widget( 'Noo_Widget_Featured_Agent' );
		register_widget( 'Noo_Widget_Payment_Calculator' );
	}

	add_action( 'widgets_init', 'noo_register_widget' );

endif;