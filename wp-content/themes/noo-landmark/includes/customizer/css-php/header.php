<?php
// Variables

// Heading attr
$noo_page_heading_spacing =     get_theme_mod( 'noo_page_heading_spacing', 85);

//background for header
$noo_header_bg_color      =     get_theme_mod( 'noo_header_bg_color' );

//background for top bar
$noo_header_top_bg_color  =     get_theme_mod( 'noo_header_top_bg_color' );

// topbar
$noo_header_custom_top_font     =     get_theme_mod( 'noo_header_custom_top_font', false );
$noo_header_top_font            =     get_theme_mod( 'noo_header_top_font', '' );
$noo_header_top_font_weight     =     get_theme_mod( 'noo_header_top_font_weight' );
$noo_header_top_color           =     get_theme_mod( 'noo_header_top_link_color', '#000' );
$noo_header_top_hover_color     =     get_theme_mod( 'noo_header_top_link_hover_color' );
$noo_header_top_font_size       =     get_theme_mod( 'noo_header_top_font_size', 14 );
$noo_header_top_font_size_right =     get_theme_mod( 'noo_header_top_font_size_right', 14 );
$noo_header_top_height          =     get_theme_mod( 'noo_header_top_height', 45 );

// navigation
$noo_header_custom_nav_font   =     get_theme_mod( 'noo_header_custom_nav_font', false );
$noo_header_nav_font          =     get_theme_mod( 'noo_header_nav_font', '' );
$noo_header_nav_font_weight   =     get_theme_mod( 'noo_header_nav_font_weight', 'bold' );
$noo_header_nav_color         =     get_theme_mod( 'noo_header_nav_link_color', '#333333' );
$noo_header_nav_hover_color   =     get_theme_mod( 'noo_header_nav_link_hover_color', noo_landmark_func_get_theme_default( 'primary_color' ));
$noo_header_nav_font_size     =     get_theme_mod( 'noo_header_nav_font_size', 18 );
$noo_header_nav_uppercase     =     get_theme_mod( 'noo_header_nav_uppercase', false ) ? 'uppercase': 'normal';

// logo text
$noo_header_use_image_logo    =     get_theme_mod( 'noo_header_use_image_logo', false );
$noo_header_logo_font         =     get_theme_mod( 'noo_header_logo_font', noo_landmark_func_get_theme_default( 'headings_font_family' ) );
$noo_header_logo_font_size    =     get_theme_mod( 'noo_header_logo_font_size', '30' );
$noo_header_logo_font_color   =     get_theme_mod( 'noo_header_logo_font_color', noo_landmark_func_get_theme_default( 'logo_color' ) );
$noo_header_logo_font_style   =     get_theme_mod( 'noo_header_logo_font_style', 'normal' );
$noo_header_logo_font_weight  =     get_theme_mod( 'noo_header_logo_font_weight', '700' );
$noo_header_logo_font_subset  =     get_theme_mod( 'noo_header_logo_font_subset', 'latin' );
$noo_header_logo_uppercase    =     get_theme_mod( 'noo_header_logo_uppercase', false ) ? 'uppercase': 'normal';

// header attr
$noo_header_logo_image_height =     get_theme_mod( 'noo_header_logo_image_height', '70' );
$noo_header_nav_height        =     get_theme_mod( 'noo_header_nav_height', '128' );
$noo_header_nav_link_spacing  =     get_theme_mod( 'noo_header_nav_link_spacing', 20);

?>

/* Header */
/* ====================== */
<?php if ( $noo_header_bg_color != '' ) : ?>
    header.noo-header .navbar-wrapper{
        background-color: <?php echo esc_attr($noo_header_bg_color); ?>;
    }
<?php endif; ?>

/*
* Heading spacing
* ===============================
*/

.noo-page-heading {
    padding-top:    <?php echo esc_attr( $noo_page_heading_spacing ).'px'; ?>;
    padding-bottom:   <?php echo esc_attr( $noo_page_heading_spacing - 15 ).'px'; ?>;
}

/* Top Bar */
/* ====================== */
header.noo-header .noo-topbar{
    <?php if ( $noo_header_top_bg_color != '' ) : ?>
        background-color: <?php echo esc_attr($noo_header_top_bg_color); ?>;
    <?php endif; ?>
    height: <?php echo esc_attr( $noo_header_top_height ).'px'; ?>;
}
header.noo-header .noo-topbar a, header.noo-header .noo-topbar p {
    line-height: <?php echo esc_attr( $noo_header_top_height ).'px'; ?>;    
}

header.noo-header .noo-topbar .fa-mobile{
    line-height: <?php echo esc_attr( $noo_header_top_height ).'px'; ?>;
}

<?php if ( $noo_header_custom_top_font != '' ) : ?>
header.noo-header .noo-topbar .noo-topmeta, header.noo-header .noo-topbar .noo-topmeta span, header.noo-header .noo-topbar .noo-topmeta a, header.noo-header .noo-topbar .noo-topmeta p {
    font-weight:  <?php echo esc_html($noo_header_top_font_weight); ?>;
    font-size:  <?php echo esc_html($noo_header_top_font_size); ?>px;
    color:        <?php echo esc_attr( $noo_header_top_color ) ; ?>;
}
header.noo-header .noo-topbar, header.noo-header .noo-topbar span:not(.fa), header.noo-header .noo-topbar a:not(.fa), header.noo-header .noo-topbar p {
    font-family: "<?php echo esc_html($noo_header_top_font); ?>", sans-serif;
}
header.noo-header .noo-topbar .noo-topmeta-right a, header.noo-header .noo-topbar .noo-topmeta-right p, header.noo-header .noo-topbar .noo-topmeta-right {
    font-size:  <?php echo esc_html($noo_header_top_font_size_right); ?>px;
}
header.noo-header .noo-topbar a:hover {
    color: <?php echo esc_attr( $noo_header_top_hover_color ) ; ?>;
}
<?php endif; ?>
/*
* Typography for menu
* ===============================
*/

header .noo-main-menu .navbar-nav > li > a{
    margin-left:    <?php echo esc_attr( $noo_header_nav_link_spacing ).'px'; ?>;
    margin-right:   <?php echo esc_attr( $noo_header_nav_link_spacing ).'px'; ?>;
}

header .noo-main-menu .navbar-nav li > a{
    font-size:       <?php echo esc_attr( $noo_header_nav_font_size ) . 'px'; ?>;
    text-transform:  <?php echo esc_attr( $noo_header_nav_uppercase ) ; ?>;
    line-height:     <?php echo esc_attr( $noo_header_nav_height ).'px'; ?>;
    <?php if( $noo_header_custom_nav_font ): ?>
        <?php if($noo_header_nav_font != ''): ?> font-family: "<?php echo esc_html($noo_header_nav_font); ?>", sans-serif; <?php endif; ?>
        font-weight:  <?php echo esc_html($noo_header_nav_font_weight); ?>;
        color:        <?php echo esc_attr( $noo_header_nav_color ) ; ?>;
    <?php endif; ?>
}

header.header_agency .menu-nav-control, header .noo-menu-option li > a, header .noo-menu-option .button-menu-extend, header .noo-menu-option .button-expand-option {
    line-height:     <?php echo esc_attr( $noo_header_nav_height ).'px'; ?>;
}

<?php if( $noo_header_custom_nav_font && $noo_header_nav_hover_color != '' ): ?>
    header .noo-main-menu .navbar-nav li > a:hover,
    header .noo-main-menu .navbar-nav li > a:focus,
    header .noo-main-menu .navbar-nav li > a:active,
    header .noo-main-menu .navbar-nav li.current_page_item > a,
    header .noo-main-menu .navbar-nav li.current-menu-parent > a,
    header .noo-main-menu .navbar-nav .sub-menu li.current-menu-item > a,
    .noo-header-minicart .widget_shopping_cart_content .minicart-link-hover:hover,
    .noo-header-minicart .widget_shopping_cart_content .noo_wrap_minicart a:hover {
        color: <?php echo esc_attr( $noo_header_nav_hover_color ) ; ?>;
    }
    header .noo-main-menu .navbar-nav li > .sub-menu li a:hover, header .noo-main-menu .navbar-nav li:hover > a{
        color: <?php echo esc_attr( $noo_header_nav_hover_color ) ; ?>;
    }
    .noo-main-menu .navbar-nav #magic-line {
        background: <?php echo esc_attr( $noo_header_nav_hover_color ) ; ?>;
    }
    <!-- Menu on mobile -->
    .off-canvas #off-canvas-nav .noo-main-canvas .nav-collapse li.active > a,
    .off-canvas #off-canvas-nav .noo-main-canvas .nav-collapse li.current-menu-parent > a,
    .off-canvas #off-canvas-nav .noo-main-canvas .nav-collapse li.current-menu-item > a,
    .off-canvas #off-canvas-nav .noo-main-canvas .nav-collapse li.current-menu-ancestor > a,
    .off-canvas #off-canvas-nav .noo-main-canvas .nav-collapse li a:hover {
        color: <?php echo esc_attr( $noo_header_nav_hover_color ) ; ?>;
    }
<?php endif; ?>

/**
 * Minicart
 */
.noo-header .noo-menu-option ul li a, .noo-header-minicart .widget_shopping_cart_content .minicart-link-hover {
    line-height: <?php echo esc_attr( $noo_header_nav_height ).'px'; ?>;
}
/**
 * Navbar Meta
 */
header .navbar-meta{
    height: <?php echo esc_attr( $noo_header_nav_height ).'px'; ?>;
}
header .navbar-meta.meta-social{
    line-height: <?php echo esc_attr( $noo_header_nav_height ).'px'; ?>;
}
header .navbar-meta.meta-property .meta-content .meta-property{
    margin-top: <?php echo esc_attr( ($noo_header_nav_height - 50)/2 ).'px'; ?>;
}

/*
* Typography for Logo text
* ===============================
*/
header .navbar-brand .noo-logo-img{
    height: <?php echo esc_attr( $noo_header_logo_image_height ). 'px'; ?>;
}
header .navbar{
    min-height: <?php echo esc_attr( $noo_header_nav_height ).'px'; ?>;
}
header .navbar-brand{
    line-height: <?php echo esc_attr( $noo_header_nav_height ).'px'; ?>;
    <?php if( $noo_header_use_image_logo == false): ?>
        font-family:    <?php echo esc_attr( $noo_header_logo_font ); ?>, sans-serif;
        font-size:      <?php echo esc_attr( $noo_header_logo_font_size ) .'px'; ?>;
        color:          <?php echo esc_attr( $noo_header_logo_font_color ); ?>;
        font-style:     <?php echo esc_attr( $noo_header_logo_font_style ); ?>;
        text-transform: <?php echo esc_attr( $noo_header_logo_uppercase ); ?>;
        font-weight:    <?php echo esc_attr( $noo_header_logo_font_weight ); ?>;
    <?php endif; ?>
}