<?php
/**
 * Box Detail Comment
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
$user_rating = get_comment_meta( $comment->comment_ID, 'user_rating', true );
?>
<li id="rp-user-comment-<?php echo esc_attr( $comment->comment_ID ) ?>" class="rp-property-item-comment">
	<div class="item-comment-header">
		<div class="item-comment-header-left">
			<h4 class="name-user-comment">
				<?php echo esc_html( $comment->comment_author ); ?>
			</h4>
			<time datetime="<?php echo get_comment_date( 'd-m-Y', $comment->comment_ID ); ?>">
				<?php echo get_comment_date( 'M d, Y', $comment->comment_ID ); ?>
			</time>
		</div>
		<?php if ( !empty( $user_rating ) ) : ?>
			<div class="item-comment-header-right">
		        <div class="rp-stars-rating">
		            <span style="width: <?php echo absint( $user_rating ) ?>%"></span>
		        </div>
			</div>
		<?php endif; ?>
	</div>
	<p class="item-comment-content">
		<?php echo esc_html( $comment->comment_content ); ?>
	</p>
</li>