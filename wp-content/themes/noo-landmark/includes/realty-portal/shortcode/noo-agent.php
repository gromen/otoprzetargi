<?php
/**
 * Shortcode Visual: Noo Agent
 *
 * @package     LandMark
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if ( !function_exists( 'noo_shortcode_agent' ) ) :

    function noo_shortcode_agent( $atts ) {

        if ( !class_exists( 'RP_Agent' ) || !function_exists( 'rp_get_list_social_agent' ) ) {
            return false;
        }
        
        extract( shortcode_atts( array(
            'title'          => '',
            'sub_title'      => '',
            'agent_category' => '',
            'only_agent'     => 'no',
            'posts_per_page' => '10',
            'orderby'        => 'latest',
        ), $atts ) );

        /**
         * Enqueue script
         */
            wp_enqueue_style( 'slick' );
            wp_enqueue_script( 'slick' );

        /**
         * Check data order
         */
            $order = 'DESC';

            switch ($orderby) :

                case 'latest':
                    $orderby = 'date';
                    break;

                case 'oldest':
                    $orderby = 'date';
                    $order = 'ASC';
                    break;

                case 'alphabet':
                    $orderby = 'title';
                    $order = 'ASC';
                    break;

                case 'ralphabet':
                    $orderby = 'title';
                    break;

                default:
                    $orderby = 'date';
                    break;

            endswitch;

        /**
         * Query
         */
            $agent_args   = array(
                'post_type'      => 'noo_agent',
                'post_status'    => 'publish',
                'posts_per_page' => $posts_per_page,
                'orderby'        => $orderby,
                'order'          => $order,
            );

            if ( !empty( $agent_category ) ) {

                $tax_query             = array();
                $tax_query['relation'] = 'AND';

                if ( !empty( $agent_category ) ) {

                    $tax_query[] = array(
                        'taxonomy'     => 'agent_category',
                        'field'        => 'id',
                        'terms'        => $agent_category
                    );

                }

                $agent_args['tax_query'] = $tax_query;

            }

            if ( !empty( $only_agent ) && $only_agent == 'yes' ) {
                query_posts( 'post_type=noo_agent&posts_per_page=-1' );
                $agent_ids = array();
                while ( have_posts() ) : the_post();
                    $agent_id       = get_the_ID();
                    $user_id        = RP_Agent::get_id_user( $agent_id );
                    if ( $user_id < 1 ) continue;
                    $total_property = count_user_posts( $user_id, 'noo_property' );
                    if ( $total_property > 0 ) {
                        $agent_ids[] = $agent_id;
                    }
                endwhile;
                wp_reset_query();
                $agent_args['post__in'] = $agent_ids;
            }

            $agent_query = new WP_Query( $agent_args );


        ob_start();
        ?>
        <div class="noo-agent-wrap">
            <div class="noo-agent">
                <?php if ( !empty( $title ) || !empty( $sub_title ) ) : ?>
                    <div class="noo-title-header">
                        <?php
                        /**
                         * Render title
                         */
                        noo_title_first_word( $title, $sub_title );
                        ?>
                        <div class="noo-action-slider">
                            <i class="prev-agent ion-ios-arrow-left"></i>
                            <i class="next-agent ion-ios-arrow-right"></i>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="noo-list-agent">
                    <?php
                    if ( $agent_query->have_posts() ) {
                        while ( $agent_query->have_posts() ) : $agent_query->the_post();

                            $agent_id     = get_the_ID();
                            $id_avatar    = get_post_thumbnail_id( $agent_id );
                            $avatar       = noo_thumb_src_id( $id_avatar, 'full', '470x550' );
                            $agent_social = rp_get_list_social_agent();
                            $position     = get_post_meta( $agent_id, 'noo_agent_position', true );
                            $position     = $position != '' ? $position : '&nbsp;';

                            ?>
                            <div class="noo-item-agent">
                                <div class="noo-thumbnail">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title() ?>">
                                        <img src="<?php echo esc_url( $avatar ) ?>" alt="<?php the_title() ?>" />
                                    </a>
                                    <span class="line"><span></span></span>
                                </div>
                                <div class="noo-content">
                                    <h4 class="name-agent">
                                        <a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
                                            <?php the_title() ?>
                                        </a>
                                    </h4>
                                    <span class="position"><?php echo esc_html( $position ) ?></span>

                                    <?php if ( !empty( $agent_social ) ) : ?>

                                        <div class="agent-social">
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
                            <?php

                        endwhile;
                        wp_reset_postdata();
                    } else {
                        echo '<div class="not_found">' . esc_html__( 'Sorry, no posts matched your criteria.', 'noo-landmark' ) . '</div>';
                    }
                    ?>
                </div><!-- /.noo-list-property -->
            </div>
        </div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode( 'noo_agent', 'noo_shortcode_agent' );

endif;