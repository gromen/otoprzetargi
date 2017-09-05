<?php

/**
 * Properties Factory
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
class RP_Properties_Factory {

	/**
	 * The product (post) ID.
	 *
	 * @var int
	 */
	public $ID = 0;

	/**
	 * $post Stores post data.
	 *
	 * @var $post WP_Post
	 */
	public $post = null;

	/**
	 * $property_info Stores property data.
	 *
	 * @var $property_info WP_Post
	 */
	public $property = null;

	public function __construct( $property ) {
		if ( is_numeric( $property ) ) {
			$this->ID   = absint( $property );
			$this->post = get_post( $this->ID );
		} elseif ( $property instanceof RP_Properties_Factory ) {
			$this->ID   = absint( $property->ID );
			$this->post = $property->post;
		} elseif ( isset( $property->ID ) ) {
			$this->ID   = absint( $property->ID );
			$this->post = $property;
		}

		$this->property = $this->get_property();
	}

	public function get_property() {

		$property_info = new stdClass();

		return $property_info;
	}

	public function title() {
		return get_the_title( $this->ID );
	}

	public function permalink() {
		return get_permalink( $this->ID );
	}

	public function edit() {

		$url_page_submit = RP_AddOn_Submit_Property::get_url_submit_property();

		if ( ! empty( $url_page_submit ) ) {
			$url_page_submit = $url_page_submit . '?edit-property=' . $this->ID;
		} else {
			$url_page_submit = get_edit_post_link( $this->ID );
		}

		return $url_page_submit;
	}

	public function thumbnail( $size = 'rp-property-medium', $echo = true, $default = '150x150' ) {
		$thumbnail_url = rp_thumb_src( $this->ID, $size, $default );
		if ( $echo ) {
			return '<img class="rp-thumbnail" src="' . $thumbnail_url . '" alt="' . $this->title() . '" />';
		}

		return $thumbnail_url;
	}

	public function get_status() {
		return $this->post->post_status;
	}

	public function get_status_html() {
		$html = array();
		switch ( $this->get_status() ) {
			case 'expired':
				$html[] = '<span class="status expired">';
				$html[] = esc_html__( 'Expired', 'realty-portal' );
				$html[] = '</span>';
				break;
			case 'pending':
				$html[] = '<span class="status wait-approval">';
				$html[] = esc_html__( 'Waiting for Approval', 'realty-portal' );
				$html[] = '</span>';
				break;
			default:
				$html[] = '<span class="status active">';
				$html[] = esc_html__( 'Active', 'realty-portal' );
				$html[] = '</span>';
				break;
		}

		return implode( "\n", $html );
	}

	public function get_content( $number = 20 ) {
		return wp_trim_words( rp_format_content( $this->post->post_content ), $number );
	}

	public function address( $echo = true ) {
		$address = get_post_meta( $this->ID, 'address', true );
		if ( ! empty( $address ) ) {
			if ( $echo ) {
				return '<address><i class="rp-icon-map-marker" aria-hidden="true"></i> ' . $address . '</address>';
			}

			return $address;
		}

		return '';
	}

	public function get_list_photo( $show = false ) {
		$property_photo = get_post_meta( $this->ID, 'property_photo', true );

		if ( 'total' == $show ) {
			$html   = array();
			$html[] = '<span class="rp-photo">';
			$html[] = '<i class="rp-icon-camera" aria-hidden="true"></i> ';
			$html[] = count( explode( ',', $property_photo ) );
			$html[] = '</span>';

			return apply_filters( 'rp_get_list_photo_html', implode( ' ', $html ) );
		}

		return apply_filters( 'rp_get_list_photo', explode( ',', $property_photo ) );
	}

	public function is_featured( $echo = true ) {
		$featured = get_post_meta( $this->ID, '_featured', true );
		if ( 'yes' == $featured ) {
			if ( $echo ) {
				$html_content = '<span class="property-featured">' . esc_html__( 'Featured', 'realty-portal' ) . '</span>';

				return apply_filters( 'rp_property_is_featured_html', $html_content );
			}

			return true;
		}

		return false;
	}

	public function listing_offers( $echo = true ) {
		$listing_offers = get_the_terms( $this->ID, apply_filters( 'rp_property_listing_offers', 'listing_offers' ) );

		if ( ! empty( $listing_offers ) && ! is_wp_error( $listing_offers ) ) {
			$types = array();
			foreach ( $listing_offers as $status ) {
				if ( $echo ) {
					$color = get_term_meta( $status->term_id, 'color', true );

					if ( empty( $color ) ) {
						$color = '#27ae60';
					}
					$types[] = '<span class="property-offers-item" style="background: ' . $color . '">' . $status->name . '</span>';
				} else {

					$types[] = $status->name;
				}
			}
			if ( $echo ) {

				return '<div class="property-offers">' . apply_filters( 'rp_listing_offers_html', implode( "\n", $types ) ) . '</div>';
			}

			return $types;
		}

		return false;
	}

	public function get_list_field_meta() {
		$primary_field_1      = rp_get_data_field( $this->ID, RP_Property::get_setting( 'primary_field', 'primary_field_1', '_area' ) );
		$primary_field_icon_1 = rp_get_data_field_icon( RP_Property::get_setting( 'primary_field', 'primary_field_icon_1', 'rp-icon-ruler' ) );

		$primary_field_2      = rp_get_data_field( $this->ID, RP_Property::get_setting( 'primary_field', 'primary_field_2', '_bedrooms' ) );
		$primary_field_icon_2 = rp_get_data_field_icon( RP_Property::get_setting( 'primary_field', 'primary_field_icon_2', 'rp-icon-bed' ) );

		$primary_field_3      = rp_get_data_field( $this->ID, RP_Property::get_setting( 'primary_field', 'primary_field_3', '_garages' ) );
		$primary_field_icon_3 = rp_get_data_field_icon( RP_Property::get_setting( 'primary_field', 'primary_field_icon_3', 'rp-icon-garage' ) );

		$primary_field_4      = rp_get_data_field( $this->ID, RP_Property::get_setting( 'primary_field', 'primary_field_4', '_bathrooms' ) );
		$primary_field_icon_4 = rp_get_data_field_icon( RP_Property::get_setting( 'primary_field', 'primary_field_icon_4', 'rp-icon-bath' ) );
		if ( ! empty( $primary_field_1 ) ) : ?>
			<span class="rp-primary-file-1">
                <?php echo wp_kses( $primary_field_icon_1, rp_allowed_html() ); ?>
				<span><?php echo wp_kses( $primary_field_1, rp_allowed_html() ); ?></span>
            </span>
		<?php endif; ?>
		<?php if ( ! empty( $primary_field_2 ) ) : ?>
			<span class="rp-primary-file-2">
                <?php echo wp_kses( $primary_field_icon_2, rp_allowed_html() ); ?>
				<span><?php echo wp_kses( $primary_field_2, rp_allowed_html() ); ?></span>
            </span>
		<?php endif; ?>
		<?php if ( ! empty( $primary_field_3 ) ) : ?>
			<span class="rp-primary-file-3">
                <?php echo wp_kses( $primary_field_icon_3, rp_allowed_html() ); ?>
				<span><?php echo wp_kses( $primary_field_3, rp_allowed_html() ); ?></span>
            </span>
		<?php endif; ?>
		<?php if ( ! empty( $primary_field_4 ) ) : ?>
			<span class="rp-primary-file-4">
                <?php echo wp_kses( $primary_field_icon_4, rp_allowed_html() ); ?>
				<span><?php echo wp_kses( $primary_field_4, rp_allowed_html() ); ?></span>
            </span>
		<?php endif;
	}

	public function agent_info( $show = 'id' ) {

		switch ( $show ) {
			case 'name':
				$data_agent = get_the_title( $this->agent_info() );
				break;

			case 'url':
				$data_agent = get_permalink( $this->agent_info() );
				break;

			case 'total_property':
				$args_property  = array(
					'post_type'    => apply_filters( 'rp_property_post_type', 'rp_property' ),
					'post_status'  => 'publish',
					'meta_key'     => 'agent_responsible',
					'meta_value'   => $this->agent_info(),
					'meta_compare' => '=',
				);
				$query_property = new WP_Query( $args_property );
				$data_agent     = $query_property->found_posts;
				break;

			case 'position':
				$data_agent = get_post_meta( $this->agent_info(), 'rp_agent_position', true );
				break;

			case 'phone':
				$data_agent = get_post_meta( $this->agent_info(), 'rp_agent_phone', true );
				break;

			case 'mobile':
				$data_agent = get_post_meta( $this->agent_info(), 'rp_agent_mobile', true );
				break;

			case 'mail':
				$data_agent = get_post_meta( $this->agent_info(), 'rp_agent_mail', true );
				break;

			case 'about':
				$data_agent = get_post_meta( $this->agent_info(), 'rp_agent_about', true );
				break;

			case 'avatar' :
				$id_avatar  = get_post_thumbnail_id( $this->agent_info() );
				$data_agent = rp_thumb_src_id( $id_avatar, 'thumbnail', '150x150' );
				break;

			default:
				$data_agent = intval( get_post_meta( $this->ID, 'agent_responsible', true ) );
				break;
		}

		return $data_agent;
	}

	public function agent_avatar( $size = '475x550', $echo = true ) {
		$id_avatar = get_post_thumbnail_id( $this->agent_info() );
		$avatar    = rp_thumb_src_id( $id_avatar, 'rp-agent-avatar-medium', $size );
		if ( $echo ) {
			$html = apply_filters( 'rp_property_avatar_agent', '<img src="' . $avatar . '" alt="' . $this->agent_info( 'name' ) . '" />' );

			return $html;
		}

		return $avatar;
	}

	public function agent_custom_field() {
		$agent_custom_field = rp_agent_render_fields();
		if ( array_key_exists( 'social_network', $agent_custom_field ) ) {
			unset( $agent_custom_field[ '' ] );
			unset( $agent_custom_field[ 'social_network' ] );
			unset( $agent_custom_field[ '_position' ] );
			unset( $agent_custom_field[ '_about' ] );
		}

		return $agent_custom_field;
	}

	public function get_listing_offers() {

		$listing_offers = get_the_terms( $this->ID, apply_filters( 'rp_property_listing_offers', 'listing_offers' ) );

		if ( ! empty( $listing_offers ) && ! is_wp_error( $listing_offers ) ) {

			return apply_filters( 'rp_get_listing_offers', $listing_offers );
		}

		return array();
	}

	public function get_listing_offers_html( $prefix = '' ) {

		$listing_offers = $this->get_listing_offers();

		$html = array();

		if ( ! empty( $listing_offers ) ) {
			$list_status = array();
			foreach ( $listing_offers as $status ) {
				$list_status[] = sprintf( '<a href="%1$s" title="%2$s">%2$s</a>', esc_url( get_term_link( $status->slug, apply_filters( 'rp_property_listing_offers', 'listing_offers' ) ) ), esc_html( $status->name ) );
			}
			$html[] = '<div class="rp-content-box-item">';
			$html[] = apply_filters( 'rp_get_listing_offers_html_prefix', $prefix );
			$html[] = '<span>' . implode( ', ', $list_status ) . '</span>';
			$html[] = '</div>';
		}

		return apply_filters( 'rp_get_listing_offers_html', implode( ' ', $html ) );
	}

	public function get_listing_type() {

		$listing_type = get_the_terms( $this->ID, apply_filters( 'rp_property_listing_type', 'listing_type' ) );

		if ( ! empty( $listing_type ) && ! is_wp_error( $listing_type ) ) {

			return apply_filters( 'rp_get_listing_type', $listing_type );
		}

		return array();
	}

	public function get_listing_type_html( $prefix = '' ) {

		$listing_type = $this->get_listing_type();

		$html = array();

		if ( ! empty( $listing_type ) ) {
			$list_type = array();
			foreach ( $listing_type as $type ) {
				$list_type[] = sprintf( '<a href="%1$s" title="%2$s">%2$s</a>', esc_url( get_term_link( $type->slug, apply_filters( 'rp_property_listing_type', 'listing_type' ) ) ), esc_html( $type->name ) );
			}
			$html[] = '<div class="rp-content-box-item">';
			$html[] = '<span>' . implode( ', ', $list_type ) . '</span>';
			$html[] = '</div>';
		}

		return apply_filters( 'rp_get_listing_type_html', implode( ' ', $html ) );
	}

	public function get_price() {

		$price = trim( get_post_meta( $this->ID, 'price', true ) );
		$price = ( preg_match( "/^([0-9]+)$/", $price ) ) ? rp_format_price( $price ) : esc_html( $price );

		return apply_filters( 'rp_get_price', $price );
	}

	public function get_price_html() {

		$before_price = '<div class="property-price"><span class="before-price">' . esc_html( get_post_meta( $this->ID, 'before_price', true ) ) . '</span>';
		$before_price = apply_filters( 'rp_before_get_price_html', $before_price );

		$after_price = '<span class="after-price">' . esc_html( get_post_meta( $this->ID, 'after_price', true ) ) . '</span></div>';
		$after_price = apply_filters( 'rp_after_get_price_html', $after_price );

		return apply_filters( 'rp_get_price_html', $before_price . ' ' . $this->get_price() . ' ' . $after_price );
	}

}