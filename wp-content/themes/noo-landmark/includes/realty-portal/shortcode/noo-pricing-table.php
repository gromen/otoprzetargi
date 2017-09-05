<?php
/**
 * Shortcode Visual: Pricing Table
 * Function show list pricing table
 *
 * @package     Noo Library
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( !function_exists( 'noo_shortcode_pricing_table' ) ) :

    function noo_shortcode_pricing_table( $atts ) {

        extract( shortcode_atts( array(
            'featured_item' => '3',
            'button_txt'    => esc_html__( 'Buy now', 'noo-landmark' )
        ), $atts ) );

        ob_start();

        $args = array(
            'post_type'           => 'noo_membership',
            'post_status'         => 'publish',
            'posts_per_page'      => -1,
            'ignore_sticky_posts' => 1,
            'order'               => 'ASC',
        );
        $membership_query = new WP_Query($args);

        /**
         * Check and loop
         */

        if ( $membership_query->have_posts() ) :
            echo '<div class="noo-row noo-pricing-table">';
            
            /**
             * VAR
             */
                $total_package     = absint( $membership_query->found_posts );
                $class             = 'noo-md-3';
                $interval_text_arr = array(
                    'day'   => array( esc_html__( 'Day', 'noo-landmark' ), esc_html__( 'Days', 'noo-landmark' ) ),
                    'week'  => array( esc_html__( 'Week', 'noo-landmark' ), esc_html__( 'Weeks', 'noo-landmark' ) ),
                    'month' => array( esc_html__( 'Month', 'noo-landmark' ), esc_html__( 'Months', 'noo-landmark' ) ),
                    'year'  => array( esc_html__( 'Year', 'noo-landmark' ), esc_html__( 'Years', 'noo-landmark' ) ),
                );

            if ( $total_package === 1 ) $class = 'noo-md-12'; 
            elseif ( $total_package === 2 ) $class = 'noo-md-6'; 
            elseif ( $total_package === 3 ) $class = 'noo-md-4'; 
            elseif ( $total_package === 4 ) $class = 'noo-md-3'; 
            elseif ( $total_package === 5 ) $class = 'noo-md-15';
            $count = 0;

            $agent_id     = RP_Agent::get_id_agent();

            while ( $membership_query->have_posts() ) : $membership_query->the_post();
                $count++;
                $package_id            = get_the_ID();
                $title                 = get_the_title();
                $interval              = get_post_meta( $package_id, 'noo_membership_interval', true );
                $interval_unit         = get_post_meta( $package_id, 'noo_membership_interval_unit', true );
                $interval_unit_text    = isset( $interval_text_arr[$interval_unit] ) ? $interval_text_arr[$interval_unit] : array( '', '' );
                $class_unlimited       = '';
                
                $properties_num           = get_post_meta( $package_id, 'noo_membership_properties_num', true );
                $properties_num_unlimited = get_post_meta( $package_id, 'noo_membership_properties_num_unlimited', true );
                if ( !empty( $properties_num_unlimited ) ) {
                 
                    $properties_num     = esc_html__( 'Unlimited', 'noo-landmark' );
                    $class_unlimited = 'unlimited';

                }
                $limit_property        = !empty( $properties_num ) ? wp_kses( sprintf( __( '<span%s>%s</span> Properties', 'noo-landmark' ), ' class="' . esc_attr( $class_unlimited ) . '"', esc_html( $properties_num ) ), noo_allowed_html() ) : '';
                
                $featured_num          = get_post_meta( $package_id, 'noo_membership_featured_num', true );
                $limit_featured        = ( !empty( $featured_num ) || $featured_num == 0 ) ? wp_kses( sprintf( __( '<span>%s</span> Featured Properties', 'noo-landmark' ), esc_html( $featured_num ) ), noo_allowed_html() ) : '';

                $after_price        = $interval_unit_text[0];
                if( absint( $interval ) > 1 ) {
                    $after_price = absint( $interval ) . ' ' . $interval_unit_text[1];
                }
                $price = get_post_meta( $package_id, 'noo_membership_price', true );
                $price = RP_Payment::format_price( $price, 'text' );
                $expired_date = get_post_meta( $package_id, 'noo_membership_expire', true );
                $expired_unit_date = get_post_meta( $package_id, 'noo_membership_expire_unit', true );
                ?>
                <div class="<?php echo esc_attr( $class ); ?> noo-pricing-table-item-wrap<?php echo ( $count === absint( $featured_item ) ? ' is_featured' : '' ) ?>">
                    <div class="noo-pricing-table-item">
                        <div class="pricing-header">
                            <h3 class="pricing-title"><?php echo esc_html( $title ); ?></h3>
                            <?php
                                if ( !empty( $price ) ) {
                                    echo '<div class="noo-price">' . wp_kses( $price, noo_allowed_html() ) . '</div>';
                                }

                                if ( !empty( $after_price ) ) {
                                    echo '<div class="noo-after-price">' . esc_html( $after_price )  . '</div>';
                                }
                            ?>
                            <span class="pricing-line"></span>
                        </div>
                        <ul class="pricing-body">
                            <?php
                                if ( !empty( $limit_property ) ) {
                                    echo '<li class="limit-property">' . $limit_property . '</li>';
                                }
                                if ( !empty( $limit_featured ) ) {
                                    echo '<li class="limit-featured">' . $limit_featured . '</li>';
                                }
                                if ( !empty( $expired_date ) ) {
                                    echo '<li class="expired-date">' . sprintf( esc_html__( '%s %ss Listing Duration', 'noo-landmark' ), $expired_date, $expired_unit_date ) . '</li>';
                                }

                                for( $i = 1; $i < 5; $i++ ) {
                                    $additional = get_post_meta( $package_id, 'noo_membership_additional_info_' . $i, true );
                                    if( !empty( $additional ) ) {
                                        echo '<li class="limit-featured">' . $additional . '</li>';
                                    }
                                }
                            ?>
                        </ul>
                        <div class="pricing-action">
                            <button class="noo-button" data-package-id="<?php echo esc_attr( $package_id ); ?>" data-agent-id="<?php echo esc_attr( $agent_id ) ?>">
                                <?php echo esc_html( $button_txt ); ?>
                                <i class="fa-li fa fa-spinner fa-spin hide"></i>
                            </button>
                        </div>
                    </div><!-- /.noo-pricing-table-item -->
                </div><!-- /.noo-pricing-table-item-wrap --><?php
            endwhile;
            echo '</div><!-- /.noo-pricing-table -->';
            wp_reset_postdata();
            wp_reset_query();

        else :

            echo esc_html__( 'Sorry, no package matched your criteria.', 'noo-landmark' );

        endif;

        $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode( 'noo_pricing_table', 'noo_shortcode_pricing_table' );

endif;