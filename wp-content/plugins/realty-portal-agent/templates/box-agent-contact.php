<?php
/**
 * Box Agent Contact
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
global $property;
if ( !is_object( $property ) ) {
	return false;
}

$agent_responsible = get_post_meta( $property->ID, 'agent_responsible', true );

if ( ! empty( $agent_responsible ) && $agent_responsible !== 'none' ) :

	$user_id = $property->post->post_author;
	?>
    <div class="rp-property-box agent">
        <h3 class="rp-title-box">
			<?php echo esc_html__( 'Contact Agent', 'realty-portal-agent' ); ?>
        </h3>
		<?php
		/**
		 * @hooked rp_before_agent_contact
		 *
		 */
		do_action( 'rp_before_agent_contact' ); ?>
		<?php
		$agent_id = $property->agent_info();

		$position = $property->agent_info( 'position' );
		$phone    = $property->agent_info( 'phone' );
		$mobile   = $property->agent_info( 'mobile' );
		$mail     = $property->agent_info( 'mail' );
		$about    = $property->agent_info( 'about' );

		$total_property = $property->agent_info( 'total_property' );

		?>
        <div class="box-info-wrap">
            <div class="rp-thumbnail">
				<?php echo $property->agent_avatar(); ?>
            </div>

            <div class="rp-box-info">
                <h4 class="agent-name">
                    <a href="<?php echo $property->agent_info( 'url' ); ?>"
                       title="<?php echo $property->agent_info( 'name' ); ?>">
						<?php echo $property->agent_info( 'name' ); ?>
                    </a>
                </h4>
                <div class="item-info">
					<?php if ( ! empty( $position ) ) : ?>
                        <div class="position">
							<?php echo esc_html( $position ); ?>
                        </div>
					<?php endif; ?>
					<?php if ( RP_Agent::is_agent() ) : ?>
                        <div class="total-property">
							<?php echo sprintf( esc_html__( '%s properties', 'realty-portal-agent' ), $total_property ); ?>
                        </div>
					<?php endif; ?>
                </div>
                <ul class="item-info">
					<?php
					foreach ( $property->agent_custom_field() as $field ) {
						if ( ! is_array( $field ) && empty( $field[ 'name' ] ) ) {
							continue;
						}
						$field_name = apply_filters( 'rp_agent_post_type', 'rp_agent' ) . $field[ 'name' ];
						$value      = get_post_meta( $agent_id, $field_name, true );
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

				<?php if ( ! empty( $about ) ) : ?>
                    <div class="agent-about">
						<?php echo esc_html( $about ); ?>
                    </div>
				<?php endif; ?>

            </div>
        </div>
		<?php
		/**
		 * @hooked rp_after_agent_contact
		 *
		 */
		do_action( 'rp_after_agent_contact' ); ?>

    </div>
<?php endif;