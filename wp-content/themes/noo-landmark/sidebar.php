
<?php
$sidebar = noo_landmark_func_get_sidebar_id();
if( ! empty( $sidebar ) ) :
?>
<div class="<?php noo_landmark_func_sidebar_class(); ?>">
	<?php do_action( 'noo_before_sidebar' ); ?>
	<div class="noo-sidebar-wrap">
		<?php // Dynamic Sidebar
		if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $sidebar ) ) : ?>
		<?php endif; // End Dynamic Sidebar sidebar-main ?>
	</div>
	<?php do_action( 'noo_after_sidebar' ); ?>
	
</div>
<?php endif; // End sidebar ?> 
