<?php
if ( ! function_exists( 'rp_tab_setting' ) ) :

	/**
	 * Create tab setting
	 *
	 * @param array $fields
	 */
	function rp_tab_setting( $fields = array() ) {

		$fields = apply_filters( 'rp_tab_setting', $fields );

		/**
		 * Reorder list field by position
		 */
		$position = array();
		foreach ( $fields as $key => $row ) {
			$position[ $key ] = $row[ 'position' ];
		}
		array_multisort( $position, SORT_ASC, $fields );

		/**
		 * Show field
		 */
		echo '<div class="rp-tab-setting">';
		foreach ( $fields as $field ) {

			if ( ! empty( $field[ 'name' ] ) ) {

				$id = ( ! empty( $field[ 'id' ] ) ? ' data-id=' . esc_attr( $field[ 'id' ] ) : '' );

				echo '<span class="rp-tab-item ' . ( ! empty( $field[ 'class' ] ) ? esc_attr( $field[ 'class' ] ) : '' ) . '"' . esc_html( $id ) . '>';
				echo esc_html( $field[ 'name' ] );
				echo '</span>';
			}
		}
		echo '</div><!-- /.rp-tab-setting -->';
	}

endif;

if ( ! function_exists( 'rp_get_list_tax' ) ) :

	/**
	 * Get list taxonomy
	 *
	 * @param string $name_tax
	 * @param bool   $reverse
	 * @param string $option_null
	 *
	 * @return array|void
	 */
	function rp_get_list_tax( $name_tax = '', $reverse = false, $option_null = '' ) {

		if ( empty( $name_tax ) ) {
			return;
		}

		$list_tax = array();
		$data_tax = (array) get_terms( esc_attr( $name_tax ), array(
			'orderby'    => 'title',
			'hide_empty' => 0,
		) );

		if ( isset( $data_tax ) && ! empty( $data_tax ) ) {

			if ( ! empty( $option_null ) ) {
				if ( $reverse ) {
					$list_tax[ $option_null ] = '';
				} else {
					$list_tax[ '' ] = $option_null;
				}
			}

			foreach ( $data_tax as $tax ) {
			    if ( empty( $tax->name ) ) {
			        continue;
                }
				if ( $reverse ) {
					$list_tax[ $tax->name ] = $tax->term_id;
				} else {
					$list_tax[ $tax->term_id ] = $tax->name;
				}
			}
		}

		return $list_tax;
	}

endif;

if ( ! function_exists( 'rp_social_sharing_property' ) ) :

	/**
	 * Get list social sharing
	 *
	 * @return string
	 */
	function rp_social_sharing_property() {

		$share_url     = urlencode( get_permalink() );
		$share_title   = urlencode( get_the_title() );
		$share_source  = urlencode( get_bloginfo( 'name' ) );
		$share_content = urlencode( get_the_content() );
		$share_media   = wp_get_attachment_thumb_url( get_post_thumbnail_id() );
		$popup_attr    = 'resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0';

		$html = array();

		$html[] = '<div class="rp-social-property">';

		if ( apply_filters( 'rp_social_sharing_property_facebook', true ) ) {
			$html[] = '<a href="#share" data-toggle="tooltip" data-placement="bottom" data-trigger="hover" class="rp-share"' . ' title="' . esc_html__( 'Share on Facebook', 'realty-portal' ) . '"' . ' onclick="window.open(' . "'http://www.facebook.com/sharer.php?u={$share_url}&amp;t={$share_title}','popupFacebook','width=650,height=270,{$popup_attr}');" . ' return false;">';
			$html[] = '<span><i class="rp-icon-facebook"></i></span>';
			$html[] = '</a>';
		}

		if ( apply_filters( 'rp_social_sharing_property_twitter', true ) ) {
			$html[] = '<a href="#share" class="rp-share"' . ' title="' . esc_html__( 'Share on Twitter', 'realty-portal' ) . '"' . ' onclick="window.open(' . "'https://twitter.com/intent/tweet?text={$share_title}&amp;url={$share_url}','popupTwitter','width=500,height=370,{$popup_attr}');" . ' return false;">';
			$html[] = '<span><i class="rp-icon-twitter"></i></span></a>';
		}

		if ( apply_filters( 'rp_social_sharing_property_google', true ) ) {
			$html[] = '<a href="#share" class="rp-share"' . ' title="' . esc_html__( 'Share on Google+', 'realty-portal' ) . '"' . ' onclick="window.open(' . "'https://plus.google.com/share?url={$share_url}','popupGooglePlus','width=650,height=226,{$popup_attr}');" . ' return false;">';
			$html[] = '<span><i class="rp-icon-google"></i></span></a>';
		}

		if ( apply_filters( 'rp_social_sharing_property_pinterest', true ) ) {
			$html[] = '<a href="#share" class="rp-share"' . ' title="' . esc_html__( 'Share on Pinterest', 'realty-portal' ) . '"' . ' onclick="window.open(' . "'http://pinterest.com/pin/create/button/?url={$share_url}&amp;media={$share_media}&amp;description={$share_title}','popupPinterest','width=750,height=265,{$popup_attr}');" . ' return false;">';
			$html[] = '<span><i class="rp-icon-pinterest"></i></span></a>';
		}

		if ( apply_filters( 'rp_social_sharing_property_linkedin', true ) ) {
			$html[] = '<a href="#share" class="rp-share"' . ' title="' . esc_html__( 'Share on LinkedIn', 'realty-portal' ) . '"' . ' onclick="window.open(' . "'http://www.linkedin.com/shareArticle?mini=true&amp;url={$share_url}&amp;title={$share_title}&amp;summary={$share_content}&amp;source={$share_source}','popupLinkedIn','width=610,height=480,{$popup_attr}');" . ' return false;">';
			$html[] = '<span><i class="rp-icon-linkedin"></i></span></a>';
		}

		$html[] = '</div>';

		echo implode( "\n", $html );
	}

endif;

if ( ! function_exists( 'rp_conver' ) ) :

	/**
	 * Conver field select by value
	 *
	 * @param string $key_value
	 * @param string $list
	 *
	 * @return bool|mixed
	 */
	function rp_conver( $key_value = '', $list = '' ) {
		$list_value  = explode( "\n", $list );
		$list_values = array();
		foreach ( $list_value as $key ) {
			$list_values[ sanitize_title( $key ) ] = $key;
		}
		if ( ! empty( $list_values[ $key_value ] ) ) {
			return $list_values[ $key_value ];
		}

		return false;
	}

endif;