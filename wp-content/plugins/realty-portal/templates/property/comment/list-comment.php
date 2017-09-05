<?php
/**
 * Box List Comment
 *
 * This template can be overridden by copying it to yourtheme/realty-portal/my-favorites.php.
 *
 * HOWEVER, on occasion NooTheme will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author        NooTheme
 * @version       0.1
 */
global $property;
if ( !is_object( $property ) ) {
	return false;
}

$title_user_comment         = apply_filters( 'rp_property_title_box_user_comment', esc_html__( 'Reviews', 'realty-portal' ) );
$text_loadmore_user_comment = apply_filters( 'rp_property_text_loadmore_user_comment', esc_html__( 'Load more', 'realty-portal' ) );

$comment_per_page = get_theme_mod( 'rp_property_number_comment', 3 );

$args_comment  = array(
	'post_id' => $property->ID,
);
$list_comments = get_comments( $args_comment );

$comments_count   = wp_count_comments( $property->ID );
$total_comment    = $comments_count->total_comments;
$max_page_comment = floor( $total_comment / $comment_per_page );
?>
    <h3 class="rp-title-box">
		<?php echo wp_kses( $title_user_comment, rp_allowed_html() ); ?>
    </h3>
    <ul class="rp-property-list-comment">
		<?php
		if ( empty( $list_comments ) ) {

			echo '<li class="none-comment">' . esc_html__( 'There are not any comments here. Be the first one to leave comments!', 'realty-portal' ) . '</li>';
		} else {

			wp_list_comments( array(
				'per_page' => $comment_per_page,
				'callback' => 'rp_property_detail_comment',
			), $list_comments );
		}
		?>
    </ul>

<?php if ( $total_comment > $comment_per_page ) : ?>
    <div class="rp-loadmore-comment">
	<span data-property-id="<?php echo esc_attr( $property->ID ); ?>"
          data-number-comment="<?php echo esc_attr( $comment_per_page ); ?>"
          data-total-comment="<?php echo esc_attr( $total_comment ); ?>"
          data-max-page="<?php echo esc_attr( $max_page_comment ); ?>" data-curent-page="1">
		<?php echo wp_kses( $text_loadmore_user_comment, rp_allowed_html() ); ?>
        <i class="rp-icon-ion-chevron-down"></i>
	</span>
    </div>
<?php endif;