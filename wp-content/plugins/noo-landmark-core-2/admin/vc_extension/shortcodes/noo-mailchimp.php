<?php
/**
 * Create shortcode: [noo_mailchimp]
 *
 * @package     Noo_LandMark_Core_2
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( ! function_exists( 'noo_shortcode_mailchimp' ) ) :
    
    function noo_shortcode_mailchimp( $atts ){

        extract( shortcode_atts(array(
            'title'     =>  '',
            'sub_title' =>  '',
            'image'     => '',
            'subscribers' => 'none'
        ), $atts ) );

        ob_start();

        ?>
        <div class="noo-mailchimp">
            <?php
                if ( !empty( $title ) )
                    echo '<h3 class="noo-title">' . esc_html($title) . '</h3>';

                if ( !empty( $sub_title ) )
                    echo '<span class="noo-sub-title">' . esc_html( $sub_title ) . '</span>';

                // Check mc4wp_default_form_id == 0
                $id_form_default =  get_option( 'mc4wp_default_form_id' ) ? get_option( 'mc4wp_default_form_id' ) : 0;

                if( $id_form_default == 0 ){

                    if( is_user_logged_in() ){

                        echo '<p class="mailchimp-notice">Your form has not been display. Please click <a href="'.esc_url(admin_url( 'admin.php?page=mailchimp-for-wp-forms' )).'">HERE</a> after that click save your form again to show it.</p>';

                    } else {

                        return;

                    }

                } else {

                    if( function_exists('mc4wp_show_form') ){

                        $obj = mc4wp_get_form();
                        // mc4wp_show_form();
                        ?>
                        <form id="mc4wp-form-1" class="mc4wp-form mc4wp-form-<?php echo esc_attr($obj->ID) ?>" method="post" data-id="<?php echo esc_attr($obj->ID) ?>" data-name="<?php echo esc_attr($obj->name) ?>">
                            <div class="mc4wp-form-fields">
                                <div class="noo-mailchimp-main">
                                    <input type="email" name="EMAIL" placeholder="<?php echo esc_attr__( 'Enter Your Email Address', 'noo-landmark-core' ); ?>" required="">
                                    <?php
                                        if( $subscribers != 'none' )
                                            echo '<input type="hidden" name="_mc4wp_lists" value="'.esc_attr($subscribers).'" />';
                                    ?>
                                    <input type="submit" value="<?php echo esc_attr__( 'Subscribe', 'noo-landmark-core' ) ?>">
                                    <i class="ion-ios-paperplane"></i>
                                </div>
                                <div style="display: none;">
                                    <input type="text" name="_mc4wp_honeypot" value="" tabindex="-1" autocomplete="off">
                                </div>
                                <input type="hidden" name="_mc4wp_timestamp" value="<?php time(); ?>">
                                <input type="hidden" name="_mc4wp_form_id" value="<?php echo esc_attr($obj->ID) ?>">
                                <input type="hidden" name="_mc4wp_form_element_id" value="mc4wp-form-1">
                            </div>
                            <div class="mc4wp-response"></div>
                        </form>
                        <?php
                        echo '<div class="after_submmit">';
                            echo mc4wp_get_submitted_form();
                        echo '</div>';
                    }

                }

                    
                if ( !empty($image) )
                    echo '<img src="'.esc_attr( wp_get_attachment_url($image) ).'" alt="'.esc_attr( get_the_title($image) ).'" />';
            ?>
        </div>

        <?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode( 'noo_mailchimp', 'noo_shortcode_mailchimp' );
    
endif;