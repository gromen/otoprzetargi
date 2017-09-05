<?php
/**
 * Show html Listings Features & Amenities page
 *
 * @author  NooTeam <suppport@nootheme.com>
 * @version 1.0
 */

wp_enqueue_script( 'rp-iconpicker' );

/**
 * VAR
 */
$custom_fields = rp_render_featured_amenities();

?>
<form class="rp-validate-form rp-setting-form" id="<?php echo $id_form = uniqid( 'id-form-' ); ?>">

    <div class="rp-setting-wrap">

        <h1 class="rp-setting-title">
			<?php echo esc_html__( 'Listings Features & Amenities', 'realty-portal' ); ?>
        </h1>

        <div class="rp-setting-notice" id="<?php echo $id_notice = uniqid( 'id-notice-' ) ?>"></div>

        <div class="rp-setting-head">
			<span class="rp-setting-col">
				<?php echo esc_html__( 'Feature Key', 'realty-portal' ); ?>
                <span class="dashicons dashicons-warning rp-tooltip"
                      data-content="<?php echo esc_html__( 'Be careful when you change the code, you could loose this field value.', 'realty-portal' ); ?>"></span>
			</span>
            <span class="rp-setting-col"><?php echo esc_html__( 'Feature Label', 'realty-portal' ); ?></span>
        </div>

        <div class="rp-setting-content-wrap">

            <div class="rp-setting-content">

				<?php foreach ( $custom_fields as $key => $value ) :

					$name = ! empty( $custom_fields[ $key ][ 'name' ] ) ? esc_attr( $custom_fields[ $key ][ 'name' ] ) : '';
					$label = ! empty( $custom_fields[ $key ][ 'label' ] ) ? esc_html( $custom_fields[ $key ][ 'label' ] ) : '';
					$icon = ! empty( $custom_fields[ $key ][ 'icon' ] ) ? esc_html( $custom_fields[ $key ][ 'icon' ] ) : '';
					$hide = ! empty( $custom_fields[ $key ][ 'hide' ] ) ? esc_attr( $custom_fields[ $key ][ 'hide' ] ) : '';

					$key = ! empty( $hide ) ? 'clone_field[0]' : esc_attr( $key );
					?>
                    <div class="rp-setting-item-field<?php echo ! empty( $hide ) ? ' clone-field hide' : '' ?>"
                         id="<?php echo $id_field = uniqid( 'id-field-' ); ?>" data-index="0">
						<span class="rp-setting-col rp-col-name">
							<input type="text" name="<?php echo esc_attr( $key ); ?>[name]"
                                   value="<?php echo esc_html( $name ); ?>"
                                   placeholder="<?php echo esc_html__( 'Enter your field key...', 'realty-portal' ); ?>"/>
						</span>
                        <span class="rp-setting-col">
							<input type="text" name="<?php echo esc_attr( $key ); ?>[label]"
                                   value="<?php echo esc_html( $label ); ?>"
                                   placeholder="<?php echo esc_html__( 'Enter your field label...', 'realty-portal' ); ?>"/>
						</span>
                        <span class="rp-iconpicker">
						    <span class="rp-iconpicker-group">
						        <input style="padding: 3px 10px;transform: translateY(2px);"
                                       data-placement="bottomRight" class="rp-iconpicker-input" type="text"
                                       name="<?php echo esc_attr( $key ); ?>[icon]"
                                       value="<?php echo esc_html( $icon ); ?>" <?php echo esc_html__( 'Select your icon...', 'realty-portal' ); ?> />
						        <span style="transform: translateY(2px);width: 27px;height: 27px;line-height: 27px;"
                                      class="input-group-addon"></span>
						    </span>
						</span>

                        <span class="rp-setting-col">
							<span class="button button-primary remove-field" type="button"
                                  data-id="<?php echo esc_attr( $id_field ) ?>">
								<?php echo esc_html__( 'Delete', 'realty-portal' ); ?>
							</span>
						</span>

                        <input type="hidden" name="<?php echo esc_attr( $key ); ?>[hide]"
                               value="<?php echo esc_attr( $hide ); ?>"/>
                        <input type="hidden" name="<?php echo esc_attr( $key ); ?>[type]" value="checkbox"/>

                    </div>

				<?php endforeach; ?>

                <div class="show-clone-field"></div>

            </div>

            <div class="rp-setting-clone">
				<span class="button button-primary" data-id="<?php echo esc_attr( $id_form ); ?>">
					<?php echo esc_html__( 'Add new', 'realty-portal' ); ?>
				</span>
            </div>

        </div>

        <input type="hidden" name="name_option" value="property_custom_featured"/>

        <button class="button button-primary btn-property-submit" type="submit" name="submit"
                data-id-notice="<?php echo esc_attr( $id_notice ); ?>" data-name-option="property"
                data-id-form="<?php echo esc_attr( $id_form ); ?>"/>
		<?php echo esc_html__( 'Save Changes', 'realty-portal' ); ?>
        <span class="hide dashicons dashicons-update"></span>
        </button>
        <script>
			jQuery(document).ready(function ( $ ) {
				$('.rp-iconpicker-input').iconpicker({
					placement: 'top'
				});
				$('body').on('click', '.rp-iconpicker-input', function ( event ) {
					event.preventDefault();
					$(this).iconpicker({
						placement: 'top'
					});
				});
			});
        </script>
    </div>

</form>