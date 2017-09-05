<?php
/**
 * Create ajax process upload button
 *
 * @package 	Noo Upload Ajax
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_upload_form_ajax' ) ) :
	
	function noo_upload_form_ajax() {

		/**
		 * Check security
		 */
			check_ajax_referer( 'noo-upload', 'security', esc_html__( 'Security Breach! Please contact admin!', 'noo-landmark-core' ) );

		/**
		 * Process
		 */
			$file   = $_FILES['noo-upload-form'];
			$status = wp_handle_upload( $file, array( 'test_form' => true, 'action' => 'noo_upload_form' ) );

		  	/**
		  	 * Adds file as attachment to WordPress
		  	 */
		  	$id_img = wp_insert_attachment( array(
				'post_mime_type' => $status['type'],
				'post_title'     => preg_replace('/\.[^.]+$/', '', basename($file['name'])),
				'post_content'   => '',
				'post_status'    => 'inherit'
		  	), $status['file']);

		  	$attach_data = wp_generate_attachment_metadata($id_img, $status['file']);
			wp_update_attachment_metadata($id_img, $attach_data);

		  	$image_attributes = wp_get_attachment_image_src( $id_img, 'thumbnail' );

		  	if ( !empty( $status['url'] ) ) {
				$response['status'] = 'success';
				$response['url']    = $image_attributes[0];
				$response['id']     = $id_img;
				$response['msg']    = esc_html__( 'Upload success', 'noo-landmark-core' );
			} else {
				$response['status'] = 'error';
				$response['msg']    = esc_html__( 'Upload error', 'noo-landmark-core' );
			}

			wp_send_json( $response );
			
	}

	add_action( 'wp_ajax_noo_upload_form', 'noo_upload_form_ajax' );
	add_action( 'wp_ajax_nopriv_noo_upload_form', 'noo_upload_form_ajax' );

endif;


/**
 * Show form upload
 *
 * @package 	Noo Upload Ajax
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_box_upload_form_ajax' ) ) :
	
	function noo_box_upload_form_ajax( $args = array(), $value = '' ) {
		wp_dequeue_script( 'noo-property' );
		wp_enqueue_style( 'carousel' );
		wp_enqueue_script( 'carousel' );
		wp_enqueue_script( 'noo-upload' );
		wp_enqueue_script( 'noo-property' );
		/**
		 * Set default
		 */
			$form_upload = wp_parse_args( $args, array(
				'btn_text'     => esc_html__( 'Upload', 'noo-landmark-core' ),
				'name'         => 'noo-upload',
				'allow_format' => 'jpg,jpeg,gif,png',
				'multi_input'  => 'false',
				'multi_upload' => 'false',
				'set_featured' => 'false',
				'slider' 	   => 'true',
				'notice' 	   => '',
			) );

			extract( $form_upload );
			
			$class_hide = '';
			if ( !empty( $value ) ) {
				$class_hide = ' hide';
			}

			$class_featured = '';
			if ( $set_featured === 'true' ) {
				$class_featured = 'featured';
			}

			$id_featured = '';
			if ( !empty( $post_id ) ) {
				$id_featured = get_post_meta( $post_id, '_thumbnail_id', true );
			}

			$id_wrap = uniqid( 'noo-upload-wrap-' );
			$id_drop_element = uniqid( 'noo-drop-element' );
			$id_upload = uniqid( 'id-upload-' );

		?><div class="noo-upload<?php echo ( $slider === 'true' ? ' slider' : ' normal' ) ?>"
			   data-browse_button="<?php echo esc_attr( $id_upload ); ?>"
			   data-id_wrap="<?php echo esc_attr( $id_wrap ); ?>"
			   data-name="<?php echo esc_attr( $name ); ?>"
			   data-allow_format="<?php echo esc_html( $allow_format ); ?>"
			   data-multi_input="<?php echo esc_attr( $multi_input ); ?>"
			   data-multi_upload="<?php echo esc_attr( $multi_upload ); ?>"
			   data-set_featured="<?php echo esc_attr( $set_featured ); ?>"
			   data-drop_element="<?php echo esc_attr( $id_drop_element ); ?>"
			   data-slider="<?php echo esc_attr( $slider ); ?>"
		>
		
			<div class="noo-upload-wrap" id="<?php echo esc_attr( $id_wrap ); ?>">
				
				<?php if ( $slider === 'true' ) : ?>

					<div class="noo-upload-main">
						<div class="noo-upload-left noo-md-9"">
							<div class="preview hide"></div>
							<span class="noo-drop-file <?php echo esc_attr( $class_hide ); ?>">
								<?php echo esc_html__( 'Drop your files or folders here', 'noo-landmark-core' ); ?>
							</span>
							<div class="noo-list-image">
								<?php
								if ( !empty( $value ) ) :
									if ( !is_array( $value ) ) {
										$value_arr   = explode( ',',$value );
									} else {
										$value_arr = $value;
									}
									foreach ($value_arr as $id) {
										$src_img = wp_get_attachment_image_src( $id );
										?>
										<div class="item-image <?php echo esc_attr( $class_featured ); ?>" id="item-image-<?php echo esc_attr( $id ) ?>">
											<?php 
												$class_is_featured = '';
												if ( $set_featured === 'true' && absint( $id_featured ) === absint($id) ) {
													echo '<i class="item-featured ion-bookmark"></i>';
													$class_is_featured = 'active';
												}
												if ( $set_featured === 'true' ) {
													echo '<i class="set-featured ion-ios-star ' . esc_attr( $class_is_featured ) . '" data-id="' . esc_attr( $id ) . '"></i>'; 
												}
											?>

											<i class="remove-item ion-trash-b" data-id="<?php echo esc_attr( $id ); ?>"></i>
											<img src="<?php echo esc_url( $src_img[0] ) ?>" alt="*" />
											<?php
												$list_input = explode( '|', $name );

												if ( $multi_input == 'true' ) {
													foreach ($list_input as $input) {
														# code...
														if ( $multi_upload == 'true' ) {

															echo '<input type="hidden" name="' . esc_attr($input) . '[]" value="' . esc_attr( $id ) . '" />';
														
														} else {

															echo '<input type="hidden" name="' . esc_attr($input) . '" value="' . esc_attr( $id ) . '" />';
														
														}

													}

												} else if( $multi_upload == 'true' ) {
													
													echo '<input type="hidden" name="' . esc_attr( $name ) . '[]"  value="' . esc_attr( $id ) . '" />';

												}else {
													
													echo '<input type="hidden" name="' . esc_attr( $name ) . '"  value="' . esc_attr( $id ) . '" />';

												}

										echo '</div>';
									}
								
								endif; ?>
								
							</div>

						</div>
						<div class="noo-upload-right noo-md-3">
							<span class="btn-upload">
								<i class="fa fa-plus" aria-hidden="true"></i>
							</span>
						</div>

						<i class="upload-show-more ion-ios-arrow-down"></i>

					</div>

					<div class="noo-upload-action">
						<span class="noo-upload-image noo-button"><?php echo esc_html( $btn_text ); ?></span>
						<span class="process-upload-media"></span>
					</div>

					<?php 
					if ( !empty( $notice ) ) {
						echo '<span class="notice">' . esc_html( $notice ) . '</span>';	
					}
					?>

				<?php else : ?>

					<div class="noo-upload-main">

						<div class="noo-upload-thumbnail noo-md-6">

							<img src="<?php echo ( ( is_int( $value ) || empty( $value ) ) ? noo_thumb_src_id( $value, 'noo-agent-avatar', '268x210' ) : $value ) ?>" alt="*" />
							<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ) ?>">

						</div>

						<div class="noo-upload-action noo-md-6">
							<?php 
							if ( !empty( $notice ) ) :
								echo '<span class="notice">' . esc_html( $notice ) . '</span>';	
							endif; ?>
							<span class="noo-upload-image noo-button btn-upload" id="<?php echo esc_attr( $id_upload ) ?>"><?php echo esc_html( $btn_text ); ?></span>
						</div>

					</div>

				<?php endif; ?>

			</div>

			<script type="text/javascript">
				// jQuery(document).ready(function($) {
				// 	$('body').find('.noo-upload').noo_upload_form({
				// 		browse_button : '<?php echo esc_attr( $id_upload ); ?>',
				// 		container : '<?php echo esc_attr( $id_wrap ); ?>',
				// 		name : '<?php echo esc_attr( $name ); ?>',
				// 		allow_format : '<?php echo esc_html( $allow_format ); ?>',
				// 		multi_input : <?php echo esc_attr( $multi_input ); ?>,
				// 		multi_upload : <?php echo esc_attr( $multi_upload ); ?>,
				// 		set_featured : <?php echo esc_attr( $set_featured ); ?>,
				// 		drop_element : '<?php echo esc_attr( $id_drop_element ); ?>',
				// 		slider : <?php echo esc_attr( $slider ); ?>
				// 	});
				// });
			</script>

		</div><?php

	}

endif;

/**
 * This function process event remove image from media
 *
 * @package 	Noo Upload Ajax
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_remove_image_media' ) ) :
	
	function noo_remove_image_media() {

		/**
    	 * Check security
    	 */
    		check_ajax_referer( 'noo-upload', 'security', esc_html__( 'Security Breach! Please contact admin!', 'noo-landmark-core' ) );

    	/**
    	 * Process data
    	 */
    		if ( !empty( $_POST['id'] ) ) {

				$media_id = absint( $_POST['id'] );

				wp_delete_attachment( $media_id );

				$response['msg']    = esc_html__( 'Remove image success.', 'noo-landmark-core' );
				$response['status'] = 'success';					

    		} else {

				$response['msg']    = esc_html__( 'Don\'t support format, please contact administration!', 'noo-landmark-core' );
				$response['status'] = 'error';

    		}

    		wp_send_json( $response );

	}

	add_action( 'wp_ajax_noo_remove_media', 'noo_remove_image_media' );
	add_action( 'wp_ajax_nopriv_noo_remove_media', 'noo_remove_image_media' );

endif;