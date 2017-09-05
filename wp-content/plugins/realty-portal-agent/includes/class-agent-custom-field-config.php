<?php
/**
 * RP_Agent_Config_Custom_Fields_Setting Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'RP_Agent_Config_Custom_Fields_Setting' ) ) :

	class RP_Agent_Config_Custom_Fields_Setting {

		public static $id_notice;

		/**
		 *    Initialize class
		 */
		public function __construct() {
			self::$id_notice = uniqid( 'id-notice-' );
			add_action( 'RP_Tab_Setting_Content/Custom_Fields_After', 'RP_Agent_Config_Custom_Fields_Setting::render_html' );

			add_filter( 'RP_Tab_Setting/Custom_Fields', 'RP_Agent_Config_Custom_Fields_Setting::tab_setting', 10 );
		}

		/**
		 * Show html setting agent
		 *
		 * @param $list_tab
		 *
		 * @return array
		 */
		public static function tab_setting( $list_tab ) {
			$list_tab[] = array(
				'name'     => esc_html__( 'Agents', 'realty-portal-agent' ),
				'id'       => 'tab-setting-custom-field-agent',
				'position' => 10,
			);

			return $list_tab;
		}

		/**
		 * Render html
		 */
		public static function render_html() {
			$custom_fields = rp_agent_render_fields();
			unset( $custom_fields[ 'social_network' ] );
			?>
			<div class="rp-setting-form" id="tab-setting-custom-field-agent">
				<form id="rp-custom-field-agent-form" class="rp-validate-form">

					<div class="rp-setting-wrap">

						<?php self::custom_field_template(); ?>

						<div class="rp-box-option">
							<h3><?php echo esc_html__( 'Social Networks', 'realty-portal-agent' ); ?></h3>
							<div class="rp-box-item">
								<?php
								$list_social   = rp_agent_custom_social_agent();
								$social_select = Realty_Portal::get_setting( 'agent_custom_field', 'social_network', rp_social_agent_default() );
								?>
								<select class="rp-select" id="social" name="social_network[]" multiple>
									<?php
									foreach ( $list_social as $social_item ) {
										$selected = '';
										if ( in_array( $social_item[ 'id' ], $social_select ) ) {
											$selected = ' selected="selected"';
										}
										echo '<option value="' . esc_attr( $social_item[ 'id' ] ) . '"' . esc_attr( $selected ) . '>' . esc_html( $social_item[ 'label' ] ) . '</option>';
									}
									?>
								</select>
							</div>
						</div>

						<input type="hidden" name="name_option" value="agent_custom_field" />

						<button class="button button-primary btn-property-submit" type="submit" name="submit" data-id-notice="<?php echo self::$id_notice ?>" data-id-form="rp-custom-field-agent-form" />
						<?php echo esc_html__( 'Save Changes', 'realty-portal-agent' ); ?>
						<span class="hide dashicons dashicons-update"></span>
						</button>
					</div>

				</form>
			</div>
			<?php
		}

		/**
		 * Show html
		 */
		public static function custom_field_template() {
			$types         = rp_custom_fields_type();
			$custom_fields = rp_agent_render_fields();
			unset( $custom_fields[ 'social_network' ] );
			?>
			<div class="rp-setting-wrap">

				<h1 class="rp-setting-title">
					<?php echo esc_html__( 'Custom Fields', 'realty-portal-agent' ); ?>
				</h1>

				<div class="rp-setting-notice" id="<?php echo self::$id_notice ?>"></div>

				<div class="rp-setting-head">
					<?php do_action( 'rp-custom-field-setting-header-before' ); ?>

					<span class="rp-setting-col">
						<?php echo esc_html__( 'Field Key', 'realty-portal-agent' ); ?>
						<span class="dashicons dashicons-warning rp-tooltip" data-content="<?php echo esc_html__( 'The key used to save this field to database.<br/>Should only includes lower characters with no space.', 'realty-portal-agent' ); ?>"></span>
					</span>
					<span class="rp-setting-col"><?php echo esc_html__( 'Field Label', 'realty-portal-agent' ); ?></span>
					<span class="rp-setting-col"><?php echo esc_html__( 'Field Type', 'realty-portal-agent' ); ?></span>
					<span class="rp-setting-col">
					<?php echo esc_html__( 'Field Value/Params', 'realty-portal-agent' ); ?>
						<span class="dashicons dashicons-warning rp-tooltip" data-content="<?php echo esc_html__( 'Default value or options for this field.<br/>
						 - Field text, number or textarea use this Value as placeholder<br/>
						 - Field select, multiple select, radio or checkbox generate the options from this Value using line break as separator. Sample options:<br/>
							value_1|Option 1<br/>
							Option 2 ( value can be obmitted )<br/>
							value_3|Option 3', 'realty-portal-agent' ); ?>"></span>
							</span>
					<span class="rp-setting-col"><?php echo esc_html__( 'Required', 'realty-portal-agent' ); ?></span>
					<span class="rp-setting-col"><?php echo esc_html__( 'Action', 'realty-portal-agent' ); ?></span>

					<?php do_action( 'rp-custom-field-setting-header-after' ); ?>
				</div>

				<div class="rp-setting-content-wrap">

					<div class="rp-setting-content">

						<?php if ( isset( $custom_fields ) && ! empty( $custom_fields ) ) : ?>
							<?php foreach ( $custom_fields as $key => $field ) :

								$name = ! empty( $field[ 'name' ] ) ? $field[ 'name' ] : '';
								$label = ! empty( $field[ 'label' ] ) ? $field[ 'label' ] : '';
								$value = ! empty( $field[ 'value' ] ) ? $field[ 'value' ] : '';
								$type = ! empty( $field[ 'type' ] ) ? $field[ 'type' ] : 'text';
								$required = ! empty( $field[ 'required' ] ) ? $field[ 'required' ] : '';
								$hide = ! empty( $field[ 'hide' ] ) ? $field[ 'hide' ] : '';
								$readonly = ! empty( $field[ 'readonly' ] ) ? $field[ 'readonly' ] : '';
								$disable = ! empty( $field[ 'disable' ] ) ? $field[ 'disable' ] : false;

								$key = ! empty( $hide ) ? 'clone_field[0]' : $key;

								$label_action = esc_html__( 'Delete', 'realty-portal-agent' );
								$class_action = '';

								if ( ! empty( $readonly ) ) {
									if ( ! empty( $disable ) ) {
										$label_action = esc_html__( 'Enable', 'realty-portal-agent' );
										$class_action = 'enable';
									} else {
										$label_action = esc_html__( 'Disable', 'realty-portal-agent' );
										$class_action = 'disable';
									}
								}

								?>
								<div class="rp-setting-item-field<?php echo ! empty( $hide ) ? ' clone-field hide' : '' ?> <?php echo ! empty( $disable ) ? ' disable' : '' ?>" id="<?php echo $id_field = uniqid( 'id-field-' ); ?>" data-index="0">

									<?php do_action( 'rp-custom-field-setting-content-before', $field ); ?>

									<span class="rp-setting-col rp-col-name">
                                        <input type="text" name="<?php echo esc_attr( $key ); ?>[name]" value="<?php echo esc_html( $name ); ?>" <?php echo ! empty( $readonly ) ? ' readonly="readonly"' : ''; ?> placeholder="<?php echo esc_html__( 'Enter your field key...', 'realty-portal-agent' ); ?>" />
                                    </span>
									<span class="rp-setting-col">
                                        <input type="text" name="<?php echo esc_attr( $key ); ?>[label]" value="<?php echo esc_html( $label ); ?>" placeholder="<?php echo esc_html__( 'Enter your field label...', 'realty-portal-agent' ); ?>" />
                                    </span>
									<span class="rp-setting-col">
                                        <select name="<?php echo esc_attr( $key ); ?>[type]">
                                            <?php if ( ! empty( $types ) ) : foreach ( $types as $c_key => $c_type ) : ?>
	                                            <option value="<?php echo $c_key; ?>"<?php selected( $type, $c_key, true ); ?>><?php echo $c_type; ?></option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                    </span>
									<span class="rp-setting-col">
                                        <textarea name="<?php echo esc_attr( $key ); ?>[value]" placeholder="<?php echo esc_html__( 'Enter your value...', 'realty-portal-agent' ); ?>"><?php echo esc_html( $value ); ?></textarea>
                                    </span>
									<span class="rp-setting-col">
                                        <div class="switch">
                                            <input id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ); ?>[required]" value="1" class="switch-checkbox" type="checkbox" <?php checked( $required, '1', true ); ?> />
                                            <label class="switch-label" for="<?php echo esc_attr( $key ) ?>"></label>
                                        </div>
                                    </span>
									<span class="rp-setting-col">
                                        <span class="button button-primary remove-field <?php echo esc_attr( $class_action ); ?>" type="button" data-id="<?php echo esc_attr( $id_field ) ?>" data-id-input="<?php echo $id_input = uniqid( 'id-input-' ); ?>" data-action="<?php echo esc_attr( $class_action ); ?>">
                                            <?php echo esc_html( $label_action ); ?>
                                        </span>
                                    </span>

									<input type="hidden" name="<?php echo esc_attr( $key ); ?>[readonly]" value="<?php echo esc_attr( $readonly ); ?>" />
									<input type="hidden" name="<?php echo esc_attr( $key ); ?>[hide]" value="<?php echo esc_attr( $hide ); ?>" />

									<?php if ( ! empty( $readonly ) ) : ?>

										<input type="hidden" id="<?php echo esc_attr( $id_input ); ?>" name="<?php echo esc_attr( $key ); ?>[disable]" value="<?php echo esc_attr( $disable ); ?>" />

									<?php endif; ?>

									<?php do_action( 'rp-custom-field-setting-content-after', $field ); ?>

								</div>

							<?php endforeach; ?>

						<?php endif; ?>

						<div class="show-clone-field"></div>

					</div>

					<div class="rp-setting-clone">
                        <span class="button button-primary">
                            <?php echo esc_html__( 'Add new', 'realty-portal-agent' ); ?>
                        </span>
					</div>

				</div>

			</div>
			<?php
		}

	}

	new RP_Agent_Config_Custom_Fields_Setting();

endif;