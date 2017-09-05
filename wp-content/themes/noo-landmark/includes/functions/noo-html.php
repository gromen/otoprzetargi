<?php
/**
 * HTML Functions for NOO Framework.
 * This file contains various functions used for rendering site's small layouts.
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

// Featured Content
get_template_part( 'includes/functions/noo-html-featured' );

// Pagination
get_template_part( 'includes/functions/noo-html-pagination' );

// Breadcrumb by Theme
get_template_part( 'includes/functions/noo-html-breadcrumbs' );


if (!function_exists('noo_landmark_func_get_readmore_link')):
    function noo_landmark_func_get_readmore_link()
    {
        return '';
        if (get_theme_mod('noo_blog_show_readmore', 1)) {

            return '<a href="' . get_permalink() . '" class="noo-button read-more">'
            . esc_html__('Read More', 'noo-landmark')
            . '</a>';

        } else {
            return '';
        }
    }
endif;

if (!function_exists('noo_landmark_func_list_comments')):
    function noo_landmark_func_list_comments($comment, $args, $depth)
    {
        $GLOBALS['comment'] = $comment;
        GLOBAL $post;
        $avatar_size = isset($args['avatar_size']) ? $args['avatar_size'] : 60;
        ?>
        <li id="li-comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
        <div class="comment-wrap">
            <div class="comment-img">
                <div>
                    <?php echo get_avatar($comment, $avatar_size); ?>
                </div>
            </div>
            <article id="comment-<?php comment_ID(); ?>" class="comment-block">
                <header class="comment-header">
                    <cite class="comment-author"><?php echo get_comment_author_link(); ?></cite>

                    <div class="comment-meta">
                        <time datetime="<?php echo get_comment_time('c'); ?>">
                            <i class="fa fa-calendar"></i>
                            <?php echo sprintf(esc_html__('%1$s at %2$s', 'noo-landmark'), get_comment_date(), get_comment_time()); ?>
                        </time>
						<span class="comment-edit">
							<?php edit_comment_link('<i class="fa fa-edit"></i> ' . esc_html__('Edit', 'noo-landmark')); ?>
						</span>
                    </div>
                    <?php if ('0' == $comment->comment_approved): ?>
                        <p class="comment-pending"><?php echo esc_html__('Your comment is awaiting moderation.', 'noo-landmark'); ?></p>
                    <?php endif; ?>
                </header>
                <section class="comment-content">
                    <?php comment_text(); ?>
                </section>
					<span class="pull-left">
						<?php comment_reply_link(array_merge($args, array(
                            'reply_text' => '<span class="comment-reply-link-after"><i class="ion-reply"></i></span>' . esc_html__('Reply', 'noo-landmark'),
                            'depth' => $depth,
                            'max_depth' => $args['max_depth']
                        ))); ?>
					</span>
            </article>
        </div>
        <?php
    }
endif;

if (!function_exists('noo_landmark_func_comment_form')) :
    function noo_landmark_func_comment_form($args = array(), $post_id = null)
    {
        global $id;
        $user = wp_get_current_user();
        $user_identity = $user->exists() ? $user->display_name : '';

        if (null === $post_id) {
            $post_id = $id;
        } else {
            $id = $post_id;
        }

        if (comments_open($post_id)) :
            ?>
            <div id="respond-wrap">
                <?php
                $commenter = wp_get_current_commenter();
                $req = get_option('require_name_email');
                $aria_req = ($req ? " aria-required='true'" : '');
                $fields = array(
                    'author'        => '<div class="noo-row"><p class="comment-form-author noo-sm-4"><input id="author" name="author" type="text" placeholder="' . esc_html__('Name', 'noo-landmark') . '" class="form-control" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></p>',
                    'email'         => '<p class="comment-form-email noo-sm-4"><input id="email" name="email" type="text" placeholder="' . esc_html__('Email', 'noo-landmark') . '" class="form-control" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></p>',
                    'url'           => '<p class="comment-form-website noo-sm-4"><input id="url" name="url" type="text" placeholder="' . esc_html__( 'Website', 'noo-landmark' ) . '" class="form-control" value="' . esc_attr(  $commenter['comment_author_url'] ) . '" size="30" /></p>',
                    'comment_field' => '<div class="noo-sm-12"><p class="comment-form-comment"><textarea class="form-control" placeholder="' . esc_html__('Comment', 'noo-landmark') . '" id="comment" name="comment" cols="40" rows="6" aria-required="true"></textarea></p></div></div>'
                );
                $comments_args = array(
                    'fields'               => apply_filters('comment_form_default_fields', $fields),
                    'logged_in_as'         => '<p class="logged-in-as">' . sprintf(wp_kses(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'noo-landmark'), noo_landmark_func_allowed_html()), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink()))) . '</p>',
                    'title_reply'          => sprintf('<span>%s</span>', esc_html__('Leave a Comment', 'noo-landmark')),
                    'title_reply_to'       => sprintf('<span>%s</span>', esc_html__('Leave a reply to %s', 'noo-landmark')),
                    'cancel_reply_link'    => esc_html__('Click here to cancel the reply', 'noo-landmark'),
                    'comment_notes_before' => '',
                    'comment_notes_after'  => '',
                    'label_submit'         => esc_html__('Post Comment', 'noo-landmark'),
                    'submit_button'        => '<button type="submit" name="%1$s" id="%2$s" class="%3$s noo-button">%4$s</button>',
                    'comment_field'        => '',
                    'must_log_in'          => ''
                );
                if (is_user_logged_in()) {
                    $comments_args['comment_field'] = '<p class="comment-form-comment"><textarea class="form-control" placeholder="' . esc_html__('Enter Your Comment', 'noo-landmark') . '" id="comment" name="comment" cols="40" rows="6" aria-required="true"></textarea></p>';
                }
                comment_form($comments_args);
                ?>
            </div>

            <?php
        endif;
    }
endif;

if (!function_exists('noo_landmark_func_social_share')) :
    function noo_landmark_func_social_share($post_id = null)
    {
        $post_id = (null === $post_id) ? get_the_id() : $post_id;
        $post_type = get_post_type($post_id);
        $prefix = 'noo_blog';

        if (get_theme_mod("{$prefix}_social", true) === false) {
            return '';
        }

        $share_url = urlencode(get_permalink());
        $share_title = urlencode(get_the_title());
        $share_source = urlencode(get_bloginfo('name'));
        $share_content = urlencode(get_the_content());
        $share_media = wp_get_attachment_thumb_url(get_post_thumbnail_id());
        $popup_attr = 'resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0';

        $share_title = get_theme_mod("{$prefix}_social_title", '');
        $facebook = get_theme_mod("{$prefix}_social_facebook", true);
        $twitter = get_theme_mod("{$prefix}_social_twitter", true);
        $google = get_theme_mod("{$prefix}_social_google", true);
        $pinterest = get_theme_mod("{$prefix}_social_pinterest", false);
        $linkedin = get_theme_mod("{$prefix}_social_linkedin", false);
        $html = array();

        if ($facebook || $twitter || $google || $pinterest || $linkedin) {
            $html[] = '<div class="content-share">';
            if ($share_title !== '') {
                $html[] = '<p class="social-title">';
                $html[] = '  ' . $share_title;
                $html[] = '</p>';
            }
            $html[] = '<div class="noo-social social-share">';

            if ($facebook) {
                $html[] = '<a href="#share" data-toggle="tooltip" data-placement="bottom" data-trigger="hover" class="noo-share"'
                    . ' title="' . esc_html__('Share on Facebook', 'noo-landmark') . '"'
                    . ' onclick="window.open('
                    . "'http://www.facebook.com/sharer.php?u={$share_url}&amp;t={$share_title}','popupFacebook','width=650,height=270,{$popup_attr}');"
                    . ' return false;">';
                $html[] = '<i class="fa fa-facebook"></i>';
                $html[] = '</a>';
            }

            if ($twitter) {
                $html[] = '<a href="#share" class="noo-share"'
                    . ' title="' . esc_html__('Share on Twitter', 'noo-landmark') . '"'
                    . ' onclick="window.open('
                    . "'https://twitter.com/intent/tweet?text={$share_title}&amp;url={$share_url}','popupTwitter','width=500,height=370,{$popup_attr}');"
                    . ' return false;">';
                $html[] = '<i class="fa fa-twitter"></i></a>';
            }

            if ($google) {
                $html[] = '<a href="#share" class="noo-share"'
                    . ' title="' . esc_html__('Share on Google+', 'noo-landmark') . '"'
                    . ' onclick="window.open('
                    . "'https://plus.google.com/share?url={$share_url}','popupGooglePlus','width=650,height=226,{$popup_attr}');"
                    . ' return false;">';
                $html[] = '<i class="fa fa-google-plus"></i></a>';
            }

            if ($pinterest) {
                $html[] = '<a href="#share" class="noo-share"'
                    . ' title="' . esc_html__('Share on Pinterest', 'noo-landmark') . '"'
                    . ' onclick="window.open('
                    . "'http://pinterest.com/pin/create/button/?url={$share_url}&amp;media={$share_media}&amp;description={$share_title}','popupPinterest','width=750,height=265,{$popup_attr}');"
                    . ' return false;">';
                $html[] = '<i class="fa fa-pinterest"></i></a>';
            }

            if ($linkedin) {
                $html[] = '<a href="#share" class="noo-share"'
                    . ' title="' . esc_html__('Share on LinkedIn', 'noo-landmark') . '"'
                    . ' onclick="window.open('
                    . "'http://www.linkedin.com/shareArticle?mini=true&amp;url={$share_url}&amp;title={$share_title}&amp;summary={$share_content}&amp;source={$share_source}','popupLinkedIn','width=610,height=480,{$popup_attr}');"
                    . ' return false;">';
                $html[] = '<i class="fa fa-linkedin"></i></a>';
            }

            $html[] = '</div>'; // .noo-social.social-share
            $html[] = '</div>'; // .share-wrap
        }

        echo implode("\n", $html);
    }
endif;

if (!function_exists('noo_landmark_func_social_icons')):
    function noo_landmark_func_social_icons($position = 'topbar', $direction = '')
    {
        if ($position == 'topbar') {
            // Top Bar social
        } else {
            // Bottom Bar social
        }

        $class = isset($direction) ? $direction : '';
        $html = array();
        $html[] = '<div class="noo-social social-icons ' . $class . '">';

        $social_list = array(
            'facebook' => esc_html__('Facebook', 'noo-landmark'),
            'twitter' => esc_html__('Twitter', 'noo-landmark'),
            'google-plus' => esc_html__('Google+', 'noo-landmark'),
            'pinterest' => esc_html__('Pinterest', 'noo-landmark'),
            'linkedin' => esc_html__('LinkedIn', 'noo-landmark'),
            'rss' => esc_html__('RSS', 'noo-landmark'),
            'youtube' => esc_html__('YouTube', 'noo-landmark'),
            'instagram' => esc_html__('Instagram', 'noo-landmark'),
        );

        $social_html = array();
        foreach ($social_list as $key => $title) {
            $social = get_theme_mod("noo_social_{$key}", '');
            if ($social) {
                $social_html[] = '<a href="' . $social . '" title="' . $title . '" target="_blank">';
                $social_html[] = '<i class="fa fa-' . $key . '"></i>';
                $social_html[] = '</a>';
            }
        }

        if (empty($social_html)) {
            $social_html[] = esc_html__('No Social Media Link', 'noo-landmark');
        }

        $html[] = implode($social_html, "\n");
        $html[] = '</div>';

        echo implode($html, "\n");
    }
endif;

if (!function_exists('noo_landmark_func_entry_meta')) :
    /**
     * Prints HTML with meta information for the categories, tags.
     *
     * @since Twenty Fifteen 1.0
     */
    function noo_landmark_func_entry_meta()
    {

        $noo_blog_social = get_theme_mod( 'noo_blog_social', false );

        if ( is_single( ) ) :

            $tags_list = get_the_tag_list( '', '' );
            if ( $tags_list ) :
                echo '<div class="single-tag"><span>' . esc_html__('TAGS:', 'noo-landmark') . '</span>';
                echo noo_landmark_func_kses($tags_list);
                echo '</div>';
            endif;

            if ( !empty( $noo_blog_social ) ) :
                echo '<div class="single-social"><span>' . esc_html__('SHARE:', 'noo-landmark') . '</span>';
                    noo_landmark_func_social_share();
                echo '</div>';
            endif;

        else :

            if ( get_theme_mod('noo_blog_show_readmore', 1) ) {
                echo '<a href="' . get_permalink() . '" class="noo-button read-more">'
                . esc_html__('Read More', 'noo-landmark')
                . '</a>';
            }
            if ( !empty( $noo_blog_social ) ) :
                echo '<div class="single-social">';
                    noo_landmark_func_social_share();
                echo '</div>';
            endif;

        endif;
        
    }

endif;

if (!function_exists('noo_landmark_func_gototop')):
    function noo_landmark_func_gototop()
    {
        if (get_theme_mod('noo_back_to_top', true)) {
            echo '<a href="#" class="go-to-top hidden-print"><i class="fa fa-angle-up"></i></a>';
        }
        return;
    }

    add_action('wp_footer', 'noo_landmark_func_gototop');
endif;

if (!function_exists('noo_landmark_func_tag_date')):
    function noo_landmark_func_tag_date( $is_shortcode = false )
    {
        if ( ! is_singular() || $is_shortcode ) {
        ?>
            <span class="tag-date">
                <?php $tag_date = explode( '/', get_the_date('M/d') ); ?>
                <span><?php echo esc_attr($tag_date[1]); ?></span>
                <span><?php echo esc_attr($tag_date[0]); ?></span>
            </span>
        <?php
        }
    }
endif;