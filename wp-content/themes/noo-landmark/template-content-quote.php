<?php
$quote = '';
$quote = noo_landmark_func_get_post_meta(get_the_ID() , '_noo_wp_post_quote', '');
if($quote == '') {
    $quote = get_the_title( get_the_ID() );
}
$cite = noo_landmark_func_get_post_meta(get_the_ID() , '_noo_wp_post_quote_citation', '');

$url_img = wp_get_attachment_image_src(get_post_thumbnail_id( get_the_ID() ),'full');
?>
<div class="blog-quote" style="background-image: url(<?php echo esc_url($url_img[0]); ?>);">
    <?php if (! is_single()) : ?>
        <h3 class="content-title">
            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(sprintf(esc_html__('Permanent link to: "%s"', 'noo-landmark') , the_title_attribute('echo=0'))); ?>">
                <?php echo esc_html($quote); ?>
            </a>
        </h3>
    <?php else: ?>
        <h2 class="content-title">
            <?php echo esc_html($quote); ?>
        </h2>
    <?php endif;?>
    <cite>- <?php echo esc_html($cite);?> -</cite>
</div>