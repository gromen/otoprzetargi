<?php
if ( ! function_exists( 'rp_get_property' ) ) :

	/**
	 * Run library properties factory
	 *
	 * @param $property
	 *
	 * @return RP_Properties_Factory
	 */
	function rp_get_property( $property ) {
		return new RP_Properties_Factory( $property );
	}

endif;