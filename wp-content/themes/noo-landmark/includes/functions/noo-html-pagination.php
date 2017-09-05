<?php
/**
 * 
 * @param array|null $args
 * @param WP_Query $query
 * @return void|mixed
 */
if( !function_exists('noo_landmark_func_pagination') ) :
	function noo_landmark_func_pagination( $args = array(), $query = null ){

		$pagination_type = 'loadmore';
		if( $pagination_type == 'none' ) return;
		
		do_action( 'noo_landmark_func_pagination_start' );

		switch( $pagination_type ) {
			case 'link': noo_landmark_func_pagination_prevnext( $args, $query );break;
			case 'loadmore': noo_landmark_func_pagination_loadmore( $args, $query );break;
			default: noo_landmark_func_pagination_normal( $args, $query ); break;
		}
		
		do_action( 'noo_landmark_func_pagination_end' );
	}
endif;

if( !function_exists('noo_landmark_func_pagination_normal') ) :
	function noo_landmark_func_pagination_normal( $args = array(), $query = null ) {
		global $wp_rewrite, $wp_query;
		if ( !empty($query)) {
			$wp_query = $query;
		} 
		if ( 1 >= $wp_query->max_num_pages )
			return;
		
		$paged = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );
		
		$max_num_pages = intval( $wp_query->max_num_pages );
		
		$defaults = array(
				'base' => esc_url( add_query_arg( 'paged', '%#%' ) ),
				'format' => '',
				'total' => $max_num_pages,
				'current' => $paged,
				'prev_next' => true,
				'prev_text' => '<i class="fa fa-long-arrow-left"></i>',
				'next_text' => '<i class="fa fa-long-arrow-right"></i>',
				'show_all' => false,
				'end_size' => 1,
				'mid_size' => 1,
				'add_fragment' => '',
				'type' => 'plain',
				'before' => '<div class="pagination list-center">',
				'after' => '</div>',
				'echo' => true,
				'use_search_permastruct' => true
		);
		
		$defaults = apply_filters( 'noo_landmark_func_pagination_args_defaults', $defaults );
		
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
		
		$args = apply_filters( 'noo_landmark_func_pagination_args', $args );
		
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
		
		$page_links = apply_filters( 'noo_landmark_func_pagination', $page_links );
		
		$allow_tag_pagination = array(
			'a' => array(
				'href' => array(),
				'target' => array(),
				'title' => array(),
				'rel' => array(),
				'class' => array(),
				'style' => array(),
			),
			'div' => array(
				'class' => array(),
				'style' => array()
			),
			'span' => array(
				'class' => array(),
				'style' => array()
			),
		);

		if ( $args['echo'] )
            echo wp_kses( $page_links, $allow_tag_pagination );
        else
            return wp_kses( $page_links, $allow_tag_pagination );
	}
endif;

if( !function_exists('noo_landmark_func_pagination_prevnext') ) :
	function noo_landmark_func_pagination_prevnext( $args = array(), $query = null ) {
		global $wp_rewrite, $wp_query;
	
		if ( !empty($query)) {
			$wp_query = $query;
		} 
		
		if ( 1 >= $wp_query->max_num_pages )
			return;

		echo isset($args['before']) ? $args['before'] : '<div class="pagination container-boxed">';
		
		$page_links = posts_nav_link( '', esc_html__( 'Newer Entries', 'noo-landmark'), esc_html__( 'Older Entries', 'noo-landmark'));
		
		echo isset($args['after']) ? $args['after'] : '</div>';
	}
endif;

if( !function_exists('noo_landmark_func_pagination_loadmore') ) :
	function noo_landmark_func_pagination_loadmore( $args = array(), $query = null ) {
		global $wp_rewrite, $wp_query;
		if ( !empty($query)) {
			$wp_query = $query;
		} 
		
		if ( 1 >= $wp_query->max_num_pages )
			return;
		?>
		<div class="loadmore-action">
			<a href="#" class="btn-loadmore btn-primary" title="<?php echo esc_html__( 'Load More','noo-landmark')?>"><?php echo esc_html__( 'Load More','noo-landmark')?></a>
		</div>
		<?php
		noo_landmark_func_pagination_normal( $args, $query );
	}
endif;

if( !function_exists('noo_landmark_func_is_ajax_pagination') ) :
	function noo_landmark_func_is_ajax_pagination(){
		$pagination_type = get_theme_mod('noo_blog_pagination', 'loadmore');

		return ( $pagination_type == 'loadmore' || $pagination_type == 'infinite' );
	}
endif;

// Posts Link Attributes
// =============================================================================

if (!function_exists('noo_landmark_func_prev_posts_link_attributes')):
	function noo_landmark_func_prev_posts_link_attributes() {
		return 'class="prev-page read-more pull-right"';
	}
	add_filter('previous_posts_link_attributes', 'noo_landmark_func_prev_posts_link_attributes');
endif;

if (!function_exists('noo_landmark_func_next_posts_link_attributes')):
	function noo_landmark_func_next_posts_link_attributes() {
		return 'class="next-page read-more pull-left"';
	}
	add_filter('next_posts_link_attributes', 'noo_landmark_func_next_posts_link_attributes');
endif;

if (!function_exists('noo_landmark_func_number_format_i18n')):
	function noo_landmark_func_number_format_i18n($number) {
		if ( $number < 10 )
			return '0' . $number;
		else
			return $number;
	}
	add_filter('number_format_i18n', 'noo_landmark_func_number_format_i18n');
endif;


