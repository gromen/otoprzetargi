<?php
$blog_name           = get_bloginfo( 'name' );
$blog_desc           = get_bloginfo( 'description' );
$image_logo          = '';
$page_logo           = '';
$menu_style          = get_theme_mod( 'noo_header_nav_style', 'default_menu_style' );
$topbar_left         = get_theme_mod( 'noo_header_top_left_on_off', false );
$topbar_right        = get_theme_mod( 'noo_header_top_right_on_off', false );
$topbar_lbox         = get_theme_mod( 'noo_header_top_left_choices', 'left_phone_number' );
$topbar_rbox         = get_theme_mod( 'noo_header_top_right_choices', 'right_select_widget' );
$woocommerce_link_on = get_theme_mod( 'menu_style_5_option_woocommerce', true );
$off_canvas_menu_on  = get_theme_mod( 'menu_style_5_option_off_canvas_menu', true );
$cart_on             = get_theme_mod( 'menu_style_5_option_cart', true );

// Navbar Meta
$navbar_meta        = get_theme_mod( 'noo_header_nav_meta', 'property' );
$navbar_meta_phone  = get_theme_mod( 'noo_header_nav_phone', '' );
$navbar_meta_social = get_theme_mod( 'noo_header_nav_widget', '' );

// Page setting
$headerpage               = noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_menu_style' );
$woocommerce_link_on_page = noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_woocommerce' );
$cart_on_page             = noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_cart' );
$image_logo_page          = noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_menu_logo' );
$topbar_hidden            = noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_topbar_hidden' );

// Login/register
$login_register = get_theme_mod( 'noo_header_top_login_register', true );
if ( get_theme_mod( 'noo_header_use_image_logo', false ) ) {
	if ( noo_landmark_func_get_image_option( 'noo_header_logo_image', '' ) != '' ) {
		$image_logo = noo_landmark_func_get_image_option( 'noo_header_logo_image', '' );
	}
}

if ( is_page() ) {
	// Header Style
	if ( ! empty( $headerpage ) && $headerpage != 'menu_style_customize' ) {
		$menu_style = $headerpage;
		if ( $headerpage == 'default_basic' || $headerpage == 'style-5' || $headerpage = 'style-2' ) {
			$woocommerce_link_on = $woocommerce_link_on_page;
			$cart_on             = $cart_on_page;
		}
	}
	// Get image logo from page setting
	if ( ! empty( $image_logo_page ) ) {
		$image_logo = wp_get_attachment_url( esc_attr( $image_logo_page ) );
	}
	// Navbar Meta Of Page
	$navbar_meta_page = noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_nav_meta' );
	if ( $navbar_meta_page != 'nav_meta_customize' ) {
		$navbar_meta        = $navbar_meta_page;
		$navbar_meta_phone  = noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_nav_meta_phone' );
		$navbar_meta_social = noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_nav_meta_social' );
		$navbar_meta_buy    = noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_nav_meta_buy' );
	}
}

// Add script
if ( $menu_style == 'style-5' || $menu_style == 'style-6' || $menu_style == 'default_basic' || $menu_style == 'style-2' ) {
	wp_enqueue_script( 'noo-custom' );
}

?>
<div class="navbar-wrapper">
	<?php if ( ( $topbar_left == true || $topbar_right == true ) && ! $topbar_hidden ): ?>
		<div class="noo-topbar">
			<div class="noo-container">
				<?php if ( $topbar_left == true ): ?>
					<div class="noo-topmeta noo-topmeta-left pull-left">
						<?php if ( $topbar_lbox == 'left_phone_number' && get_theme_mod( 'noo_header_top_left_phone' ) != '' ): ?>
							<i class="fa fa-mobile"></i>
							<?php echo esc_html__( 'Call Us Today', 'noo-landmark' ); ?>
							<a href="tel:<?php echo esc_attr( get_theme_mod( 'noo_header_top_left_phone' ) ); ?>"><?php echo esc_html( get_theme_mod( 'noo_header_top_left_phone' ) ); ?></a>

						<?php elseif ( $topbar_lbox == 'left_woocommerce_links' ): ?>
							<ul>
								<?php if ( defined( 'WOOCOMMERCE_VERSION' ) && noo_landmark_func_woocommerce_wishlist_is_active() == true && get_theme_mod( 'noo_header_top_left_show_wishlist', true ) ): ?>
									<li>
										<a href="<?php echo esc_url( get_page_link( get_option( 'yith_wcwl_wishlist_page_id' ) ) ); ?>"><i class="fa fa-heart-o" aria-hidden="true"></i> <?php echo esc_html__( 'My Wishlist', 'noo-landmark' ); ?>
										</a>
									</li>
								<?php endif; ?>

								<?php
								if ( get_theme_mod( 'noo_header_top_left_show_user', true ) ):

									if ( is_user_logged_in() ):
										?>
										<li>
											<a href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>">
												<?php echo get_avatar( wp_get_current_user()->ID, 20 ) . ' ' . esc_html( wp_get_current_user()->display_name ); ?>
											</a>
										</li>
									<?php else: ?>
										<li>
											<a href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>">
												<i class="fa fa-sign-in" aria-hidden="true"></i><?php echo esc_html__( 'Sign in / register', 'noo-landmark' ); ?>
											</a>
										</li>
									<?php endif;
								endif;
								?>
							</ul>
						<?php elseif ( $topbar_lbox == 'left_custom_html' ):
							echo noo_landmark_func_html_content_filter( get_theme_mod( 'noo_header_top_left_custom_html' ) );
						elseif ( $topbar_lbox == 'left_select_menu' && get_theme_mod( 'noo_header_top_left_quick_menu' ) != 'all_menu' ):
							$menu_terms = wp_get_nav_menu_items( get_theme_mod( 'noo_header_top_left_quick_menu' ) );
							echo "<ul>";
							foreach ( $menu_terms as $key => $value ) {
								echo "<li><a href='" . $value->url . "'>" . $value->title . "</a></li>";
							}
							echo "</ul>";
						elseif ( $topbar_lbox == 'left_select_widget' && get_theme_mod( 'noo_header_top_left_widget' ) != 'select_widget' ):
							dynamic_sidebar( get_theme_mod( 'noo_header_top_left_widget' ) );
						endif; ?>
					</div>
				<?php endif; ?>
				<?php if ( $topbar_right == true ): ?>
					<div class="noo-topmeta noo-topmeta-right pull-right">
						<?php
						/**
						 * Show login/register
						 */
						if ( ! empty( $login_register ) ) {
							if ( ! is_user_logged_in() ) {
								?>
								<a id="menu-item-login" class="noo-popup-login noo-login-button menu-item-login" href="<?php echo RP_AddOn_Agent::get_url_login(); ?>" title="<?php echo esc_html__( 'Login', 'noo-landmark' ); ?>">
									<?php echo esc_html__( 'Login', 'noo-landmark' ); ?>
								</a>
								<a id="menu-item-register" class="noo-popup-register noo-login-button menu-item-register" href="<?php echo RP_AddOn_Agent::get_url_register(); ?>" title="<?php echo esc_html__( 'Register', 'noo-landmark' ); ?>">
									<?php echo esc_html__( 'Register', 'noo-landmark' ); ?>
								</a>
								<?php
							} else {
								$current_url       = noo_current_url();
								$topbar_logout_url = wp_logout_url( $current_url );
								echo '<a class="noo-login-button" href="' . esc_url( $topbar_logout_url ) . '"><i class="fa fa-sign-out"></i>&nbsp;' . esc_html__( 'Logout', 'noo-landmark' ) . '</a>';
							}
						}
						if ( $topbar_rbox == 'right_phone_number' && get_theme_mod( 'noo_header_top_right_phone' ) != '' ):?>
							<i class="fa fa-mobile"></i>
							Call Us Today
							<a href="tel:<?php echo esc_attr( get_theme_mod( 'noo_header_top_right_phone' ) ); ?>"><?php echo esc_html( get_theme_mod( 'noo_header_top_right_phone' ) ); ?></a>
							<?php
						elseif ( $topbar_rbox == 'right_select_widget' && get_theme_mod( 'noo_header_top_right_widget' ) != 'select_widget' ):
							dynamic_sidebar( get_theme_mod( 'noo_header_top_right_widget' ) );

						elseif ( $topbar_rbox == 'right_woocommerce_links' ): ?>
							<ul>
								<?php if ( defined( 'WOOCOMMERCE_VERSION' ) && noo_landmark_func_woocommerce_wishlist_is_active() == true && get_theme_mod( 'noo_header_top_right_show_wishlist', true ) ): ?>
									<li>
										<a href="<?php echo esc_url( get_page_link( get_option( 'yith_wcwl_wishlist_page_id' ) ) ); ?>"><i class="fa fa-heart-o" aria-hidden="true"></i> <?php echo esc_html__( 'My Wishlist', 'noo-landmark' ); ?>
										</a>
									</li>
								<?php endif; ?>

								<?php
								if ( get_theme_mod( 'noo_header_top_right_show_user', true ) ):

									if ( is_user_logged_in() ):
										?>
										<li>
											<a href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>">
												<?php echo get_avatar( wp_get_current_user()->ID, 20 ) . ' ' . esc_html( wp_get_current_user()->display_name ); ?>
											</a>
										</li>
									<?php endif;
								endif;
								?>
							</ul>
							<?php

						elseif ( $topbar_rbox == 'right_custom_html' ):
							echo noo_landmark_func_html_content_filter( get_theme_mod( 'noo_header_top_right_custom_html' ) );

						elseif ( $topbar_rbox == 'right_select_menu' && get_theme_mod( 'noo_header_top_right_quick_menu' ) != 'all_menu' ):
							$menu_terms = wp_get_nav_menu_items( get_theme_mod( 'noo_header_top_right_quick_menu' ) );
							echo "<ul>";
							foreach ( $menu_terms as $key => $value ) {
								echo "<li><a href='" . $value->url . "'>" . $value->title . "</a></li>";
							}
							echo "</ul>";
						endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="navbar navbar-default" role="navigation">
		<?php if ( $menu_style == 'style-4' || $menu_style == 'style-6' ): ?>
			<div class="noo-container">
				<div class="menu-position">
					<div class="navbar-header pull-left">
						<?php if ( is_front_page() ) : echo '<h1 class="sr-only">' . $blog_name . '</h1>'; endif; ?>
						<a href="<?php echo esc_attr( home_url( '/' ) ); ?>" class="navbar-brand" title="<?php echo esc_attr( $blog_desc ); ?>">
							<?php echo ( $image_logo == '' ) ? $blog_name : '<img class="noo-logo-img noo-logo-normal" src="' . esc_url( $image_logo ) . '" alt="' . esc_attr( $blog_desc ) . '">'; ?>
						</a>
					</div> <!-- / .nav-header -->
					<?php if ( $menu_style == 'style-4' ): ?>
						<nav class="noo-main-menu noo-left-menu">
							<?php
							wp_nav_menu( apply_filters( 'noo_landmark_nav_left', array(
								'theme_location' => 'primary-left',
								'container'      => false,
								'menu_class'     => 'nav-collapse navbar-nav navbar-magic-line',
								'fallback_cb'    => 'noo_notice_set_menu',
								'walker'         => new Noo_Landmark_Megamenu_Walker,
							) ) );
							?>
						</nav>

						<nav class="noo-main-menu noo-right-menu">
							<?php
							wp_nav_menu( apply_filters( 'noo_landmark_nav_right', array(
								'theme_location' => 'primary-right',
								'container'      => false,
								'menu_class'     => 'nav-collapse navbar-nav navbar-magic-line',
								'fallback_cb'    => 'noo_notice_set_menu',
								'walker'         => new Noo_Landmark_Megamenu_Walker,
							) ) );
							?>
						</nav>

						<button data-target=".nav-collapse" class="btn-navbar noo_icon_menu" type="button">
							<i class="fa fa-bars"></i>
						</button>
						<?php
					endif;
					if ( $menu_style == 'style-6' ):
						?>
						<button data-target=".nav-collapse" class="btn-navbar noo_icon_menu" type="button">
							<i class="fa fa-bars"></i>
						</button>

						<div class="menu-nav-control">
							<div class="menu-header3">
								<span></span>
								<span></span>
								<span></span>
							</div>
						</div>

						<nav class="navbar-collapse noo-main-menu">
							<?php
							wp_nav_menu( apply_filters( 'noo_landmark_nav_primary_style2', array(
								'theme_location' => 'primary',
								'container'      => false,
								'menu_class'     => 'navbar-nav nav-collapse noo-primary-menu',
								'fallback_cb'    => 'noo_notice_set_menu',
								'walker'         => new Noo_Landmark_Megamenu_Walker,
							) ) );
							?>
						</nav> <!-- /.navbar-collapse -->
					<?php endif; ?>
				</div>
			</div>

		<?php elseif ( $menu_style == 'style-3' ): ?>

		<div class="navbar-header pull-left">
			<div class="noo-logo">
				<a href="<?php echo home_url( '/' ); ?>" class="navbar-brand" title="<?php echo esc_attr( $blog_desc ); ?>">
					<?php echo ( $image_logo == '' ) ? $blog_name : '<img class="noo-logo-img noo-logo-normal" src="' . esc_url( $image_logo ) . '" alt="' . esc_attr( $blog_desc ) . '" />'; ?>
				</a>
			</div>
			<button data-target=".nav-collapse" class="btn-navbar noo_icon_menu" type="button">
				<i class="fa fa-bars"></i>
			</button>
		</div>
		<nav class="pull-left noo-main-menu">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'nav-collapse navbar-nav',
				'fallback_cb'    => 'noo_notice_set_menu',
				'walker'         => new Noo_Landmark_Megamenu_Walker,
			) );
			?>
		</nav>
		<div class="pull-left porperty-search">
			<?php if ( noo_landmark_is_plugin_active( 'realty-portal/realty-portal.php' ) && noo_landmark_is_plugin_active( 'realty-portal-agent/realty-portal-agent.php' ) && noo_landmark_is_plugin_active( 'realty-portal-advanced-search/realty-portal-advanced-search.php' ) ) : ?>
				<form class="noo-form-halfmap" action="<?php echo RP_Member::get_url_search(); ?>" method="get" accept-charset="utf-8">
					<?php
					if ( get_theme_mod( 'noo_header_nav_option_1', true ) ) {
						rp_advanced_search_fields( Realty_Portal::get_setting( 'advanced_search', 'option_1', 'keyword' ), array( 'class' => 'noo-text-form' ) );
					}

					if ( get_theme_mod( 'noo_header_nav_option_2', true ) ) {
						rp_advanced_search_fields( Realty_Portal::get_setting( 'advanced_search', 'option_2', 'property_status' ), array( 'class' => 'noo-select-form' ) );
					}
					if ( get_theme_mod( 'noo_header_nav_option_3', true ) ) {
						rp_advanced_search_fields( Realty_Portal::get_setting( 'advanced_search', 'option_3', 'property_types' ), array( 'class' => 'noo-select-form' ) );
					}
					if ( get_theme_mod( 'noo_header_nav_option_4', true ) ) {
						rp_advanced_search_fields( Realty_Portal::get_setting( 'advanced_search', 'option_4', 'city' ), array( 'class' => 'noo-select-form' ) );
					}
					if ( get_theme_mod( 'noo_header_nav_option_5', false ) ) {
						rp_advanced_search_fields( Realty_Portal::get_setting( 'advanced_search', 'option_5', '_bedrooms' ), array( 'class' => 'noo-select-form' ) );
					}
					if ( get_theme_mod( 'noo_header_nav_option_6', false ) ) {
						rp_advanced_search_fields( Realty_Portal::get_setting( 'advanced_search', 'option_6', '_bathrooms' ), array( 'class' => 'noo-select-form' ) );
					}
					if ( get_theme_mod( 'noo_header_nav_option_7', false ) ) {
						rp_advanced_search_fields( Realty_Portal::get_setting( 'advanced_search', 'option_7', '_garages' ), array( 'class' => 'noo-select-form' ) );
					}
					if ( get_theme_mod( 'noo_header_nav_option_8', false ) ) {
						rp_advanced_search_fields( Realty_Portal::get_setting( 'advanced_search', 'option_8', 'price' ), array( 'class' => 'noo-select-form' ) );
					}
					?>

					<button type="submit" class="noo-button">
						<?php echo esc_html__( 'Search Property', 'noo-landmark' ); ?>
					</button>
				</form>
			<?php endif; ?>
		</div>

		<?php else: ?>
		<div class="noo-container">
			<div class="navbar-content">
				<div class="navbar-header pull-left">
					<?php if ( is_front_page() ) : echo '<h1 class="sr-only">' . $blog_name . '</h1>';
					endif; ?>
                        <?php if ( $menu_style != 'style-5' ): ?>
						<button data-target=".nav-collapse" class="btn-navbar noo_icon_menu" type="button">
							<i class="fa fa-bars"></i>
						</button>
					<?php endif; ?>
					<a href="<?php echo esc_attr( home_url( '/' ) ); ?>" class="navbar-brand" title="<?php echo esc_attr( $blog_desc ); ?>">
						<?php echo ( $image_logo == '' ) ? $blog_name : '<img class="noo-logo-img noo-logo-normal" src="' . esc_url( $image_logo ) . '" alt="' . esc_attr( $blog_desc ) . '" />'; ?>
					</a>
				</div> <!-- / .nav-header -->

				<?php
				if ( $navbar_meta ) :
				if ( $navbar_meta == 'property' || $navbar_meta == 'buy' ) : ?>
				<div class="pull-right navbar-meta meta-property">
					<div class="meta-content">
						<?php if ( $navbar_meta == 'property' ): ?>
	                                        <?php if ( class_exists( 'RP_AddOn_Submit_Property' ) ) : ?>
						<a href="<?php echo RP_AddOn_Submit_Property::get_url_submit_property() ?>" title="<?php echo esc_html__( 'Add Property', 'noo-landmark' ); ?>" class="meta-property noo-button">
							<span><i class="fa fa-plus-circle"></i></span><?php echo esc_html__( 'Add Property', 'noo-landmark' ); ?>
						</a>
						<?php endif; ?>
                                        <?php else: ?>
						<a href="<?php echo esc_url( $navbar_meta_buy ); ?>" title="<?php echo esc_html__( 'Buy Now', 'noo-landmark' ); ?>" class="meta-property noo-button">
							<span><i class="fa fa-shopping-cart"></i></span><?php echo esc_html__( 'Buy Now', 'noo-landmark' ); ?>
						</a>
						<?php endif; ?>
					</div>
				</div>

				<?php elseif ( $navbar_meta == 'phone' && $navbar_meta_phone != '' ): ?>
				<div class="pull-right navbar-meta meta-phone">
					<div class="meta-content">
						<i class="fa fa-mobile"></i>
						<div class="meta-desc">
							<?php echo esc_html__( 'CALL US NOW', 'noo-landmark' ); ?><br>
							<span><?php echo '<a href="tel:'.esc_attr( $navbar_meta_phone ).'">'.esc_html( $navbar_meta_phone ).'</a>'; ?></span>
						</div>
					</div>
				</div>
				<?php elseif ( $navbar_meta == 'social' && $navbar_meta_social != 'select_widget' ): ?>
				<div class="pull-right navbar-meta meta-social">
					<div class="meta-content">
						<?php dynamic_sidebar( $navbar_meta_social ); ?>
					</div>
				</div>
				<?php
				endif;
				endif;
				?>

                    <?php if ( defined( 'WOOCOMMERCE_VERSION' ) && $cart_on == true && $menu_style == 'default_menu_style' ) : ?>
				<div class="pull-right noo-header-minicart">
					<div class="widget_shopping_cart_content">
						<?php woocommerce_mini_cart(); ?>
					</div><!-- /.widget_shopping_cart_content -->
				</div>
				<?php endif; ?>

                    <?php
				if ( $menu_style == 'default_basic' || $menu_style == 'style-5' || $menu_style == 'style-2' ) {
				?>
				<nav class="pull-right noo-menu-option">
					<a href="#" class="button-expand-option"><i class="fa fa-ellipsis-v"></i></a>
					<ul>
						<?php
						if ( defined( 'WOOCOMMERCE_VERSION' ) ) :
						if ( $woocommerce_link_on == true ) :
						if ( is_user_logged_in() ):
						?>
						<li>
							<a href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>">
								<i class="fa fa-user" aria-hidden="true"></i>
								<?php echo esc_html__( 'My Account', 'noo-landmark' ); ?>
							</a>
						</li>
						<?php else: ?>
						<li>
							<a href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>">
								<i class="fa fa-sign-in" aria-hidden="true"></i><?php echo esc_html__( 'Sign in / register', 'noo-landmark' ); ?>
							</a>
						</li>
						<?php endif; ?>

                                        <?php if ( noo_landmark_func_woocommerce_wishlist_is_active() == true ): ?>
						<li>
							<a href="<?php echo esc_url( get_page_link( get_option( 'yith_wcwl_wishlist_page_id' ) ) ); ?>"><i class="fa fa-heart-o" aria-hidden="true"></i><?php echo esc_html__( 'my wishlist', 'noo-landmark' ); ?>
							</a>
						</li>
						<?php
						endif;
						endif;
						?>

                                    <?php if ( $cart_on == true ) : ?>
						<li class="noo-header-minicart">
							<div class="widget_shopping_cart_content">
								<?php woocommerce_mini_cart(); ?>
							</div><!-- /.widget_shopping_cart_content -->
						</li>
						<?php endif; ?>

                                <?php endif; ?>
					</ul>
					<?php
					/**
					 * Check menu style 5
					 */
					if ( $menu_style == 'style-5' ) {
					if ( !empty( $off_canvas_menu_on ) ) {
					echo '<a href="#" class="button-menu-extend"><i class="fa fa-bars"></i></a>';
					}
					}
					?>
				</nav>
				<?php } ?>

				<nav class="pull-right noo-main-menu">
					<?php
					wp_nav_menu( apply_filters( 'noo_landmark_nav_primary_style1', array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'nav-collapse navbar-nav', 'fallback_cb' => 'noo_notice_set_menu', 'walker' => new Noo_Landmark_Megamenu_Walker ) ) );
					?>
				</nav>

				<?php if ( $menu_style == 'style-5' ) {
				echo '<button data-target=".nav-collapse" class="btn-navbar noo_icon_menu" type="button"><i class="fa fa-bars"></i></button>';
				} ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>