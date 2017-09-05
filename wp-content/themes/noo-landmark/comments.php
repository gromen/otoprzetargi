<?php

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() )
	return;

?>

<div id="comments" class="comments-area hidden-print">

	<?php if ( have_comments() ) : ?>

		<h2 class="comments-title"><?php comments_number( esc_html__('No Comments','noo-landmark'), esc_html__( 'One Comment', 'noo-landmark'), esc_html__( '% Comments', 'noo-landmark') );?></h2>
		<ol class="comments-list">
			<?php
			wp_list_comments( array(
				'callback'    => 'noo_landmark_func_list_comments',
				'style'       => 'ol',
				'avatar_size' => 60,
				) );
				?>
		</ol> <!-- /.comments-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav id="comment-nav-below" class="navigation" role="navigation">
				<h1 class="sr-only"><?php echo esc_html__( 'Comment navigation', 'noo-landmark' ); ?></h1>
				<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'noo-landmark' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'noo-landmark' ) ); ?></div>
			</nav>
		<?php endif; ?>

		<?php if ( ! comments_open() && get_comments_number() ) : ?>
			<p class="nocomments"><?php echo esc_html__( 'Comments are closed.' , 'noo-landmark' ); ?></p>
		<?php endif; ?>

	<?php endif; // end have_comments() ?>
		<?php
		noo_landmark_func_comment_form( array(
			'comment_notes_after' => '',
			'id_submit'           => 'entry-comment-submit',
			'label_submit'        => esc_html__( 'Submit' , 'noo-landmark' )
			) );
			?>
</div> <!-- /#comments.comments-area -->