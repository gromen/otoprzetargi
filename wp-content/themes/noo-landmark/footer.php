<?php
$num                     = ( get_theme_mod( 'noo_footer_widgets', '4' ) == '' ) ? '4' : get_theme_mod( 'noo_footer_widgets', '4' );
$show_footer_widget      = get_theme_mod( 'noo_footer_widget_show', '1' );
$noo_bottom_bar_content  = get_theme_mod( 'noo_bottom_bar_content', noo_landmark_func_html_content_filter( __( '&copy; 2016. Designed with <i class="fa fa-heart text-primary" ></i> by NooTheme', 'noo-landmark' ) ) );
$footer_style            = get_theme_mod( 'noo_landmark_footer_style', 'footer_1' );
$footer_background_on    = get_theme_mod( 'noo_landmark_footer_background_on_off', 'true' );
$footer_background       = ( $footer_background_on == true ) ? get_theme_mod( 'noo_landmark_footer_background' ) : '';
$footer_fixed            = ( get_theme_mod( 'noo_landmark_footer_fixed', false ) ) ? 'true' : 'false';
$footer_copyright_social = ( get_theme_mod( 'noo_bottom_social_on', true ) ) ? 'true' : 'false';

$header_style = get_theme_mod( 'noo_header_nav_style', 'default_menu_style' );
// Check Page Setting Footer Of Page
if ( is_page() ) {
	$footer_page = noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_footer_style' );
	if ( ! empty( $footer_page ) && $footer_page != 'default_footer_style' ) {
		$footer_style              = $footer_page;
		$footer_background_page    = noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_footer_background' );
		$footer_background_page_on = noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_use_footer_background' );
		if ( ! empty( $footer_background_page ) && $footer_background_page_on == true ) {
			$footer_background = wp_get_attachment_url( esc_attr( $footer_background_page ) );
		} elseif ( $footer_background_page_on == false ) {
			$footer_background = 'none';
		}
		$footer_fixed = ( noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_footer_fixed' ) ) ? 'true' : 'false';
	}
	$num_page = noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_footer_layout' );
	if ( ! empty( $num_page ) && $footer_page == 'footer_1' || $footer_page == 'footer_2' ) {
		$num = $num_page;
	}
	// Header Style
	$headerpage = noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_menu_style' );
	if ( ! empty( $headerpage ) && $headerpage != 'menu_style_customize' ) {
		$header_style = $headerpage;
	}
}
?>
<footer class="wrap-footer<?php noo_landmark_func_footer_class();
if ( 'true' == $footer_fixed ) {
	echo ' fixed';
} ?>">
	<?php if ( $show_footer_widget != 0 ) : ?>
	<!--Start footer widget-->
	<?php if ( is_active_sidebar( 'noo-footer-top' ) || ( is_active_sidebar( 'noo-footer-1' ) || is_active_sidebar( 'noo-footer-2' ) || is_active_sidebar( 'noo-footer-3' ) || is_active_sidebar( 'noo-footer-4' ) ) ): ?>
	<div class="main-footer" <?php if ( ! empty( $footer_background ) ) {
		echo 'style="background-image:url(' . esc_url( $footer_background ) . ')"';
	} ?>>
		<div class="colophon wigetized">
			<div class="noo-container">
				<div class="noo-row">
					<?php
					if ( 'footer_1' != $footer_style ) { ?>
					<div class="noo-md-12">
						<div class="sidebar-footer-top">
							<?php if ( is_active_sidebar( 'noo-footer-top' ) ) {
								dynamic_sidebar( 'noo-footer-top' );
							} else {
								if ( is_admin() ): ?>
									<aside class="widget">
										<h3 class="widget-title"><?php echo esc_html__( 'NOO Footer Top', 'noo-landmark' ); ?></h3>
										<a class="demo-widgets" href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php echo esc_html__( 'Click here to add your widgets', 'noo-landmark' ); ?></a>
									</aside>
								<?php endif;
							}
							echo '</div>';
							echo '</div>';
							}
							if ( 'footer_3' != $footer_style ) {
								$class = '';
								for ( $i = 1; $i <= $num; $i ++ ) {
									$col = get_theme_mod( 'noo_footer_' . $i, '3' );
									if ( is_page() ) {
										$col_page = noo_landmark_func_get_post_meta( get_the_ID(), '_noo_wp_page_column_' . $i );
										if ( ! empty( $col_page ) && $footer_page == 'footer_1' || $footer_page == 'footer_2' ) {
											$col = $col_page;
										}
									}
									echo '<div class="footer-column-item noo-md-' . $col . ' noo-sm-6">';
									if ( is_active_sidebar( 'noo-footer-' . $i ) ) {
										dynamic_sidebar( 'noo-footer-' . $i );
									} else {
										if ( is_admin() ): ?>
											<aside class="widget">
												<h3 class="widget-title"><?php echo esc_html__( 'NOO Footer Column #', 'noo-landmark' ) . $i; ?></h3>
												<a class="demo-widgets" href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php echo esc_html__( 'Click here to add your widgets', 'noo-landmark' ); ?></a>
											</aside>
										<?php endif;
									}
									echo '</div>';
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
			<!--End footer widget-->
			<?php endif; ?>

			<?php if ( ! empty( $noo_bottom_bar_content ) ) : ?>
				<div class="copyright">
					<div class="noo-bottom-bar-content">
						<div class="noo-container">
							<div class="copyright-content copyright-text">
								<?php echo noo_landmark_func_html_content_filter( $noo_bottom_bar_content ); ?>
							</div>
							<?php if ( 'true' == $footer_copyright_social ): ?>
								<div class="copyright-content copyright-social">
									<?php
									if ( is_active_sidebar( 'noo-footer-copyright' ) ) {
										dynamic_sidebar( 'noo-footer-copyright' );
									} else {
										if ( is_admin() ):
											?>
											<aside class="widget">
												<a class="demo-widgets" href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php echo esc_html__( 'Click here to add your widgets "NOO - Footer Copyright"', 'noo-landmark' ); ?></a>
											</aside>
											<?php
										endif;
									}
									?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
</footer>
<!-- For header style-5 Extend Menu -->
<?php
if ( $header_style == 'style-5' ) : ?>
	<div class="noo-menu-extend">
		<div class="menu-extend-wrap">
			<span class="menu-closed"></span>
			<?php
			if ( is_active_sidebar( 'sidebar-secondary' ) ) {
				dynamic_sidebar( 'sidebar-secondary' );
			} else { ?>
				<aside class="widget">
					<h3 class="widget-title"><?php echo esc_html__( 'Secondary Sidebar', 'noo-landmark' ); ?></h3>
					<?php if ( is_admin() ): ?>
						<a class="demo-widgets" href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php echo esc_html__( 'Click here to add your widgets', 'noo-landmark' ); ?></a>
					<?php endif; ?>
				</aside>
			<?php }
			?>
		</div>
	</div>
	<div class="noo-menu-extend-overlay"></div>
	<?php
endif;
?>
</div>
<!--End .site -->

<?php wp_footer(); ?>
</body>
</html>
