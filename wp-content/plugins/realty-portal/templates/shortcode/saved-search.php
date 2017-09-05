<?php
/**
 * Show content main shortcode saved search
 *
 * This template can be overridden by copying it to yourtheme/realty-portal/shortcode/saved-search.php.
 *
 * HOWEVER, on occasion NooTheme will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author        NooTheme
 * @version       0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php if ( is_wp_error( RP_Agent::can_view() ) ) : ?>

	<?php
	/**
	 * rp_error_main_saved_search hook.
	 *
	 * @hooked rp_message_notices - 5
	 */
	do_action( 'rp_error_main_saved_search', RP_Agent::can_view() );
	?>

<?php else : ?>

	<?php
	/**
	 * rp_before_main_saved_search hook.
	 *
	 */
	do_action( 'rp_before_main_saved_search' );
	$list_search = get_user_meta( RP_Agent::is_user(), 'list_search', true );

	?>

    <div class="rp-saved-search">

		<?php
		if ( ! empty( $list_search ) && is_array( $list_search ) ) {
			foreach ( $list_search as $position_item => $search_item ) {
				RP_Template::get_template( 'saved-search/search-content.php', array(
					'search_item'   => $search_item,
					'position_item' => $position_item + 1,
				) );
			}
		} else {
			echo sprintf( '<div class="rp-saved-search-item not_found">%s</div>', esc_html__( "You don't have any saved search", 'realty-portal' ) );
		}
		?>

    </div>

	<?php
	if ( ! empty( $search_item ) && is_array( $search_item ) ) {

		$search_parameters = $min_price = $max_price = $min_area = $max_area = '';
		if ( isset( $search_item[ 'types' ] ) && ! empty( $search_item[ 'types' ] ) ) {
			$types = get_term_by( 'id', absint( $search_item[ 'types' ] ), apply_filters( 'rp_property_listing_type', 'listing_type' ) );
			if ( ! is_wp_error( $types ) ) {
				$search_parameters .= $types->name . ', ';
			}
		}
		if ( isset( $search_item[ 'rp_property_bedrooms' ] ) && ! empty( $search_item[ 'rp_property_bedrooms' ] ) ) {
			$search_parameters .= $search_item[ 'rp_property_bedrooms' ] . ' ' . esc_html__( 'Bedrooms', 'realty-portal' ) . ', ';
		}
		if ( isset( $search_item[ 'rp_property_bathrooms' ] ) && ! empty( $search_item[ 'rp_property_bathrooms' ] ) ) {
			$search_parameters .= $search_item[ 'rp_property_bathrooms' ] . ' ' . esc_html__( 'Bathrooms', 'realty-portal' ) . ', ';
		}
		if ( isset( $search_item[ 'offers' ] ) && ! empty( $search_item[ 'offers' ] ) ) {
			$offers = get_term_by( 'id', absint( $search_item[ 'offers' ] ), apply_filters( 'rp_property_listing_offers', 'listing_offers' ) );
			if ( ! is_wp_error( $offers ) ) {
				$search_parameters .= $offers->name . ', ';
			}
		}
		if ( isset( $search_item[ 'location' ] ) && ! empty( $search_item[ 'location' ] ) ) {
			$search_parameters .= esc_html__( 'in', 'realty-portal' ) . ' ' . $search_item[ 'location' ] . ', ';
		}
		if ( isset( $search_item[ 'rp_property_area' ] ) && ! empty( $search_item[ 'rp_property_area' ] ) ) {
			$search_parameters .= $search_item[ 'rp_property_area' ] . ', ';
		}
		if ( isset( $search_item[ 'keyword' ] ) && ! empty( $search_item[ 'keyword' ] ) ) {
			$search_parameters .= $search_item[ 'keyword' ] . ', ';
		}
		if ( isset( $search_item[ 'min_price' ] ) && ! empty( $search_item[ 'min_price' ] ) ) {
			$min_price = $search_item[ 'min_price' ];
		}
		if ( isset( $search_item[ 'max_price' ] ) && ! empty( $search_item[ 'max_price' ] ) ) {
			$max_price = $search_item[ 'max_price' ];
		}
		if ( isset( $search_item[ 'min_area' ] ) && ! empty( $search_item[ 'min_area' ] ) ) {
			$min_area = $search_item[ 'min_area' ];
		}
		if ( isset( $search_item[ 'max_area' ] ) && ! empty( $search_item[ 'max_area' ] ) ) {
			$max_area = $search_item[ 'max_area' ];
		}

		if ( ! empty( $min_price ) && ! empty( $max_price ) ) {
			$search_parameters .= esc_html__( 'From', 'realty-portal' ) . ' ' . rp_format_price( $min_price ) . ' ' . esc_html__( 'to', 'realty-portal' ) . ' ' . rp_format_price( $max_price ) . ', ';
		}
		if ( ! empty( $min_area ) && ! empty( $max_area ) ) {
			$search_parameters .= esc_html__( 'Area', 'realty-portal' ) . ' ' . rp_format_area( $min_area ) . ' ' . esc_html__( 'to', 'realty-portal' ) . ' ' . rp_format_area( $max_area );
		}
		if ( ! empty( $position_item ) ) :
			?>
            <div class="rp-saved-search-item">
                <div class="remove-search" data-position_item="<?php echo esc_attr( $position_item ); ?>">
                    <i class="rp-icon-remove"></i>
                </div>
                <div class="rp-content">
                    <h3>
						<?php echo esc_html__( 'Search Parameters:', 'realty-portal' ); ?>
                    </h3>
                    <div class="content">
						<?php echo wp_kses( $search_parameters, rp_allowed_html() ); ?>
                    </div>
                </div>
                <div class="rp-action">
                    <button class="rp-button"
                            onclick="window.location.href='<?php echo esc_url( add_query_arg( $search_item, RP_Member::get_url_search() ) ) ?>'"><?php echo esc_html__( 'Search', 'realty-portal' ); ?></button>
                </div>
            </div>
		<?php else : ?>
            <div class="rp-box-results-search">
                <div class="text">
                    <i class="rp-icon-ion-ios-search-strong"></i>
					<?php echo wp_kses( $search_parameters, rp_allowed_html() ); ?>
                </div>
                <span class="save" data-search='<?php echo json_encode( $search_item ); ?>'>
	            		<i class="rp-icon-ion-ios-download-outline"></i>
					<?php echo esc_html__( 'Save', 'realty-portal' ); ?>
	            	</span>
            </div>
			<?php
		endif;
	}
	?>

	<?php
	/**
	 * rp_after_main_saved_search hook.
	 *
	 */
	do_action( 'rp_after_main_saved_search' );
	?>

<?php endif; ?>