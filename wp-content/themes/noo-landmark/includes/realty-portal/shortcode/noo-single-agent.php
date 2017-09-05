<?php
/**
 * Shortcode Visual: Single Agent
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( !function_exists( 'noo_shortcode_single_agent' ) ) :

    function noo_shortcode_single_agent( $atts ) {

        extract( shortcode_atts( array(
            'agent_id'       => ''
        ), $atts ) );

        ob_start();
        ?>
        <div class="noo-about-agent">
            <?php
                $user_id   = get_post_meta( $agent_id, '_associated_user_id', true );
                $id_avatar = get_post_thumbnail_id( $agent_id );
                $avatar    = noo_thumb_src_id( $id_avatar, 'noo-agent-avatar-medium', '475x550' );
                $position  = get_post_meta( $agent_id, 'noo_agent_position', true );
                $phone     = get_post_meta( $agent_id, 'noo_agent_phone', true );
                $mobile    = get_post_meta( $agent_id, 'noo_agent_mobile', true );
                $mail      = get_post_meta( $agent_id, 'noo_agent_mail', true );
                $about     = get_post_meta( $agent_id, 'noo_agent_about', true );

                $total_property = count_user_posts( $user_id, 'noo_property' );
                $agent_social   = rp_get_list_social_agent();
                ?>
                <div class="noo-row noo-agent-detail">

                    <div class="noo-thumbnail noo-md-5">
                        <img src="<?php echo esc_url( $avatar ) ?>" alt="<?php echo get_the_title($agent_id) ?>" />
                    </div>

                    <div class="noo-info-agent noo-md-7">
                        <div class="noo-box-content">
                            <h3 class="noo-title">
                                <a href="<?php echo get_permalink( $agent_id ); ?>" title="<?php echo get_the_title($agent_id); ?>">
                                    <?php echo get_the_title($agent_id); ?>
                                </a>
                            </h3>
                            <div class="noo-box-info">
                                <div class="item-info">
                                    <?php if ( !empty( $position ) ) : ?>
                                        <span class="position">
                                            <?php echo esc_html( $position ); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ( RP_Agent::is_agent() & $total_property != 0 ) : ?>
                                        <span class="total-property">
                                            <?php echo sprintf( esc_html__( '%s properties', 'noo-landmark' ), $total_property ); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php
                                    $agent_custom_field = rp_agent_render_fields();
                                    if ( is_array( $agent_custom_field ) ) :
                                        echo '<ul class="item-info">';
                                        unset( $agent_custom_field[''] );
                                        unset( $agent_custom_field['social_network'] );
                                        unset( $agent_custom_field['_position'] );
                                        unset( $agent_custom_field['_about'] );
                                        foreach ( $agent_custom_field as $field ) {
                                            
                                            if ( empty( $field['name'] ) ) continue;

                                            $field_name = 'noo_agent' . $field['name'];
                                            $value = get_post_meta( $agent_id, $field_name, true );
                                            if ( !empty( $value ) ) {
                                                if ( $field['name'] === '_email' ) {

                                                    echo '<li class="' . esc_attr( $field_name ) . '">';
                                                    echo '  <a href="mailto:' . esc_html( $value ) . '" target="_top">' . esc_html( $value ) . '</a>';
                                                    echo '</li>';

                                                } elseif ( $field['name'] === '_phone' || $field['name'] === '_mobile' ) {

                                                    echo '<li class="' . esc_attr( $field_name ) . '">';
                                                    echo '  <a href="tel:' . absint( $value ) . '" target="_top">' . esc_html( $value ) . '</a>';
                                                    echo '</li>';                       

                                                } elseif ( $field['name'] === '_website' ) {

                                                    echo '<li class="' . esc_attr( $field_name ) . '">';
                                                    echo '  <a href="' . esc_attr( $value ) . '" target="_top">' . esc_html( $value ) . '</a>';
                                                    echo '</li>';                       

                                                } else {

                                                    echo '<li class="' . esc_attr( $field_name ) . '">' . esc_html( $value ) . '</li>';
                                                    
                                                }
                                            }
                                        }
                                        echo '</ul>';
                                    endif;
                                ?>

                                <?php if ( !empty( $about ) ) : ?>
                                    <div class="agent-about">
                                        <?php echo esc_html( $about ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="noo-agent-action">
                                    <div class="noo-md-6 noo-contact-now">
                                        <a href="<?php echo get_permalink( $agent_id ); ?>" title="<?php echo get_the_title( $agent_id ) ?>" class="noo-button">
                                            <?php echo esc_html__( 'Contact now', 'noo-landmark' ); ?>
                                        </a>
                                    </div>
                                    <?php if ( !empty( $agent_social ) ) : ?>

                                        <div class="noo-md-6 agent-social text-right">
                                        <?php
                                            foreach ( $agent_social as $item_social ) {
                                                $class_social = str_replace( 'noo_agent_', '', $item_social['id']);
                                                $value_social = get_post_meta( $agent_id, $item_social['id'], true );
                                                if ( !empty( $value_social ) ) {
                                                    echo '<a class="' . esc_attr( $class_social ) . '" href="' . esc_attr( $value_social ) . '"></a>';                      
                                                }
                                            }
                                        ?>
                                        </div>

                                    <?php endif; ?>
                                </div>
                            
                            </div>
                        </div>
                    </div>

                </div><!--/.row-->
                
        </div><!-- /.noo-about-agent -->
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode( 'noo_single_agent', 'noo_shortcode_single_agent' );

endif;