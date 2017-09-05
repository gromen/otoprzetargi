<?php
/**
 * Field: Image
 *
 * @package     Custom Fields
 * @author      NooTheme
 * @version     1.0
 */

if ( ! function_exists( 'rp_render_image_field' ) ) :
	function rp_render_image_field( $field = array(), $value = null, $show_front_end = true ) {
		if ( empty( $field ) ) {
			return false;
		}

		if ( function_exists( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		} else {
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'thickbox' );
		}
		$image_id = $value;
		$image    = wp_get_attachment_image( $image_id, 'thumbnail' );
		$output   = ! empty( $image_id ) ? $image : '';
		$btn_text = ! empty( $image_id ) ? esc_html__( 'Change Image', 'realty-portal' ) : esc_html__( 'Select Image', 'realty-portal' );
		?>
        <div class="rp-image-wrap">

            <div class="rp-thumb-wrapper">
				<?php echo wp_kses( $output, array(
					'img' => array(
						'src'   => array(),
						'alt'   => array(),
						'title' => array(),
					),
				) ); ?>
            </div>
            <input type="hidden" name="<?php echo esc_attr( $field[ 'name' ] ); ?>"
                   id="<?php echo esc_attr( $field[ 'name' ] ); ?>" value="<?php echo( $value ); ?>"/>
            <input type="button" class="button button-primary"
                   name="<?php echo esc_attr( $field[ 'name' ] ); ?>_button_upload"
                   id="<?php echo esc_attr( $field[ 'name' ] ); ?>_upload" value="<?php echo $btn_text; ?>"/>
            <input type="button" class="button" name="<?php echo esc_attr( $field[ 'name' ] ); ?>_button_clear"
                   id="<?php echo esc_attr( $field[ 'name' ] ); ?>_clear"
                   value="<?php echo esc_html__( 'Clear Image', 'realty-portal' ); ?>"/>

        </div>

        <script>
			jQuery(document).ready(function ( $ ) {

				<?php if ( empty ( $value ) ) : ?> $('#<?php echo esc_attr( $field[ 'name' ] ); ?>_clear').css('display', 'none'); <?php endif; ?>

				$('#<?php echo esc_attr( $field[ 'name' ] ); ?>_upload').on('click', function ( event ) {
					event.preventDefault();

					var rp_upload_btn = $(this);

					// if media frame exists, reopen
					if ( wp_media_frame ) {
						wp_media_frame.open();
						return;
					}

					// create new media frame
					// I decided to create new frame every time to control the selected images
					var wp_media_frame = wp.media.frames.wp_media_frame = wp.media({
						title   : "<?php echo esc_html__( 'Select or Upload your Image', 'realty-portal' ); ?>",
						button  : {
							text: "<?php echo esc_html__( 'Select', 'realty-portal' ); ?>"
						},
						library : { type: 'image' },
						multiple: false
					});

					// when open media frame, add the selected image
					wp_media_frame.on('open', function () {
						var selected_id = rp_upload_btn.siblings('#<?php echo esc_attr( $field[ 'name' ] ); ?>').val();
						if ( !selected_id ) {
							return;
						}
						var selection = wp_media_frame.state().get('selection');
						var attachment = wp.media.attachment(selected_id);
						attachment.fetch();
						selection.add(attachment ? [ attachment ] : []);
					});

					// when image selected, run callback
					wp_media_frame.on('select', function () {
						var attachment = wp_media_frame.state().get('selection').first().toJSON();
						rp_upload_btn.siblings('#<?php echo esc_attr( $field[ 'name' ] ); ?>').val(attachment.id);

						rp_thumb_wraper = rp_upload_btn.siblings('.rp-thumb-wrapper');
						rp_thumb_wraper.html('');
						rp_thumb_wraper.append('<img src="' + attachment.url + '" alt="" />');

						rp_upload_btn.attr('value', '<?php echo esc_html__( 'Change Image', 'realty-portal' ); ?>');
						$('#<?php echo esc_attr( $field[ 'name' ] ); ?>_clear').css('display', 'inline-block');
					});

					// open media frame
					wp_media_frame.open();
				});

				$('#<?php echo esc_attr( $field[ 'name' ] ); ?>_clear').on('click', function ( event ) {
					var rp_clear_btn = $(this);
					rp_clear_btn.hide();
					$('#<?php echo esc_attr( $field[ 'name' ] ); ?>_upload').attr('value', '<?php echo esc_html__( 'Select Image', 'realty-portal' ); ?>');
					rp_clear_btn.siblings('#<?php echo esc_attr( $field[ 'name' ] ); ?>').val('');
					rp_clear_btn.siblings('.rp-thumb-wrapper').html('');
				});
			});
        </script>
		<?php
	}

	rp_add_custom_field_type(
        'image',
        __( 'Image', 'realty-portal' ),
        array( 'form' => 'rp_render_image_field' ),
        array( 'can_search' => false, 'is_system'  => true )
    );
endif;