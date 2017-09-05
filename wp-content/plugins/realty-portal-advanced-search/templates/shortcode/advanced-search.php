<?php
/**
 * Show content main shortcode advanced search
 *
 * This template can be overridden by copying it to yourtheme/realty-portal/shortcode/advanced-search.php.
 *
 * HOWEVER, on occasion NooTheme will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		NooTheme
 * @version     0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
extract($atts);

$class_form = '';
if ( $show_map === 'yes' ) {
    wp_enqueue_script( 'google-map-search-property' );
    $class_form = 'rp-box-map ' . $style;
}
?>

<div class="rp-advanced-search-property">
    <?php
    /**
     * Check if source is property
     */
    if ( 'property' == $source ) : ?>
        <form class="rp-validate-form rp-advanced-search-property-form <?php echo esc_attr( $class_form ) ?>" action="<?php echo RP_Member::get_url_search(); ?>" method="get" accept-charset="utf-8">
            <?php
                /**
                 * Show google map
                 */
                if ( $show_map === 'yes' ) {
                    $id_map           = uniqid( 'id-map' );
                    $background_map   = RP_Property::get_setting( 'google_map', 'background_map', '' );
                    $background_map   = ( !empty( $background_map ) ? rp_thumb_src_id( $background_map, 'full' ) : '' );
                    $background_style = '';
                    if( !empty( $background_map ) ) {
                        $background_style = ' background: url(' . esc_url_raw( $background_map ) . ') repeat-x scroll 0 center transparent;';
                    }
                    echo '<div
                            class="rp-search-map"
                            style="height: 750px; ' . $background_style . '"
                            id="' . esc_attr( $id_map ) . '"
                            data-source="property"
                            data-id="' . esc_attr( $id_map ) . '"
                            data-latitude="' . esc_attr( $latitude ) . '"
                            data-longitude="' . esc_attr( $longitude ) . '"
                            data-zoom="' . esc_attr( $zoom ) . '"
                            data-drag-map="' . esc_attr( $drag_map ) . '"
                            data-disable_auto_complete="' . esc_attr( $disable_auto_complete ) . '"
                            data-country_restriction="' . esc_attr( $country_restriction ) . '"
                            data-location_type="' . esc_attr( $location_type ) . '"
                            data-fitbounds="' . esc_attr( $fitbounds ) . '">';

                    ?>
                            <div class="gmap-loading"><?php echo esc_html__( 'Loading Maps', 'realty-portal-advanced-search' ); ?>
                                <div class="gmap-loader">
                                    <div class="rect1"></div>
                                    <div class="rect2"></div>
                                    <div class="rect3"></div>
                                    <div class="rect4"></div>
                                    <div class="rect5"></div>
                                </div>
                           </div>

                        </div>

                        <div class="gmap-controls-wrap <?php echo ( $show_controls === 'true' ? ' hidden' : '' ) ?>">
                            <div class="gmap-controls">
                                <div class="map-view">
                                    <i class="rp-icon-picture-o"></i>
                                    <?php echo esc_html__( 'View', 'realty-portal-advanced-search' ); ?>
                                    <span class="map-view-type">
                                        <span data-type="roadmap">
                                            <?php echo esc_html__( 'Roadmap', 'realty-portal-advanced-search' ); ?>
                                        </span>
                                        <span data-type="satellite">
                                            <?php echo esc_html__( 'Satellite', 'realty-portal-advanced-search' ); ?>
                                        </span>
                                        <span data-type="hybrid">
                                            <?php echo esc_html__( 'Hybrid', 'realty-portal-advanced-search' ); ?>
                                        </span>
                                        <span data-type="terrain">
                                            <?php echo esc_html__( 'Terrain', 'realty-portal-advanced-search' ); ?>
                                        </span>
                                    </span>
                                </div>
                                <div class="my-location" id="<?php echo esc_attr( uniqid( 'my-location-' ) ) ?>">
                                    <i class="rp-icon-map-marker"></i>
                                    <?php echo esc_html__( 'My Location', 'realty-portal-advanced-search' ); ?>
                                </div>
                                <div class="gmap-full">
                                    <i class="rp-icon-expand"></i>
                                    <?php echo esc_html__( 'Fullscreen', 'realty-portal-advanced-search' ); ?>
                                </div>
                                <div class="gmap-prev">
                                    <i class="rp-icon-chevron-left"></i>
                                    <?php echo esc_html__( 'Prev', 'realty-portal-advanced-search' ); ?>
                                </div>
                                <div class="gmap-next">
                                    <?php echo esc_html__( 'Next', 'realty-portal-advanced-search' ); ?>
                                    <i class="rp-icon-chevron-right"></i>
                                </div>
                            </div>
                            <div class="gmap-zoom">
                                <span class="zoom-in" id="<?php echo esc_attr( uniqid( 'zoom-in-' ) ) ?>">
                                    <i class="rp-icon-plus"></i>
                                </span>
                                <span class="miniature" id="<?php echo esc_attr( uniqid( 'miniature-' ) ) ?>">
                                    <i class="rp-icon-minus"></i>
                                </span>
                            </div>
                            <div class="box-search-map">
                                <input type="text" id="gmap_search_input" name="find-address-map" placeholder="<?php echo esc_html__( 'Google Maps Search', 'realty-portal-advanced-search' ); ?>"  autocomplete="off" />
                            </div>
                        </div>
                        <?php
                }
            ?>

            <div class="rp-advanced-search-property-wrap">
                <div class="rp-action-search-top">
                    <button type="submit" class="show-filter-property">
                        <?php echo wp_kses( __( 'We found <b>0</b> results. Do you want to load the results now?', 'realty-portal-advanced-search' ), rp_allowed_html() ); ?>
                    </button>
                </div>
                <div class="rp-row rp-box-field">
                    <?php
                        /**
                         * Process option
                         */
                        rp_advanced_search_fields( $option_1 );
                        rp_advanced_search_fields( $option_2 );
                        rp_advanced_search_fields( $option_3 );
                        rp_advanced_search_fields( $option_4 );
                        rp_advanced_search_fields( $option_5 );
                        rp_advanced_search_fields( $option_6 );
                        rp_advanced_search_fields( $option_7 );
                        rp_advanced_search_fields( $option_8 );
                    ?>
                    <?php
                    /**
                     * Check if show feature
                     */
                    if ( empty( $show_features ) || $show_features !== 'true' ) :
                    ?>
                    <div class="rp-md-6 rp-box-button">
                        <button type="submit" class="rp-button">
                            <?php echo esc_html( $text_button_search ) ?>
                        </button>
                    </div>
                    <?php endif; ?>
                </div><!-- /.rp-box-field -->

                <?php
                /**
                 * Check if show feature
                 */
                if ( !empty( $show_features ) && $show_features === 'true' ) :
                ?>
                <div class="rp-row rp-box-action">

                    <div class="rp-md-6 box-show-features">
                        <?php
                            $id_features = uniqid( 'box-features' );
                            echo '<span class="show-features">' . esc_html( $text_show_features ) . '</span>';
                        ?>
                    </div>
                    <div class="rp-md-6 rp-box-button">
                        <button type="submit" class="rp-button">
                            <?php echo esc_html( $text_button_search ) ?>
                        </button>
                    </div>

                </div>
                <?php endif; ?>
                <?php
                    /**
                     * Check if show feature
                     */
                    if ( !empty( $show_features ) && $show_features === 'true' ) {
                        echo '<div class="rp-row rp-box-features">';
                            echo '<div id="' . esc_attr( $id_features ) . '" class="rp-md-12 rp-box-features-content">';
                                $args_property_featured = array(
                                    'name'        => 'property_featured',
                                    'title'       => '',
                                    'type'        => 'property_featured',
                                    'class'       => '',
                                    'class_child' => 'rp-md-3',
                                );
                                rp_create_element( $args_property_featured, '' );
                            echo '</div>';

                        echo '</div>';
                    }
                ?>

            </div><!-- /.rp-advanced-search-property-wrap -->
        </form>
    <?php
    /**
     * Check if source is idx
     */
    else : ?>
        <?php
        /**
         * rp_advanced_search_idx_form hooked
         */
        do_action( 'rp_advanced_search_idx_form', array_merge( $list_option, array( 'class_form' => $class_form ) ), $atts );
        ?>
    <?php
    /**
     * Check if source is property
     */
    endif; ?>

    <?php
    /**
     * Show results
     */
    if ( $show_map === 'yes' ) {
        echo '<div class="rp-results-property"></div>';
    }
    ?>
</div><!-- /.rp-advanced-search-property -->