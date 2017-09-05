<?php
/**
 * Field: Line
 *
 * @package     Custom Fields
 * @author      NooTheme
 * @version     1.0
 */

if ( ! function_exists( 'rp_render_line_field' ) ) :
	function rp_render_line_field() {
		echo '<hr />';
	}

	rp_add_custom_field_type(
		'line',
		__( 'Line', 'realty-portal' ),
		array( 'form' => 'rp_render_line_field' ),
		array( 'can_search' => false, 'is_system'  => true )
	);
endif;