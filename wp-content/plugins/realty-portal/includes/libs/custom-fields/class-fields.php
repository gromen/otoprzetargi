<?php

class RP_Custom_Fields {
	/**
	 * @var array - store all field types
	 */
	protected static $field_types = array();

	/**
	 * Create new field type
	 */
	public static function addField( $name = '', $label = '', $callbacks = array(), $args = array() ) {

		if ( ! empty( $name ) && ! empty( $label ) ) {
			$label        = ! empty( $label ) ? $label : $name;
			$default_args = array(
				'label'       => $label,
				'callbacks'   => $callbacks,
				'can_search'  => true,
				'is_system'   => false,
				'has_choice'  => false,
				'is_multiple' => false,
				'is_readonly' => false,
			);
			$args         = array_merge( $default_args, $args );

			self::$field_types[ $name ] = apply_filters( 'RP_Custom_Fields/addField', $args );
		}

		return true;
	}

	public static function getReadOnlyFieldTypes() {

		$field_types = array();
		foreach ( self::$field_types as $name => $type ) {
			if ( ! $type[ 'is_readonly' ] ) {
				$field_types[ $name ] = $type[ 'label' ];
			}
		}

		return $field_types;
	}

	public static function getPublicFieldTypes() {

		$field_types = array();
		foreach ( self::$field_types as $name => $type ) {
			if ( ! $type[ 'is_system' ] ) {
				$field_types[ $name ] = $type[ 'label' ];
			}
		}

		return $field_types;
	}

	public static function canSearch( $name = '' ) {
		if ( empty( $name ) ) {
			return false;
		}

		return isset( self::$field_types[ $name ] ) && self::$field_types[ $name ][ 'can_search' ];
	}

	public static function getHaveChoicesFields() {
		$field_types = array();
		foreach ( self::$field_types as $name => $type ) {
			if ( isset( $type[ 'has_choice' ] ) && ( $type[ 'has_choice' ] ) ) {
				$field_types[] = $name;
			}
		}

		return $field_types;
	}

	public static function getMultipleFields() {
		$field_types = array();
		foreach ( self::$field_types as $name => $type ) {
			if ( isset( $type[ 'has_choice' ] ) && ( $type[ 'has_choice' ] ) && isset( $type[ 'is_multiple' ] ) && ( $type[ 'is_multiple' ] ) ) {
				$field_types[] = $name;
			}
		}

		return $field_types;
	}

	public static function renderFormField( $name, $field, $value, $show_front_end = true ) {

		if ( isset( self::$field_types[ $name ][ 'callbacks' ][ 'form' ] ) ) {
			return call_user_func( self::$field_types[ $name ][ 'callbacks' ][ 'form' ], $field, $value, $show_front_end );
		}

		do_action( 'rp_render_form_field_' . $name, $field, $value, $show_front_end );

		return '';
	}

	public static function renderSearchField( $name, $field, $value ) {
		if ( isset( self::$field_types[ $name ][ 'callbacks' ][ 'search' ] ) ) {
			return call_user_func( self::$field_types[ $name ][ 'callbacks' ][ 'search' ], $field, $value );
		} elseif ( isset( self::$field_types[ $name ][ 'callbacks' ][ 'form' ] ) ) {
			return call_user_func( self::$field_types[ $name ][ 'callbacks' ][ 'form' ], $field, $value );
		}

		do_action( 'rp_render_search_field_' . $name, $field, $value );

		return '';
	}

	public static function displayField( $name, $field, $value ) {
		if ( isset( self::$field_types[ $name ][ 'callbacks' ][ 'display' ] ) ) {
			return call_user_func( self::$field_types[ $name ][ 'callbacks' ][ 'display' ], $field, $value );
		}

		do_action( 'rp_display_field_' . $name, $field, $value );

		return '';
	}

	public static function validateField( $name, $field, $value ) {
		if ( isset( self::$field_types[ $name ][ 'callbacks' ][ 'validate' ] ) ) {
			return call_user_func( self::$field_types[ $name ][ 'callbacks' ][ 'validate' ], $field, $value );
		}

		do_action( 'rp_validate_field_' . $name, $field, $value );

		return '';
	}

	public static function check_required( $field, $value ) {
		if ( ! empty( $field[ 'validate' ][ 'data-validation' ] ) ) {
			$field[ 'validate' ][ 'data-validation' ] .= " {$value}";
		} else {
			$field[ 'validate' ][ 'data-validation' ] = " {$value}";
		}

		return $field;
	}

	public static function validate_field( $field = array(), $echo = true ) {

		if ( ! isset( $field[ 'type' ] ) || empty( $field[ 'type' ] ) ) {
			return false;
		}

		$field_type = $field[ 'type' ];

		/**
		 * Check placeholder field
		 */
		if ( isset( $field[ 'placeholder' ] ) && ! empty( $field[ 'placeholder' ] ) ) {
			$field[ 'validate' ][ 'placeholder' ] = $field[ 'placeholder' ];
		}

		/**
		 * Check readonly field
		 */
		if ( isset( $field[ 'readonly' ] ) && ! empty( $field[ 'readonly' ] ) ) {
			$field[ 'validate' ][ 'readonly' ] = $field[ 'readonly' ];
		}

		/**
		 * Create id, class tags
		 */
		if ( isset( $field[ 'name' ] ) && ! empty( $field[ 'name' ] ) ) {
			$field[ 'validate' ][ 'name' ]  = apply_filters( 'rp_validation_field_name', $field[ 'name' ] );
			$field[ 'validate' ][ 'id' ]    = apply_filters( 'rp_validation_field_id', 'rp-field-item-' . $field[ 'name' ] );
			$field[ 'validate' ][ 'class' ] = apply_filters( 'rp_validation_field_class', 'rp-field-item-' . $field[ 'type' ] . ' rp-field-item-' . $field[ 'name' ] );
		}

		/**
		 * Check field support multiple
		 */
		if ( in_array( $field_type, rp_multiple_field_types() ) || ! empty( $field[ 'multiple' ] ) ) {
			$field[ 'validate' ][ 'name' ] .= '[]';
		}

		if ( 'multiple_select' == $field_type ) {
			$field[ 'validate' ][ 'multiple' ] = 'multiple';
		}

		$list_field_css_full = apply_filters( 'rp_validation_list_field_css_full', array(
			'select',
			'multiple_select',
			'pages',
		) );

		if ( in_array( $field_type, $list_field_css_full ) ) {
			$field[ 'validate' ][ 'class' ] .= ' rp-select';
		}

		/**
		 * Process type field
		 */
		if ( isset( $field[ 'required' ] ) && ! empty( $field[ 'required' ] ) ) {

			switch ( $field_type ) {
				case 'checkbox':
					$field                                        = self::check_required( $field, 'checkbox_group' );
					$field[ 'validate' ][ 'data-validation-qty' ] = apply_filters( 'rp_validation_field_checkbox_qty', 'min1' );
					break;

				case 'date':
					$format_date                                     = apply_filters( 'rp_validation_field_format_date', 'dd/mm/yyyy' );
					$field                                           = self::check_required( $field, 'date' );
					$field[ 'validate' ][ 'data-validation-format' ] = $format_date;
					if ( empty( $field[ 'validate' ][ 'placeholder' ] ) ) {
						$field[ 'validate' ][ 'placeholder' ] = $format_date;
					}
					break;

				case 'email' :
					$field = self::check_required( $field, 'email' );
					break;

				case 'number' :
					$field = self::check_required( $field, 'number' );
					break;

				case 'url' :
					$field = self::check_required( $field, 'url' );
					break;

				case 'password' :
					$field[ 'validate' ][ 'class' ] .= ' is_strength';
					break;
			}

			if ( ! empty( $field[ 'validate' ][ 'data-validation' ] ) ) {
				$field[ 'validate' ][ 'data-validation' ] .= ' required';
			} else {
				$field[ 'validate' ][ 'data-validation' ] = ' required';
			}
		}

		$validate = array();
		if ( ! empty( $field[ 'validate' ] ) && is_array( $field[ 'validate' ] ) ) {
			foreach ( $field[ 'validate' ] as $key => $val ) {
				if ( 'readonly' == $key ) {
					$validate[] = 'readonly';
				} else {
					$validate[] = $key . '="' . trim( $val ) . '"';
				}
			}
		}
		if ( ! empty( $echo ) ) {
			return apply_filters( 'rp_custom_field_validate_field_html', implode( ' ', $validate ) );
		}

		return apply_filters( 'rp_custom_field_validate_field', $validate );
	}
}

/**
 * Helper function to register new field.
 */
if ( ! function_exists( 'rp_add_custom_field_type' ) ) :
	function rp_add_custom_field_type( $name, $label, $callbacks = array(), $args = array() ) {
		return RP_Custom_Fields::addField( $name, $label, $callbacks, $args );
	}

endif;

if ( ! function_exists( 'rp_has_choice_field_types' ) ) :
	// old name is rp_multiple_value_field_type
	function rp_has_choice_field_types() {

		$types = RP_Custom_Fields::getHaveChoicesFields();

		return apply_filters( 'rp_has_choice_field_types', $types );
	}

endif;

if ( ! function_exists( 'rp_multiple_field_types' ) ) :

	function rp_multiple_field_types() {

		$types = RP_Custom_Fields::getMultipleFields();

		return apply_filters( 'rp_multiple_field_types', $types );
	}

endif;