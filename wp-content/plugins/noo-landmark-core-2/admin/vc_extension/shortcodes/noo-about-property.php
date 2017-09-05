<?php
/**
 * Shortcode Visual: Noo About Property
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( ! function_exists( 'noo_shortcode_about_property' ) ) :
    
    function noo_shortcode_about_property( $atts ){

        extract( shortcode_atts(array(
            'title'       =>  '',
            'description' =>  '',
            'box'         => '',
        ), $atts ) );

        ob_start();
        $box = vc_param_group_parse_atts( $box );
        ?>
        <div class="noo-about-property">
            <div class="noo-about-property-header">
            
                <?php
                /**
                 * Check title
                 */
                if ( !empty( $title ) ) {
                    echo '<h3 class="noo-title-header">';
                    echo    esc_html( $title );
                    echo '</h3>';
                }
                ?>

                <?php
                /**
                 * Check description
                 */
                if ( !empty( $description ) ) {
                    echo '<p class="noo-description">';
                    echo    esc_html( $description );
                    echo '</p>';
                }
                ?>
            </div>

            <?php
            /**
             * Check content box
             */
            $class = 'noo-md-3';
            if ( !empty( $box ) && is_array( $box ) ) : ?>

                <div class="noo-about-property-content noo-row">

                    <?php foreach( $box as $item ): ?>

                        <div class="noo-about-property-item <?php echo esc_attr( $class ) ?>">
                            <?php 
                            /**
                             * Check box icon
                             */
                            if ( !empty( $item['icon'] ) ) {
                                echo '<div class="noo-icon">';
                                echo '  <i class="' . esc_attr( $item['icon'] ) . '"></i>';
                                echo '</div>';
                            }

                            echo '<div class="noo-content">';
                                if( !empty( $item['box_title'] ) ) {
                                    echo '<h4 class="noo-content-title">' . esc_html( $item['box_title'] ) . '</h4>';
                                }
                                if( !empty( $item['box_content'] ) ) {
                                    echo '<p>' . esc_html( $item['box_content'] ) . '</p>';
                                }
                            echo '</div>';
                            ?>
                        </div>

                    <?php endforeach; ?>

                </div><!-- End /.noo-about-property-content -->

            <?php endif; ?>

        </div><!-- /.noo-about-property -->

        <?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode( 'noo_about_property', 'noo_shortcode_about_property' );

endif;