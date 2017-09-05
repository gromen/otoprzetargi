<?php
include ( dirname( __FILE__ ) . '/class-addon.php' );
wp_enqueue_script( 'rp-addon' );
$reload_addons = add_query_arg( 'force_load', true );

$addons = RP_Addons::get_addons();
$addons = apply_filters( 'rp_addons_filter', (array) $addons );
?>
<div class="wrap plugin-install-tab-featured">
	<h1>
		<?php echo esc_html__( 'Add-ons', 'realty-portal' ); ?>
		<a href="<?php echo esc_url( $reload_addons ); ?>"
		   class="rp-dash-button rp-dash-icon-small"><?php echo esc_html__( 'Update list', 'realty-portal' ); ?></a>
	</h1>
	<form id="plugin-filter" method="post">
		<div class="wp-list-table widefat plugin-install">
			<h2 class="screen-reader-text"><?php echo esc_html__( 'Plugins list', 'realty-portal' ); ?></h2>
			<div id="RP_Addon" class="view_wrapper">
				<div class='wrap'>
					<div class="rp-dashboard rp-dash-addons">
						<?php if ( ! empty( $addons ) ) : ?>

						<?php
							$plugins = get_plugins();
							foreach ( $addons as $addon ) {
								if ( version_compare( RP_VERSION, $addon->version_from, '<' ) || version_compare( RP_VERSION, $addon->version_to, '>' ) ) {
									continue;
								}
								if ( empty( $addon->title ) ) {
									continue;
								}

								$rp_dash_background_style = ! empty( $addon->background ) ? 'style="background-image: url(' . $addon->background . ');"' : "";
								?>
								<div class="rp-dash-widget <?php echo $addon->slug; ?>" <?php echo $rp_dash_background_style; ?>>
									<div class="rp-dash-title-wrap">
										<div class="rp-dash-title"><?php echo $addon->title; ?></div>
										<?php
										//Plugin Status
										$rp_addon_not_activated = $rp_addon_activated = $rp_addon_not_installed = 'style="display:none"';
										$rp_addon_version       = "";
										if ( array_key_exists( $addon->slug . '/' . $addon->slug . '.php', $plugins ) ) {
											if ( is_plugin_inactive( $addon->slug . '/' . $addon->slug . '.php' ) ) {
												$rp_addon_not_activated = 'style="display:block"';
											} else {
												$rp_addon_activated = 'style="display:block"';
											}
											$rp_addon_version = $plugins[ $addon->slug . '/' . $addon->slug . '.php' ][ 'Version' ];
										} else {
											$rp_addon_not_installed = 'style="display:block"';
										}

										//Check for registered
										$rp_addon_validated = get_option( 'rp-addon-valid', 'true' );
										$rp_addon_validated = $rp_addon_validated == 'true' ? true : false;

										if ( $rp_addon_validated ) {
											?>
											<div class="rp-dash-title-button rp-status-orange" <?php echo $rp_addon_not_activated; ?> data-plugin="<?php echo $addon->slug . '/' . $addon->slug . '.php'; ?>" data-alternative="<i class='icon-no-problem-found'></i>Activate">
												<i class="icon-update-refresh"></i><?php echo esc_html__( "Not Active", 'realty-portal' ); ?>
											</div>
											<div class="rp-dash-button-gray rp-dash-deactivate-addon rp-dash-title-button" <?php echo $rp_addon_activated; ?> data-plugin="<?php echo $addon->slug . '/' . $addon->slug . '.php'; ?>" data-alternative="<i class='icon-update-refresh'></i>Deactivate">
												<i class="icon-update-refresh"></i><?php echo esc_html__( "Deactivate", 'realty-portal' ); ?>
											</div>
											<div class=" rp-dash-title-button rp-status-green" <?php echo $rp_addon_activated; ?> data-plugin="<?php echo $addon->slug . '/' . $addon->slug . '.php'; ?>" data-alternative="<i class='icon-update-refresh'></i>Deactivate">
												<i class="icon-no-problem-found"></i><?php echo esc_html__( "Active", 'realty-portal' ); ?>
											</div>
											<div class=" rp-dash-title-button rp-status-red" <?php echo $rp_addon_not_installed; ?> data-alternative="<i class='icon-update-refresh'></i>Install" data-plugin="<?php echo $addon->slug; ?>">
												<i class="icon-not-registered"></i><?php echo esc_html__( "Not Installed", 'realty-portal' ); ?>
											</div>
										<?php } else {
											$rp_addon_version = "";
											$result           = deactivate_plugins( $addon->slug . '/' . $addon->slug . '.php' );
											?>
											<div class="rp-dash-title-button rp-status-red" style="display:block">
												<i class="icon-not-registered"></i><?php echo esc_html__( "Add-on locked", 'realty-portal' ); ?>
											</div>
										<?php }
										?>
									</div>
									<div class="rp-dash-widget-inner rp-dash-widget-registered">

										<div class="rp-dash-content">
											<div class="rp-dash-strong-content"><?php echo $addon->line_1; ?></div>
											<div><?php echo $addon->line_2; ?></div>
										</div>
										<div class="rp-dash-content-space"></div>
										<?php if ( ! empty( $rp_addon_version ) ) { ?>
											<div class="rp-dash-version-info">
												<div class="rp-dash-strong-content ">
													<?php echo esc_html__( 'Installed Version', 'realty-portal' ); ?>
												</div>
												<?php echo $rp_addon_version; ?>
											</div>
										<?php } ?>
										<div class="rp-dash-version-info">
											<div class="rp-dash-strong-content rp-dash-version-info">
												<?php echo esc_html__( 'Available Version', 'realty-portal' ); ?>
											</div>
											<?php echo $addon->available; ?>
										</div>
										<?php if ( ! empty( $rp_addon_version ) ) { ?>
											<div class="rp-dash-content-space"></div>
											<a class="rp-dash-inverp-button" href="?page=realty-portal-addon&amp;check_update=true"><?php echo esc_html__( 'Check for Update', 'realty-portal' ); ?></a>
											<div class="rp-dash-content-space"></div>
										<?php } ?>
										<div class="rp-dash-bottom-wrapper">
											<?php if ( ! empty( $rp_addon_version ) ) { ?>
												<?php
												if ( version_compare( $rp_addon_version, $addon->available ) >= 0 ) { ?>
													<span class="rp-dash-button-gray"><?php echo esc_html__( 'Up to date', 'realty-portal' ); ?></span>
													<?php
												} else { ?>
													<a href="update-core.php?check_update=true" class="rp-dash-button"><?php echo esc_html__( 'Update Now', 'realty-portal' ); ?></a>
													<?php
												}
												?>
											<?php } else {
												if ( $rp_addon_validated ) {
													?>
													<span data-plugin="<?php echo $addon->slug; ?>" data-is-buy="<?php echo $addon->is_buy; ?>" data-url="<?php echo $addon->url_button; ?>" class="rp-addon-not-installed rp-dash-button"><?php echo esc_html__( 'Install this Add-on', 'realty-portal' ); ?></span>
													<?php
												} else { ?>
													<a href="<?php echo admin_url( 'admin.php?page=realty-portal-config' ); ?>" class="rp-dash-button"><?php echo esc_html__( 'Register Realty Portal', 'realty-portal' ); ?></a>
													<?php
												}
											} ?>

											<?php if ( ! empty( $addon->button ) && ! empty( $addon->url_button ) && $rp_addon_validated && ! empty( $rp_addon_version ) ) {
												if ( $rp_addon_activated == 'style="display:block"' ) {
													?>
													<span <?php echo $rp_addon_activated == 'style="display:none"' ? $rp_addon_activated : ''; ?> onclick="window.location = '<?php echo $addon->url_button; ?>'" class="rp-dash-button rp-dash-action-button" id="rp-dash-addons-trigger_<?php echo $addon->slug; ?>"><?php echo $addon->button; ?></span>
												<?php } else { ?>
													<span data-plugin="<?php echo $addon->slug . '/' . $addon->slug . '.php'; ?>" class="rp-addon-not-activated rp-dash-button rp-dash-action-button rp-dash-margin-left-10" id="rp-dash-addons-trigger_<?php echo $addon->slug; ?>"><?php echo esc_html__( 'Activate Plugin', 'realty-portal' ); ?></span>
												<?php }
											} ?>
										</div>
									</div>
								</div>
								<?php
							}
						?>

						<?php else: ?>
							<p><?php echo esc_html__( 'Currently there are no addons. We will update soon.', 'realty-portal' ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>