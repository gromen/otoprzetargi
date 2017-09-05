<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/class-fields.php';

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/text/text.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/date/date.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/email/email.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/password/password.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/number/number.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/url/url.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/image/image.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/select/select.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/multiple_select/multiple_select.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/radio/radio.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/checkbox/checkbox.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/textarea/textarea.php';

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/gmap/gmap.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/line/line.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/pages/pages.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/upload_image/upload_image.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/icon/icon.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'custom-fields/defaults/color_picker/color_picker.php';

if ( ! function_exists( 'rp_custom_fields_type' ) ) :
	function rp_custom_fields_type() {

		$types = RP_Custom_Fields::getPublicFieldTypes();

		return apply_filters( 'rp_custom_fields_type', $types );
	}
endif;

if ( ! function_exists( 'rp_custom_fields_readonly_type' ) ) :
	function rp_custom_fields_readonly_type() {

		$types = RP_Custom_Fields::getReadOnlyFieldTypes();

		return apply_filters( 'rp_custom_fields_readonly_type', $types );
	}
endif;

/**
 * This function merge all field
 */

if ( ! function_exists( 'rp_merge_custom_fields' ) ) :

	function rp_merge_custom_fields( $default_fields = array(), $custom_fields = array() ) {

		foreach ( array_reverse( $default_fields ) as $key => $field ) {
			if ( array_key_exists( $key, $custom_fields ) ) {
				$diff_keys = array_diff_key( $field, $custom_fields[ $key ] );
				foreach ( $diff_keys as $index => $diff ) {
					$custom_fields[ $key ][ $index ] = $diff;
				}
				$custom_fields[ $key ][ 'is_default' ] = true;
				if ( isset( $field[ 'is_tax' ] ) && $field[ 'is_tax' ] ) {
					// Not allow changing label with tax fields
					$custom_fields[ $key ][ 'label' ] = isset( $field[ 'label' ] ) ? $field[ 'label' ] : $custom_fields[ $key ][ 'label' ];
					unset( $custom_fields[ $key ][ 'label_translated' ] );
				}
			} else {
				$custom_fields = array( $key => $field ) + $custom_fields;
			}
		}

		return $custom_fields;
	}

endif;

/**
 * Create element html
 */
if ( ! function_exists( 'rp_create_element' ) ) :

	function rp_create_element( $args = array(), $value = '', $show_front_end = true ) {

		$defaults = array(
			'title'        => '',
			'name'         => '',
			'type'         => '',
			'std'          => '',
			'class'        => '',
			'class_child'  => '',
			'placeholder'  => '',
			'list'         => '',
			'required'     => '',
			'data_notice'  => '',
			'readonly'     => false,
			'options'      => array(),
			'notice_error' => '',
			'after_notice' => '',
		);

		$field = wp_parse_args( $args, $defaults );

		if ( empty( $field[ 'name' ] ) || empty( $field[ 'type' ] ) ) {
			return false;
		}

		echo '<div id="rp-item-' . esc_attr( $field[ 'name' ] ) . '-wrap" class="rp-item-wrap ' . esc_attr( $field[ 'class' ] ) . '">';

		if ( ! empty( $field[ 'title' ] ) ) {
			echo '<label>' . esc_html( $field[ 'title' ] ) . '</label>';
		}

		$value             = ! empty( $value ) ? $value : $field[ 'std' ];
		$field[ 'notice' ] = $field[ 'after_notice' ];

		RP_Custom_Fields::renderFormField( $field[ 'type' ], $field, $value, $show_front_end );

		echo '</div>';
	}

endif;
