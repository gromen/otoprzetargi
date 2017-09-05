<?php
/**
 * Info Agent
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $agent;
if ( !is_object( $agent ) ) {
	return false;
}
?>
    <ul class="item-info">
		<?php if ( ! empty( $agent->agent_info( 'position' ) ) ) : ?>
            <li class="position">
				<?php echo esc_html( $agent->agent_info( 'position' ) ); ?>
            </li>
		<?php endif; ?>
        <li class="total-property">
			<?php echo sprintf( esc_html__( '%s properties', 'realty-portal-agent' ), $agent->agent_info( 'total_property' ) ); ?>
        </li>
		<?php
		foreach ( $agent->agent_custom_field() as $field ) {
			if ( ! is_array( $field ) && empty( $field[ 'name' ] ) ) {
				continue;
			}
			$field_name = apply_filters( 'rp_agent_post_type', 'rp_agent' ) . $field[ 'name' ];
			$value      = get_post_meta( $agent->ID, $field_name, true );
			if ( ! empty( $value ) ) {
				switch ( $field[ 'name' ] ) {
					case '_email':
						echo '<li class="' . esc_attr( $field_name ) . '">';
						echo '	<a href="mailto:' . esc_html( $value ) . '" target="_top">' . esc_html( $value ) . '</a>';
						echo '</li>';
						break;

					case '_website' :
						echo '<li class="' . esc_attr( $field_name ) . '">';
						echo '	<a href="' . esc_attr( $value ) . '" target="_top">' . esc_html( $value ) . '</a>';
						echo '</li>';
						break;

					case '_phone' :
					case '_mobile' :
						echo '<li class="' . esc_attr( $field_name ) . '">';
						echo '	<a href="tel:' . absint( $value ) . '" target="_top">' . esc_html( $value ) . '</a>';
						echo '</li>';
						break;

					case '_about' :

						break;

					default:
						echo '<li class="' . esc_attr( $field_name ) . '">' . esc_html( $value ) . '</li>';
						break;
				}
			}
		}
		?></ul>

<?php if ( ! empty( $agent->list_social_agent() ) ) : ?>

    <div class="agent-social">
		<?php
		foreach ( $agent->list_social_agent() as $item_social ) {
			$class_social = str_replace( apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_', '', $item_social[ 'id' ] );
			$value_social = get_post_meta( $agent->ID, $item_social[ 'id' ], true );
			if ( ! empty( $value_social ) ) {
				echo '<a class="' . esc_attr( $class_social ) . '" href="' . esc_attr( $value_social ) . '"></a>';
			}
		}
		?>
    </div>

<?php endif; ?>