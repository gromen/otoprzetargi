<?php
/**
 * Content Agent
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $agent;

// Ensure visibility
if ( empty( $agent ) ) {
	return;
}
?>
<div class="noo-row noo-agent-detail">

	<div class="noo-thumbnail noo-md-4">
		<?php echo $agent->agent_avatar( 'rp-property-thumbnail' ); ?>
		<div class="agent-social">
			<?php RP_Template::get_template( 'loop/social.php', '', '', RP_AGENT_TEMPLATES ); ?>
		</div>

	</div>

	<div class="noo-info-agent noo-md-8">
		<div class="noo-box-content">
			<h3 class="noo-title">
				<a href="<?php echo $agent->permalink() ?>" title="<?php echo $agent->title() ?>">
					<?php echo $agent->title() ?>
				</a>
			</h3>
			<div class="noo-box-info">
				<div class="item-info">
					<span class="total-property">
						<?php echo sprintf( esc_html__( '%s properties', 'noo-landmark' ), $agent->agent_info( 'total_property' ) ); ?>
					</span>
				</div>
				<ul class="item-info">
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
					?>
				</ul>

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

			</div>
		</div>
	</div>

</div>