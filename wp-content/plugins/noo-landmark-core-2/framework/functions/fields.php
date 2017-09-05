<?php
/**
 * Create form setting
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_render_form_setting' ) ) :
	
	function noo_render_form_setting( $fields = array() ) {
		
		$default_field = array(
			'title'     => '',
			'name'      => '',
			'id_form'   => uniqid( 'id-form-' ),
			'button'    => esc_html__( 'Save Changes', 'noo-landmark-core' ),
			'fields'    => array()
		);

		$fields = wp_parse_args( $fields, $default_field );

		extract( $fields );

		?><form class="noo-setting-form" id="<?php echo esc_attr( $id_form ); ?>">

			<div class="noo-setting-wrap">

				<?php if ( !empty( $title ) ) : ?>
					
					<h1 class="noo-setting-title">
					
						<?php echo esc_html( $title ); ?>
					
					</h1>

				<?php endif; ?>
				
				<div class="noo-setting-notice" id="<?php echo $id_notice = uniqid( 'id-notice-' ) ?>"></div>

				<?php
				foreach ( $fields as $field ) {
					if( isset( $field['is_section'] ) && $field['is_section'] ) {
						echo "<h3>{$field['title']}</h3>";
						continue;
					}
					if ( empty( $field['name'] ) || empty( $field['type'] ) ) continue;
					$std         = ( isset( $field['std'] ) ? esc_attr( $field['std'] ) : '' );
					$type        = ( isset( $field['type'] ) ? esc_attr( $field['type'] ) : '' );
					$notice      = ( isset( $field['notice'] ) ? esc_attr( $field['notice'] ) : '' );
					$class       = ( isset( $field['class'] ) ? esc_attr( $field['class'] ) : '' );
					$placeholder = ( isset( $field['placeholder'] ) ? ' placeholder="' . esc_attr( $field['placeholder'] ) . '"' : '' );
					$value       = Realty_Portal::get_setting( esc_attr( $name ), esc_attr( $field['name'] ), $std );
					
					$required    = !empty( $field['required'] ) ? 'required' : '';
					$readonly    = !empty( $field['readonly'] ) ? 'readonly' : '';
					
					$list_field  = get_option( 'noo_list_field', array() );
					?>
					<div class="noo-setting-item <?php echo esc_attr( $type ); ?> <?php echo esc_attr( $class ); ?>">
						<?php if( isset( $field['title'] ) ) : ?>
							<label for="<?php echo esc_attr( $field['name'] ); ?>">
								<?php echo esc_html( $field['title'] ); ?>
							</label>
						<?php endif; ?>
						<?php
							/**
							 * Require field
							 */
							$result = Noo_Custom_Fields::renderFormField( $type, $field, $value, false );
						?>
					</div><?php
				}
				?>
			
				<input type="hidden" name="name_option" value="<?php echo esc_attr( $name ); ?>" />

				<input class="button button-primary btn-property-submit" type="submit" name="submit" data-id-notice="<?php echo esc_attr( $id_notice ); ?>" data-name-option="property" data-id-form="<?php echo esc_attr( $id_form ); ?>" value="<?php echo esc_html( $button ); ?>" />
			</div>
			
		</form>
		<?php

	}

endif;

/**
 * Create element html
 *
 * @package 	LandMark
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */
// if ( ! function_exists( 'noo_create_element' ) ) :
	
// 	function noo_create_element( $args = array(), $value = '' ) {
// 		wp_enqueue_style( 'qtip' );
// 		wp_enqueue_script( 'qtip' );

// 		$defaults = array(
// 			'title'        => '',
// 			'name'         => '',
// 			'type'         => '',
// 			'std'          => '',
// 			'class'        => '',
// 			'class_child'  => '',
// 			'placeholder'  => '',
// 			'list'         => '',
// 			'required'     => '',
// 			'post_id'      => '',
// 			'btn_text'     => esc_html__( 'Upload', 'noo-landmark-core' ),
// 			'multi_upload' => 'true',
// 			'multi_input'  => 'false',
// 			'slider'       => 'true',
// 			'set_featured' => 'false',
// 			'readonly'     => false,
// 			'options'      => array(),
// 			'notice_error' => esc_html__( 'Don\'t leave this field blank.', 'noo-landmark-core' ),
// 			'after_notice' => ''
// 		);

// 		$field = wp_parse_args( $args, $defaults );
// 		// extract( $args );

// 		if ( empty( $field['name'] ) || empty( $field['type'] ) ) return;

// 		echo '<div id="noo-item-' . esc_attr( $field['name'] ) . '-wrap" class="noo-item-wrap ' . esc_attr( $field['class'] ) . '">';

// 			if ( !empty( $field['title'] ) ) {

// 				echo '<label for="noo-item-' . esc_attr( $field['name'] ) . '">';
// 				echo 	esc_html( $field['title'] );
// 				echo 	'<i class="ion-help-circled noo-tooltip" data-content="' . esc_html( $field['notice_error'] ) . '"></i>';
// 				echo '</label>';

// 			}

// 			$value           = !empty( $value ) ? $value : $field['std'];
// 			$required        = !empty( $field['required'] ) ? 'required' : '';
// 			$readonly        = !empty( $field['readonly'] ) ? 'readonly' : '';
// 			$placeholder     = ( isset( $field['placeholder'] ) ? ' placeholder="' . esc_attr( $field['placeholder'] ) . '"' : '' );
// 			$field['notice'] = $field['after_notice'];

// 			/**
// 			 * Require field
// 			 */
// 			$list_field     = get_option( 'noo_list_field', array() );
// 			$show_front_end = true;
// 			if ( is_array( $list_field ) && in_array( $field['type'], $list_field ) ) {
// 				require noo_get_template( $field['type'], 'framework/fields/' . esc_attr( $field['type'] ) );
// 			} else {
// 				echo esc_html__( 'Do not support this field!', 'noo-landmark-core' );
// 			}
			

// 		echo '</div>';

// 	}

// endif;