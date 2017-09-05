<?php
/**
 * Agents Factory
 *
 * @package Realty_Portal
 * @author NooTeam <suppport@nootheme.com>
 */
class RP_Agents_Factory {

	/**
	 * The product (post) ID.
	 *
	 * @var int
	 */
	public $ID = 0;

	/**
	 * $post Stores post data.
	 *
	 * @var $post WP_Post
	 */
	public $post = null;

	/**
	 * $agent_info Stores agent data.
	 *
	 * @var $agent_info WP_Post
	 */
	public $agent = null;

	public function __construct( $agent ) {
		if ( is_numeric( $agent ) ) {
			$this->ID   = absint( $agent );
			$this->post = get_post( $this->ID );
		} elseif ( $agent instanceof RP_Agents_Factory ) {
			$this->ID   = absint( $agent->ID );
			$this->post = $agent->post;
		} elseif ( isset( $agent->ID ) ) {
			$this->ID   = absint( $agent->ID );
			$this->post = $agent;
		}
	}

	public function title() {
		return get_the_title( $this->ID );
	}

	public function permalink() {
		return get_permalink( $this->ID );
	}

	public function agent_info( $show = 'id' ) {
		
		switch ( $show ) {
			case 'name':
				$data_agent = get_the_title( $this->agent_info() );
				break;

			case 'url':
				$data_agent = get_permalink( $this->agent_info() );
				break;

			case 'total_property':
				$args_agent = array(
					'post_type'    => apply_filters( 'rp_property_post_type', 'rp_property' ),
					'post_status'  => 'publish',
					'meta_key'     => 'agent_responsible',
					'meta_value'   => $this->agent_info(),
					'meta_compare' => '='
				);
				$query_agent = new WP_Query( $args_agent );
				$data_agent     = $query_agent->found_posts;
				break;

			case 'position':
				$data_agent = get_post_meta( $this->agent_info(), apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_position', true );
				break;

			case 'phone':
				$data_agent = get_post_meta( $this->agent_info(), apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_phone', true );
				break;

			case 'mobile':
				$data_agent = get_post_meta( $this->agent_info(), apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_mobile', true );
				break;

			case 'mail':
				$data_agent = get_post_meta( $this->agent_info(), apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_mail', true );
				break;

			case 'about':
				$data_agent = get_post_meta( $this->agent_info(), apply_filters( 'rp_agent_post_type', 'rp_agent' ) . '_about', true );
				break;
			
			default:
				$data_agent = intval( $this->ID );
				break;
		}

		return $data_agent;

	}

	public function query_property( $args = array() ) {
		$args_default = array(
			'post_type'    => apply_filters( 'rp_property_post_type', 'rp_property' ),
			'post_status'  => 'publish',
			'meta_key'     => 'agent_responsible',
			'meta_value'   => $this->agent_info(),
			'meta_compare' => '=',
			'order'        => 'DESC'
		);

		$args_property = wp_parse_args( $args, $args_default );

		return apply_filters( 'agent_factory_query_property', $args_property );
	}

	public function agent_avatar( $size = 'rp-agent-avatar-medium', $echo = true ) {
		$id_avatar = get_post_thumbnail_id( $this->agent_info() );
		$avatar    = rp_thumb_src_id( $id_avatar, $size, '475x550' );
		if ( $echo ) {
			$html = apply_filters( 'rp_agent_avatar_agent', '<img src="' . $avatar . '" alt="' . self::agent_info( 'name' ) . '" />' );
			return $html;
		}
		return $avatar;
	}

	public function agent_custom_field() {
		$agent_custom_field = rp_agent_render_fields();
		if ( array_key_exists( 'social_network', $agent_custom_field ) ) {
			unset( $agent_custom_field[''] );
			unset( $agent_custom_field['social_network'] );
			unset( $agent_custom_field['_position'] );
			unset( $agent_custom_field['_about'] );
		}

		return apply_filters( 'agent_factory_custom_filed', $agent_custom_field );
	}

	public function list_social_agent() {
		return apply_filters( 'agent_factory_list_social', rp_get_list_social_agent() );
	}

}