<?php
/**
 * Box Rating
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */
$label_box_rating         = apply_filters( 'rp_label_box_rating', esc_html__( 'Your Rating', 'realty-portal' ) );


$label_rating_5_stars     = apply_filters( 'rp_label_rating_5_stars', esc_html__( 'Awesome - 5 stars', 'realty-portal' ) );
$label_rating_4half_stars = apply_filters( 'rp_label_rating_4half_stars', esc_html__( 'Pretty good - 4.5 stars', 'realty-portal' ) );
$label_rating_4_stars     = apply_filters( 'rp_label_rating_4_stars', esc_html__( 'Pretty good - 4 stars', 'realty-portal' ) );
$label_rating_3half_stars = apply_filters( 'rp_label_rating_3half_stars', esc_html__( 'Meh - 3.5 stars', 'realty-portal' ) );
$label_rating_3_stars     = apply_filters( 'rp_label_rating_3_stars', esc_html__( 'Meh - 3 stars', 'realty-portal' ) );
$label_rating_2half_stars = apply_filters( 'rp_label_rating_2half_stars', esc_html__( 'Kinda bad - 2.5 stars', 'realty-portal' ) );
$label_rating_2_stars     = apply_filters( 'rp_label_rating_2_stars', esc_html__( 'Kinda bad - 2 stars', 'realty-portal' ) );
$label_rating_1half_stars = apply_filters( 'rp_label_rating_1half_stars', esc_html__( 'Meh - 1.5 stars', 'realty-portal' ) );
$label_rating_1_stars     = apply_filters( 'rp_label_rating_1_stars', esc_html__( 'Sucks big time - 1 star', 'realty-portal' ) );
$label_rating_0half_stars = apply_filters( 'rp_label_rating_0half_stars', esc_html__( 'Sucks big time - 0.5 stars', 'realty-portal' ) );
?>
<div class="rp-box-rating rating">
	
	<label>
		<?php echo wp_kses( $label_box_rating, rp_allowed_html() ) ?>
	</label>

	<div class="rp-rating">
	    <input type="radio" id="star5" name="rating" value="100" checked />
	    <label class="full" for="star5" data-rating="100" title="<?php echo wp_kses( $label_rating_5_stars, rp_allowed_html() ); ?>"></label>
	    
	    <input type="radio" id="star4half" name="rating" value="87" />
	    <label class="half" for="star4half" data-rating="87" title="<?php echo wp_kses( $label_rating_4half_stars, rp_allowed_html() ); ?>"></label>
	    
	    <input type="radio" id="star4" name="rating" value="80" />
	    <label class="full" for="star4" data-rating="80" title="<?php echo wp_kses( $label_rating_4_stars, rp_allowed_html() ); ?>"></label>
	    
	    <input type="radio" id="star3half" name="rating" value="67" />
	    <label class="half" for="star3half" data-rating="67" title="<?php echo wp_kses( $label_rating_3half_stars, rp_allowed_html() ); ?>"></label>

	    <input type="radio" id="star3" name="rating" value="60" />
	    <label class="full" for="star3" data-rating="60" title="<?php echo wp_kses( $label_rating_3_stars, rp_allowed_html() ); ?>"></label>

	    <input type="radio" id="star2half" name="rating" value="48" />
	    <label class="half" for="star2half" data-rating="48" title="<?php echo wp_kses( $label_rating_2half_stars, rp_allowed_html() ); ?>"></label>

	    <input type="radio" id="star2" name="rating" value="40" />
	    <label class="full" for="star2" data-rating="40" title="<?php echo wp_kses( $label_rating_2_stars, rp_allowed_html() ); ?>"></label>

	    <input type="radio" id="star1half" name="rating" value="28" />
	    <label class="half" for="star1half" data-rating="28" title="<?php echo wp_kses( $label_rating_1half_stars, rp_allowed_html() ); ?>"></label>

	    <input type="radio" id="star1" name="rating" value="20" />
	    <label class="full" for="star1" data-rating="20" title="<?php echo wp_kses( $label_rating_1_stars, rp_allowed_html() ); ?>"></label>

	    <input type="radio" id="starhalf" name="rating" value="8" />
	    <label class="half" for="starhalf" data-rating="8" title="<?php echo wp_kses( $label_rating_0half_stars, rp_allowed_html() ); ?>"></label>
	</div>

</div>