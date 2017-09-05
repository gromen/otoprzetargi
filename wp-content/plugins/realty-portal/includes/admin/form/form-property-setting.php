<?php
/**
 * Show form property setting
 *
 * @author         NooTeam <suppport@nootheme.com>
 * @version        0.1
 */
if ( ! function_exists( 'rp_form_property_setting' ) ) :

	function rp_form_property_setting() {

		$currency_symbol = rp_currency_symbol();

		rp_render_form_setting( array(
			'title'   => esc_html__( 'Property Setting', 'realty-portal' ),
			'name'    => 'property_setting',
			'id_form' => 'tab-setting-property',
			'fields'  => array(
				array(
					'title' => esc_html__( 'Property Archive Base (slug)', 'realty-portal' ),
					'name'  => 'archive_slug',
					'type'  => 'text',
					'std'   => 'properties',
				),
				array(
					'title' => esc_html__( 'Listing Type Base (slug)', 'realty-portal' ),
					'name'  => 'listing_type',
					'type'  => 'text',
					'std'   => 'type',
				),
				array(
					'title' => esc_html__( 'Listing Offers Base (slug)', 'realty-portal' ),
					'name'  => 'listing_offers',
					'type'  => 'text',
					'std'   => 'offers',
				),
				array(
					'title' => esc_html__( 'Area Unit', 'realty-portal' ),
					'name'  => 'property_area_unit',
					'type'  => 'text',
					'std'   => 'm2',
				),
				array(
					'title'   => esc_html__( 'Currency', 'realty-portal' ),
					'name'    => 'property_currency',
					'type'    => 'select',
					'std'     => 'USD',
					'options' => rp_currency(),
				),
				array(
					'title'   => esc_html__( 'Currency Position', 'realty-portal' ),
					'name'    => 'property_currency_position',
					'type'    => 'select',
					'std'     => 'left_space',
					'options' => array(
						'left'        => esc_html__( 'Left', 'realty-portal' ) . ' (' . esc_attr( $currency_symbol ) . '99.99)',
						'right'       => esc_html__( 'Right', 'realty-portal' ) . ' (99.99' . esc_attr( $currency_symbol ) . ')',
						'left_space'  => esc_html__( 'Left with space', 'realty-portal' ) . ' (' . esc_attr( $currency_symbol ) . ' 99.99)',
						'right_space' => esc_html__( 'Right with space', 'realty-portal' ) . ' (99.99 ' . esc_attr( $currency_symbol ) . ')',
					),
				),
				array(
					'title' => esc_html__( 'Thousand Separator', 'realty-portal' ),
					'name'  => 'price_thousand_sep',
					'type'  => 'text',
					'std'   => ',',
				),
				array(
					'title' => esc_html__( 'Decimal Separator', 'realty-portal' ),
					'name'  => 'price_decimal_sep',
					'type'  => 'text',
					'std'   => '.',
				),
				array(
					'title' => esc_html__( 'Number of Decimals', 'realty-portal' ),
					'name'  => 'price_num_decimals',
					'type'  => 'text',
					'std'   => '0',
				),
				array(
					'name' => 'property_linebreak',
					'type' => 'line',
				),
				array(
					'is_section' => true,
					'title'      => esc_html__( 'Property Submit', 'realty-portal' ),
				),
				array(
					'title' => esc_html__( 'Roles don\'t need approval', 'realty-portal' ),
					'name'  => 'skip_role',
					'type'  => 'text',
					'std'   => 'administrator',
				),
			),
		) );
	}

	add_action( 'RP_Tab_Setting_Content/Config_After', 'rp_form_property_setting', 5 );

endif;