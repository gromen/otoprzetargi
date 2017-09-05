<form method="GET" class="form-horizontal" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="form">
<label class="sr-only"><?php esc_html__( 'Search', 'noo-landmark' ); ?></label>
	<input type="search" name="s" class="form-control" value="<?php echo get_search_query(); ?>" placeholder="<?php echo esc_attr__( 'Search...', 'noo-landmark' ); ?>" />
	<input type="submit" class="hidden" value="<?php echo esc_attr__( 'Search', 'noo-landmark' ); ?>" />
</form>