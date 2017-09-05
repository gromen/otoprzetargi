<?php
/**
 * This function allow support tag html
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_allowed_html' ) ) :
    
    function noo_allowed_html() {
  
        return apply_filters( 'noo_allowed_html', array(
            'a' => array(
                'href' => array(),
                'target' => array(),
                'title' => array(),
                'rel' => array(),
                'class' => array(),
                'style' => array(),
            ),
            'img' => array(
                'src' => array(),
                'class' => array(),
                'style' => array(),
            ),
            'h1' => array(
                'class' => array(),
                'style' => array()
            ),
            'h2' => array(
                'class' => array(),
                'style' => array()
            ),
            'h3' => array(
                'class' => array(),
                'style' => array()
            ),
            'h4' => array(
                'class' => array(),
                'style' => array()
            ),
            'h5' => array(
                'class' => array(),
                'style' => array()
            ),
            'p' => array(
                'class' => array(),
                'style' => array()
            ),
            'br' => array(
                'class' => array(),
                'style' => array()
            ),
            'hr' => array(
                'class' => array(),
                'style' => array()
            ),
            'span' => array(
                'class' => array(),
                'style' => array()
            ),
            'em' => array(
                'class' => array(),
                'style' => array()
            ),
            'strong' => array(
                'class' => array(),
                'style' => array()
            ),
            'small' => array(
                'class' => array(),
                'style' => array()
            ),
            'b' => array(
                'class' => array(),
                'style' => array()
            ),
            'i' => array(
                'class' => array(),
                'style' => array()
            ),
            'u' => array(
                'class' => array(),
                'style' => array()
            ),
            'ul' => array(
                'class' => array(),
                'style' => array()
            ),
            'ol' => array(
                'class' => array(),
                'style' => array()
            ),
            'li' => array(
                'class' => array(),
                'style' => array()
            ),
            'blockquote' => array(
                'class' => array(),
                'style' => array()
            ),
            'iframe' => array(
                'width'           => array(),
                'width'           => array(),
                'src'             => array(),
                'class'           => array(),
                'frameborder'     => array(),
                'allowfullscreen' => array()
            ),
        ) );

    }

endif;

if ( ! function_exists( 'noo_get_plugin_path' ) ) :
    
    function noo_get_plugin_path( $name ) {

        $plugin_path = untrailingslashit( NOO_PLUGIN_SERVER_PATH . '/' . $name );

        return $plugin_path;

    }

endif;

/**
 * Add class first word
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( ! function_exists( 'noo_first_word' ) ) :
    
    function noo_first_word( $str = '' ) {

        if ( !empty($str) ) {
            
            $str    = explode( ' ', $str );
            $str[0] = '<span class="first-word">' . esc_html( $str[0] ) . '<span></span></span>';
            $str    = implode( ' ', $str );    
            
        }
        
        return $str;

    }

endif;

/**
 * This function show html title and sub title
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_title_first_word' ) ) :
    
    function noo_title_first_word( $title, $sub_title ) {

        if ( empty( $title ) ) return;
        ?>
        <div class="noo-theme-wraptext">
            <div class="wrap-title">
                <?php if ( !empty( $title ) ) : ?>
                    <h3 class="noo-theme-title">
                        <?php echo noo_first_word( esc_html( $title ) ); ?>
                    </h3>
                <?php endif; ?>

                <?php if ( !empty( $sub_title ) ) : ?>
                    <p class="noo-theme-sub-title">
                        <i class="icon-decotitle"></i>
                        <?php echo esc_html( $sub_title ); ?>
                    </p>
                <?php endif; ?>
            </div><!-- End /.wrap-title -->
        
        </div><!-- End /.noo-theme-wraptext -->
        <?php

    }

endif;

/**
 * Load options fields
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_load_option_fields' ) ) :
    
    function noo_load_option_fields() {

        /**
         * Load all fields
         */
            $name_field = array();
            $list_field = glob( NOO_FRAMEWORK .'/fields/*/*.php' );
            if ( !empty( $list_field ) && is_array( $list_field ) ) {
                
                foreach ( $list_field as $noo_field ) {
                    $basename_field = explode( '.', basename( $noo_field ) );
                    if ( !empty( $basename_field[0] ) ) {
                        $name_field[]   = esc_attr( $basename_field[0] );
                    }
                    require $noo_field;
                }

                update_option( 'noo_list_field', apply_filters( 'noo_list_field', $name_field ) );

            }

    }

    add_action( 'plugins_loaded', 'noo_load_option_fields' );

endif;

if (!function_exists('noo_ajax_get_option')) {
    function noo_ajax_get_option($option, $default = null)
    {
        if (isset($_POST['noo_customize_ajax'])) {
            // AJAX customizer get option
            global $noo_customizer;
            if (!isset($noo_customizer) || empty($noo_customizer)) {
                if (isset($_POST['customized']))
                    $noo_customizer = json_decode(wp_unslash($_POST['customized']), true);
                else
                    $noo_customizer = false;
            }
            return apply_filters('noo_theme_settings', $noo_customizer, $option, $default);
        } else {
            return false;
        }
    }

    add_filter('pre_option_theme_mods_' . get_option('stylesheet'), 'noo_ajax_get_option', 10, 2);

}


/**
 * Get id user by post_id
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 */

if ( ! function_exists( 'noo_get_author_by_post_id' ) ) :
    
    function noo_get_author_by_post_id( $post_id = 0 ) {

        $post = get_post( $post_id );
        return $post->post_author;

    }

endif;

if ( ! function_exists( 'noo_author_profile_fields' ) ) :
    function noo_author_profile_fields ( $contactmethods ) {
        
        $contactmethods['google_profile'] = esc_html__( 'Google+ Profile URL', 'noo-landmark');
        $contactmethods['twitter_profile'] = esc_html__( 'Twitter Profile URL', 'noo-landmark');
        $contactmethods['facebook_profile'] = esc_html__( 'Facebook Profile URL', 'noo-landmark');
        $contactmethods['linkedin_profile'] = esc_html__( 'LinkedIn Profile URL', 'noo-landmark');
        
        return $contactmethods;
    }
    add_filter( 'user_contactmethods', 'noo_author_profile_fields', 10, 1);
endif;

/**
 * Get permalink page search
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_get_url_page_search_template' ) ) :
    
    function noo_get_url_page_search_template() {

        /**
         * Get url page template search
         */
        $id_page_search_template  = noo_get_page_by_template( 'property-search.php' );
        $url_page_property_search = apply_filters(
            'noo_url_page_property_search',
            !empty( $id_page_search_template ) ? get_permalink( absint( $id_page_search_template ) ) : get_post_type_archive_link( 'noo_property' ) 
        );

        if ( empty( $url_page_property_search ) ) {
            $url_page_property_search = home_url();
        }

        return $url_page_property_search;

    }

endif;

/**
 * Get template page search
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_get_layout_page_search_template' ) ) :
    
    function noo_get_layout_page_search_template() {

        /**
         * Get url page template search
         */
        $id_page_search_template = noo_get_page_by_template( 'property-search.php' );
        $layout_search_template  = get_post_meta( $id_page_search_template, 'page_search_template', true );

        if ( empty( $layout_search_template ) ) {
            $layout_search_template = '';
        }

        return $layout_search_template;

    }

endif;

/**
 * Retrieve the current user object.
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_get_current_user' ) ) :
    
    function noo_get_current_user( $show_info = false, $name_field = 'id' ) {

        if ( ! function_exists( 'wp_get_current_user' ) ) {
            require( ABSPATH . WPINC . '/pluggable.php' );
            require( ABSPATH . WPINC . '/pluggable-deprecated.php' );
        }

        $info_current_user = wp_get_current_user();

        if ( empty( $info_current_user ) ) return false;

        if ( !empty( $show_info ) ) {
            if ( $name_field === 'id' && isset( $info_current_user->ID ) ) {
                return $info_current_user->ID;
            }
            return false;
        }
        return $info_current_user;

    }

endif;

if (!function_exists('noo_wp_editor')):
    function noo_wp_editor($content, $editor_id, $configs = array()) {
        $configs_default = array(
            'editor_class'  => 'noo-editor',
            'media_buttons' => false,
            'editor_height' => 380,
            'textarea_name' => $editor_id
        );
        $configs = wp_parse_args( $configs, $configs_default );

        $configs = apply_filters('noo_editor_config', $configs);
        return wp_editor($content, $editor_id, $configs);
    }
endif;