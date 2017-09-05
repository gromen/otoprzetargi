<?php
/**
 * Register widget
 *
 * @package       Realty_Portal
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_register_widget' ) ) :

	function rp_register_widget() {

		register_widget( 'RP_Widget_Property_Listing' );
		register_widget( 'RP_Widget_Featured_Property' );
		register_widget( 'RP_Widget_Property_Taxonomies' );
		register_widget( 'RP_Widget_Simple_Search_Property' );
	}

	add_action( 'widgets_init', 'rp_register_widget' );

endif;