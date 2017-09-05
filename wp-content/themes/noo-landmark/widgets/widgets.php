<?php
if ( ! class_exists( 'Noo_Landmark_Class_Widget_Categories' ) ) {

	class Noo_Landmark_Class_Widget_Categories extends WP_Widget {

		public function __construct() {
			$widget_ops = array(
				'classname'   => 'widget_noo_categories',
				'description' => esc_html__( "A list or dropdown of categories.", 'noo-landmark' ),
			);
			parent::__construct( 'noo_categories', esc_html__( 'Noo Categories', 'noo-landmark' ), $widget_ops );
		}

		public function widget( $args, $instance ) {

			/** This filter is documented in wp-includes/default-widgets.php */
			$title = apply_filters( 'widget_title', empty( $instance[ 'title' ] ) ? esc_html__( 'Categories', 'noo-landmark' ) : $instance[ 'title' ], $instance, $this->id_base );
			$c     = ! empty( $instance[ 'count' ] ) ? '1' : '0';
			$h     = ! empty( $instance[ 'hierarchical' ] ) ? '1' : '0';
			$p     = ! empty( $instance[ 'parent' ] ) ? 0 : '';
			echo $args[ 'before_widget' ];
			if ( $title ) {
				echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
			}

			$cat_args = array(
				'orderby'      => 'name',
				'show_count'   => $c,
				'parent'       => $p,
				'hierarchical' => $h,
			);
			?>
			<ul>
				<?php
				$cat_args[ 'title_li' ] = '';

				/**
				 * Filter the arguments for the Categories widget.
				 *
				 * @since 2.8.0
				 *
				 * @param array $cat_args An array of Categories widget options.
				 */
				wp_list_categories( apply_filters( 'widget_noo_categories_args', $cat_args ) );
				?>
			</ul>
			<?php

			echo $args[ 'after_widget' ];
		}

		public function update( $new_instance, $old_instance ) {
			$instance                   = $old_instance;
			$instance[ 'title' ]        = strip_tags( $new_instance[ 'title' ] );
			$instance[ 'count' ]        = ! empty( $new_instance[ 'count' ] ) ? 1 : 0;
			$instance[ 'hierarchical' ] = ! empty( $new_instance[ 'hierarchical' ] ) ? 1 : 0;
			$instance[ 'parent' ]       = ! empty( $new_instance[ 'parent' ] ) ? 1 : 0;

			return $instance;
		}

		public function form( $instance ) {
			//Defaults
			$instance     = wp_parse_args( (array) $instance, array( 'title' => '' ) );
			$title        = esc_attr( $instance[ 'title' ] );
			$count        = isset( $instance[ 'count' ] ) ? (bool) $instance[ 'count' ] : false;
			$hierarchical = isset( $instance[ 'hierarchical' ] ) ? (bool) $instance[ 'hierarchical' ] : false;
			$parent       = isset( $instance[ 'parent' ] ) ? (bool) $instance[ 'parent' ] : false;
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_html__( 'Title:', 'noo-landmark' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
			</p>

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>"<?php checked( $count ); ?> />
			<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php echo esc_html__( 'Show post counts', 'noo-landmark' ); ?></label>
			<br />

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'hierarchical' ); ?>" name="<?php echo $this->get_field_name( 'hierarchical' ); ?>"<?php checked( $hierarchical ); ?> />
			<label for="<?php echo $this->get_field_id( 'hierarchical' ); ?>"><?php echo esc_html__( 'Show hierarchy', 'noo-landmark' ); ?></label></p>

			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'parent' ); ?>" name="<?php echo $this->get_field_name( 'parent' ); ?>"<?php checked( $parent ); ?> />
			<label for="<?php echo $this->get_field_id( 'parent' ); ?>"><?php echo esc_html__( 'Only Show Parent', 'noo-landmark' ); ?></label></p>
			<?php
		}
	}

	register_widget( 'Noo_Landmark_Class_Widget_Categories' );
}

if ( ! class_exists( 'Noo_Landmark_Class_Recent_News' ) ) {

	class Noo_Landmark_Class_Recent_News extends WP_Widget {

		public function __construct() {
			$widget_ops = array(
				'classname'   => 'widget_recent_news',
				'description' => esc_html__( "Your site&#8217;s most recent Posts.", 'noo-landmark' ),
			);
			parent::__construct( 'recent-news', esc_html__( 'Noo Recent News', 'noo-landmark' ), $widget_ops );
			$this->alt_option_name = 'widget_recent_news';
		}

		public function widget( $args, $instance ) {
			$cache = array();
			if ( ! $this->is_preview() ) {
				$cache = wp_cache_get( 'widget_recent_news', 'widget' );
			}

			if ( ! is_array( $cache ) ) {
				$cache = array();
			}

			if ( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this->id;
			}

			if ( isset( $cache[ $args[ 'widget_id' ] ] ) ) {
				echo $cache[ $args[ 'widget_id' ] ];

				return;
			}

			ob_start();

			$title = ( ! empty( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : esc_html__( 'Recent News', 'noo-landmark' );

			/** This filter is documented in wp-includes/default-widgets.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

			$number = ( ! empty( $instance[ 'number' ] ) ) ? absint( $instance[ 'number' ] ) : 5;
			if ( ! $number ) {
				$number = 5;
			}
			$show_date = isset( $instance[ 'show_date' ] ) ? $instance[ 'show_date' ] : false;

			/**
			 * Filter the arguments for the Recent Posts widget.
			 *
			 * @since 3.4.0
			 *
			 * @see   WP_Query::get_posts()
			 *
			 * @param array $args An array of arguments used to retrieve the recent posts.
			 */
			$r = new WP_Query( apply_filters( 'widget_news_args', array(
				'posts_per_page'      => $number,
				'no_found_rows'       => true,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
			) ) );

			if ( $r->have_posts() ) :
				?>
				<?php echo $args[ 'before_widget' ]; ?>
				<?php if ( $title ) {
				echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
			} ?>
				<ul>
					<?php while ( $r->have_posts() ) : $r->the_post(); ?>
						<li>
							<?php if ( has_post_thumbnail() ):
								the_post_thumbnail( array(
									70,
									70,
								) );
							else: ?>
								<img width="70" height="70" src="<?php echo get_template_directory_uri() . '/assets/images/no-image.jpg'; ?>" alt="<?php the_title_attribute(); ?>" />
							<?php endif; ?>
							<?php
							$getlength  = strlen( get_the_title() );
							$thelength  = 28;
							$last_title = substr( get_the_title(), 0, $thelength );
							if ( $getlength > $thelength ) {
								$last_title = $last_title . "...";
							}
							?>
							<h5>
								<a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php echo get_the_title() ? $last_title : get_the_ID(); ?></a>
							</h5>
							<?php if ( $show_date ) : ?>
								<span class="post-date"><?php echo get_the_date(); ?></span>
							<?php endif; ?>
						</li>
					<?php endwhile; ?>
				</ul>
				<?php echo $args[ 'after_widget' ]; ?>
				<?php
				// Reset the global $the_post as this query will have stomped on it
				wp_reset_postdata();

			endif;

			if ( ! $this->is_preview() ) {
				$cache[ $args[ 'widget_id' ] ] = ob_get_flush();
				wp_cache_set( 'widget_recent_news', $cache, 'widget' );
			} else {
				ob_end_flush();
			}
		}

		public function update( $new_instance, $old_instance ) {
			$instance                = $old_instance;
			$instance[ 'title' ]     = strip_tags( $new_instance[ 'title' ] );
			$instance[ 'number' ]    = (int) $new_instance[ 'number' ];
			$instance[ 'show_date' ] = isset( $new_instance[ 'show_date' ] ) ? (bool) $new_instance[ 'show_date' ] : false;

			$alloptions = wp_cache_get( 'alloptions', 'options' );
			if ( isset( $alloptions[ 'noo_widget_recent_entries' ] ) ) {
				delete_option( 'noo_widget_recent_entries' );
			}

			return $instance;
		}

		public function form( $instance ) {
			$title     = isset( $instance[ 'title' ] ) ? esc_attr( $instance[ 'title' ] ) : '';
			$number    = isset( $instance[ 'number' ] ) ? absint( $instance[ 'number' ] ) : 5;
			$show_date = isset( $instance[ 'show_date' ] ) ? (bool) $instance[ 'show_date' ] : false;
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_html__( 'Title:', 'noo-landmark' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php echo esc_html__( 'Number of posts to show:', 'noo-landmark' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php echo esc_html__( 'Display post date?', 'noo-landmark' ); ?></label>
			</p>
			<?php
		}
	}

	register_widget( 'Noo_Landmark_Class_Recent_News' );
}

if ( ! class_exists( 'Noo_Landmark_Social' ) ) {
	/*Widget Noo Social*/

	class Noo_Landmark_Social extends WP_Widget {

		/* *
		* Register widget with WordPress.
		* parent user function class father
		*/
		function __construct() {
			parent::__construct( 'noo_social', // Base Id
				esc_html__( 'Noo Social', 'noo-landmark' ), // NAME
				array( 'description' => esc_html__( 'Display social network', 'noo-landmark' ) ) // args
			);
		}

		/**
		 * Front-end display of widget
		 */
		public function widget( $args, $instance ) {
			extract( $args );
			$title = apply_filters( 'widget_title', $instance[ 'title' ] );
			echo $before_widget;
			if ( $title ) :
				echo $before_title . $title . $after_title;
			endif;
			$arg_social = array(
				array( 'id' => 'facebook' ),
				array( 'id' => 'google' ),
				array( 'id' => 'twitter' ),
				array( 'id' => 'youtube' ),
				array( 'id' => 'skype' ),
				array( 'id' => 'linkedin' ),
				array( 'id' => 'dribbble' ),
				array( 'id' => 'pinterest' ),
				array( 'id' => 'flickr' ),
				array( 'id' => 'instagram' ),

			);
			?>
			<div class="noo_social">
				<div class="social-all">
					<?php
					foreach ( $arg_social as $social ):
						if ( ! empty( $instance[ $social[ 'id' ] ] ) ):
							?>
							<a href="<?php echo( $instance[ $social[ 'id' ] ] ); ?>" target="_blank" class="<?php echo esc_attr( $social[ 'id' ] ); ?>"><i class="fa fa-<?php echo esc_attr( $social[ 'id' ] ); ?>"></i></a>
							<?php
						endif;
					endforeach;
					?>
				</div>
			</div>
			<?php
			echo $after_widget;
		}

		/**
		 * Back-end widget form
		 */
		public function form( $instrance ) {
			// wp_parse_args : set default values
			$instrance = wp_parse_args( $instrance, array(
				'title'     => 'NooTheme',
				'facebook'  => '',
				'google'    => '',
				'twitter'   => '',
				'youtube'   => '',
				'skype'     => '',
				'linkedin'  => '',
				'dribbble'  => '',
				'pinterest' => '',
				'flickr'    => '',
				'instagram' => '',
			) );
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">
					<?php esc_html_e( 'Title', 'noo-landmark' ); ?>
				</label>
				<br>
				<input type="text" name="<?php echo $this->get_field_name( 'title' ); ?>" id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat" value="<?php echo esc_html( $instrance[ 'title' ] ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'facebook' ) ?>">
					<?php esc_html_e( 'Facebook', 'noo-landmark' ); ?>
				</label>
				<br>
				<input type="text" name="<?php echo $this->get_field_name( 'facebook' ); ?>" id="<?php echo $this->get_field_id( 'facebook' ); ?>" class="widefat" value="<?php echo esc_attr( $instrance[ 'facebook' ] ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'google' ) ?>">
					<?php esc_html_e( 'Google', 'noo-landmark' ); ?>
				</label>
				<br>
				<input type="text" name="<?php echo $this->get_field_name( 'google' ); ?>" id="<?php echo $this->get_field_id( 'google' ); ?>" class="widefat" value="<?php echo esc_attr( $instrance[ 'google' ] ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'twitter' ) ?>">
					<?php esc_html_e( 'Twitter', 'noo-landmark' ); ?>
				</label>
				<br>
				<input type="text" name="<?php echo $this->get_field_name( 'twitter' ); ?>" id="<?php echo $this->get_field_id( 'twitter' ); ?>" class="widefat" value="<?php echo esc_attr( $instrance[ 'twitter' ] ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'youtube' ) ?>">
					<?php esc_html_e( 'Youtube', 'noo-landmark' ); ?>
				</label>
				<br>
				<input type="text" name="<?php echo $this->get_field_name( 'youtube' ); ?>" id="<?php echo $this->get_field_id( 'youtube' ); ?>" class="widefat" value="<?php echo esc_attr( $instrance[ 'youtube' ] ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'skype' ); ?>">
					<?php esc_html_e( 'Skype', 'noo-landmark' ); ?>
				</label>
				<br>
				<input type="text" name="<?php echo $this->get_field_name( 'skype' ); ?>" id="<?php echo $this->get_field_id( 'skype' ); ?>" class="widefat" value="<?php echo esc_attr( $instrance[ 'skype' ] ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'linkedin' ) ?>">
					<?php esc_html_e( 'linkedin', 'noo-landmark' ); ?>
				</label>
				<br>
				<input type="text" name="<?php echo $this->get_field_name( 'linkedin' ); ?>" id="<?php echo $this->get_field_id( 'linkedin' ); ?>" class="widefat" value="<?php echo esc_attr( $instrance[ 'linkedin' ] ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'dribbble' ) ?>">
					<?php esc_html_e( 'Dribbble', 'noo-landmark' ); ?>
				</label>
				<br>
				<input type="text" name="<?php echo $this->get_field_name( 'dribbble' ); ?>" id="<?php echo $this->get_field_id( 'dribbble' ); ?>" class="widefat" value="<?php echo esc_attr( $instrance[ 'dribbble' ] ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'pinterest' ) ?>">
					<?php esc_html_e( 'Pinterest', 'noo-landmark' ); ?>
				</label>
				<br>
				<input type="text" name="<?php echo $this->get_field_name( 'pinterest' ); ?>" id="<?php echo $this->get_field_id( 'pinterest' ); ?>" class="widefat" value="<?php echo esc_attr( $instrance[ 'pinterest' ] ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'flickr' ) ?>">
					<?php esc_html_e( 'Flickr', 'noo-landmark' ); ?>
				</label>
				<br>
				<input type="text" name="<?php echo $this->get_field_name( 'flickr' ); ?>" id="<?php echo $this->get_field_id( 'flickr' ); ?>" class="widefat" value="<?php echo esc_attr( $instrance[ 'flickr' ] ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'instagram' ) ?>">
					<?php esc_html_e( 'Instagram', 'noo-landmark' ); ?>
				</label>
				<br>
				<input type="text" name="<?php echo $this->get_field_name( 'instagram' ); ?>" id="<?php echo $this->get_field_id( 'instagram' ); ?>" class="widefat" value="<?php echo esc_attr( $instrance[ 'instagram' ] ); ?>">
			</p>
			<?php
		}

		/* *
		 * Method update
		 */
		function update( $new_instance, $old_instance ) {
			$instance                = array();
			$instance[ 'title' ]     = ( ! empty( $new_instance[ 'title' ] ) ) ? strip_tags( $new_instance[ 'title' ] ) : '';
			$instance[ 'facebook' ]  = ( ! empty( $new_instance[ 'facebook' ] ) ) ? strip_tags( $new_instance[ 'facebook' ] ) : '';
			$instance[ 'google' ]    = ( ! empty( $new_instance[ 'google' ] ) ) ? strip_tags( $new_instance[ 'google' ] ) : '';
			$instance[ 'twitter' ]   = ( ! empty( $new_instance[ 'twitter' ] ) ) ? strip_tags( $new_instance[ 'twitter' ] ) : '';
			$instance[ 'youtube' ]   = ( ! empty( $new_instance[ 'youtube' ] ) ) ? strip_tags( $new_instance[ 'youtube' ] ) : '';
			$instance[ 'skype' ]     = ( ! empty( $new_instance[ 'skype' ] ) ) ? strip_tags( $new_instance[ 'skype' ] ) : '';
			$instance[ 'linkedin' ]  = ( ! empty( $new_instance[ 'linkedin' ] ) ) ? strip_tags( $new_instance[ 'linkedin' ] ) : '';
			$instance[ 'dribbble' ]  = ( ! empty( $new_instance[ 'dribbble' ] ) ) ? strip_tags( $new_instance[ 'dribbble' ] ) : '';
			$instance[ 'pinterest' ] = ( ! empty( $new_instance[ 'pinterest' ] ) ) ? strip_tags( $new_instance[ 'pinterest' ] ) : '';
			$instance[ 'flickr' ]    = ( ! empty( $new_instance[ 'flickr' ] ) ) ? strip_tags( $new_instance[ 'flickr' ] ) : '';
			$instance[ 'instagram' ] = ( ! empty( $new_instance[ 'instagram' ] ) ) ? strip_tags( $new_instance[ 'instagram' ] ) : '';

			return $instance;
		}

	}

	register_widget( 'Noo_Landmark_Social' );
}

// Noo LandMark About
if ( ! class_exists( 'Noo_Landmark_About' ) ) {
	class Noo_Landmark_About extends WP_Widget {

		public function __construct() {
			$widget_option = array(
				'classname'   => 'widget_about',
				'description' => esc_html__( 'Display about', 'noo-landmark' ),
			);
			parent::__construct( 'noo-about', esc_html__( 'Noo About', 'noo-landmark' ), $widget_option );
			add_action( 'admin_enqueue_scripts', array(
				$this,
				'register_js',
			) );
		}

		public function register_js() {

			wp_enqueue_media();
			wp_register_script( 'upload_img', get_template_directory_uri() . '/admin_assets/js/upload_img.js', false, false, $in_footer = true );
			wp_enqueue_script( 'upload_img' );
		}

		// method for front-end
		public function widget( $args, $instance ) {
			extract( $args );
			extract( $instance );
			echo $before_widget;
			?>
			<div class="noo_about_widget">
				<a href="<?php echo esc_url( $link ); ?>">
					<img src="<?php echo esc_url( $image ); ?>" alt="<?php bloginfo( 'title' ); ?>">
				</a>
				<?php if ( isset( $title ) && ! empty( $title ) ): ?>
					<h3 class="about-title"><?php echo esc_html( $title ); ?></h3><?php endif; ?>
				<p>
					<?php echo esc_html( $description ); ?>
				</p>
			</div>
			<?php
			echo $after_widget;
		}

		// method widget form
		public function form( $instance ) {

			$instance = wp_parse_args( $instance, array(
				'title'       => 'Upload logo',
				'link'        => '',
				'image'       => '',
				'description' => '',
			) );
			extract( $instance );
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'noo-landmark' ); ?></label>
				<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ) ?>" class="widefat" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php esc_html_e( 'Link Image:', 'noo-landmark' ); ?></label>
				<input type="text" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ) ?>" class="widefat" value="<?php echo esc_attr( $link ); ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php esc_html_e( 'Image', 'noo-landmark' ); ?></label>
				<input class="widefat" type="text" name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" value="<?php echo esc_attr( $image ); ?>">
				<a href="#" class="noo_upload_button button" rel="image"><?php esc_html_e( 'Upload', 'noo-landmark' ) ?></a>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php esc_html_e( 'Description', 'noo-landmark' ); ?></label>
				<textarea name="<?php echo $this->get_field_name( 'description' ); ?>" id="<?php echo $this->get_field_id( 'description' ); ?>" cols="10" rows="5" class="widefat"><?php echo esc_attr( $description ); ?></textarea>
			</p>


			<?php
		}

		// method update
		public function update( $new_instance, $old_instance ) {
			$instance                  = $old_instance;
			$instance[ 'title' ]       = $new_instance[ 'title' ];
			$instance[ 'link' ]        = $new_instance[ 'link' ];
			$instance[ 'image' ]       = $new_instance[ 'image' ];
			$instance[ 'description' ] = $new_instance[ 'description' ];

			return $instance;
		}

	}

	register_widget( 'Noo_Landmark_About' );
}

if ( ! class_exists( 'Noo_Landmark_Class_Post_Slider' ) ) {

	class Noo_Landmark_Class_Post_Slider extends WP_Widget {

		public function __construct() {
			$widget_ops = array(
				'classname'   => 'widget_post_slider',
				'description' => __( "Your site&#8217;s most recent Posts.", 'noo-landmark' ),
			);
			parent::__construct( 'post-slider', esc_html__( 'Post Slider', 'noo-landmark' ), $widget_ops );
			$this->alt_option_name = 'widget_post_slider';
		}

		public function widget( $args, $instance ) {
			$cache = array();
			if ( ! $this->is_preview() ) {
				$cache = wp_cache_get( 'widget_post_slider', 'widget' );
			}

			if ( ! is_array( $cache ) ) {
				$cache = array();
			}

			if ( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this->id;
			}

			if ( isset( $cache[ $args[ 'widget_id' ] ] ) ) {
				echo $cache[ $args[ 'widget_id' ] ];

				return;
			}

			ob_start();

			$title = ( ! empty( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : esc_html__( 'Post Slider', 'noo-landmark' );

			/** This filter is documented in wp-includes/default-widgets.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

			$number = ( ! empty( $instance[ 'number' ] ) ) ? absint( $instance[ 'number' ] ) : 5;
			if ( ! $number ) {
				$number = 5;
			}
			$show_date = isset( $instance[ 'show_date' ] ) ? $instance[ 'show_date' ] : false;

			/**
			 * Filter the arguments for the Recent Posts widget.
			 *
			 * @since 3.4.0
			 *
			 * @see   WP_Query::get_posts()
			 *
			 * @param array $args An array of arguments used to retrieve the recent posts.
			 */

			$ar                  = array(
				'posts_per_page'      => $number,
				'no_found_rows'       => true,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
			);
			$ar[ 'tax_query' ][] = array(
				'taxonomy' => 'post_format',
				'field'    => 'slug',
				'terms'    => 'post-format-gallery',
			);
			$r                   = new WP_Query( $ar );
			wp_enqueue_script( 'imagesloaded' );
			wp_enqueue_script( 'noo-carousel' );
			$posts_in_column = 1;
			$columns         = 1;
			$noo_post_uid    = uniqid( 'noo_post_' );
			$class           = '';
			$class           .= ' ' . $noo_post_uid;
			$class           = ( $class != '' ) ? ' class="' . esc_attr( $class ) . '"' : '';
			if ( $r->have_posts() ) :
				?>
				<?php echo $args[ 'before_widget' ]; ?>
				<?php if ( $title ) {
				echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
			} ?>
				<div class="noo-widget-post-slider-wrap" id="<?php echo esc_attr( $noo_post_uid ); ?>" data-id="<?php echo esc_attr( $noo_post_uid ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>">

					<div class="row">
						<div class="widget-post-slider-content gallery">

							<?php $i = 0; ?>
							<?php while ( $r->have_posts() ): $r->the_post();
								global $post;
								?>

								<?php if ( $i ++ % $posts_in_column == 0 ): ?>
									<div class="noo-post-slider-item col-sm-<?php echo absint( ( 12 / $columns ) ) ?>">
								<?php endif; ?>
								<div class="noo-post-slider-inner">
									<div class="post-slider-featured">
										<?php the_post_thumbnail( 'noo-thumbnail-square' ) ?>
									</div>
									<div class="post-slider-content">
										<h5 class="post-slider-title">
											<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permanent link to: "%s"', 'noo-landmark' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a>
										</h5>
									</div>
								</div>
								<?php if ( $i % $posts_in_column == 0 || $i == $r->post_count ): ?>
									</div>
								<?php endif; ?>

							<?php endwhile; ?>
						</div>
					</div>
					<div class="noo-post-navi">
						<div class="noo_slider_prev"><i class="fa fa-caret-left"></i></div>
						<div class="noo_slider_next"><i class="fa fa-caret-right"></i></div>
					</div>
				</div>
				<?php echo $args[ 'after_widget' ]; ?>
				<?php
				// Reset the global $the_post as this query will have stomped on it
				wp_reset_postdata();

			endif;

			if ( ! $this->is_preview() ) {
				$cache[ $args[ 'widget_id' ] ] = ob_get_flush();
				wp_cache_set( 'widget_post_slider', $cache, 'widget' );
			} else {
				ob_end_flush();
			}
		}

		public function update( $new_instance, $old_instance ) {
			$instance                = $old_instance;
			$instance[ 'title' ]     = strip_tags( $new_instance[ 'title' ] );
			$instance[ 'number' ]    = (int) $new_instance[ 'number' ];
			$instance[ 'show_date' ] = isset( $new_instance[ 'show_date' ] ) ? (bool) $new_instance[ 'show_date' ] : false;

			$alloptions = wp_cache_get( 'alloptions', 'options' );
			if ( isset( $alloptions[ 'widget_recent_entries' ] ) ) {
				delete_option( 'widget_recent_entries' );
			}

			return $instance;
		}

		public function form( $instance ) {
			$title     = isset( $instance[ 'title' ] ) ? esc_attr( $instance[ 'title' ] ) : '';
			$number    = isset( $instance[ 'number' ] ) ? absint( $instance[ 'number' ] ) : 5;
			$show_date = isset( $instance[ 'show_date' ] ) ? (bool) $instance[ 'show_date' ] : false;
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_html__( 'Title:', 'noo-landmark' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php echo esc_html__( 'Number of posts to show:', 'noo-landmark' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php echo esc_html__( 'Display post date?', 'noo-landmark' ); ?></label>
			</p>
			<?php
		}
	}

	register_widget( 'Noo_Landmark_Class_Post_Slider' );
}

// Widget: Noo Gallery

if ( ! class_exists( 'Noo_Landmark_Class_Gallery' ) ) {

	class Noo_Landmark_Class_Gallery extends WP_Widget {

		public function __construct() {
			$widget_ops = array(
				'classname'   => 'widget_gallery',
				'description' => esc_html__( "Show Your Gallery.", 'noo-landmark' ),
			);
			parent::__construct( 'gallery', esc_html__( 'Noo Gallery', 'noo-landmark' ), $widget_ops );
			$this->alt_option_name = 'widget_gallery';
		}

		public function widget( $args, $instance ) {
			$cache = array();
			if ( ! $this->is_preview() ) {
				$cache = wp_cache_get( 'widget_gallery', 'widget' );
			}

			if ( ! is_array( $cache ) ) {
				$cache = array();
			}

			if ( ! isset( $args[ 'widget_id' ] ) ) {
				$args[ 'widget_id' ] = $this->id;
			}

			if ( isset( $cache[ $args[ 'widget_id' ] ] ) ) {
				echo $cache[ $args[ 'widget_id' ] ];

				return;
			}

			ob_start();

			$title = ( ! empty( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : esc_html__( 'Gallery', 'noo-landmark' );

			/** This filter is documented in wp-includes/default-widgets.php */
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

			$limit = ( ! empty( $instance[ 'limit' ] ) ) ? absint( $instance[ 'limit' ] ) : 6;
			if ( ! $limit ) {
				$litmit = 6;
			}
			$orderby = isset( $instance[ 'orderby' ] ) ? $instance[ 'orderby' ] : 'latest';

			$order = 'DESC';
			switch ( $orderby ) {
				case 'latest':
					$orderby = 'date';
					break;

				case 'oldest':
					$orderby = 'date';
					$order   = 'ASC';
					break;

				case 'alphabet':
					$orderby = 'title';
					$order   = 'ASC';
					break;

				case 'ralphabet':
					$orderby = 'title';
					break;

				case 'rand':
					$orderby = 'rand';
					break;

				default:
					$orderby = 'date';
					break;
			}

			/**
			 * Filter the arguments for the Gallery widget.
			 *
			 * @since 3.4.0
			 *
			 * @see   WP_Query::get_posts()
			 *
			 * @param array $args An array of arguments used to retrieve the Gallery.
			 */
			$r_args = array(
				'post_type'      => 'noo_gallery',
				'orderby'        => $orderby,
				'order'          => $order,
				'posts_per_page' => $limit,
			);

			$r = new WP_Query( $r_args );

			/*
	         * Required library LightGallery
	         */
			wp_enqueue_style( 'lightgallery' );
			wp_enqueue_script( 'lightgallery' );
			/**
			 * A jQuery plugin that adds cross-browser mouse wheel support with Lightgallery
			 */
			wp_enqueue_script( 'lightgallery_mousewheel' );

			$noo_gallery_uid = uniqid( 'noo_gallery_' );
			$class           = '';
			$class           .= ' ' . $noo_gallery_uid;
			$class           = ( $class != '' ) ? ' class="' . esc_attr( $class ) . '"' : '';
			if ( $r->have_posts() ) :
				?>
				<?php echo $args[ 'before_widget' ]; ?>
				<?php if ( $title ) {
				echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
			} ?>
				<div class="noo-widget-gallery-wrap" id="<?php echo esc_attr( $noo_gallery_uid ); ?>" data-id="<?php echo esc_attr( $noo_gallery_uid ); ?>">

					<div class="row">
						<div class="widget-latest_ratting-content widget_gallery_wrap galleries">

							<?php while ( $r->have_posts() ): $r->the_post();
								global $post;
								?>
								<a class="gallery-item" href="<?php echo get_the_post_thumbnail_url(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
									<?php the_post_thumbnail( array(
										66,
										66,
									) ); ?>
								</a>
							<?php endwhile; ?>
						</div>
					</div>
				</div>

				<?php echo $args[ 'after_widget' ]; ?>
				<?php
				// Reset the global $the_post as this query will have stomped on it
				wp_reset_postdata();

			endif;

			if ( ! $this->is_preview() ) {
				$cache[ $args[ 'widget_id' ] ] = ob_get_flush();
				wp_cache_set( 'widget_latest_ratting', $cache, 'widget' );
			} else {
				ob_end_flush();
			}
		}

		public function update( $new_instance, $old_instance ) {
			$instance              = $old_instance;
			$instance[ 'title' ]   = strip_tags( $new_instance[ 'title' ] );
			$instance[ 'orderby' ] = strip_tags( $_POST[ 'orderbyOption' ] );
			$instance[ 'limit' ]   = (int) ( $new_instance[ 'limit' ] );

			$alloptions = wp_cache_get( 'alloptions', 'options' );
			if ( isset( $alloptions[ 'widget_recent_entries' ] ) ) {
				delete_option( 'widget_recent_entries' );
			}

			return $instance;
		}

		public function form( $instance ) {
			$title        = isset( $instance[ 'title' ] ) ? esc_attr( $instance[ 'title' ] ) : 'Gallery';
			$orderby      = isset( $instance[ 'orderby' ] ) ? esc_attr( $instance[ 'orderby' ] ) : '';
			$limit        = isset( $instance[ 'limit' ] ) ? esc_attr( $instance[ 'limit' ] ) : 6;
			$orderbyValue = array(
				'lastest'   => 'Latest',
				'oldest'    => 'Oldest',
				'alphabet'  => 'Alphabet',
				'ralphabet' => 'Ralphabet',
				'rand'      => 'Random',
			);
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_html__( 'Title:', 'noo-landmark' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php echo esc_html__( 'Orderby:', 'noo-landmark' ); ?></label>
				<select class="widefat" name="orderbyOption">
					<?php foreach ( $orderbyValue as $key => $value ) { ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php if ( $orderby == $key ) {
							echo 'selected="selected"';
						} ?> ><?php echo esc_html( $value ); ?></option>
					<?php } ?>
					<option value="lastest">Latest</option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php echo esc_html__( 'Limit number of Images to show:', 'noo-landmark' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo $limit; ?>" size="3" />
			</p>
			<?php
		}
	}

	register_widget( 'Noo_Landmark_Class_Gallery' );
}