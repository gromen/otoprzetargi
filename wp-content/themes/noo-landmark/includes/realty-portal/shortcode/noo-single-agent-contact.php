<?php
/**
 * Shortcode Visual: Single Agent Contact
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( !function_exists( 'noo_shortcode_single_agent_contact' ) ) :

    function noo_shortcode_single_agent_contact( $atts ) {

        extract( shortcode_atts( array(
            'agent_id'       => ''
        ), $atts ) );

        ob_start();
        ?>
        <div class="noo-agent-contact">
            <div class="noo-box-contact">
            <h2 class="noo-title"><?php echo esc_html__( 'Contact info', 'noo-landmark' ); ?></h2>
            <div class="noo-box-form">
                <?php do_action( 'noo_before_single_agent_contact' ); ?>
                <?php
                    $cf7_id = Realty_Portal::get_setting( 'contact_email', 'agent_contact_form', '' );
                    $cf7_id = apply_filters( 'wpml_object_id', $cf7_id, 'wpcf7_contact_form' );

                    $contact_form = !empty( $cf7_id ) ? wpcf7_contact_form( $cf7_id ) : false;
                ?>
                <?php if ( $contact_form ) :
                    echo '<div class="noo-box-contact-form-7">';
                    $atts = array(
                        'id'         => $cf7_id,
                        'title'      => '',
                        'html_id'    => '',
                        'html_name'  => '',
                        'html_class' => '',
                        'output'     => 'form'
                    );
                    
                    $form_html = $contact_form->form_html( $atts );

                    if ( !empty( $agent_id ) ) :
                        $hidden_fields[] = '<input type="hidden" name="_wpcf7_agent_id" value="' . esc_attr( $agent_id ) . '">';
                    endif;

                    if ( !empty( $property_id ) ) :
                        $hidden_fields[] = '<input type="hidden" name="_wpcf7_property_id" value="' . esc_attr( $property_id ) . '">';
                    endif;

                    $form_html = str_replace('</form></div>', implode('', $hidden_fields) . '</form></div>', $form_html);

                    echo $form_html;
                    echo '</div><!-- /.noo-box-contact-form-7 -->';
                else :
                    ?>
                    <form class="noo-box-contact-agent">

                        <?php do_action( 'noo_before_field_single_agent_contact' ); ?>
                        <div class="noo-row">
                            <div class="noo-item-wrap noo-box-text-field">
                                <input type="text" name="name" placeholder="<?php echo esc_html__( 'Name *', 'noo-landmark' ); ?>" />
                            </div>
                            <div class="noo-item-wrap noo-box-text-field">
                                <input type="text" name="phone" placeholder="<?php echo esc_html__( 'Phone', 'noo-landmark' ); ?>" />
                            </div>
                            <div class="noo-item-wrap noo-box-text-field">
                                <input type="text" name="email" placeholder="<?php echo esc_html__( 'Email *', 'noo-landmark' ); ?>" />
                            </div>
                        </div>
                        <div class="noo-item-wrap noo-box-textarea-field">
                            <textarea name="message" placeholder="<?php echo esc_html__( 'Your Message *', 'noo-landmark' ); ?>"></textarea>
                        </div>

                        <?php do_action( 'noo_after_field_single_agent_contact' ); ?>

                        <div class="noo-form-action">
                            <button type="submit" class="noo-submit">
                                <span><?php echo esc_html__( 'Send Message', 'noo-landmark' ); ?></span>
                                <i class="fa-li fa fa-spinner fa-spin hide"></i>
                            </button>
                        </div>
                        <?php if ( !empty( $agent_id ) ) : ?>
                            <input type="hidden" name="agent_id" value="<?php echo esc_attr( $agent_id ) ?>">
                        <?php endif; ?>

                        <?php if ( !empty( $property_id ) ) : ?>
                            <input type="hidden" name="property_id" value="<?php echo esc_attr( $property_id ) ?>">
                        <?php endif; ?>

                    </form><!-- /.noo-box-contact-agent -->

                <?php endif; // === end check contact form ?>
            
                <?php do_action( 'noo_after_single_agent_contact' ); ?>

            </div>
        </div>
        </div><!-- /.noo-contact-agent -->
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode( 'noo_single_agent_contact', 'noo_shortcode_single_agent_contact' );

endif;