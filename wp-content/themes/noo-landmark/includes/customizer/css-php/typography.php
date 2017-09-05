<?php
// Use Custom Headings Font
$noo_typo_use_custom_headings_font = get_theme_mod( 'noo_typo_use_custom_headings_font', false );
// Use Custom Body Font
$noo_typo_use_custom_body_font     = get_theme_mod( 'noo_typo_use_custom_body_font', false );

if( $noo_typo_use_custom_headings_font ) :
    $noo_typo_text_transform = !empty( $noo_typo_headings_uppercase ) ? 'uppercase' : 'none';
    $noo_typo_headings_font = get_theme_mod( 'noo_typo_headings_font', noo_landmark_func_get_theme_default( 'headings_font_family' ) );
    $noo_typo_headings_font_color =  get_theme_mod( 'noo_typo_headings_font_color', noo_landmark_func_get_theme_default( 'headings_color' ) );
?>
    /* Headings */
    /* ====================== */
    h1, h2, h3, h4, h5, h6,
    .h1, .h2, .h3, .h4, .h5, .h6, .noo-button{
        font-family:    "<?php echo esc_html( $noo_typo_headings_font ); ?>", sans-serif;
        color:          <?php echo esc_html( $noo_typo_headings_font_color ); ?>;
        text-transform: <?php echo esc_html( $noo_typo_text_transform ); ?>;
    }

    .noo-button-icon,
    .noo-button-revert,
    .hentry .entry-content blockquote,
    #comments ol li .comment-wrap .comment-block .comment-header .comment-author,
    #comments ul li .comment-wrap .comment-block .comment-header .comment-author,
    .noo-main-menu .navbar-nav li > a,
    .masonry-filters,
    .noo-blog-slider > .noo-owlslider > .sliders.owl-theme > .owl-controls .owl-buttons div,
    .noo-sidebar-wrap .widget ul.product_list_widget li a,
    .noo-sidebar-wrap .widget_price_filter .price_slider_amount .button,
    .noo-item-wrap > label,
    .page-template-property-half-map .noo-header .navbar-nav li > a,
    .noo-box-menu ul.noo-box-content li.package-title,
    .noo-agent-contact .noo-submit,
    .noo-agent-grid .noo-agent-info-general .noo-agent-name,
    .noo-box-contact-form-7 .wpcf7-submit,
    .noo-sc-caption:before,
    .noo-box-map .noo-advanced-search-property-top button,
    .noo-box-map .noo-advanced-search-property-wrap .noo-label-form,
    .noo-floor-plant .noo-content .noo-tab > span,
    .noo-floor-plant .box-features > label,
    .noo-ads-banner .noo-action a,
    .noo-contact-form button[type='submit'],
    .woocommerce-info a.button, .woocommerce-error a.button, .woocommerce-message a.button,
    .woocommerce .compare.button,
    .woocommerce .yith-wcwl-add-to-wishlist [class^="yith-wcwl-"] a,
    .woocommerce div.product .woocommerce-tabs.wc-tabs-wrapper ul.wc-tabs li a,
    .woocommerce div.product .noo-single-product-header div.noo_variations .pa_type label,
    .woocommerce div.product .noo-single-product-header .single_add_to_cart_button,
    .woocommerce div.product .noo-single-product-header form.cart .compare-button a,
    .woocommerce ul.products li.noo-product .noo-loop-item .noo-loop-featured-item .custom-action .add_to_cart_button, .woocommerce ul.products li.noo-product .noo-loop-item .noo-loop-featured-item .custom-action .noo-quick-view,
    .woocommerce ul.products.product-list li.noo-product:not(.product-category) .noo-loop-item div.noo-loop-info-item .noo-quick-view,
    .woocommerce ul.products.product-list li.noo-product:not(.product-category) .noo-loop-item div.noo-loop-info-item .add_to_cart_button,
    .woocommerce .noo-upsell-products .noo-owlslider ul.products.owl-theme .owl-controls .owl-buttons div, .woocommerce .noo-related-products .noo-owlslider ul.products.owl-theme .owl-controls .owl-buttons div,
    .woocommerce .return-to-shop a,
    .woocommerce table.shop_table.cart th,
    .woocommerce table.shop_table td.actions input.button,
    .woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
    .woocommerce input.button,
    .woocommerce table.wishlist_table.shop_table td.product-add-to-cart a,
    .quick-content .quick-right a.button, .quick-content .quick-right button.button,
    .widget_shopping_cart_content .noo_wrap_minicart p.buttons .button {
        font-family:    "<?php echo esc_html( $noo_typo_headings_font ); ?>", sans-serif;
    }

<?php endif; ?>
<?php
if( $noo_typo_use_custom_body_font ) :
    $noo_typo_body_font = get_theme_mod( 'noo_typo_body_font', noo_landmark_func_get_theme_default( 'font_family' ) );
    $noo_typo_body_font_color = get_theme_mod( 'noo_typo_body_font_color', noo_landmark_func_get_theme_default( 'text_color' ) );
    $noo_typo_body_font_size = get_theme_mod( 'noo_typo_body_font_size', noo_landmark_func_get_theme_default( 'font_size' ) );
?>
    /* Body style */
    /* ===================== */
     body {
        font-family: "<?php echo esc_html( $noo_typo_body_font ); ?>", sans-serif;
        color:        <?php echo esc_html( $noo_typo_body_font_color ); ?>;
        font-size:    <?php echo esc_html( $noo_typo_body_font_size ) . 'px'; ?>;
    }

    .hentry .content-featured .blog-quote .content-title,
    .hentry.grid .entry-footer .noo-button,
    #comments #respond .comment-reply-title small,
    .noo-main-menu .navbar-nav li > .sub-menu li a,
    .noo-register-member-right .noo-register-member-notice > h3,
    .noo-faq .noo_faq_group .noo_faq_item h4,
    .woocommerce #reviews #review_form #respond h3,
    .woocommerce #reviews #comments h2 {
        font-family: "<?php echo esc_html( $noo_typo_body_font ); ?>", sans-serif;
    }
    
<?php endif; ?>