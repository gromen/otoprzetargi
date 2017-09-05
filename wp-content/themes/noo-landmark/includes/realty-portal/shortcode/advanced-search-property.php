<?php
/**
 * Shortcode Visual: Advanced Search Property
 * Function show box find property
 *
 * @package     Noo Library
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( !function_exists( 'noo_shortcode_advanced_search_property' ) ) :

    function noo_shortcode_advanced_search_property( $atts ) {

        $list_option = array(
            'source'                => 'property',
            'show_map'              => 'yes',
            'show_controls'         => 'yes',
            'style'                 => 'style-1',
            'latitude'              => Realty_Portal::get_setting( 'google_map', 'latitude', '40.714398' ),
            'longitude'             => Realty_Portal::get_setting( 'google_map', 'longitude', '-74.005279' ),
            'zoom'                  => Realty_Portal::get_setting( 'google_map', 'zoom', '17' ),
            'height'                => Realty_Portal::get_setting( 'google_map', 'map_height', '800' ),
            'drag_map'              => 'true',
            'fitbounds'             => 'true',
            'disable_auto_complete' => Realty_Portal::get_setting( 'google_map', 'disable_auto_complete', false ),
            'country_restriction'   => Realty_Portal::get_setting( 'google_map', 'country_restriction', 'all' ),
            'location_type'         => Realty_Portal::get_setting( 'google_map', 'location_type', 'geocode' ),
            'title'                 => esc_html__( 'Find Property', 'noo-landmark' ),
            'sub_title'             => '',
            'option_1'              => Realty_Portal::get_setting( 'advanced_search', 'option_1', 'keyword' ),
            'option_2'              => Realty_Portal::get_setting( 'advanced_search', 'option_2', 'property_status' ),
            'option_3'              => Realty_Portal::get_setting( 'advanced_search', 'option_3', 'property_type' ),
            'option_4'              => Realty_Portal::get_setting( 'advanced_search', 'option_4', 'city' ),
            'option_5'              => Realty_Portal::get_setting( 'advanced_search', 'option_5', '_bedrooms' ),
            'option_6'              => Realty_Portal::get_setting( 'advanced_search', 'option_6', '_bathrooms' ),
            'option_7'              => Realty_Portal::get_setting( 'advanced_search', 'option_7', '_garages' ),
            'option_8'              => Realty_Portal::get_setting( 'advanced_search', 'option_8', 'price' ),
            'show_features'         => Realty_Portal::get_setting( 'advanced_search', 'show_features', 'true' ),
            'text_show_features'    => esc_html__( 'More Filters', 'noo-landmark' ),
            'text_button_search'    => esc_html__( 'Search Property', 'noo-landmark' ),
        );
        extract( shortcode_atts( $list_option, $atts ) );

        /**
         * VAR
         */
            $url_page_property_search = noo_get_url_page_search_template();

            $class_form = '';
            if ( $show_map === 'yes' ) {
                wp_enqueue_script( 'google-map-search-property' );
                $class_form = 'noo-box-map ' . $style;
            }

        ob_start(); ?>
        <div class="noo-advanced-search-property">
            <?php
                /**
                 * Call title first word
                 */
                if ( $show_map === 'no' ) {
                    noo_title_first_word( $title, $sub_title );
                }
            ?>
            <?php
            /**
             * Check if source is property
             */
            if ( $source === 'property' ) : ?>
                <form class="noo-advanced-search-property-form <?php echo esc_attr( $class_form ) ?>" action="<?php echo esc_url( $url_page_property_search ) ?>" method="get" accept-charset="utf-8">
                    <?php
                    /**
                     * Show boxx find address if choose style 3
                     */
                    if ( $style === 'style-3' ) : ?>
                    <div class="noo-advanced-search-property-top-wrap">
                        <div class="noo-advanced-search-property-top">
                            <?php
                            /**
                             * Create box address
                             */
                            $args_address = array(
                                'name'        => 'address_map',
                                'title'       => '',
                                'type'        => 'text',
                                'placeholder' => esc_html__( 'Enter an address, town, street, or zip ...', 'noo-landmark' ),
                                'class'       => 'noo-address-map noo-input-map',
                            );
                            rp_create_element( $args_address, '' );
                            ?>
                            <button type="submit">
                                <span class="ion-search"></span>
                                <?php echo esc_html__( 'Search', 'noo-landmark' ); ?>
                            </button>
        
                        </div><!-- /.noo-advanced-search-property-top -->
                    </div><!-- /.noo-advanced-search-property-top-wrap -->
                    <?php endif; ?>

                    <?php
                        /**
                         * Show google map
                         */
                        if ( $show_map === 'yes' ) {
                            $id_map           = uniqid( 'id-map' );
                            $background_map   = Realty_Portal::get_setting( 'google_map', 'background_map', '' );
                            $background_map   = ( !empty( $background_map ) ? noo_thumb_src_id( $background_map, 'full' ) : '' );
                            $background_style = '';
                            if( !empty( $background_map ) ) {
                                $background_style = ' background: url(' . esc_url_raw( $background_map ) . ') repeat-x scroll 0 center transparent;';
                            }
                            echo '<div
                                    class="noo-search-map"
                                    style="height: ' . esc_attr( $height ) . 'px; ' . $background_style . '"
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
                                    <div class="gmap-loading"><?php echo esc_html__( 'Loading Maps', 'noo-landmark' ); ?>
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
                                            <i class="fa fa-picture-o"></i>
                                            <?php echo esc_html__( 'View', 'noo-landmark' ); ?>
                                            <span class="map-view-type">
                                                <span data-type="roadmap">
                                                    <?php echo esc_html__( 'Roadmap', 'noo-landmark' ); ?>
                                                </span>
                                                <span data-type="satellite">
                                                    <?php echo esc_html__( 'Satellite', 'noo-landmark' ); ?>
                                                </span>
                                                <span data-type="hybrid">
                                                    <?php echo esc_html__( 'Hybrid', 'noo-landmark' ); ?>
                                                </span>
                                                <span data-type="terrain">
                                                    <?php echo esc_html__( 'Terrain', 'noo-landmark' ); ?>
                                                </span>
                                            </span>
                                        </div>
                                        <div class="my-location" id="<?php echo esc_attr( uniqid( 'my-location-' ) ) ?>">
                                            <i class="fa fa-map-marker"></i>
                                            <?php echo esc_html__( 'My Location', 'noo-landmark' ); ?>
                                        </div>
                                        <div class="gmap-full">
                                            <i class="fa fa-expand"></i> 
                                            <?php echo esc_html__( 'Fullscreen', 'noo-landmark' ); ?>
                                        </div>
                                        <div class="gmap-prev">
                                            <i class="fa fa-chevron-left"></i> 
                                            <?php echo esc_html__( 'Prev', 'noo-landmark' ); ?>
                                        </div>
                                        <div class="gmap-next">
                                            <?php echo esc_html__( 'Next', 'noo-landmark' ); ?>
                                            <i class="fa fa-chevron-right"></i>
                                        </div>
                                    </div>
                                    <div class="gmap-zoom">
                                        <span class="zoom-in" id="<?php echo esc_attr( uniqid( 'zoom-in-' ) ) ?>">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        <span class="miniature" id="<?php echo esc_attr( uniqid( 'miniature-' ) ) ?>">
                                            <i class="fa fa-minus"></i>
                                        </span>
                                    </div>
                                    <div class="box-search-map">
                                        <input type="text" id="gmap_search_input" name="find-address-map" placeholder="<?php echo esc_html__( 'Google Maps Search', 'noo-landmark' ); ?>"  autocomplete="off" />
                                    </div>
                                </div>
                                <?php
                        }
                    ?>

                    <?php
                    /**
                     * If show style 3 then hide form
                     */
                    if ( $style !== 'style-3' ) : ?>
                    <div class="noo-advanced-search-property-wrap">
                        <div class="noo-action-search-top">
                            <?php
                                if ( $show_map === 'yes' ) {
                                    echo '<div class="noo-label-form">' . esc_html( $title ) . '</div>';
                                }
                            ?>
                            <button type="submit" class="show-filter-property">
                                <?php echo wp_kses( __( 'We found <b>0</b> results. Do you want to load the results now?', 'noo-landmark' ), noo_allowed_html() ); ?>
                            </button>
                        </div>
                        <div class="noo-row noo-box-field">
                            <?php
                                /**
                                 * Process option
                                 */
                                rp_advanced_search_fields( $option_1, array( 'class' => 'noo-md-3' ) );
                                rp_advanced_search_fields( $option_2, array( 'class' => 'noo-md-3' ) );
                                rp_advanced_search_fields( $option_3, array( 'class' => 'noo-md-3' ) );
                                rp_advanced_search_fields( $option_4, array( 'class' => 'noo-md-3' ) );
                                rp_advanced_search_fields( $option_5, array( 'class' => 'noo-md-3' ) );
                                rp_advanced_search_fields( $option_6, array( 'class' => 'noo-md-3' ) );
                                rp_advanced_search_fields( $option_7, array( 'class' => 'noo-md-3' ) );
                                rp_advanced_search_fields( $option_8, array( 'class' => 'noo-md-3' ) );
                            ?>
                            <?php
                            /**
                             * Check if show feature
                             */
                            if ( empty( $show_features ) || $show_features !== 'true' ) :
                            ?>
                            <div class="noo-md-3 noo-box-button">
                                <button type="submit" class="noo-button">
                                    <span class="ion-search"></span>
                                    <?php echo esc_html( $text_button_search ) ?>
                                </button>
                            </div>
                            <?php endif; ?>
                        </div><!-- /.noo-box-field -->
                        
                        <?php
                        /**
                         * Check if show feature
                         */
                        if ( !empty( $show_features ) && $show_features === 'true' ) :
                        ?>
                        <div class="noo-row noo-box-action">
                            
                            <div class="noo-md-9 box-show-features">
                                <?php
                                    $id_features = uniqid( 'box-features' );
                                    echo '<span class="show-features">' . esc_html( $text_show_features ) . '</span>';
                                ?>
                            </div>
                            <div class="noo-md-3 noo-box-button">
                                <button type="submit" class="noo-button">
                                    <span class="ion-search"></span>
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
                                echo '<div class="noo-row noo-box-features">';
                                    echo '<div id="' . esc_attr( $id_features ) . '" class="noo-md-12 noo-box-features-content">';
                                        $args_property_featured = array(
                                            'name'        => 'property_featured',
                                            'title'       => '',
                                            'type'        => 'property_featured',
                                            'class'       => '',
                                            'class_child' => 'noo-md-3',
                                        );
                                        rp_create_element( $args_property_featured, '' );
                                    echo '</div>';

                                echo '</div>';
                            }
                        ?>

                    </div><!-- /.noo-advanced-search-property-wrap -->
                    <?php endif; ?>
                </form>
            <?php
            /**
             * Check if source is idx
             */
            else : ?>
                <?php
                /**
                 * noo_advanced_search_idx_form hooked
                 */
                do_action( 'noo_advanced_search_idx_form', array_merge( $list_option, array( 'class_form' => $class_form ) ), $atts );
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
                echo '<div class="noo-results-property"></div>';
            }
            ?>
        </div><!-- /.noo-advanced-search-property -->
        <?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode( 'noo_advanced_search_property', 'noo_shortcode_advanced_search_property' );

endif;