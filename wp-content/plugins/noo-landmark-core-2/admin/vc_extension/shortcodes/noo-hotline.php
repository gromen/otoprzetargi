<?php
/**
 * Create shortcode: [noo_hotline]
 *
 * @package     Noo_LandMark_Core_2
 * @author      JAMES <luyentv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_shortcode_hotline' ) ) :
    
    function noo_shortcode_hotline( $atts ){

        extract( shortcode_atts(array(
            'title'     =>  '',
            'sub_title' =>  '',
            'phone'     => '',
            'image'     => ''
        ), $atts ) );

        ob_start();

        ?>
        <div class="noo-ads-service">
            <?php
                if ( !empty( $title ) )
                    echo '<h3 class="noo-title">' . esc_html($title) . '</h3>';

                if ( !empty( $sub_title ) )
                    echo '<span class="noo-sub-title">' . esc_html( $sub_title ) . '</span>';
                if ( !empty( $phone ) ){ ?>
                    <div class="noo-ads-phone">
                        <i class="fa fa-phone"></i>
                        <div class="noo-ads-desc">
                            <?php echo esc_html__('CALL US NOW', 'noo-landmark-core');?><br>
                            <a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a>
                        </div>
                    </div>
                <?php }

                if ( !empty($image) )
                    echo '<img src="'.esc_attr( wp_get_attachment_url($image) ).'" alt="'.esc_attr( get_the_title($image) ).'" />';
            ?>
        </div>

        <?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode( 'noo_hotline', 'noo_shortcode_hotline' );

endif;