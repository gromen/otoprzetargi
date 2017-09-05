<?php
class RP_Meta_Boxes {
	public $prefix   = 'realty-portal';
	public $page     = 'post';
	public $context  = 'normal';
	public $priority = 'high';

	/**
	 * RP_Meta_Boxes constructor.
	 *
	 * @param       $prefix
	 * @param array $args
	 */
	public function __construct( $prefix, $args = array() ) {
		$this->prefix = $prefix;

		$keys = array_keys( get_object_vars( $this ) );
		foreach ( $keys as $key ) {
			if ( isset( $args[ $key ] ) ) {
				$this->$key = $args[ $key ];
			}
		}
	}

	/**
	 * Add meta box
	 *
	 * @param        $meta_box
	 * @param string $template
	 * @param array  $template_data
	 *
	 * @return bool
	 */
	public function add_meta_box( $meta_box, $template = '', $template_data = array() ) {

		if ( ! is_array( $meta_box ) ) {
			return false;
		}

		if ( ! isset( $meta_box[ 'id' ] ) || empty( $meta_box[ 'id' ] ) ) {
			return false;
		}

		if ( ! isset( $meta_box[ 'page' ] ) || empty( $meta_box[ 'page' ] ) ) {
			$meta_box[ 'page' ] = $this->page;
		}

		if ( ! isset( $meta_box[ 'context' ] ) || empty( $meta_box[ 'context' ] ) ) {
			$meta_box[ 'context' ] = $this->context;
		}

		if ( ! isset( $meta_box[ 'priority' ] ) || empty( $meta_box[ 'priority' ] ) ) {
			$meta_box[ 'priority' ] = $this->priority;
		}

		if ( ! isset( $meta_box[ 'fields' ] ) || empty( $meta_box[ 'fields' ] ) ) {
			if ( empty( $template ) ) {
				return false;
			}

			$meta_box[ 'fields' ] = array();
			switch ( $template ) {
				case 'post-type':
					if ( ! is_array( $template_data ) ) {
						return false;
					}

					$options = array();
					foreach ( $template_data as $key => $title ) {
						$options[] = array(
							'label' => $title,
							'value' => $key,
							'class' => 'post-format-icon post-format-' . $key,
						);
					}

					$meta_box[ 'fields' ][] = array(
						'id'      => $this->prefix . '_post_type',
						'type'    => 'radio',
						'std'     => 'image',
						'class'   => 'rp-post-type',
						'options' => $options,
					);

					break;
				case 'data-link':
					$meta_box[ 'fields' ][] = array(
						'id'    => $this->prefix . '_link',
						'label' => esc_html__( 'The Link', 'realty-portal' ),
						'type'  => 'text',
						'std'   => 'https://nootheme.com',
					);

					break;
				case 'data-video':
					$meta_box[ 'fields' ][] = array(
						'id'    => $this->prefix . '_video_m4v',
						'label' => esc_html__( 'M4V File URL', 'realty-portal' ),
						'desc'  => esc_html__( 'Place the URL to your .m4v video file here.', 'realty-portal' ),
						'type'  => 'text',
					);
					$meta_box[ 'fields' ][] = array(
						'id'    => $this->prefix . '_video_ogv',
						'label' => esc_html__( 'OGV File URL', 'realty-portal' ),
						'desc'  => esc_html__( 'Place the URL to your .ogv video file here.', 'realty-portal' ),
						'type'  => 'text',
					);
					$meta_box[ 'fields' ][] = array(
						'id'      => $this->prefix . '_video_aspect_ratio',
						'label'   => esc_html__( 'Video Aspect Ratio', 'realty-portal' ),
						'desc'    => esc_html__( 'Choose the aspect ratio for your video.', 'realty-portal' ),
						'type'    => 'select',
						'std'     => '16:9',
						'options' => array(
							array(
								'value' => '16:9',
								'label' => '16:9',
							),
							array(
								'value' => '5:3',
								'label' => '5:3',
							),
							array(
								'value' => '5:4',
								'label' => '5:4',
							),
							array(
								'value' => '4:3',
								'label' => '4:3',
							),
							array(
								'value' => '3:2',
								'label' => '3:2',
							),
						),
					);
					$meta_box[ 'fields' ][] = array(
						'id'    => $this->prefix . '_video_embed',
						'label' => esc_html__( 'Embedded Video Code', 'realty-portal' ),
						'desc'  => esc_html__( 'If you are using something other than self hosted video (YouTube, Vimeo, etc.) paste the embed code here. This field will override the above settings.', 'realty-portal' ),
						'type'  => 'textarea',
						'std'   => '',
					);
					break;

				case 'data-image':
					$meta_box[ 'fields' ][] = array(
						'id'    => $this->prefix . '_image',
						'label' => esc_html__( 'Upload Image', 'realty-portal' ),
						'type'  => 'image',
					);
					break;

				case 'data-gallery':
					$meta_box[ 'fields' ][] = array(
						'id'   => $this->prefix . '_gallery',
						'type' => 'gallery',
					);
					break;
			}
		}

		if ( empty( $meta_box[ 'fields' ] ) ) {
			return false;
		}

		// Create a callback function
		$callback = create_function( '$post,$meta_box', 'rp_create_meta_box( $post, $meta_box["args"] );' );

		add_meta_box( $meta_box[ 'id' ], $meta_box[ 'title' ], $callback, $meta_box[ 'page' ], $meta_box[ 'context' ], $meta_box[ 'priority' ], $meta_box );
	}
}
