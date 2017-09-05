<?php
// Variables


$noo_site_primary_hover_color             =   get_theme_mod( 'noo_site_primary_color', noo_landmark_func_get_theme_default( 'primary_color' ) );
$noo_site_secondary_hover_color           =	  get_theme_mod( 'noo_site_secondary_color', noo_landmark_func_get_theme_default( 'secondary_color' ) );

$use_custom_color = get_theme_mod( 'noo_site_use_custom_color', false );
if ( ! $use_custom_color ) {
	$preset_color = noo_landmark_func_get_theme_preset();
	$preset = get_theme_mod( 'noo_site_preset_color', 'preset_1' );
	$noo_site_primary_hover_color   = $preset_color[$preset]['primary'];
	$noo_site_secondary_hover_color = $preset_color[$preset]['secondary'];
}

$noo_site_link_color_fade_95              =   noo_landmark_func_css_fade( $noo_site_primary_hover_color, '95%' );
$noo_site_link_color_fade_90              =   noo_landmark_func_css_fade( $noo_site_primary_hover_color, '90%' );
$noo_site_link_color_fade_85              =   noo_landmark_func_css_fade( $noo_site_primary_hover_color, '85%' );
$noo_site_link_color_fade_80              =   noo_landmark_func_css_fade( $noo_site_primary_hover_color, '80%' );
$noo_site_link_color_fade_60              =   noo_landmark_func_css_fade( $noo_site_primary_hover_color, '60%' );
$noo_site_link_color_darken_10            =   noo_landmark_func_css_darken( $noo_site_primary_hover_color, '10%' );
$noo_site_link_color_darken_15            =   noo_landmark_func_css_darken( $noo_site_primary_hover_color, '15%' );

$noo_site_secondary_link_color_fade_82      =   noo_landmark_func_css_fade( $noo_site_secondary_hover_color, '82%' );
$noo_site_secondary_link_color_fade_70      =   noo_landmark_func_css_fade( $noo_site_secondary_hover_color, '70%' );
$noo_site_secondary_link_color_fade_65      =   noo_landmark_func_css_fade( $noo_site_secondary_hover_color, '65%' );
$noo_site_secondary_link_color_darken_5    =   noo_landmark_func_css_darken( $noo_site_secondary_hover_color, '5%' );
$noo_site_secondary_link_color_darken_6    =   noo_landmark_func_css_darken( $noo_site_secondary_hover_color, '6%' );
$noo_site_secondary_link_color_darken_10    =   noo_landmark_func_css_darken( $noo_site_secondary_hover_color, '10%' );
$noo_site_secondary_link_color_lighten_10   =   noo_landmark_func_css_lighten( $noo_site_secondary_hover_color, '10%' );
$noo_site_secondary_link_color_lighten_15   =   noo_landmark_func_css_lighten( $noo_site_secondary_hover_color, '15%' );

// Variables Footer
$footer_background_on = get_theme_mod('noo_landmark_footer_background_on_off', 'true');
$noo_landmark_footer_color = get_theme_mod('noo_landmark_footer_color', noo_landmark_func_css_fade( '#fff', '80%' ));

?>
h1 a:hover, h2 a:hover, h3 a:hover, h4 a:hover, h5 a:hover, h6 a:hover,
.h1 a:hover, .h2 a:hover, .h3 a:hover, .h4 a:hover, .h5 a:hover, .h6 a:hover,
a:hover,
a:focus{
    color: <?php echo esc_html($noo_site_primary_hover_color); ?>;
}

/*
.pagination .page-numbers.current,
.pagination a.page-numbers:hover{
    background: <?php echo esc_html($noo_site_primary_hover_color); ?>;
    border-color: <?php echo esc_html($noo_site_primary_hover_color); ?>;
}
*/
.bg-primary-overlay:after{
	background: <?php echo esc_html($noo_site_primary_hover_color); ?>;
    opacity: 0.9;
}

.hentry.sticky:after,
#comments #respond .form-submit input:hover{
    background: <?php echo esc_html($noo_site_primary_hover_color); ?>;
}

.hentry.sticky{
    border-color: <?php echo esc_html($noo_site_primary_hover_color); ?>;
}

<?php if($footer_background_on == true): ?>
.main-footer:before {
	background: <?php echo esc_html($noo_landmark_footer_color); ?>;
}
<?php endif; ?>

/*
* Color Primary
* ===============================
*/
.bg-primary,
.btn-primary,
.btn-primary.disabled, .btn-primary[disabled], fieldset[disabled] .btn-primary, .btn-primary.disabled:hover, .btn-primary[disabled]:hover, fieldset[disabled] .btn-primary:hover, .btn-primary.disabled:focus, .btn-primary[disabled]:focus, fieldset[disabled] .btn-primary:focus, .btn-primary.disabled.focus, .btn-primary[disabled].focus, fieldset[disabled] .btn-primary.focus, .btn-primary.disabled:active, .btn-primary[disabled]:active, fieldset[disabled] .btn-primary:active, .btn-primary.disabled.active, .btn-primary[disabled].active, fieldset[disabled] .btn-primary.active,
.noo-button:before,
.noo-button-icon:before,
.noo-button-revert,
.noo-button-revert:before,
.hentry.sticky:after,
.noo-topbar,
.noo-main-menu .navbar-nav > li:hover > a::before,
.noo-main-menu .navbar-nav > li.current_page_item > a::before, .noo-main-menu .navbar-nav > li.current-menu-parent > a::before,
.navbar-meta.meta-property .meta-content .meta-property,
.navbar-meta.meta-social a,
.menu-header3 span,
.noo-page-heading .wrap-page-title,
.noo-blog-slider > .noo-owlslider > .sliders.owl-theme > .owl-controls .owl-buttons div:before,
.noo-sidebar-wrap .widget_price_filter .price_slider_amount .button:before,
.noo-box-property-slider .noo-action-slider > i, .noo-recent-property .noo-action-slider > i, .noo-agent .noo-action-slider > i, .noo-partner .noo-action-slider > i,
.noo-box-property-slider .noo-action-slider > i:after, .noo-recent-property .noo-action-slider > i:after, .noo-agent .noo-action-slider > i:after, .noo-partner .noo-action-slider > i:after,
.noo-property-floor-plan .noo-arrow-button .noo-arrow-back, .noo-property-floor-plan .noo-arrow-button .noo-arrow-next,
.noo-property-compare input[type="submit"],
.noo-control .ui-state-hover, .noo-control .ui-widget-content .ui-state-hover, .noo-control .ui-widget-header .ui-state-hover, .noo-control .ui-state-focus, .noo-control .ui-widget-content .ui-state-focus, .noo-control .ui-widget-header .ui-state-focus, .noo-control .ui-state-active, .noo-control .ui-widget-content .ui-state-active, .noo-control .ui-widget-header .ui-state-active,
.page-template-property-half-map .noo-header .navbar-header,
.page-template-header-half-map .header_half_map .navbar-header,
.noo-upload .noo-upload-main .noo-upload-left .noo-list-image .owl-controls .owl-next i, .noo-upload .noo-upload-main .noo-upload-left .noo-list-image .owl-controls .owl-prev i,
.noo-pricing-table-item .pricing-header,
.noo-pricing-table-item .pricing-header:before,
.noo-pricing-table-item .pricing-header:after,
.noo-box-map .noo-advanced-search-property-top-wrap,
.noo-box-map .noo-property-item-map-wrap .noo-action .more:hover,
.noo-agent .noo-content .agent-social a,
.noo-main-menu .navbar-nav .magic-line,
.noo-contact-form button[type='submit']:before,
.noo-main-menu .navbar-nav li > .sub-menu li:before,
.gmap-loading .gmap-loader > div {
	background-color: <?php echo esc_html($noo_site_primary_hover_color); ?>;
}

.text-primary,
.btn-primary .badge,
.noo-title-header .item-title,
.hentry .entry-content blockquote,
.noo-main-menu .navbar-nav li > a:hover, .noo-main-menu .navbar-nav li > a:focus, .noo-main-menu .navbar-nav li > a:active,
.noo-main-menu .navbar-nav li.current_page_item > a, .noo-main-menu .navbar-nav li.current-menu-parent > a,
.noo-main-menu .navbar-nav li > .sub-menu li a:hover,
.noo-main-menu .navbar-nav li > .sub-menu li:hover > a,
.noo-main-menu .navbar-nav li > .sub-menu li.current-menu-item > a,
.noo-main-menu .navbar-nav li:hover > a,
.noo-main-menu li.noo_megamenu > .sub-menu > li .noo_megamenu_widget_area li a:after,
.off-canvas #off-canvas-nav .noo-main-canvas .nav-collapse li.active > a,
.off-canvas #off-canvas-nav .noo-main-canvas .nav-collapse li.current-menu-parent > a,
.off-canvas #off-canvas-nav .noo-main-canvas .nav-collapse li.current-menu-item > a,
.off-canvas #off-canvas-nav .noo-main-canvas .nav-collapse li.current-menu-ancestor > a,
.off-canvas #off-canvas-nav .noo-main-canvas .nav-collapse li a:hover,
.noo-menu-option ul li a:hover, .noo-menu-option ul li a:focus,
.noo-menu-item-cart .cart-item span,
.noo-menu-item-cart .noo-minicart .minicart-body .cart-product .cart-product-details .cart-product-quantity,
.noo-tooltip,
.noo-property-compare .item-compare i:hover,
.noo-agent-list .noo-thumbnail .agent-social a,
.noo-agent-grid .noo-agent-info-more .agent-social a,
.noo-upload .noo-upload-main .noo-upload-left .owl-item i:hover,
.noo-upload .noo-upload-main .noo-upload-left .owl-item .item-image.featured i.set-featured.active,
.noo-theme-wraptext .wrap-title .noo-theme-title,
.noo-about-property .noo-title-header,
.noo-property-detail .noo-content .noo-title,
.noo-floor-plant .noo-floor-plant-header .noo-title,
.noo-progress .noo-progress-header .noo-title,
.noo-faq .noo-title .first-word,
.noo-faq .noo_faq_group .noo_faq_item h4,
.noo-faq .noo_faq_group .noo_faq_item h4:before,
.widget_noo_recent_property .noo-thumbnail a:after {
	color: <?php echo esc_html($noo_site_primary_hover_color); ?>;
}



.hentry.sticky,
.noo-control .ui-state-hover, .noo-control .ui-widget-content .ui-state-hover,
.noo-control .ui-widget-header .ui-state-hover, .noo-control .ui-state-focus,
.noo-control .ui-widget-content .ui-state-focus, .noo-control .ui-widget-header .ui-state-focus,
.noo-control .ui-state-active, .noo-control .ui-widget-content .ui-state-active,
.noo-control .ui-widget-header .ui-state-active {
	border-color: <?php echo esc_html($noo_site_primary_hover_color); ?>;
}

.noo-property-box-meta-content .noo-info,
.noo-property-box-meta-content .noo-item-head {
	border-bottom-color: <?php echo esc_html( noo_landmark_func_css_lighten( $noo_site_primary_hover_color, '14%' ) ); ?>;
}

/*
* Color Primary fade 95
* ===============================
*/
.bg-primary-overlay:after,
.noo-property-box-meta-content {
	background-color: <?php echo esc_html($noo_site_link_color_fade_95); ?>;
}
.noo-property-banner.style-2 .noo-property-box-meta-content:after {
	border-top-color: <?php echo esc_html($noo_site_link_color_fade_95); ?>;
}

/*
* Color Primary fade 90
* ===============================
*/
.noo-agent-contact:before,
.noo-agent-grid .noo-agent-info-more:before {
	background-color: <?php echo esc_html($noo_site_link_color_fade_90); ?>;
}

/*
* Color Primary fade 85
* ===============================
*/
.bg-primary-overlay-creative-1:after {
	background-color: <?php echo esc_html($noo_site_link_color_fade_85); ?>;
}
.bg-primary-overlay-creative-1 span.skew:after {
	border-left-color: <?php echo esc_html($noo_site_link_color_fade_85); ?>;
}
.bg-primary-overlay-creative-2:after {
	border-right-color: <?php echo esc_html($noo_site_link_color_fade_85); ?>;
}

/*
* Color Primary fade 80
* ===============================
*/
.noo-property-detail .noo-thumbnail > a:before,
.noo-video > a:before,
.noo-advanced-search-property .gmap-controls-wrap .gmap-controls,
.noo-advanced-search-property .gmap-controls-wrap .gmap-zoom > span,
.noo-advanced-search-property .gmap-controls-wrap .gmap-controls .map-view-type > span,
.noo-advanced-search-property .gm-svpc {
	background-color: <?php echo esc_html($noo_site_link_color_fade_80); ?> !important;
}

/*
* Color Primary fade 60
* ===============================
*/
.noo-agent-list .noo-thumbnail:before,
.noo-box-map .noo-property-item-map-wrap .noo-thumbnail a:before,
.noo-recent-property-wrap.style-2 .noo-item-featured a:after,
.noo-recent-property-wrap.style-4 .noo-property-item-wrap .noo-item-featured a:before,
.noo-sidebar-wrap .widget_gallery .widget_gallery_wrap .gallery-item:after,
.noo-gallery .galleries .gallery-item:after, 
.noo-testimonial .testimonial-content .bt-testimonial,
.noo-property-floor-plan-wrap .noo-property-floor-plan-item:before {
	background-color: <?php echo esc_html($noo_site_link_color_fade_60); ?>;
}
.noo-testimonial .testimonial-content .bt-testimonial:hover {
	background-color: <?php echo esc_html($noo_site_secondary_link_color_fade_70); ?>
}

/*
* Color Primary darken 10
* ===============================
*/
a.bg-primary:hover, a.bg-primary:focus,
.btn-primary:focus, .btn-primary.focus,
.btn-primary:hover,
.btn-primary:active, .btn-primary.active, .open > .dropdown-toggle.btn-primary {
	background-color: <?php echo esc_html($noo_site_link_color_darken_10); ?>;
}
a.text-primary:hover, a.text-primary:focus {
	color: <?php echo esc_html($noo_site_link_color_darken_10); ?>;
}

/*
* Color Primary darken 15
* ===============================
*/
.noo-property-compare input[type="submit"]:hover {
	background-color: <?php echo esc_html($noo_site_link_color_darken_15); ?>;
}
.noo-tooltip:hover {
	color: <?php echo esc_html($noo_site_link_color_darken_15); ?>;
}

/*
* Color Secondary
* ===============================
*/
.woocommerce-pagination .page-numbers.current, .pagination .page-numbers.current,
.woocommerce-pagination a.page-numbers:hover, .pagination a.page-numbers:hover,
.noo-pagination-loop a:hover, .noo-pagination-loop span:hover, .noo-pagination-loop a.current, .noo-pagination-loop span.current,
.wysihtml5-editor-toolbar [data-wysihtml5-command]:hover, .wysihtml5-editor-toolbar [data-wysihtml5-action]:hover, .wysihtml5-editor-toolbar .fore-color:hover,
.wysihtml5-editor-toolbar [data-wysihtml5-dialog-action="save"],
.noo-title-header .item-title .first-word:after,
.noo-button,
.noo-button-icon,
.noo-button-revert,
.noo-button-revert:before,
.hentry .tag-date,
.post-navigation .next-post:after, .post-navigation .prev-post:after,
.navbar-meta.meta-property .meta-content .meta-property span,
.navbar-meta.meta-social a::before,
.masonry-filters ul li a:before,
.noo-owlslider ul.products.owl-theme .owl-controls .owl-buttons div, .noo-owlslider .sliders.owl-theme .owl-controls .owl-buttons div,
.noo-blog-slider > .noo-owlslider > .sliders.owl-theme > .owl-controls .owl-buttons div,
.wrap-footer .widget-title::after,
.widget ul li a:hover:before,
.widget ul li.is-active a:before, .widget ul li.current-cat a:before,
.noo-sidebar-wrap .widget_price_filter .ui-slider .ui-slider-handle, .noo-sidebar-wrap .widget_price_filter .ui-slider .ui-slider-range,
.noo-sidebar-wrap .widget_price_filter .price_slider_amount .button,
.noo-box-property-slider .noo-action-slider > i:hover, .noo-recent-property .noo-action-slider > i:hover, .noo-agent .noo-action-slider > i:hover, .noo-partner .noo-action-slider > i:hover,
.noo-list-property .noo-property-item-wrap .noo-thumbnail .wait-approval, .noo-list-property .noo-property-item-wrap .noo-thumbnail .stock,
.noo-list-property .noo-property-item-wrap .noo-action-post span:hover,
.noo-list-property.style-grid .noo-property-item-wrap:after,
.noo-property-floor-plan .noo-arrow-button .noo-arrow-back:hover, .noo-property-floor-plan .noo-arrow-button .noo-arrow-next:hover,
.noo-property-comment .noo-loadmore-comment:before, .noo-property-comment .noo-loadmore-comment:after,
.noo-property-comment .noo-property-form-item:after,
.noo-stars-rating:before,
.noo-stars-rating span:before,
.noo-control .ui-state-default, .noo-control .ui-widget-content .ui-state-default, .noo-control .ui-widget-header .ui-state-default,
.page-template-property-half-map .noo-property-item-wrap:after,
.page-template-property-half-map .noo-item-featured .noo-price,
.agent-social a:hover,
.noo-agent-contact .noo-box-text-field:after,
.noo-agent-contact .noo-box-textarea-field:after,
.noo-agent-contact .noo-submit,
.noo-agent-list .noo-thumbnail .agent-social a:hover,
.noo-agent-grid .noo-agent-info-more .agent-social a:hover,
.noo-register-member-tab > span.active, .noo-register-member-tab > span:hover,
.noo-register-member-left .noo-item-wrap#noo-item-agree-term-of-service-wrap .noo-item-checkbox .checked label, .noo-login-member .noo-item-wrap#noo-item-agree-term-of-service-wrap .noo-item-checkbox .checked label,
.noo-register-member-right .noo-register-member-notice ul.noo-register-member-list li:before,
.noo-box-contact-form-7 p span:after,
.noo-box-contact-form-7 .wpcf7-submit,
.noo-upload .noo-upload-main .noo-upload-left .noo-list-image .owl-controls .owl-next i:hover, .noo-upload .noo-upload-main .noo-upload-left .noo-list-image .owl-controls .owl-prev i:hover,
.noo-testimonial .testimonial-content .slick-dots .slick-active button,
.noo-mailchimp .noo-mailchimp-main:after,
.noo-pricing-table-item .pricing-header .pricing-line:before,
.noo-pricing-table-item .pricing-header .pricing-line:after,
.noo-box-map .noo-advanced-search-property-top button,
.noo-box-map .noo-advanced-search-property-wrap .noo-label-form,
.noo-box-map .noo-property-item-map-wrap .noo-action .more,
.noo-recent-property-wrap.style-2 .noo-property-item-wrap:after,
.noo-recent-property-wrap.style-3 .noo-property-item-wrap .noo-item-featured .noo-price,
.noo-recent-property-wrap.style-3 .noo-property-item-wrap:after,
.noo-recent-property-wrap.style-4 .noo-property-item-wrap .noo-item-featured .noo-price,
.noo-recent-property-wrap.style-4 .noo-slider-pagination > span.swiper-pagination-bullet-active, .noo-recent-property-wrap.style-4 .noo-slider-pagination > span:hover,
.noo-service.style-2 .noo-service-main .noo-service-item .noo-service-icon .icon:after,
.noo-agent .noo-thumbnail .line > span:before,
.noo-agent .noo-thumbnail .line > span:after,
.noo-agent .noo-content .agent-social a:hover,
.noo-property-detail .noo-thumbnail > a > span,
.noo-floor-plant .noo-content .noo-tab > span:after,
.noo-ads-banner .noo-action a,
.noo-property-banner.style-2 .noo-property-box-meta-content:before,
.noo-progress .noo-progress-bar .noo-single-bar .noo-bar,
.noo-video > a > span,
.noo-information .wrap-info .info-item .info-icon,
.noo-contact-form button[type='submit'],
.widget_noo_advanced_search_property .chosen-container-single .chosen-single:hover div b:before,
.noo-advanced-search-property .gmap-controls-wrap .gmap-controls > div:hover,
.noo-mortgage-paymant-calculator .ui-slider .ui-widget-header,
.noo-mortgage-paymant-calculator .ui-slider .ui-slider-handle,
.noo-mortgage-paymant-calculator .spinner > div,
.noo-property-yelp .yelp-cat-item .cat-title .yelp-cat-icon,
.noo-header-advance .header-control span.active, .noo-header-advance .header-control span:hover {
	background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
}
.noo-advanced-search-property .gmap-controls-wrap .gmap-zoom > span:hover {
	background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?> !important;
}

.noo-icon-map {
	color: <?php echo esc_html($noo_site_secondary_hover_color); ?> !important;
}

.noo-control .ui-slider.ui-widget-content .ui-widget-header,
.noo-advanced-search-property .gm-svpc:hover {
	background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?> !important;
}

.navbar-meta.meta-property .meta-content .meta-property::before {
	background-color: <?php echo esc_html( noo_landmark_func_css_lighten( $noo_site_secondary_hover_color, '1%' ) ); ?>;
}

.main-footer ul.contact-info li i {
	background-color: <?php echo esc_html( noo_landmark_func_css_lighten( $noo_site_secondary_hover_color, '11%' ) ); ?>;
}

.noo-service.style-1 .noo-service-main .noo-service-item .noo-service-icon span {
	background: -moz-radial-gradient(center, ellipse cover, <?php echo esc_html($noo_site_secondary_hover_color); ?> 0%, <?php echo esc_html($noo_site_secondary_link_color_darken_5); ?> 100%); /* ff3.6+ */
	background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, <?php echo esc_html($noo_site_secondary_hover_color); ?>), color-stop(100%, <?php echo esc_html($noo_site_secondary_link_color_darken_5); ?>)); /* safari4+,chrome */
	background:-webkit-radial-gradient(center, ellipse cover, <?php echo esc_html($noo_site_secondary_hover_color); ?> 0%, <?php echo esc_html($noo_site_secondary_link_color_darken_5); ?> 100%); /* safari5.1+,chrome10+ */
	background: -o-radial-gradient(center, ellipse cover, <?php echo esc_html($noo_site_secondary_hover_color); ?> 0%, <?php echo esc_html($noo_site_secondary_link_color_darken_5); ?> 100%); /* opera 11.10+ */
	background: -ms-radial-gradient(center, ellipse cover, <?php echo esc_html($noo_site_secondary_hover_color); ?> 0%, <?php echo esc_html($noo_site_secondary_link_color_darken_5); ?> 100%); /* ie10+ */
	background:radial-gradient(ellipse at center, <?php echo esc_html($noo_site_secondary_hover_color); ?> 0%, <?php echo esc_html($noo_site_secondary_link_color_darken_5); ?> 100%); /* w3c */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fbb040', endColorstr='<?php echo esc_html($noo_site_secondary_link_color_darken_5); ?>',GradientType=1 ); /* ie6-9 */
}

/**
 * Keyframe
 */
 @keyframes preload_audio_wave {
  0% {
    height: 5px;
    transform: translateY(0px);
    background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
  }
  25% {
    height: 30px;
    transform: translateY(15px);
    background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
  }
  50% {
    height: 5px;
    transform: translateY(0px);
    background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
  }
  100% {
    height: 5px;
    transform: translateY(0px);
    background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
  }
}
@-webkit-keyframes preload_audio_wave {
  0% {
    height: 5px;
    -webkit-transform: translateY(0px);
    background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
  }
  25% {
    height: 30px;
    -webkit-transform: translateY(15px);
    background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
  }
  50% {
    height: 5px;
    -webkit-transform: translateY(0px);
    background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
  }
  100% {
    height: 5px;
    -webkit-transform: translateY(0px);
    background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
  }
}
@-moz-keyframes preload_audio_wave {
  0% {
    height: 5px;
    -moz-transform: translateY(0px);
    background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
  }
  25% {
    height: 30px;
    -moz-transform: translateY(15px);
    background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
  }
  50% {
    height: 5px;
    -moz-transform: translateY(0px);
    background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
  }
  100% {
    height: 5px;
    -moz-transform: translateY(0px);
    background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
  }
}
@keyframes preload_audio_wave {
  0% {
    height: 5px;
    transform: translateY(0px);
    background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
  }
  25% {
    height: 30px;
    transform: translateY(15px);
    background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
  }
  50% {
    height: 5px;
    transform: translateY(0px);
    background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
  }
  100% {
    height: 5px;
    transform: translateY(0px);
    background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
  }
}

a:hover, a:focus,
.btn-link:hover, .btn-link:focus,
.noo-title,
.noo-title-header .item-title .first-word,
.notice a,
.hentry .item-info a:hover,
.hentry .entry-content blockquote:after, .hentry .entry-content blockquote:before,
.hentry.grid .entry-footer .noo-button:hover,
.noo-topbar .noo-topmeta a:hover,
.noo-topbar .noo-topmeta ul li a:hover,
.navbar-meta.meta-phone .meta-content .fa-mobile,
.noo-menu-item-cart .noo-minicart .minicart-body .cart-product .cart-product-details .cart-product-title a:hover,
.noo-page-heading .page-title,
.masonry-filters ul li a:hover, .masonry-filters ul li a.selected,
.wrap-footer .widget-title,
.copyright .copyright-social a:hover i,
.copyright .text-primary,
.widget ul li.is-active a, .widget ul li.current-cat a,
.noo-sidebar-wrap .widget-title,
.noo-item-wrap.validate-error-tooltip .error-notice,
.noo-list-property .noo-property-item-wrap .noo-action-post i.is-featured:before, .noo-list-property .noo-property-item-wrap .noo-action-post i.unavailable:before,
.noo-list-property .noo-property-item-wrap .noo-action-post i.fa-heart:before,
.noo-list-property .noo-property-item-wrap .noo-action-post .noo-property-sharing .noo-social-property i:hover:before,
.noo-list-property .noo-property-item-wrap .noo-genarel .noo-title:hover,
.noo-list-property .noo-property-item-wrap .noo-info > span > i,
.noo-list-property .noo-property-item-wrap .noo-info > span:before,
.noo-list-property .noo-property-item-wrap:hover .noo-action .noo-price,
.noo-list-property .listing .noo-property-item-wrap .noo-action-post i.fa-heart:before,
.noo-list-property.style-grid .noo-property-item-wrap .item-title .ion-bookmark,
.noo-list-property.style-grid .noo-property-item-wrap .noo-action-post i.fa-heart:before,
.noo-list-property.style-grid .noo-property-item-wrap .noo-action-post .noo-property-sharing .noo-social-property i:hover:before,
.noo-single-property-detail .noo-property-box .noo-content-box .noo-content-box-item.stock > span,
.noo-single-property-detail .noo-property-box .noo-content-box.feature .noo-content-box-item > i,
.noo-detail-header .noo-action-post i:hover,
.noo-detail-header .noo-action-post i.fa-heart,
.noo-detail-header .noo-property-sharing .noo-social-property i:hover:before,
.noo-property-gallery .noo-arrow-button .noo-arrow-back:hover, .noo-property-gallery .noo-arrow-button .noo-arrow-next:hover,
.noo-property-box-meta-content .noo-item-head .item-title a:hover,
.noo-property-comment .noo-loadmore-comment > span:hover,
.noo-property-comment .noo-property-form-item.validate-error:before,
.noo-rating > input:checked ~ label, .noo-rating:not(:checked) > label:hover, .noo-rating:not(:checked) > label:hover ~ label,
.noo-property-compare .item-compare i,
.noo-compare-wrap .noo-compare-item .property-price,
.noo-compare-list .item-value .ion-checkmark,
.page-template-property-half-map .noo-item-head .item-title .ion-bookmark,
.page-template-property-half-map .noo-list-property .noo-loading-property i,
.page-template-property-half-map .noo-list-menu-header li:hover a,
.noo-box-menu .noo-list-menu li a.noo-item.active,
.noo-box-menu .noo-list-menu li a.noo-item.active i,
.noo-box-menu .noo-list-menu li:hover a.noo-item, .noo-box-menu .noo-list-menu li.active a.noo-item, .noo-box-menu .noo-list-menu li:hover i, .noo-box-menu .noo-list-menu li.active i,
.noo-box-menu .noo-box-title,
.noo-box-menu form.noo-box-content .noo-more-detail:hover,
.noo-agent-detail .noo-box-content ul.item-info li:hover a, .noo-agent-detail .noo-box-content ul.item-info li:hover:before,
.noo-agent-grid .noo-agent-info-more .noo-title a,
.noo-box-login form .noo-login-member-action > p a,
.noo-box-login form .noo-register-member-action > p a,
.noo-register-member-left .noo-item-wrap#noo-item-agree-term-of-service-wrap .noo-item-checkbox > label a, .noo-login-member .noo-item-wrap#noo-item-agree-term-of-service-wrap .noo-item-checkbox > label a,
.noo-box-contact-form-7 p span .wpcf7-not-valid-tip,
.noo-upload .noo-upload-main .noo-upload-left .owl-item .item-image.featured i.set-featured.active:hover,
.noo-upload .noo-upload-main .noo-upload-right .btn-upload i:hover:before,
.noo-upload.slider .upload-show-more:hover,
.noo-upload.slider .noo-upload-left.upload-show-box-more .upload-close-more:hover,
.noo-theme-wraptext .wrap-title .noo-theme-title .first-word,
.noo-theme-wraptext .wrap-title .noo-theme-sub-title .icon-decotitle,
.noo-testimonial .style-2 .testimonial-content .noo-testimonial-item .noo-testimonial-content:before,
.noo-testimonial .style-2 .testimonial-content .noo-testimonial-item .noo-testimonial-content:after,
.noo-mailchimp .noo-mailchimp-main:hover i,
.noo-ads-service .noo-ads-phone .noo-ads-desc a:hover,
.noo-pricing-table-item .pricing-header .pricing-title,
.noo-pricing-table-item ul.pricing-body li span:not(.unlimited),
.noo-box-map .noo-advanced-search-property-wrap .noo-action-search-top .show-filter-property:hover,
.noo-box-map .noo-property-item-map-wrap .noo-content .noo-info > span > i,
.noo-box-map .noo-property-item-map-wrap .noo-content .noo-info > span:before,
.noo-advanced-search-property-form .show-features:hover,
.noo-results-property .noo-loading-property i,
.noo-recent-property-wrap.style-2 .noo-property-item-wrap .ion-bookmark,
.noo-recent-property-wrap.style-3 .noo-property-item-wrap .noo-item-head .item-title .ion-bookmark,
.noo-recent-property-wrap.style-4 .noo-property-item-wrap .item-title .ion-bookmark,
.noo-service.style-1 .noo-service-main .noo-service-item .noo-service-icon,
.noo-service.style-2 .noo-service-main .noo-service-item .noo-service-icon:hover .icon,
.noo-about-property .noo-about-property-item .noo-icon i,
.noo-featured .noo-featured-main .noo-view .noo-readmore:hover,
.noo-property-detail .noo-content-box.feature .noo-content-box-item > i,
.noo-floor-plant .noo-content .noo-tab > span:hover, .noo-floor-plant .noo-content .noo-tab > span.active,
.noo-floor-plant .box-features > ul li:hover,
.noo-advanced-search-property .gmap-controls-wrap .gmap-controls .map-view-type > span:hover,
.noo-mailchimp .mc4wp-response *,
.noo-testimonial .noo-property-title a:hover, .noo-testimonial .style-2 .noo-property-title a:hover,
.noo-prpoerty-walkscore .walkscore_details a,
.noo-single-property-detail.tab .noo-detail-tabs .noo-tab>span.active, .noo-single-property-detail.tab .noo-detail-tabs .noo-tab>span:hover {
	color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
}

.noo-mailchimp .noo-mailchimp-main input:focus::-moz-placeholder, .noo-mailchimp .noo-mailchimp-main input:hover::-moz-placeholder,
.noo-mailchimp .noo-mailchimp-main input:focus:-ms-input-placeholder, .noo-mailchimp .noo-mailchimp-main input:hover:-ms-input-placeholder,
.noo-mailchimp .noo-mailchimp-main input:focus::-webkit-input-placeholder, .noo-mailchimp .noo-mailchimp-main input:hover::-webkit-input-placeholder,
.noo-agent-contact .noo-box-text-field input[type="text"]:focus::-moz-placeholder, .noo-agent-contact .noo-box-text-field input[type="text"]:hover::-moz-placeholder,
.noo-agent-contact .noo-box-text-field input[type="text"]:focus:-ms-input-placeholder, .noo-agent-contact .noo-box-text-field input[type="text"]:hover:-ms-input-placeholder,
.noo-agent-contact .noo-box-text-field input[type="text"]:focus::-webkit-input-placeholder, .noo-agent-contact .noo-box-text-field input[type="text"]:hover::-webkit-input-placeholder,
.noo-agent-contact .noo-box-text-field textarea:focus::-moz-placeholder, .noo-agent-contact .noo-box-text-field textarea:hover::-moz-placeholder,
.noo-agent-contact .noo-box-text-field textarea:focus:-ms-input-placeholder, .noo-agent-contact .noo-box-text-field textarea:hover:-ms-input-placeholder,
.noo-agent-contact .noo-box-text-field textarea:focus::-webkit-input-placeholder, .noo-agent-contact .noo-box-text-field textarea:hover::-webkit-input-placeholder {
	color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
}

.noo-title-header .item-title .first-word > span,
.hentry .entry-footer .single-tag a:hover,
#comments #respond .comment-form input[type='text']:focus, #comments #respond .comment-form input[type='email']:focus, #comments #respond .comment-form input[type='text']:active, #comments #respond .comment-form input[type='email']:active,
#comments #respond .comment-form textarea:focus, #comments #respond .comment-form textarea:active,
.noo-sidebar-wrap .widget_product_search input[type="search"]:active, .noo-sidebar-wrap .widget_search input[type="search"]:active, .noo-sidebar-wrap .widget_product_search input[type="search"]:focus, .noo-sidebar-wrap .widget_search input[type="search"]:focus,
.noo-sidebar-wrap .widget_product_tag_cloud a:hover, .noo-sidebar-wrap .widget_tag_cloud a:hover,
.lg-outer .lg-thumb-outer .lg-thumb-item:hover, .lg-outer .lg-thumb-outer .lg-thumb-item.active,
.noo-item-wrap > input[type="url"]:focus, .noo-item-wrap > input[type="number"]:focus, .noo-item-wrap > input[type="password"]:focus, .noo-item-wrap > input[type="text"]:focus,
.noo-advanced-search-property .gmap-controls-wrap .box-search-map > input[type="text"]:focus,
.noo-item-wrap textarea:focus,
.noo-item-wrap .noo-item-checkbox .checked label:after,
.noo-property-box-meta-content,
.noo-control .ui-state-default, .noo-control .ui-widget-content .ui-state-default, .noo-control .ui-widget-header .ui-state-default,
.noo-register-member-left .noo-item-wrap#noo-item-agree-term-of-service-wrap .noo-item-checkbox .checked label, .noo-login-member .noo-item-wrap#noo-item-agree-term-of-service-wrap .noo-item-checkbox .checked label,
.noo-box-contact-form-7 p input[type="text"]:focus, .noo-box-contact-form-7 p input[type="email"]:focus, .noo-box-contact-form-7 p textarea:focus,
.noo-box-contact-form-7 .wpcf7-response-output,
.noo-featured .noo-featured-main .noo-view p,
.noo-contact-form input[type='text']:focus, .noo-contact-form input[type='email']:focus, .noo-contact-form input[type='text']:active, .noo-contact-form input[type='email']:active,
.noo-contact-form textarea:focus, .noo-contact-form textarea:active,
.widget_noo_advanced_search_property .chosen-container-single .chosen-single:hover,
.widget_noo_advanced_search_property .chosen-container-single .chosen-single:hover div b:before {
	border-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
}

.hentry.list-2-column .content-featured .tag-date:before,
.hentry.grid .content-featured .tag-date:before {
	border-top-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
}

.hentry .tag-date:before {
	border-bottom-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
}

.noo-service.style-2 .noo-service-main .noo-service-item .noo-service-icon .icon {
	border-color: <?php echo esc_html( noo_landmark_func_css_lighten( $noo_site_secondary_hover_color, '12%' ) ); ?>;
}

/*
* Color Secondary fade 82
* ===============================
*/
.woocommerce ul.products li.noo-product.product-category .noo-loop-item:hover h3 {
	background-color: <?php echo esc_html($noo_site_secondary_link_color_fade_82); ?>;
}

/*
* Color Secondary fade 65
* ===============================
*/
.noo-service.style-1 .noo-service-main .noo-service-item:after {
	-webkit-box-shadow: 0 0 85px 15px <?php echo esc_html($noo_site_secondary_link_color_fade_65); ?>;
    box-shadow: 0 0 85px 15px <?php echo esc_html($noo_site_secondary_link_color_fade_65); ?>;
}

/*
* Color Secondary darken 10
* ===============================
*/
.noo-ads-banner .noo-action a:hover:before {
	background-color: <?php echo esc_html($noo_site_secondary_link_color_darken_10); ?>;
}

/*
* Color Secondary darken 15
* ===============================
*/
.wysihtml5-editor-toolbar [data-wysihtml5-dialog-action="save"]:hover,
.noo-agent-contact .noo-submit:hover:before,
.noo-box-contact-form-7 .wpcf7-submit:hover,
.noo-pricing-table-item-wrap.is_featured .noo-pricing-table-item .pricing-header,
.noo-pricing-table-item-wrap.is_featured .noo-pricing-table-item .pricing-header:before, .noo-pricing-table-item-wrap.is_featured .noo-pricing-table-item .pricing-header:after,
.noo-box-map .noo-advanced-search-property-top button:hover {
	background-color: <?php echo esc_html($noo_site_secondary_link_color_darken_6); ?>;
}

.noo-agent-grid .noo-agent-info-more .noo-title a:hover {
	color: <?php echo esc_html($noo_site_secondary_link_color_darken_6); ?>;
}

/*
* Color Secondary lighten 10
* ===============================
*/
.noo-video > a > span,
.noo-property-detail .noo-thumbnail > a > span {
	-webkit-box-shadow: 0 0 0 5px <?php echo esc_html($noo_site_secondary_link_color_lighten_10); ?>;
  	box-shadow: 0 0 0 5px <?php echo esc_html($noo_site_secondary_link_color_lighten_10); ?>;
}

/*
* Color Secondary lighten 15
* ===============================
*/
.noo-rating > input:checked + label:hover,
.noo-rating > input:checked ~ label:hover,
.noo-rating > label:hover ~ input:checked ~ label,
.noo-rating > input:checked ~ label:hover ~ label {
	color: <?php echo esc_html($noo_site_secondary_link_color_lighten_15); ?>;
}

/*
* Woocommerce - Color Primary
* ===============================
*/
.noo-button:before,
.noo-button-icon:before,
.noo-button-revert,
.noo-button-revert:before,
.woocommerce-info a.button:before, .woocommerce-error a.button:before, .woocommerce-message a.button:before,
.woocommerce .compare.button:before,
.woocommerce .yith-wcwl-add-to-wishlist [class^="yith-wcwl-"] a:before,
.woocommerce div.product .noo-single-product-header .single_add_to_cart_button,
.woocommerce div.product .noo-single-product-header .single_add_to_cart_button:before,
.woocommerce div.product .noo-single-product-header .single_add_to_cart_button.disabled,
.woocommerce div.product .noo-single-product-header form.cart .compare-button a:before,
.woocommerce ul.products li.noo-product .noo-loop-item .noo-loop-featured-item .custom-action .add_to_cart_button:before, .woocommerce ul.products li.noo-product .noo-loop-item .noo-loop-featured-item .custom-action .noo-quick-view:before,
.woocommerce ul.products.product-list li.noo-product:not(.product-category) .noo-loop-item div.noo-loop-info-item .noo-quick-view:before,
.woocommerce ul.products.product-list li.noo-product:not(.product-category) .noo-loop-item div.noo-loop-info-item .add_to_cart_button,
.woocommerce ul.products.product-list li.noo-product:not(.product-category) .noo-loop-item div.noo-loop-info-item .add_to_cart_button:before,
.woocommerce .noo-upsell-products .noo-owlslider ul.products.owl-theme .owl-controls .owl-buttons div:before, .woocommerce .noo-related-products .noo-owlslider ul.products.owl-theme .owl-controls .owl-buttons div:before,
.woocommerce .return-to-shop a:before,
.woocommerce table.shop_table.cart th,
.woocommerce table.shop_table td.actions input.button:before,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button:before,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover,
.woocommerce input.button:before,
.woocommerce table.wishlist_table.shop_table td.product-add-to-cart a,
.woocommerce table.wishlist_table.shop_table td.product-add-to-cart a:before,
.woocommerce table.wishlist_table.shop_table td.product-add-to-cart a:hover,
.quick-content .quick-right a.button, .quick-content .quick-right button.button,
.quick-content .quick-right a.button:before, .quick-content .quick-right button.button:before,
.quick-content .quick-right a.button:hover, .quick-content .quick-right button.button:hover,
.widget_shopping_cart_content .noo_wrap_minicart .bx-controls-direction a,
.widget_shopping_cart_content .noo_wrap_minicart p.buttons .button,
.noo-header-advance .header-control span {
	background-color: <?php echo esc_html($noo_site_primary_hover_color); ?>;
}

.woocommerce div.product .noo-single-product-header .price_and_stock .status-stock span,
.woocommerce div.product .noo-single-product-header div.noo_variations .pa_refresh a,
.woocommerce table.wishlist_table.shop_table tr td.product-stock-status span.wishlist-in-stock {
	color: <?php echo esc_html($noo_site_primary_hover_color); ?>;
}

/*
* Woocommerce - Color Primary darken 10
* ===============================
*/
.widget_shopping_cart_content .noo_wrap_minicart .bx-controls-direction a:hover,
.widget_shopping_cart_content .noo_wrap_minicart p.buttons .button:hover {
	background-color: <?php echo esc_html($noo_site_link_color_darken_10); ?>;
}

/*
* Woocommerce - Color Secondary
* ===============================
*/
.noo-button-icon,
.noo-button-revert,
.noo-button-revert:before,
.woocommerce-info a.button, .woocommerce-error a.button, .woocommerce-message a.button,
.woocommerce-info a.button:hover, .woocommerce-error a.button:hover, .woocommerce-message a.button:hover, .woocommerce-info a.button:focus, .woocommerce-error a.button:focus, .woocommerce-message a.button:focus,
.woocommerce .compare.button,
.woocommerce .yith-wcwl-add-to-wishlist [class^="yith-wcwl-"] a,
.woocommerce div.product .woocommerce-tabs.wc-tabs-wrapper ul.wc-tabs li:before,
.woocommerce div.product .noo-single-product-header .single_add_to_cart_button,
.woocommerce div.product .noo-single-product-header .single_add_to_cart_button:before,
.woocommerce div.product .noo-single-product-header form.cart .compare-button a,
.woocommerce ul.products li.noo-product .noo-loop-item .noo-loop-featured-item .custom-action .compare-button .compare.button:hover,
.woocommerce ul.products li.noo-product .noo-loop-item .noo-loop-featured-item .custom-action .add_to_cart_button, .woocommerce ul.products li.noo-product .noo-loop-item .noo-loop-featured-item .custom-action .noo-quick-view,
.woocommerce ul.products.product-list li.noo-product:not(.product-category) .noo-loop-item div.noo-loop-info-item .compare-button .compare.button:hover,
.woocommerce ul.products.product-list li.noo-product:not(.product-category) .noo-loop-item div.noo-loop-info-item .noo-quick-view,
.woocommerce ul.products.product-list li.noo-product:not(.product-category) .noo-loop-item div.noo-loop-info-item .add_to_cart_button,
.woocommerce ul.products.product-list li.noo-product:not(.product-category) .noo-loop-item div.noo-loop-info-item .add_to_cart_button:before,
.woocommerce .noo-upsell-products .noo-owlslider ul.products.owl-theme .owl-controls .owl-buttons div, .woocommerce .noo-related-products .noo-owlslider ul.products.owl-theme .owl-controls .owl-buttons div,
.woocommerce .return-to-shop a,
.woocommerce .return-to-shop a:hover,
.woocommerce table.shop_table td.actions input.button,
.woocommerce table.shop_table td.actions input.button:disabled[disabled]:hover,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
.woocommerce-cart .wc-proceed-to-checkout a.checkout-button:before,
.woocommerce input.button,
.woocommerce input.button.alt,
.woocommerce-account a.button,
.woocommerce table.wishlist_table.shop_table td.product-add-to-cart a,
.woocommerce table.wishlist_table.shop_table td.product-add-to-cart a:before,
.quick-content .quick-right a.button, .quick-content .quick-right button.button,
.quick-content .quick-right a.button:before, .quick-content .quick-right button.button:before,
.noo-header-minicart .widget_shopping_cart_content .minicart-link-hover span,
.widget_shopping_cart_content .noo_wrap_minicart p.buttons .button.checkout {
	background-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
}

.woocommerce .noo-quantity-attr button:active,
.woocommerce div.product .woocommerce-tabs.wc-tabs-wrapper ul.wc-tabs li a:hover,
.woocommerce p.stars a,
.woocommerce .star-rating:before,
.woocommerce .star-rating span,
.woocommerce .noo-toolbar-products .product-style-control span.active,
.woocommerce .noo-toolbar-products select:not([multiple]),
.woocommerce-cart .cart-collaterals .cross-sells h2,
.woocommerce h3#order_review_heading {
	color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
}

.woocommerce #reviews #review_form #respond .comment-form input[type='text']:focus, .woocommerce #reviews #review_form #respond .comment-form input[type='email']:focus, .woocommerce #reviews #review_form #respond .comment-form input[type='text']:active, .woocommerce #reviews #review_form #respond .comment-form input[type='email']:active,
.woocommerce #reviews #review_form #respond .comment-form textarea:focus, .woocommerce #reviews #review_form #respond .comment-form textarea:active,
.woocommerce div.product .noo-single-product-header div.noo_variations .pa_type.pa_size .pa_item input:checked,
.woocommerce div.product .noo-woo-thumbnails__slide.noo-woo-thumbnails__slide--active .noo-woo-thumbnails__image, .woocommerce div.product .noo-woo-thumbnails__slide:hover .noo-woo-thumbnails__image {
	border-color: <?php echo esc_html($noo_site_secondary_hover_color); ?>;
}

/*
* Woocommerce - Color Secondary darken 10
* ===============================
*/
.woocommerce table.shop_table td.actions input.button:hover,
.woocommerce input.button:hover,
.woocommerce input.button.alt:hover,
.woocommerce-account a.button:hover,
.widget_shopping_cart_content .noo_wrap_minicart p.buttons .button.checkout:hover {
	background-color: <?php echo esc_html($noo_site_secondary_link_color_darken_10); ?>;	
}