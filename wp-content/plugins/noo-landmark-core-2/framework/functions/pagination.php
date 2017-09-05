<?php
/**
 * This function paging loop
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_pagination_loop' ) ) :
    
    function noo_pagination_loop( $args = array(), $query = null ) {

        global $wp_rewrite, $wp_query;
        if ( !empty($query)) {
            $wp_query = $query;
        } 
        if ( 1 >= $wp_query->max_num_pages )
            return;
        
        $paged = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );
        
        $max_num_pages = intval( $wp_query->max_num_pages );
        
        $defaults = array(
            'base'                   => esc_url( add_query_arg( 'paged', '%#%' ) ),
            'format'                 => '',
            'total'                  => $max_num_pages,
            'current'                => $paged,
            'prev_next'              => true,
            'prev_text'              => '<i class="ion-ios-arrow-back"></i>',
            'next_text'              => '<i class="ion-ios-arrow-forward"></i>',
            'show_all'               => false,
            'end_size'               => 1,
            'mid_size'               => 1,
            'add_fragment'           => '',
            'type'                   => 'plain',
            'before'                 => '<span class="noo-pagination-loop">',
            'after'                  => '</span>',
            'echo'                   => true,
            'use_search_permastruct' => true
        );
        
        $defaults = apply_filters( 'noo_pagination_loop_args_defaults', $defaults );
        
        if( $wp_rewrite->using_permalinks() && ! is_search() ) {
            $defaults['base'] = user_trailingslashit( trailingslashit( get_pagenum_link() ) . 'page/%#%' );
        }
        
        if ( is_search() )
            $defaults['use_search_permastruct'] = false;
        
        if ( is_search() ) {
            if ( class_exists( 'BP_Core_User' ) || $defaults['use_search_permastruct'] == false ) {
                $search_query = get_query_var( 's' );
                $paged = get_query_var( 'paged' );
                $base = esc_url( add_query_arg( 's', urlencode( $search_query ) ) );
                $base = esc_url( add_query_arg( 'paged', '%#%' ) );
                $defaults['base'] = $base;
            } else {
                $search_permastruct = $wp_rewrite->get_search_permastruct();
                if ( ! empty( $search_permastruct ) ) {
                    $base = get_search_link();
                    $base = esc_url( add_query_arg( 'paged', '%#%', $base ) );
                    $defaults['base'] = $base;
                }
            }
        }
        
        $args = wp_parse_args( $args, $defaults );
        
        $args = apply_filters( 'noo_pagination_loop_args', $args );
        
        if ( 'array' == $args['type'] )
            $args['type'] = 'plain';
        
        $pattern = '/\?(.*?)\//i';
        
        preg_match( $pattern, $args['base'], $raw_querystring );
        
        if(!empty($raw_querystring)){
            if( $wp_rewrite->using_permalinks() && $raw_querystring )
                $raw_querystring[0] = str_replace( '', '', $raw_querystring[0] );
            $args['base'] = str_replace( $raw_querystring[0], '', $args['base'] );
            $args['base'] .= substr( $raw_querystring[0], 0, -1 );
        }
        $page_links = paginate_links( $args );
        
        $page_links = str_replace( array( '&#038;paged=1\'', '/page/1\'' ), '\'', $page_links );
        
        $page_links = $args['before'] . $page_links . $args['after'];
        
        $page_links = apply_filters( 'noo_pagination_loop', $page_links );
        
        if ( $args['echo'] )
            echo wp_kses( $page_links, noo_allowed_html() );
        else
            return wp_kses( $page_links, noo_allowed_html() );

    }

endif;