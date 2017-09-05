<?php
/**
 * Process data when clicking submit button on back-end
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 * @version 1.0
 */
if ( ! function_exists( 'rp_save_property_settings' ) ) :

	function rp_save_property_settings() {

		/**
		 * Check security
		 */
		check_ajax_referer( 'rp-property-backend', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal' ) );

		/**
		 * Validate $_POST
		 */
		$data_request = wp_kses_post_deep( $_POST );

		/**
		 * Process name option
		 */
		if ( ! isset( $data_request[ 'name_option' ] ) || empty( $data_request[ 'name_option' ] ) ) {

			$response[ 'status' ] = 'error';
			$response[ 'msg' ]    = esc_html__( 'Don\'t allow this action, please check again!', 'realty-portal' );
		} else {

			/**
			 * VAR
			 */
			$name_option  = sanitize_text_field( $data_request[ 'name_option' ] );
			$data_result  = $data_request;

			unset( $data_result[ 'action' ] );
			unset( $data_result[ 'security' ] );
			unset( $data_result[ 'name_option' ] );

			/**
			 * Set value default WPML
			 */
			if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
				$wpml_prefix     = $name_option . '_';
				if ( 'property_custom_field' == $name_option ) {
					$wpml_label = ( 'RP Custom Fields' );
				} else {
					if ( 'property_custom_featured' == $name_option ) {
						$wpml_label = ( ( 'Property Custom Features' ) );
					} else {
						if ( 'agent_custom_field' == $name_option ) {
							$wpml_label = 'RP Agent Custom Fields';
						} else {
							$wpml_label = '';
						}
					}
				}
				if ( ! empty( $wpml_label_vale ) ) {
					if ( isset( $wpml_label_vale ) ) {
						if ( ( 'property_custom_field' == $name_option ) ) {
							$wpml_label_vale = ( 'RP Custom Fields Value' );
						} else {
							if ( 'agent_custom_field' == $name_option ) {
								$wpml_label_vale = 'RP Agent Custom Fields Value';
							} else {
								$wpml_label_vale = '';
							}
						}
					}
				}
			}

			/**
			 * Process security
			 */
			foreach ( $data_result as $key => $value ) {

				if ( !empty( $data_result[ $key ][ 'multiple' ] ) ) {

				} elseif ( ! empty( $data_result[ $key ][ 'name' ] ) ) {

					$data_result[ $key ][ 'name' ]     = rp_validate_data( $data_result[ $key ][ 'name' ] );
					$data_result[ $key ][ 'label' ]    = rp_validate_data( $data_result[ $key ][ 'label' ] );
					$data_result[ $key ][ 'type' ]     = rp_validate_data( $data_result[ $key ][ 'type' ] );
					$data_result[ $key ][ 'required' ] = rp_validate_data( $data_result[ $key ][ 'required' ] );
					$data_result[ $key ][ 'value' ]    = rp_validate_data( $data_result[ $key ][ 'value' ] );
					$data_result[ $key ][ 'hide' ]     = rp_validate_data( $data_result[ $key ][ 'hide' ] );
					$data_result[ $key ][ 'readonly' ] = rp_validate_data( $data_result[ $key ][ 'readonly' ] );

					/**
					 * Support WPML
					 */
					if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
						if ( ! isset( $data_result[ $key ][ 'name' ] ) || empty( $data_result[ $key ][ 'name' ] ) ) {
							continue;
						}
						if ( ! isset( $data_result[ $key ][ 'label' ] ) || empty( $data_result[ $key ][ 'label' ] ) ) {
							continue;
						}
						do_action( 'wpml_register_single_string', $wpml_label, $wpml_prefix . sanitize_title( $data_result[ $key ][ 'name' ] ), $data_result[ $key ][ 'label' ] );

						if ( in_array( $data_result[ $key ][ 'type' ], rp_has_choice_field_types() ) ) {
							$list_option = explode( "\n", $data_result[ $key ][ 'value' ] );
							foreach ( $list_option as $index => $option ) {
								$option_key      = explode( '|', $option );
								$option_key[ 0 ] = trim( $option_key[ 0 ] );
								if ( empty( $option_key[ 0 ] ) ) {
									continue;
								}
								$option_key[ 1 ] = isset( $option_key[ 1 ] ) ? $option_key[ 1 ] : $option_key[ 0 ];
								$option_key[ 0 ] = sanitize_title( $option_key[ 0 ] );

								do_action( 'wpml_register_single_string', $wpml_label_vale, sanitize_title( $data_result[ $key ][ 'name' ] ) . '_value_' . $option_key[ 0 ], $option_key[ 1 ] );
							}
						} else {
							if ( isset( $data_result[ $key ][ 'value' ] ) || empty( $data_result[ $key ][ 'value' ] ) ) {
								do_action( 'wpml_register_single_string', $wpml_label_vale, sanitize_title( $data_result[ $key ][ 'name' ] ) . '_value', $data_result[ $key ][ 'value' ] );
							}
						}
					}
				} else if ( ! isset( $data_result[ 'clone_field' ] ) ) {

					$data_result[ $key ] = rp_validate_data( $data_result[ $key ] );
				}
			}

			if ( isset( $data_result[ 'clone_field' ] ) && ! empty( $data_result[ 'clone_field' ] ) ) {

				$clone_field = $data_result[ 'clone_field' ];

				foreach ( $clone_field as $field ) {

					if ( empty( $field[ 'name' ] ) ) {
						unset( $field );
						continue;
					}

					$field_name = rp_validate_data( $field[ 'name' ], 'strtolower' );

					$data_result[ $field_name ] = $field;

					/**
					 * Support WPML
					 */
					if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
						if ( ! isset( $field[ 'name' ] ) || empty( $field[ 'name' ] ) ) {
							continue;
						}
						if ( ! isset( $field[ 'label' ] ) || empty( $field[ 'label' ] ) ) {
							continue;
						}
						do_action( 'wpml_register_single_string', $wpml_label, $wpml_prefix . sanitize_title( $field[ 'name' ] ), $field[ 'label' ] );

						if ( in_array( $field[ 'type' ], rp_has_choice_field_types() ) ) {
							$list_option = explode( "\n", $field[ 'value' ] );
							foreach ( $list_option as $index => $option ) {
								$option_key      = explode( '|', $option );
								$option_key[ 0 ] = trim( $option_key[ 0 ] );
								if ( empty( $option_key[ 0 ] ) ) {
									continue;
								}
								$option_key[ 1 ] = isset( $option_key[ 1 ] ) ? $option_key[ 1 ] : $option_key[ 0 ];
								$option_key[ 0 ] = sanitize_title( $option_key[ 0 ] );

								do_action( 'wpml_register_single_string', 'RP Custom Fields Value', sanitize_title( $field[ 'name' ] ) . '_value_' . $option_key[ 0 ], $option_key[ 1 ] );
							}
						} else {
							if ( isset( $field[ 'value' ] ) || empty( $field[ 'value' ] ) ) {
								do_action( 'wpml_register_single_string', 'RP Custom Fields Value', sanitize_title( $field[ 'name' ] ) . '_value', $field[ 'value' ] );
							}
						}
					}
				}

				unset( $data_result[ 'clone_field' ] );
			}

			/**
			 * Unset field clone
			 */
			unset( $data_result[ 'ID' ] );
			unset( $data_result[ 'filter' ] );

			/**
			 * Process data
			 */
			update_option( rp_validate_data( $name_option ), $data_result );

			$response[ 'status' ] = 'success';
			$response[ 'msg' ]    = esc_html__( 'Update success!', 'realty-portal' );
		}

		wp_send_json( $response );
	}

	add_action( 'wp_ajax_property_settings', 'rp_save_property_settings' );
	add_action( 'wp_ajax_nopriv_property_settings', 'rp_save_property_settings' );

endif;