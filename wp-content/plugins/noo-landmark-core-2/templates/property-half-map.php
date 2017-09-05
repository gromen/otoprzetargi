<?php
if ( !noo_landmark_is_plugin_active( 'realty-portal/realty-portal.php' ) ) :
	exit( esc_html__( 'Please active plugin Realty Portal', 'noo-landmark' ) );
endif;
/**
 * Property Half Map
 *
 * @package LandMark
 * @author  KENT <tuanlv@vietbrain.com>
 */

$display_style 		= 'style-half-map';
$class_grid 		= '';
$class_column 	    = 'noo-md-6';

$user_id            = noo_get_current_user(true);

$show_social 		= get_theme_mod( 'noo_property_social', true);
$show_favories 		= get_theme_mod( 'noo_property_favories', true);
$show_compare 		= get_theme_mod( 'noo_property_compare', true);

$is_favorites       = get_user_meta( $user_id, 'is_favorites', true );

/**
 * Create query
 */
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
if( is_front_page() ) {
	$paged = get_query_var( 'page' );
}

$args = array(
	'post_type'   => 'noo_property',
	'post_status' => 'publish',
	'paged'       => $paged
);
if( function_exists( 'pll_current_language' ) ) {
	$args['lang'] = pll_current_language();
}

$r               = new WP_Query( $args );
$blog_name       = get_theme_mod( 'blogname' );
$blog_desc       = get_bloginfo( 'description' );
$image_logo      = '';
$page_id 		 = get_the_ID();
$image_logo_page = get_post_meta( $page_id, '_noo_wp_page_menu_logo', true );

if ( get_theme_mod( 'noo_header_use_image_logo', false ) ) {
    if ( get_theme_mod( 'noo_header_logo_image', '' ) !=  '' ) {
        $image_logo = (int)get_theme_mod( 'noo_header_logo_image', '' );
        $image_logo = noo_thumb_src_id( $image_logo );
    }
}
if( !empty($image_logo_page) ){
    $image_logo = noo_thumb_src_id( $image_logo_page );
}
/**
 * Get url page template search
 */
$url_page_property_search = noo_get_url_page_search_template();

$noo_bottom_bar_content = get_theme_mod( 'noo_bottom_bar_content', noo_landmark_func_html_content_filter( __( '&copy; 2015. Designed with <i class="fa fa-heart text-primary" ></i> by NooTheme', 'noo-landmark' ) ) );
$footer_copyright_social = get_theme_mod( 'noo_bottom_social_on', 'true' );
?>
<?php get_header(); ?>
	<div class="noo-header">
		<div class="navbar-header pull-left">
			<div class="noo-logo">
				<a href="<?php echo home_url( '/' ); ?>" class="navbar-brand" title="<?php echo esc_attr($blog_desc); ?>">
	                <?php echo ( $image_logo == '' ) ? $blog_name : '<img class="noo-logo-img noo-logo-normal" src="' . esc_url($image_logo) . '" alt="' . esc_attr($blog_desc) . '" />'; ?>
	            </a>
			</div>
            <button data-target=".nav-collapse" class="btn-navbar noo_icon_menu" type="button">
                <i class="fa fa-bars"></i>
            </button>
		</div>
		<nav class="pull-left noo-main-menu">
			<?php
        	/**
        	 * Get menu header
        	 */
			$menu_header = get_post_meta( $page_id, 'menu_header', true );
			wp_nav_menu( array(
				'menu'        => $menu_header,
				'container'   => false,
				'menu_class'  => 'nav-collapse navbar-nav',
				'fallback_cb' => 'noo_notice_set_menu',
				'walker'      => new Noo_Landmark_Megamenu_Walker
            ) );
        	?>
		</nav>
		<div class="pull-left porperty-search">
		<?php if ( noo_landmark_is_plugin_active( 'realty-portal-advanced-search/realty-portal-advanced-search.php' ) ) : ?>
			<form class="noo-form-halfmap" action="<?php echo esc_url( $url_page_property_search ) ?>" method="get" accept-charset="utf-8">
				<?php
					rp_advanced_search_fields(
                        Realty_Portal::get_setting( 'advanced_search', 'option_1', 'keyword' ), 
                        array( 'class' => 'noo-text-form' )
                    );
					rp_advanced_search_fields(
                        Realty_Portal::get_setting( 'advanced_search', 'option_2', 'property_status' ),
                        array( 'class' => 'noo-select-form' )
                    );
					rp_advanced_search_fields(
                        Realty_Portal::get_setting( 'advanced_search', 'option_3', 'property_types' ),
                        array( 'class' => 'noo-select-form' )
                    );
					rp_advanced_search_fields(
                        Realty_Portal::get_setting( 'advanced_search', 'option_4', 'city' ),
                        array( 'class' => 'noo-select-form' )
                    );
				?>

				<button type="submit" class="noo-button">
					<?php echo esc_html__( 'Search Property', 'noo-landmark-core' ); ?>
				</button>
			</form>
			<?php endif; ?>
		</div>
	</div>
    <div class="noo-advanced-search-property">
        <div class="noo-box-map noo-advanced-search-property-form noo-row">
        	<?php
        	wp_enqueue_script( 'google-map-search-property' );
        	/**
        	 * Show map
        	 */
    		$id_map           = uniqid( 'id-map' );
    		$latitude         = Realty_Portal::get_setting( 'google_map', 'latitude', '40.714398' );
    		$longitude        = Realty_Portal::get_setting( 'google_map', 'longitude', '-74.005279' );
    		$zoom             = Realty_Portal::get_setting( 'google_map', 'zoom', '17' );
    		$drag_map         = Realty_Portal::get_setting( 'google_map', 'drag_map', true );
    		$fitbounds        = Realty_Portal::get_setting( 'google_map', 'fitbounds', true );
    		$background_map   = Realty_Portal::get_setting( 'google_map', 'background_map', '' );
    		$background_map   = ( !empty( $background_map ) ? noo_thumb_src_id( $background_map, 'full' ) : '' );
    		$background_style = '';
            if( !empty( $background_map ) ) {
                $background_style = ' background: url(' . esc_url_raw( $background_map ) . ') repeat-x scroll 0 center transparent;';
            }
            ?>
            <div class="noo-search-map-wrap">
                <div
        			class="noo-search-map"
        			style="height: 100%; <?php echo $background_style; ?>"
        			id="<?php echo esc_attr( $id_map ); ?>"
        			data-id="<?php echo esc_attr( $id_map ); ?>"
                    data-source="property"
        			data-latitude="<?php echo esc_attr( $latitude ); ?>"
        			data-longitude="<?php echo esc_attr( $longitude ); ?>"
        			data-zoom="<?php echo esc_attr( $zoom ); ?>"
        			data-drag-map="<?php echo esc_attr( $drag_map ); ?>"
        			data-fitbounds="<?php echo esc_attr( $fitbounds ); ?>">
        	
                    <div class="gmap-loading"><?php echo esc_html__( 'Loading Maps', 'noo-landmark-core' ); ?>
                        <div class="gmap-loader">
                            <div class="rect1"></div>
                            <div class="rect2"></div>
                            <div class="rect3"></div>
                            <div class="rect4"></div>
                            <div class="rect5"></div>
                        </div>
                   </div>

                </div>

                <div class="gmap-controls-wrap">
                    <div class="gmap-controls">
                        <div class="map-view">
                            <i class="fa fa-picture-o"></i>
                            <?php echo esc_html__( 'View', 'noo-landmark-core' ); ?>
                            <span class="map-view-type">
                                <span data-type="roadmap">
                                    <?php echo esc_html__( 'Roadmap', 'noo-landmark-core' ); ?>
                                </span>
                                <span data-type="satellite">
                                    <?php echo esc_html__( 'Satellite', 'noo-landmark-core' ); ?>
                                </span>
                                <span data-type="hybrid">
                                    <?php echo esc_html__( 'Hybrid', 'noo-landmark-core' ); ?>
                                </span>
                                <span data-type="terrain">
                                    <?php echo esc_html__( 'Terrain', 'noo-landmark-core' ); ?>
                                </span>
                            </span>
                        </div>
                        <div class="my-location" id="<?php echo esc_attr( uniqid( 'my-location-' ) ) ?>">
                            <i class="fa fa-map-marker"></i>
                            <?php echo esc_html__( 'My Location', 'noo-landmark-core' ); ?>
                        </div>
                        <div class="gmap-full">
                            <i class="fa fa-expand"></i> 
                            <?php echo esc_html__( 'Fullscreen', 'noo-landmark-core' ); ?>
                        </div>
                        <div class="gmap-prev">
                            <i class="fa fa-chevron-left"></i> 
                            <?php echo esc_html__( 'Prev', 'noo-landmark-core' ); ?>
                        </div>
                        <div class="gmap-next">
                            <?php echo esc_html__( 'Next', 'noo-landmark-core' ); ?> 
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
                        <input type="text" id="gmap_search_input" name="find-address-map" placeholder="<?php echo esc_html__( 'Google Maps Search', 'noo-landmark-core' ); ?>"  autocomplete="off" />
                    </div>
                </div><!-- /.gmap-controls-wrap -->

            </div><!-- /.noo-search-map-wrap -->
            <div class="noo-search-map-content">
        		<div class="noo-list-property <?php echo esc_attr( $class_grid ); ?>">
        			<div class="noo-list-property-action">
        				<div class="noo-count">
        					<?php echo sprintf( esc_html__( '%s listings found', 'noo-landmark-core' ), $r->found_posts ) ?>
        				</div>
        				<form class="sort-property">
        					<?php echo esc_html__( 'Sort By:', 'noo-landmark-core' ); ?>
        					<select name="orderby">
        						<option value="date"><?php echo esc_html__( 'Date', 'noo-landmark-core' ); ?></option>
        						<option value="price"><?php echo esc_html__( 'Price', 'noo-landmark-core' ); ?></option>
        						<option value="bath"><?php echo esc_html__( 'Bath', 'noo-landmark-core' ); ?></option>
        						<option value="bed"><?php echo esc_html__( 'Bed', 'noo-landmark-core' ); ?></option>
        						<option value="area"><?php echo esc_html__( 'Area', 'noo-landmark-core' ); ?></option>
        						<option value="featured"><?php echo esc_html__( 'Featured', 'noo-landmark-core' ); ?></option>
        						<option value="name"><?php echo esc_html__( 'Name', 'noo-landmark-core' ); ?></option>
        					</select>
        					<input type="hidden" name="keyword" value="" />
        					<input type="hidden" name="types" value="" />
        					<input type="hidden" name="status" value="" />
        					<input type="hidden" name="location" value="" />
        					<input type="hidden" name="style" value="" />
        				</form>
        			</div>
                    <?php
                    if ( $r->have_posts() ) :
                        while ( $r->have_posts() ) : $r->the_post();
        					$property_id        = get_the_ID();
        					$check_is_favorites = ( !empty( $is_favorites ) && in_array( $property_id, $is_favorites ) ) ? true : false;
        					$class_favorites    = $check_is_favorites ? 'is_favorites' : 'add_favorites';
        					$text_favorites     = $check_is_favorites ? esc_html__( 'View favorites', 'noo-landmark-core' ) : esc_html__( 'Add to favorites', 'noo-landmark-core' );
        					$icon_favorites     = $check_is_favorites ? 'fa-heart' : 'fa-heart-o';
                        	require noo_get_template( 'property/item-property' );

                        endwhile;
                        noo_pagination_loop( array(), $r );
                        wp_reset_postdata();
                    else :
                    	echo '<div class="noo-found">' . esc_html__( 'Nothing Found!', 'noo-landmark-core' ) . '</div>';
                    endif; ?>
                </div><!-- /.noo-list-property -->
                <div class="noo-map-footer">
                	<?php
                	/**
                	 * Get menu footer
                	 */
        			$menu_footer = get_post_meta( $page_id, 'menu_footer', true );
        			if ( !empty( $menu_footer ) ) {
                        $menu = wp_get_nav_menu_object( $menu_footer );
                        /**
                         * Check menu exist
                         */
                        if ( !empty( $menu->term_id ) ) {
            			
                        	$menu_items  = wp_get_nav_menu_items($menu->term_id);
            				echo '<ul class="noo-list-menu-footer">';
            				foreach ( (array) $menu_items as $key => $menu_item ) :
                                $title = sanitize_text_field( $menu_item->title );
                                $url   = esc_url( $menu_item->url );
                                echo '<li><a href="' . esc_url( $url ) . '" title="' . esc_html( $title ) . '">' . esc_html( $title ) . '</a></li>';
                            endforeach;
                            echo '</ul>';

                        }
        			}
                	?>
                	<?php if (!empty($noo_bottom_bar_content)) : ?>
        		        <div class="copyright">
                            <div class="copyright-content copyright-text">
                                <?php echo noo_landmark_func_html_content_filter($noo_bottom_bar_content); ?>
                            </div>
                            <?php if($footer_copyright_social == 'true'): ?>
                                <div class="copyright-content copyright-social">
                                    <?php 
                                        if(is_active_sidebar('noo-footer-copyright')){
                                            dynamic_sidebar( 'noo-footer-copyright');
                                        } else { ?>
                                            <aside class="widget">
                                                <a class="demo-widgets" href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php echo esc_html__( 'Click here to add your widgets "NOO - Footer Copyright"', 'noo-landmark' ); ?></a>
                                            </aside>
                                        <?php }
                                    ?>    
                                </div>
                            <?php endif; ?>
        		        </div>
        		    <?php endif; ?>
                </div><!-- /.noo-map-footer -->
            </div><!-- /.noo-search-map-content -->
    	</div><!-- .noo-advanced-search-property-form -->
    </div><!-- .noo-advanced-search-property -->
<?php get_footer(); ?>