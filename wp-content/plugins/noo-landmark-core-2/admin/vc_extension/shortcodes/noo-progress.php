<?php
/**
 * Create shortcode: [noo_progress]
 *
 * @package     Noo_LandMark_Core_2
 * @author      JAMES <luyentv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_shortcode_progress' ) ) :
    
    function noo_shortcode_progress( $atts ){

        extract( shortcode_atts(array(
            'image'     => '',
            'title' => '',
            'sub_title' => '',
            'progress'  => ''
        ), $atts ) );

        $progress = vc_param_group_parse_atts( $progress );

        wp_enqueue_script( 'wow' );

        ob_start();
        ?>
        <?php
            if( $image != '' )
                echo '<div class="noo-progress has-image">';
            else
                echo '<div class="noo-progress">';
        ?>
            <div class="noo-progress-header">
                <?php
                    if ( !empty( $title ) )
                        echo '<h3 class="noo-title">' . esc_html($title) . '</h3>';

                    if ( !empty( $sub_title ) )
                        echo '<p>' . esc_html( $sub_title ) . '</p>';
                ?>
            </div>
            <div class="noo-progress-bar">
                <?php
                    echo '<div id="codeconSkills">';
                    foreach ($progress as $value) {
                        $lab = isset($value['label']) ? $value['label'] : '';
                        $val = isset($value['value']) ? $value['value'] : '';
                        if( $lab != '' && $val != '' ){ 
                        ?>
                            <div class="noo-single-bar">
                                <small style="width:<?php echo esc_attr( $val ); ?>%" class="label-bar">
                                    <span class="noo-progress-label"><?php echo esc_attr( $lab ); ?></span>
                                    <span class="noo-label-units"><?php echo esc_attr( $val ); ?>%</span>
                                </small>
                                <span class="noo-bar wow loadSkill" data-wow-duration="1.5s" data-wow-delay="0.6s" style="max-width: <?php echo esc_attr( $val ); ?>%;"></span>
                            </div>
                        <?php 
                        }
                    }
                    echo '</div>';
                ?>
            </div>
            <script>
                jQuery(document).ready(function($) {
                    new WOW().init();
                });
            </script>
            <?php
                if ( !empty($image) )
                    echo '<img src="'.esc_attr( wp_get_attachment_url($image) ).'" alt="'.esc_attr( get_the_title($image) ).'" />';
            ?>
                
        </div>

        <?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode( 'noo_progress', 'noo_shortcode_progress' );

endif;