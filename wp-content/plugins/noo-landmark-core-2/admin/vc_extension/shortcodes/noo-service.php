<?php
/**
 * Create shortcode: [noo_service]
 *
 * @package     Noo_LandMark_Core_2
 * @author      JAMES <luyentv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_shortcode_service' ) ) :
    
    function noo_shortcode_service( $atts ){

        extract( shortcode_atts(array(
            'title'     => '',
            'sub_title' => '',
            'style'     => 'style-1',
            'column'   => '2',
            'box'       => '',
            'btname'       => '',
            'readmore'  => '',
        ), $atts ) );

        ob_start();
        $box = vc_param_group_parse_atts( $box );
        ?>
        <div class="noo-service <?php echo esc_attr( $style ); ?>">

            <?php noo_title_first_word( $title, $sub_title ) ?>

            <div class="noo-service-main" data-columns="<?php echo esc_attr( $column ); ?>">
                <?php foreach( $box as $item ): ?>
                    <div class="noo-service-item">
                        <?php if( !empty( $item['box_icon'] ) ): ?>
                            <div class="noo-service-icon <?php if($style == 'style-1') echo 'icon-mapmarker'; ?>">
                                <span class="icon <?php echo esc_attr($item['box_icon']); ?>"></span>
                            </div>
                        <?php endif;
                            if( !empty( $item['box_title'] ) )
                                if (!empty($item['box_url']))
                                    echo '<h4 class="noo-service-title"><a href='.$item['box_url'].'>'.esc_html( $item['box_title'] ).'</a></h4>';
                                else 
                                    echo '<h4 class="noo-service-title">'.esc_html( $item['box_title'] ).'</h4>';
                            if( !empty( $item['box_content'] ) )
                                echo '<p>'.esc_html($item['box_content']).'</p>';
                        ?>
                    </div>
                <?php endforeach; ?>
            </div><!-- End /.noo-service-main -->
            <?php
                if( !empty( $readmore ) ): ?>
                    <div class="btlink">
                        <a class="<?php echo ( $style=='style-1' ) ? 'noo-readmore' : 'noo-button'; ?>" href="<?php echo esc_url($readmore); ?>">
                            <?php echo !empty( $btname ) ? esc_html( $btname ) : 'Read more'; ?>
                        </a>
                    </div>
                <?php
                endif;
            ?>
        </div>

        <?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode( 'noo_service', 'noo_shortcode_service' );

endif;