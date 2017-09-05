<?php
/**
 * Create ajax process upload button
 *
 * @package       RP Upload Ajax
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_upload_form_ajax' ) ) :

	function rp_upload_form_ajax() {

		/**
		 * Check security
		 */
		check_ajax_referer( 'rp-upload', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal' ) );

		/**
		 * Process
		 */
		$file   = $_FILES[ 'rp-upload-form' ];
		$status = wp_handle_upload( $file, array(
			'test_form' => true,
			'action'    => 'rp_upload_form',
		) );

		/**
		 * Adds file as attachment to WordPress
		 */
		$id_img = wp_insert_attachment( array(
			'post_mime_type' => $status[ 'type' ],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file[ 'name' ] ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		), $status[ 'file' ] );

		$attach_data = wp_generate_attachment_metadata( $id_img, $status[ 'file' ] );
		wp_update_attachment_metadata( $id_img, $attach_data );

		$image_attributes = wp_get_attachment_image_src( $id_img, 'thumbnail' );

		if ( ! empty( $status[ 'url' ] ) ) {
			$response[ 'status' ] = 'success';
			$response[ 'url' ]    = $image_attributes[ 0 ];
			$response[ 'id' ]     = $id_img;
			$response[ 'msg' ]    = esc_html__( 'Upload success', 'realty-portal' );
		} else {
			$response[ 'status' ] = 'error';
			$response[ 'msg' ]    = esc_html__( 'Upload error', 'realty-portal' );
		}

		wp_send_json( $response );
	}

	add_action( 'wp_ajax_rp_upload_form', 'rp_upload_form_ajax' );
	add_action( 'wp_ajax_nopriv_rp_upload_form', 'rp_upload_form_ajax' );

endif;

/**
 * Show form upload
 *
 * @package       RP Upload Ajax
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_box_upload_form_ajax' ) ) :

	function rp_box_upload_form_ajax( $args = array(), $value = '' ) {
		wp_dequeue_script( 'rp-property' );
		wp_enqueue_style( 'carousel' );
		wp_enqueue_script( 'carousel' );
		wp_enqueue_script( 'rp-upload' );
		wp_enqueue_script( 'rp-property' );
		/**
		 * Set default
		 */
		$form_upload = wp_parse_args( $args, array(
			'btn_text'     => esc_html__( 'Upload', 'realty-portal' ),
			'name'         => 'rp-upload',
			'allow_format' => 'jpg,jpeg,gif,png',
			'multi_input'  => 'false',
			'multi_upload' => 'false',
			'set_featured' => 'false',
			'slider'       => 'true',
			'notice'       => '',
		) );

		extract( $form_upload );

		$class_hide = '';
		if ( ! empty( $value ) ) {
			$class_hide = ' hide';
		}

		$class_featured = '';
		if ( 'true' == $set_featured ) {
			$class_featured = 'featured';
		}

		$id_featured = '';
		if ( ! empty( $post_id ) ) {
			$id_featured = get_post_meta( $post_id, '_thumbnail_id', true );
		}

		$id_wrap         = uniqid( 'rp-upload-wrap-' );
		$id_drop_element = uniqid( 'rp-drop-element' );
		$id_upload       = uniqid( 'id-upload-' );

		?>
	<div class="rp-upload<?php echo( 'true' == $slider ? ' slider' : ' normal' ) ?>"
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

		<div class="rp-upload-wrap" id="<?php echo esc_attr( $id_wrap ); ?>">

			<?php if ( 'true' == $slider ) : ?>

				<div class="rp-upload-main">
					<div class="rp-upload-left rp-md-9"
					">
					<div class="preview hide"></div>
					<span class="rp-drop-file <?php echo esc_attr( $class_hide ); ?>">
                                    <?php echo esc_html__( 'Drop your files or folders here', 'realty-portal' ); ?>
                                </span>
					<div class="rp-list-image">
						<?php
						if ( ! empty( $value ) ) :
						if ( ! is_array( $value ) ) {
							$value_arr = explode( ',', $value );
						} else {
							$value_arr = $value;
						}
						foreach ( $value_arr as $id ) {
						$src_img = wp_get_attachment_image_src( $id );
						?>
						<div class="item-image <?php echo esc_attr( $class_featured ); ?>"
						     id="item-image-<?php echo esc_attr( $id ) ?>">
							<?php
							$class_is_featured = '';
							if ( $set_featured === 'true' && absint( $id_featured ) === absint( $id ) ) {
								echo '<i class="item-featured rp-icon-ion-bookmark"></i>';
								$class_is_featured = 'active';
							}
							if ( $set_featured === 'true' ) {
								echo '<i class="set-featured rp-icon-ion-ios-star ' . esc_attr( $class_is_featured ) . '" data-id="' . esc_attr( $id ) . '"></i>';
							}
							?>

							<i class="remove-item rp-icon-ion-trash-b" data-id="<?php echo esc_attr( $id ); ?>"></i>
							<img src="<?php echo esc_url( $src_img[ 0 ] ) ?>" alt="*" />
							<?php
							$list_input = explode( '|', $name );

							if ( 'true' == $multi_input ) {
								foreach ( $list_input as $input ) {
									# code...
									if ( 'true' == $multi_upload ) {

										echo '<input type="hidden" name="' . esc_attr( $input ) . '[]" value="' . esc_attr( $id ) . '" />';
									} else {

										echo '<input type="hidden" name="' . esc_attr( $input ) . '" value="' . esc_attr( $id ) . '" />';
									}
								}
							} else if ( 'true' == $multi_upload ) {

								echo '<input type="hidden" name="' . esc_attr( $name ) . '[]"  value="' . esc_attr( $id ) . '" />';
							} else {

								echo '<input type="hidden" name="' . esc_attr( $name ) . '"  value="' . esc_attr( $id ) . '" />';
							}

							echo '</div>';
							}

							endif; ?>

						</div>

					</div>
					<div class="rp-upload-right rp-md-3">
                        <span class="btn-upload">
                            <i class="rp-icon-plus" aria-hidden="true"></i>
                        </span>
					</div>

					<i class="upload-show-more rp-icon-ion-ios-arrow-down"></i>

				</div>

				<div class="rp-upload-action">
					<button class="rp-upload-image rp-button"><?php echo esc_html( $btn_text ); ?></button>
					<span class="process-upload-media"></span>
				</div>

				<?php
				if ( ! empty( $notice ) ) {
					echo '<span class="notice">' . esc_html( $notice ) . '</span>';
				}
				?>

			<?php else : ?>

				<div class="rp-upload-main">

					<div class="rp-upload-thumbnail">

						<img src="<?php echo( ( is_int( $value ) || empty( $value ) ) ? rp_thumb_src_id( $value, 'rp-agent-avatar', '268x210' ) : $value ) ?>"
						     alt="*" />
						<input type="hidden" name="<?php echo esc_attr( $name ); ?>"
						       value="<?php echo esc_attr( $value ) ?>">

					</div>

					<div class="rp-upload-action">
						<?php
						if ( ! empty( $notice ) ) :
							echo '<span class="notice">' . esc_html( $notice ) . '</span>';
						endif; ?>
						<button class="rp-upload-image rp-button btn-upload"
						        id="<?php echo esc_attr( $id_upload ) ?>"><?php echo esc_html( $btn_text ); ?></button>
					</div>

				</div>

			<?php endif; ?>

		</div>

		</div><?php
	}

endif;

/**
 * This function process event remove image from media
 *
 * @package       RP Upload Ajax
 * @author        NooTeam <suppport@nootheme.com>
 * @version       1.0
 */

if ( ! function_exists( 'rp_remove_image_media' ) ) :

	function rp_remove_image_media() {

		/**
		 * Check security
		 */
		check_ajax_referer( 'rp-upload', 'security', esc_html__( 'Security Breach! Please contact admin!', 'realty-portal' ) );

		/**
		 * Process data
		 */
		if ( ! empty( $_POST[ 'id' ] ) ) {

			$media_id = absint( $_POST[ 'id' ] );

			wp_delete_attachment( $media_id );

			$response[ 'msg' ]    = esc_html__( 'Remove image success.', 'realty-portal' );
			$response[ 'status' ] = 'success';
		} else {

			$response[ 'msg' ]    = esc_html__( 'Don\'t support format, please contact administration!', 'realty-portal' );
			$response[ 'status' ] = 'error';
		}

		wp_send_json( $response );
	}

	add_action( 'wp_ajax_rp_remove_media', 'rp_remove_image_media' );
	add_action( 'wp_ajax_nopriv_rp_remove_media', 'rp_remove_image_media' );

endif;

if ( ! function_exists( 'rp_upload_dir_name' ) ):
	function rp_upload_dir_name() {
		return apply_filters( 'rp_upload_dir_name', 'rp-theme' );
	}
endif;

if ( ! function_exists( 'rp_create_upload_dir' ) ):
	function rp_create_upload_dir( $wp_filesystem = null ) {
		if ( empty( $wp_filesystem ) ) {
			return false;
		}

		$upload_dir = wp_upload_dir();
		global $wp_filesystem;

		$rp_upload_dir = $wp_filesystem->find_folder( $upload_dir[ 'basedir' ] ) . rp_upload_dir_name();
		if ( ! $wp_filesystem->is_dir( $rp_upload_dir ) ) {
			if ( wp_mkdir_p( $rp_upload_dir ) ) {
				return $rp_upload_dir;
			}

			return false;
		}

		return $rp_upload_dir;
	}
endif;