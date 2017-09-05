<?php
/**
 * Shortcode Visual: Floor Plan
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( !function_exists( 'noo_shortcode_floor_plan' ) ) :

    function noo_shortcode_floor_plan( $atts ) {

        extract( shortcode_atts( array(
            'title'       => '',
            'description' => '',
            'image'       => '',
            'box'         => '',
        ), $atts ) );

        ob_start();

        $box = vc_param_group_parse_atts( $box );
        $id_box = uniqid('noo-box');
        ?>
        <div class="noo-floor-plan">
            <div class="noo-floor-plan-header">
                <?php
                /**
                 * Check title
                 */
                if ( !empty( $title ) ) {
                    echo '<h3 class="noo-title">' . esc_html( $title ) . '</h3>';
                }

                if ( !empty( $description ) ) {
                    echo '<p>' . esc_html( $description ) . '</p>';
                }
                ?>
            </div>
            <div class="noo-floor-plan-skeleton">
                <div class="noo-thumbnail">
                    <img src="<?php echo noo_thumb_src_id( $image, 'full' ) ?>" alt="<?php echo esc_html( $title ) ?>" />
                </div>
                
                <div class="noo-content">
                    <div class="noo-tab">
                        <?php $i = 0; foreach ($box as $box_item) : ?>
                            <span class="<?php echo ( $i === 0 ? ' active' : '' ); ?>" data-class="<?php echo esc_attr( $id_box . $i ) ?>">
                                <?php echo esc_html( $box_item['box_title'] ) ?>
                            </span>
                        <?php $i++; endforeach; ?>  
                    </div>
                    
                    <div class="noo-tab-content">
                        <?php
                            $i = 0; foreach ( $box as $box_item ) :
                            $box_features = vc_param_group_parse_atts( $box_item['box_features'] );
                            
                            echo '<div class="content-tab ' . esc_attr( $id_box . $i ) . ( $i === 0 ? ' show' : '' ) . '">';
                                echo '  <p>' . esc_html( $box_item['box_content'] ) . '</p>';

                                if ( !empty( $box_features ) ) {

                                    echo '<div class="box-features">';
                                    echo '  <label>' . esc_html__( 'Features', 'noo-landmark-core' ) . '</label>';
                                    echo '  <ul>';
                                                foreach ($box_features as $features) {
                                                    if ( !empty( $features['box_features_item'] ) ) {
                                                        echo '<li>' . $features['box_features_item'] . '</li>';
                                                    }
                                                }
                                    echo '  </ul>';
                                    echo '</div>';

                                }

                                if( isset( $box_item['box_link'] ) && !empty( $box_item['box_link'] )){
                                    $link = vc_build_link( $box_item['box_link'] );
                                    echo '<a class="noo-button" href="' . esc_url( $link['url'] ) . '" title="' . esc_html( $link['title'] ) . '">' . esc_html( $link['title'] ) . '</a>';
                                }

                            echo '</div>';

                        $i++; endforeach; ?>  
                    </div>
                </div>
            </div>
        </div><!-- /.noo-floor-plan -->
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode( 'noo_floor_plan', 'noo_shortcode_floor_plan' );

endif;