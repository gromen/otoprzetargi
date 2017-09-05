<?php
if ( ! function_exists( 'rp_currency' ) ) :

	/**
	 * Create list currency
	 *
	 * @return array
	 */
	function rp_currency() {

		return array_unique( apply_filters( 'rp_currency', array(
			'AED' => esc_html__( 'United Arab Emirates Dirham', 'realty-portal' ),
			'EUR' => esc_html__( 'Euros', 'realty-portal' ),
			'AUD' => esc_html__( 'Australian Dollars', 'realty-portal' ),
			'BDT' => esc_html__( 'Bangladeshi Taka', 'realty-portal' ),
			'BRL' => esc_html__( 'Brazilian Real', 'realty-portal' ),
			'BGN' => esc_html__( 'Bulgarian Lev', 'realty-portal' ),
			'CAD' => esc_html__( 'Canadian Dollars', 'realty-portal' ),
			'CLP' => esc_html__( 'Chilean Peso', 'realty-portal' ),
			'CNY' => esc_html__( 'Chinese Yuan', 'realty-portal' ),
			'COP' => esc_html__( 'Colombian Peso', 'realty-portal' ),
			'HRK' => esc_html__( 'Croatia kuna', 'realty-portal' ),
			'CZK' => esc_html__( 'Czech Koruna', 'realty-portal' ),
			'DKK' => esc_html__( 'Danish Krone', 'realty-portal' ),
			'HKD' => esc_html__( 'Hong Kong Dollar', 'realty-portal' ),
			'HUF' => esc_html__( 'Hungarian Forint', 'realty-portal' ),
			'ISK' => esc_html__( 'Icelandic krona', 'realty-portal' ),
			'IDR' => esc_html__( 'Indonesia Rupiah', 'realty-portal' ),
			'INR' => esc_html__( 'Indian Rupee', 'realty-portal' ),
			'ILS' => esc_html__( 'Israeli Shekel', 'realty-portal' ),
			'JPY' => esc_html__( 'Japanese Yen', 'realty-portal' ),
			'KES' => esc_html__( 'Kenyan Shilling', 'realty-portal' ),
			'MYR' => esc_html__( 'Malaysian Ringgits', 'realty-portal' ),
			'MXN' => esc_html__( 'Mexican Peso', 'realty-portal' ),
			'NGN' => esc_html__( 'Nigerian Naira', 'realty-portal' ),
			'NOK' => esc_html__( 'Norwegian Krone', 'realty-portal' ),
			'NZD' => esc_html__( 'New Zealand Dollar', 'realty-portal' ),
			'PHP' => esc_html__( 'Philippine Pesos', 'realty-portal' ),
			'PKR' => esc_html__( 'Pakistani Rupees', 'realty-portal' ),
			'PLN' => esc_html__( 'Polish Zloty', 'realty-portal' ),
			'GBP' => esc_html__( 'Pounds Sterling', 'realty-portal' ),
			'RON' => esc_html__( 'Romanian Leu', 'realty-portal' ),
			'RUB' => esc_html__( 'Russian Ruble', 'realty-portal' ),
			'SGD' => esc_html__( 'Singapore Dollar', 'realty-portal' ),
			'ZAR' => esc_html__( 'South African rand', 'realty-portal' ),
			'KRW' => esc_html__( 'South Korean Won', 'realty-portal' ),
			'SEK' => esc_html__( 'Swedish Krona', 'realty-portal' ),
			'CHF' => esc_html__( 'Swiss Franc', 'realty-portal' ),
			'TWD' => esc_html__( 'Taiwan New Dollars', 'realty-portal' ),
			'THB' => esc_html__( 'Thai Baht', 'realty-portal' ),
			'TRY' => esc_html__( 'Turkish Lira', 'realty-portal' ),
			'USD' => esc_html__( 'US Dollars', 'realty-portal' ),
			'VND' => esc_html__( 'Vietnamese Dong', 'realty-portal' ),
			'CLN' => esc_html__( 'Colones', 'realty-portal' ),
		) ) );
	}

endif;

if ( ! function_exists( 'rp_currency_symbol' ) ) :

	/**
	 * Create currency symbol
	 *
	 * @param string $currency
	 *
	 * @return mixed|void
	 */
	function rp_currency_symbol( $currency = '' ) {

		if ( empty( $currency ) ) {

			$currency = RP_Property::get_setting( 'property_setting', 'property_currency', 'USD' );
		}

		switch ( $currency ) {
			case 'AED' :
				$currency_symbol = 'د.إ';
				break;
			case 'BDT':
				$currency_symbol = '&#2547;&nbsp;';
				break;
			case 'BRL' :
				$currency_symbol = '&#82;&#36;';
				break;
			case 'BGN' :
				$currency_symbol = '&#1083;&#1074;.';
				break;
			case 'AUD' :
			case 'CAD' :
			case 'CLP' :
			case 'MXN' :
			case 'NZD' :
			case 'HKD' :
			case 'SGD' :
			case 'USD' :
				$currency_symbol = '&#36;';
				break;
			case 'EUR' :
				$currency_symbol = '&euro;';
				break;
			case 'CNY' :
			case 'RMB' :
			case 'JPY' :
				$currency_symbol = '&yen;';
				break;
			case 'RUB' :
				$currency_symbol = '&#1088;&#1091;&#1073;.';
				break;
			case 'KRW' :
				$currency_symbol = '&#8361;';
				break;
			case 'TRY' :
				$currency_symbol = '&#84;&#76;';
				break;
			case 'NOK' :
				$currency_symbol = '&#107;&#114;';
				break;
			case 'ZAR' :
				$currency_symbol = '&#82;';
				break;
			case 'CZK' :
				$currency_symbol = '&#75;&#269;';
				break;
			case 'MYR' :
				$currency_symbol = '&#82;&#77;';
				break;
			case 'DKK' :
				$currency_symbol = 'kr.';
				break;
			case 'HUF' :
				$currency_symbol = '&#70;&#116;';
				break;
			case 'IDR' :
				$currency_symbol = 'Rp';
				break;
			case 'INR' :
				$currency_symbol = '&#8377;';
				break;
			case 'ISK' :
				$currency_symbol = 'Kr.';
				break;
			case 'ILS' :
				$currency_symbol = '&#8362;';
				break;
			case 'PHP' :
				$currency_symbol = '&#8369;';
				break;
			case 'PKR' :
				$currency_symbol = 'Rs';
				break;
			case 'PLN' :
				$currency_symbol = '&#122;&#322;';
				break;
			case 'SEK' :
				$currency_symbol = '&#107;&#114;';
				break;
			case 'CHF' :
				$currency_symbol = '&#67;&#72;&#70;';
				break;
			case 'TWD' :
				$currency_symbol = '&#78;&#84;&#36;';
				break;
			case 'THB' :
				$currency_symbol = '&#3647;';
				break;
			case 'GBP' :
				$currency_symbol = '&pound;';
				break;
			case 'RON' :
				$currency_symbol = 'lei';
				break;
			case 'VND' :
				$currency_symbol = '&#8363;';
				break;
			case 'NGN' :
				$currency_symbol = '&#8358;';
				break;
			case 'HRK' :
				$currency_symbol = 'Kn';
				break;
			case 'KES' :
				$currency_symbol = 'KSh';
				break;
			case 'CLN' :
				$currency_symbol = '&#8353;';
				break;
			default    :
				$currency_symbol = '';
				break;
		}

		return apply_filters( 'rp_currency_symbol', $currency_symbol, $currency );
	}

endif;

if ( ! function_exists( 'rp_price_inr_comma' ) ) :

	/**
	 * Process price when code is INR
	 *
	 * @param        $input
	 * @param string $thousands_sep
	 *
	 * @return string
	 */
	function rp_price_inr_comma( $input, $thousands_sep = ',' ) {

		// This function is written by some anonymous person – I got it from Google
		if ( strlen( $input ) <= 2 ) {
			return $input;
		}

		$length = substr( $input, 0, strlen( $input ) - 2 );

		$formatted_input = rp_price_inr_comma( $length, $thousands_sep ) . $thousands_sep . substr( $input, - 2 );

		return $formatted_input;
	}

endif;

if ( ! function_exists( 'rp_price_number_format' ) ) :

	/**
	 * This function process number format
	 *
	 * @param        $num
	 * @param int    $num_decimals
	 * @param string $decimal_sep
	 * @param string $thousands_sep
	 * @param string $currency_code
	 *
	 * @return string|void
	 */
	function rp_price_number_format( $num, $num_decimals = 2, $decimal_sep = '.', $thousands_sep = ',', $currency_code = '' ) {

		if ( empty( $num ) ) {
			return false;
		}

		if ( empty( $currency_code ) || 'INR' != $currency_code ) {
			return number_format( $num, $num_decimals, $decimal_sep, $thousands_sep );
		}

		// Special format for Indian Rupee
		$pos = strpos( (string) $num, '.' );
		if ( false == $pos ) {
			$decimalpart = str_repeat( "0", $num_decimals );
		} else {
			$decimalpart = substr( $num, $pos + 1, $num_decimals );
			$num         = substr( $num, 0, $pos );
		}

		$decimalpart = ! empty( $decimalpart ) ? $decimal_sep . $decimalpart : '';

		if ( strlen( $num ) > 3 & strlen( $num ) <= 12 ) {
			$last3digits         = substr( $num, - 3 );
			$numexceptlastdigits = substr( $num, 0, - 3 );
			$formatted           = rp_price_inr_comma( $numexceptlastdigits, $thousands_sep );
			$stringtoreturn      = $formatted . $thousands_sep . $last3digits . $decimalpart;
		} elseif ( strlen( $num ) <= 3 ) {
			$stringtoreturn = $num . $decimalpart;
		} elseif ( strlen( $num ) > 12 ) {
			$stringtoreturn = number_format( $num, $num_decimals, $decimal_sep, $thousands_sep );
		}

		if ( substr( $stringtoreturn, 0, 2 ) == ( '-' . $decimal_sep ) ) {
			$stringtoreturn = '-' . substr( $stringtoreturn, 2 );
		}

		return $stringtoreturn;
	}

endif;

if ( ! function_exists( 'rp_format_price' ) ) :

	/**
	 * This function get format price
	 *
	 * @param      $price
	 * @param bool $html
	 *
	 * @return mixed|string|void
	 */
	function rp_format_price( $price, $html = true ) {
		$currency_code      = RP_Property::get_setting( 'property_setting', 'property_currency', 'USD' );
		$currency_symbol    = rp_currency_symbol( $currency_code );
		$currency_position  = RP_Property::get_setting( 'property_setting', 'property_currency_position', 'left_space' );
		$price_thousand_sep = RP_Property::get_setting( 'property_setting', 'price_thousand_sep', ',' );
		$price_decimal_sep  = RP_Property::get_setting( 'property_setting', 'price_decimal_sep', '.' );
		$price_num_decimals = RP_Property::get_setting( 'property_setting', 'price_num_decimals', '0' );
		switch ( $currency_position ) {
			case 'left' :
				$format = '<span class="format_price">%1$s</span>%2$s';
				break;
			case 'right' :
				$format = '%2$s<span class="format_price">%1$s</span>';
				break;
			case 'left_space' :
				$format = '<span class="format_price">%1$s</span>&nbsp;%2$s';
				break;
			case 'right_space' :
				$format = '%2$s&nbsp;<span class="format_price">%1$s</span>';
				break;
			default:
				$format = '<span class="format_price">%1$s</span>%2$s';
		}

		$thousands_sep = wp_specialchars_decode( stripslashes( $price_thousand_sep ), ENT_QUOTES );
		$decimal_sep   = wp_specialchars_decode( stripslashes( $price_decimal_sep ), ENT_QUOTES );
		$num_decimals  = absint( $price_num_decimals );

		$price = filter_var( $price, FILTER_SANITIZE_NUMBER_INT );

		if ( ! $html ) {
			return rp_price_number_format( $price, $num_decimals, '.', '', $currency_code );
		}

		if ( isset( $price ) ) {
			$price = rp_price_number_format( $price, $num_decimals, $decimal_sep, $thousands_sep, $currency_code );
		}
		if ( 'text' === $html ) {
			return sprintf( $format, $currency_symbol, $price );
		}

		if ( 'number' === $html ) {
			return $price;
		}

		//$price = preg_replace( '/' . preg_quote( re_get_property_setting('price_decimal_sep'), '/' ) . '0++$/', '', $price );
		$return = '<span class="amount">' . sprintf( $format, $currency_symbol, $price ) . '</span>';

		return $return;
	}

endif;

if ( ! function_exists( 'rp_property_price' ) ) :

	/**
	 * This function process price
	 *
	 * @param string $post_id
	 * @param bool   $label
	 *
	 * @return mixed|string|void
	 */
	function rp_property_price( $post_id = '', $label = true ) {

		if ( empty( $post_id ) ) {
			return false;
		}

		$price        = trim( get_post_meta( $post_id, 'price', true ) );
		$price        = ( preg_match( "/^([0-9]+)$/", $price ) ) ? rp_format_price( $price ) : esc_html( $price );
		$before_price = '<span class="before-price">' . esc_html( get_post_meta( $post_id, 'before_price', true ) ) . '</span>';
		$after_price  = '<span class="after-price">' . esc_html( get_post_meta( $post_id, 'after_price', true ) ) . '</span>';
		if ( $label ) {
			return $before_price . ' ' . $price . ' ' . $after_price;
		} else {
			return $price;
		}
	}

endif;

if ( ! function_exists( 'rp_property_render_price_search_field' ) ) :

	/**
	 * This function show field price to form search
	 *
	 * @param $field
	 */
	function rp_property_render_price_search_field( $field ) {

		global $wpdb;

		$min_price   = $max_price = 0;
//		$min_price   = ceil( $wpdb->get_var( $wpdb->prepare( '
//				SELECT min(meta_value + 0)
//				FROM %1$s
//				LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id
//				WHERE meta_key = \'%3$s\' AND post_type = \'%4$s\' AND post_status = \'%5$s\'
//				', $wpdb->posts, $wpdb->postmeta, 'price', apply_filters( 'rp_property_post_type', 'rp_property' ), 'publish' ) ) );
		$max_price   = ceil( $wpdb->get_var( $wpdb->prepare( '
				SELECT max(meta_value + 0)
				FROM %1$s
				LEFT JOIN %2$s ON %1$s.ID = %2$s.post_id
				WHERE meta_key = \'%3$s\' AND post_type = \'%4$s\' AND post_status = \'%5$s\'
				', $wpdb->posts, $wpdb->postmeta, 'price', apply_filters( 'rp_property_post_type', 'rp_property' ), 'publish' ) ) );
		$g_min_price = isset( $_GET[ 'min_price' ] ) ? esc_attr( $_GET[ 'min_price' ] ) : $min_price;
		$g_max_price = isset( $_GET[ 'max_price' ] ) ? esc_attr( $_GET[ 'max_price' ] ) : $max_price;

		?>
		<div id="rp-item-price-wrap"
		     class="rp-control rp-item-wrap <?php echo ! empty( $field[ 'class' ] ) ? esc_attr( $field[ 'class' ] ) : '' ?>">
			<!-- <?php echo( ! empty( $field[ 'label' ] ) ? '<label>' . esc_html( $field[ 'label' ] ) . '</label>' : '' ) ?> -->
			<div class="rp-price">
				<div class="price-slider-range"></div>
				<input type="hidden" name="min_price" class="price_min" data-min="<?php echo $min_price ?>"
				       value="<?php echo $g_min_price ?>">
				<input type="hidden" name="max_price" class="price_max" data-max="<?php echo $max_price ?>"
				       value="<?php echo $g_max_price ?>">
				<div class="price-results">
					<span class="min-price">
						<?php echo rp_format_price( $g_min_price, 'text' ); ?>
					</span> -
					<span class="max-price">
						<?php echo rp_format_price( $g_max_price, 'text' ); ?>
					</span>
				</div>
			</div>
		</div>
		<?php
	}

endif;