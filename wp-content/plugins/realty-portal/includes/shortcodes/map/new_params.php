<?php
/**
 * This function get list custom field property
 *
 * @package     VC Extension
 * @author      NooTeam <suppport@nootheme.com>
 * @version     1.0
 */

if ( ! function_exists( 'rp_custom_fields_property_param' ) ) :

	function rp_custom_fields_property_param( $settings, $value ) {

		$class = 'wpb_vc_param_value wpb-input wpb-select ' . $settings[ 'param_name' ] . ' ' . $settings[ 'type' ] . '_field';
		$html  = array( '<div class="rp_vc_custom_param custom_fields_property">' );

		$html[] = '  <select name="' . $settings[ 'param_name' ] . '" class="' . $class . '" >';
		$html[] = '     <option value="none">' . esc_html__( 'None', 'realty-portal' ) . '</option>';
		/**
		 * Show list custom field default
		 */
		$list_fields_default = rp_list_custom_fields_property_default();
		foreach ( $list_fields_default as $key => $val ) {

			$name  = ! empty( $list_fields_default[ $key ][ 'name' ] ) ? esc_attr( $list_fields_default[ $key ][ 'name' ] ) : '';
			$label = ! empty( $list_fields_default[ $key ][ 'label' ] ) ? esc_html( $list_fields_default[ $key ][ 'label' ] ) : '';
			if ( empty( $name ) || empty( $label ) ) {
				continue;
			}

			$html[] = '    <option value="' . $name . '" ' . ( selected( $value, $name, false ) ) . '>';
			$html[] = '      ' . $label;
			$html[] = '    </option>';
		}

		/**
		 * Show list custom field
		 */
		$custom_fields = rp_property_render_fields();
		foreach ( $custom_fields as $key => $val ) {

			$name  = ! empty( $custom_fields[ $key ][ 'name' ] ) ? esc_attr( $custom_fields[ $key ][ 'name' ] ) : '';
			$label = ! empty( $custom_fields[ $key ][ 'label' ] ) ? esc_html( $custom_fields[ $key ][ 'label' ] ) : '';
			if ( empty( $name ) || empty( $label ) ) {
				continue;
			}

			$html[] = '    <option value="' . $name . '" ' . ( selected( $value, $name, false ) ) . '>';
			$html[] = '      ' . sprintf( esc_html__( 'Custom Field: %s', 'realty-portal' ), $label );
			$html[] = '    </option>';
		}

		$html[] = '  </select>';
		$html[] = '</div>';

		$html[] = '<script>';
		$html[] = '  jQuery("document").ready( function() {';
		$html[] = '    jQuery( "select[name=\'' . $settings[ 'param_name' ] . '-select\']" ).click( function() {';
		$html[] = '      var selected_values = jQuery(this).find("option:selected").map(function(){ return this.value; }).get().join(",");';
		$html[] = '      jQuery( "input[name=\'' . $settings[ 'param_name' ] . '\']" ).val( selected_values );';
		$html[] = '    } );';
		$html[] = '  } );';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'custom_fields_property', 'rp_custom_fields_property_param' );

endif;

/**
 * This function get list property featured
 *
 * @package     VC Extension
 * @author      NooTeam <suppport@nootheme.com>
 * @version     1.0
 */

if ( ! function_exists( 'rp_custom_property_featured_param' ) ) :

	function rp_custom_property_featured_param( $settings, $value ) {

		$class = 'wpb_vc_param_value wpb-input wpb-select ' . $settings[ 'param_name' ] . ' ' . $settings[ 'type' ] . '_field';
		$html  = array( '<div class="rp_vc_custom_param custom_property_featured">' );

		$html[] = '  <select name="' . $settings[ 'param_name' ] . '" class="' . $class . '" >';

		/**
		 * Show list custom field default
		 */

		$args                = array(
			'post_type'      => apply_filters( 'rp_property_post_type', 'rp_property' ),
			'posts_per_page' => - 1,
		);
		$args[ 'tax_query' ] = array( 'relation' => 'AND' );

		$args[ 'meta_query' ][] = array(
			'key'   => '_featured',
			'value' => 'yes',
		);

		$query    = new WP_Query( $args );
		$featured = array();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				global $post;
				$featured[ get_the_ID() ] = get_the_title();
			}
		}
		wp_reset_postdata();

		foreach ( $featured as $key => $val ) {

			$html[] = '    <option value="' . $key . '" ' . ( selected( $value, $key, false ) ) . '>';
			$html[] = '      ' . $val;
			$html[] = '    </option>';
		}

		$html[] = '  </select>';
		$html[] = '</div>';

		$html[] = '<script>';
		$html[] = '  jQuery("document").ready( function() {';
		$html[] = '    jQuery( "select[name=\'' . $settings[ 'param_name' ] . '-select\']" ).click( function() {';
		$html[] = '      var selected_values = jQuery(this).find("option:selected").map(function(){ return this.value; }).get().join(",");';
		$html[] = '      jQuery( "input[name=\'' . $settings[ 'param_name' ] . '\']" ).val( selected_values );';
		$html[] = '    } );';
		$html[] = '  } );';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'custom_property_featured', 'rp_custom_property_featured_param' );

endif;

/**
 * This function get list agent
 *
 * @package     VC Extension
 * @author      NooTeam <suppport@nootheme.com>
 * @version     1.0
 */

if ( ! function_exists( 'rp_agent_list_param' ) ) :

	function rp_agent_list_param( $settings, $value ) {

		$class = 'wpb_vc_param_value wpb-input wpb-select ' . $settings[ 'param_name' ] . ' ' . $settings[ 'type' ] . '_field';
		$html  = array( '<div class="rp_vc_custom_param custom_agent">' );

		$html[] = '  <select name="' . $settings[ 'param_name' ] . '" class="' . $class . '" >';

		/**
		 * Show list custom field default
		 */

		$args = array(
			'post_type'      => 'rp_agent',
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
		);

		$query = new WP_Query( $args );
		$agent = array();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$agent[ get_the_ID() ] = get_the_title();
			}
		}

		wp_reset_postdata();

		foreach ( $agent as $key => $val ) {

			$html[] = '    <option value="' . $key . '" ' . ( selected( $value, $key, false ) ) . '>';
			$html[] = '      ' . $val;
			$html[] = '    </option>';
		}

		$html[] = '  </select>';
		$html[] = '</div>';

		$html[] = '<script>';
		$html[] = '  jQuery("document").ready( function() {';
		$html[] = '    jQuery( "select[name=\'' . $settings[ 'param_name' ] . '-select\']" ).click( function() {';
		$html[] = '      var selected_values = jQuery(this).find("option:selected").map(function(){ return this.value; }).get().join(",");';
		$html[] = '      jQuery( "input[name=\'' . $settings[ 'param_name' ] . '\']" ).val( selected_values );';
		$html[] = '    } );';
		$html[] = '  } );';
		$html[] = '</script>';

		return implode( "\n", $html );
	}

	ns_add_shortcode_param( 'agent_list', 'rp_agent_list_param' );

endif;