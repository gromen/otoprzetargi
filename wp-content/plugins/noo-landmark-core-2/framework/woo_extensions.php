<?php

class Noo_Woocommerce_Extensions {

    public $slug = 'noo-woo';

    public $plugin_path;
    
    public $plugin_url;

    public $ajax_nonce_string;

    function __construct() {

        $this->plugin_path                      =                plugin_dir_path( __FILE__ );
        $this->plugin_url                       =                plugin_dir_url( __FILE__ );
        $this->ajax_nonce_string                =                $this->slug . '_ajax_nonce';
        
        add_action( 'plugins_loaded', array( $this, 'setup' ) );

    }

    function setup() {

        add_filter( 'woocommerce_available_variation',                          array( $this, 'add_other_images_variation_json' ), 10 );

        if ( ! is_admin() ) {

            add_action( 'woocommerce_before_single_product',                    array( $this, 'remove_show_product_images' ) );
            add_action( 'woocommerce_before_single_product_summary',            array( $this, 'show_product_all_images' ), 20);
            add_action( 'wp_enqueue_scripts',                                   array( $this, 'frontend_script' ) );

        } else {

            add_action( 'admin_enqueue_scripts',                                array( $this, 'admin_scripts' ) );
            add_action( 'woocommerce_save_product_variation',                   array( $this, 'save_product_variation' ), 10, 2 );

            add_action( 'wp_ajax_admin_load_other_images',                      array( $this, 'admin_load_other_images' ) );

            add_action( 'wp_ajax_noo_woo_get_variation',                         array( $this, 'ajax_get_variation' ) );
            add_action( 'wp_ajax_nopriv_noo_woo_get_variation',                  array( $this, 'ajax_get_variation' ) );

        }

    }

    public function remove_show_product_images() {

        remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

    }

    public function show_product_all_images() {

        global $product;

        $default_variation_id                   =           $this->get_default_variation_id();
        $init_product_id                        =           ($default_variation_id) ? $default_variation_id : $product->get_id();
        $init_product_id                        =           $this->get_selected_variation( $init_product_id );

        $image_ids                              =           $this->get_all_image_ids( $init_product_id );
        $images                                 =           $this->get_all_image_sizes( $image_ids );

        $default_image_ids                      =           $this->get_all_image_ids( $product->get_id() );
        $default_images                         =           $this->get_all_image_sizes( $default_image_ids );


        $classes = array(
            'noo-woo-all-images-wrap',
            'noo-woo-all-images-wrap--thumbnails-below'
        );

        if($default_variation_id == "" || $default_variation_id == $product->get_id()) {
            $classes[] = 'noo-woo-reset';
        }

        // $classes[] = 'noo-woo-hover-icons';

        ?>

        <div class="<?php echo implode(' ', $classes); ?>" data-showing="<?php echo $init_product_id; ?>" data-parentid="<?php echo $product->get_id(); ?>" data-default="<?php echo esc_attr( json_encode( $default_images ) ); ?>" data-slide-count="<?php echo count($image_ids); ?>">

            <?php if(!empty($images)) { ?>

                <div class="<?php echo $this->slug; ?>-images-wrap">

                    <div class="<?php echo $this->slug; ?>-images <?php echo $this->slug."-images--click_anywhere"; ?>">

                        <?php $i = 0; foreach($images as $image): ?>

                            <div class="<?php echo $this->slug; ?>-images__slide <?php if($i == 0) echo $this->slug."-images__slide--active"; ?>" data-index="<?php echo $i; ?>">

                                <?php
                                $src = $i == 0 ? $image['single'][0] : "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACwAAAAAAQABAAACAkQBADs=";
                                $data_src = $i == 0 ? false : $image['single'][0];
                                $aspect = $i == 0 ? false : ($image['single'][2]/$image['single'][1])*100;
                                $srcset = isset( $image['single']['retina'][0] ) ? sprintf('data-srcset="%s, %s 2x"', $image['single'][0], $image['single']['retina'][0]) : "";
                                ?>

                                <img class="<?php echo $this->slug; ?>-images__image" src="<?php echo $src; ?>" <?php echo $srcset; ?> <?php if($data_src) printf('data-noo-woo-src="%s"', $data_src); ?> data-large-image="<?php echo $image['large'][0]; ?>" data-large-image-width="<?php echo $image['large'][1]; ?>" data-large-image-height="<?php echo $image['large'][2]; ?>" title="<?php echo $image['title']; ?>" alt="<?php echo $image['alt']; ?>" width="<?php echo $image['single'][1]; ?>" height="<?php echo $image['single'][2]; ?>" <?php if($aspect) printf('style="padding-top: %s%%; height: 0px;"', $aspect); ?> />

                            </div>

                        <?php $i++; endforeach; ?>

                    </div>

                    <div class="<?php echo $this->slug; ?>-loading-overlay"><i class="fa fa-circle-o-notch fa-spin fa-fw"></i></div>

                </div>

            <?php } ?>

            <?php

            $count_thumb  = get_theme_mod('noo_woocommerce_thumbnail_count', 4 );
            $count_thumb = 5;
            $mode_thumb   = "horizontal";// "vertical";

            ?>

            <?php if(!empty($images)) { ?>

                <?php $image_count = count($images); ?>

                <div class="<?php echo $this->slug; ?>-thumbnails-wrap number-<?php echo $image_count ?> <?php echo $this->slug; ?>-thumbnails-wrap--sliding <?php echo $this->slug; ?>-thumbnails-wrap--<?php echo $mode_thumb; ?>">

                    <div class="<?php echo $this->slug; ?>-thumbnails">

                        <?php if( $image_count > 1 ) { ?>

                            <?php $i = 0; foreach($images as $image): ?>

                                <?php $srcset = isset( $image['thumb']['retina'][0] ) ? sprintf('data-srcset="%s, %s 2x"', $image['thumb'][0], $image['thumb']['retina'][0]) : ""; ?>

                                <div class="<?php echo $this->slug; ?>-thumbnails__slide <?php if($i == 0) { ?><?php echo $this->slug; ?>-thumbnails__slide--active<?php } ?>" data-index="<?php echo $i; ?>">

                                    <img class="<?php echo $this->slug; ?>-thumbnails__image" src="<?php echo $image['thumb'][0]; ?>" <?php echo $srcset; ?> title="<?php echo $image['title']; ?>" alt="<?php echo $image['alt']; ?>" width="<?php echo $image['thumb'][1]; ?>" height="<?php echo $image['thumb'][2]; ?>">

                                </div>

                            <?php $i++; endforeach; ?>

                            <?php
                            
                            if( $image_count < $count_thumb ) {

                                $empty_count = $count_thumb - $image_count;
                                $i = 0;

                                while( $i < $empty_count ) {

                                    echo "<div></div>";
                                    $i++;

                                }

                            }

                            ?>

                        <?php } ?>

                    </div>

                    <a href="javascript: void(0);" class="<?php echo $this->slug; ?>-thumbnails__control <?php echo $this->slug; ?>-thumbnails__control--<?php echo ( $mode_thumb == "horizontal" ) ? "left" : "up"; ?>" data-direction="prev"><i class="fa fa-chevron-<?php echo ( $mode_thumb == "horizontal" ) ? "left" : "up"; ?>-open-mini"></i></a>
                    <a href="javascript: void(0);" class="<?php echo $this->slug; ?>-thumbnails__control <?php echo $this->slug; ?>-thumbnails__control--<?php echo ( $mode_thumb == "horizontal" ) ? "right" : "down"; ?>" data-direction="next"><i class="fa fa-chevron-<?php echo ( $mode_thumb == "horizontal" ) ? "right" : "down"; ?>-open-mini"></i></a>

                </div>

            <?php } ?>

            <?php do_action( 'noo_woocommerce_after_other_images' ); ?>
        </div>
        <?php
    }

    public function add_other_images_variation_json( $variation_data ) {

        $img_ids = $this->get_all_image_ids( $variation_data['variation_id'] );
        $images = $this->get_all_image_sizes( $img_ids );

        $variation_data['other_images'] = $images;

        return $variation_data;

    }

    public function admin_scripts() {

        global $post, $pagenow;

        if ( $post ) {
            if ( get_post_type( $post->ID ) == "product" ) {
                if ( "post.php" == $pagenow || "post-new.php" == $pagenow ) {

                    wp_enqueue_style( $this->slug.'_admin', NOO_ADMIN_ASSETS_URI . '/css/noo.woo.admin.extensions.css' , array(), false );
                    wp_enqueue_script( $this->slug, NOO_ADMIN_ASSETS_URI . '/js/noo-woo-admin.extensions.js', array('jquery'), false, true );

                    $vars = array(
                        'nonce'   => wp_create_nonce( $this->ajax_nonce_string ),
                        'ajaxurl' => admin_url( 'admin-ajax.php' )
                    );

                    wp_localize_script( $this->slug, 'nooWooVars', $vars );

                }
            }
        }
    }

    public function frontend_script() {

        // Bxslider
        wp_register_script( 'bxslider', NOO_PLUGIN_ASSETS_URI . '/vendor/bxslider/min/jquery.bxslider.min.js', null, null, false );

        if ( ( function_exists('is_product') && is_product() ) ) {

            // Photoswipe
            wp_enqueue_script( 'photoswipe', NOO_PLUGIN_ASSETS_URI . '/vendor/photoswipe/photoswipe.min.js', array('jquery'), false, true );
            wp_enqueue_script( 'photoswipe-ui', NOO_PLUGIN_ASSETS_URI . '/vendor/photoswipe/photoswipe-ui-default.min.js', array('jquery'), false, true );

            // Main
            wp_enqueue_style( $this->slug . '-css', NOO_PLUGIN_ASSETS_URI . '/css/noo.woo.extensions.css', array(), null );
            wp_enqueue_script( $this->slug . '-script', NOO_PLUGIN_ASSETS_URI . '/js/min/noo-woo-main.extensions.min.js', array('jquery', 'imagesloaded', 'bxslider'), null, true );

            $vars = array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'slug'    => $this->slug,
                'nonce'   => wp_create_nonce( $this->ajax_nonce_string ),
            );

            wp_localize_script( $this->slug . '-script', 'nooWooVars', $vars );

            add_action( 'wp_footer', array( $this, 'photoswipe_export_html' ), 1 );

        }

    }

    function save_product_variation( $variation_id, $i ) {

        if ( isset( $_POST['variation_other_images'][$variation_id] ) ) {

            update_post_meta($variation_id, 'variation_other_images', $_POST['variation_other_images'][$variation_id]);

        }

    }

    function admin_load_other_images() {

        if ( ! isset( $_REQUEST['nonce'] ) ) {
            die();
        }

        if ( ! wp_verify_nonce( $_REQUEST['nonce'], $this->ajax_nonce_string ) ) {
            die();
        }

        $attachments = get_post_meta($_GET['varID'], 'variation_other_images', true);
        $attachmentsExp = array_filter(explode(',', $attachments));
        $image_ids = array(); ?>

			<ul class="other-variation tip-remove-img" data-tip="Click or Drag">

				<?php if (!empty($attachmentsExp)) { ?>

					<?php foreach ($attachmentsExp as $id) { $image_ids[] = $id; ?>
						<li class="image" data-attachment_id="<?php echo $id; ?>">
							<a href="#" class="delete"><?php echo wp_get_attachment_image( $id, 'thumbnail' ); ?></a>
						</li>
					<?php } ?>

				<?php } ?>

			</ul>
			<input type="hidden" class="variation_other_images" name="variation_other_images[<?php echo $_GET['varID']; ?>]" value="<?php echo $attachments; ?>">

		<?php exit;
    }

    public function photoswipe_export_html() {
        ?>
        <div class="noo-woo-pswp" tabindex="-1" role="dialog" aria-hidden="true">

            <div class="pswp__bg"></div>

            <div class="pswp__scroll-wrap">

                <div class="pswp__container">
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                </div>

                <div class="pswp__ui pswp__ui--hidden">

                    <div class="pswp__top-bar">

                        <div class="pswp__counter"></div>

                        <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                        <button class="pswp__button pswp__button--share" title="Share"></button>

                        <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                        <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                        <div class="pswp__preloader">
                            <div class="pswp__preloader__icn">
                              <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                              </div>
                            </div>
                        </div>
                    </div>

                    <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                        <div class="pswp__share-tooltip"></div>
                    </div>

                    <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                    </button>

                    <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                    </button>

                    <div class="pswp__caption">
                        <div class="pswp__caption__center"></div>
                    </div>

                </div>

            </div>

        </div>
        <?php
    }

    public function get_default_variation_id() {

        global $post, $woocommerce, $product;

        $default_variation_id = $product->get_id();

        if ($product->is_type('variable')) {

            $defaults = $product->get_variation_default_attributes();
            $variations = array_reverse($product->get_available_variations());

            if (!empty($defaults)) {

                foreach ($variations as $variation) {

                    $varCount = count($variation["attributes"]);
                    $attMatch = 0;
                    $partMatch = 0;

                    foreach ($defaults as $dAttName => $dAttVal) {

                        if (isset($variation["attributes"]['attribute_'.$dAttName])) {

                            $theAtt = $variation["attributes"]['attribute_'.$dAttName];

                            if ($theAtt == $dAttVal) {
                                $attMatch++;
                                $partMatch++;
                            }

                            if ($theAtt == "") {
                                $partMatch++;
                            }

                        }

                    }

                    if ($varCount == $partMatch) {
                        $default_variation_id = $variation['variation_id'];
                    }

                    if ($varCount == $attMatch) {
                        $default_variation_id = $variation['variation_id'];
                    }

                }

            }

        }

        return $default_variation_id;

    }

    public function get_selected_variation($current_id) {
        global $post, $woocommerce, $product;

        if ($product->is_type('variable')) {

            $selected_atts = array();
            if( isset( $_GET ) ) {
                foreach( $_GET as $key => $value ) {
                    if( strpos($key, 'attribute_') !== false ) {
                        $selected_atts[$key] = $value;
                    }
                }
            }

            $selected_atts_count = count($selected_atts);
            $available_atts_count = count($product->get_variation_attributes());

            if( empty($selected_atts) || $selected_atts_count < $available_atts_count )
                return $current_id;

            $args = array(
            	'post_type' => 'product_variation',
            	'post_parent' => (int)$current_id,
            	'meta_query' => array( 'relation' => 'AND' )
            );

            foreach( $selected_atts as $key => $value ) {
                $args['meta_query'][] = array(
                    'key'     => $key,
                    'value'   => $value,
                    'compare' => '='
                );
            }

            $variation = new WP_Query( $args );

            if( $variation->found_posts >= 1 ) {
                $current_id = $variation->posts[0]->ID;
            }

            wp_reset_query();

        }

        return $current_id;
    }

    public function ajax_get_variation() {

        $response = array(
            'success' => false,
            'variation' => false
        );

        if( ( isset( $_GET['variation_id'] ) && !empty( $_GET['variation_id'] ) ) && ( isset( $_GET['product_id'] ) && !empty( $_GET['product_id'] ) )  ) {

            $variable_product = wc_get_product( absint( $_GET['product_id'] ), array( 'product_type' => 'variable' ) );
            $variation = $variable_product->get_available_variation( absint( $_GET['variation_id'] ) );

            $response['success'] = true;
            $response['variation'] = $variation;

        }

        $response['get'] = $_GET;

        header('Content-Type: text/javascript; charset=utf8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Max-Age: 3628800');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

        echo htmlspecialchars($_GET['callback']) . '(' . json_encode( $response ) . ')';

        wp_die();

    }

    public function get_all_image_ids( $id ) {

        $all_images = array();

        $show_gallery = false;

        if (has_post_thumbnail($id)) {

            $all_images['featured'] = get_post_thumbnail_id($id);

        } else {

            $prod = get_post($id);
            $prod_parent_id = $prod->post_parent;
            if ($prod_parent_id && has_post_thumbnail($prod_parent_id)) {
                $all_images['featured'] = get_post_thumbnail_id($prod_parent_id);
            } else {
                $all_images[] = 'placeholder';
            }

            $show_gallery = true;
        }

        if (get_post_type($id) == 'product_variation') {
            $wt_attachments = array_filter(explode(',', get_post_meta($id, 'variation_other_images', true)));
            $all_images = array_merge($all_images, $wt_attachments);
        }


        if (get_post_type($id) == 'product' || $show_gallery) {
            $product = wc_get_product($id);
            $attach_ids = $product->get_gallery_image_ids();

            if (!empty($attach_ids)) {
                $all_images = array_merge($all_images, $attach_ids);
            }
        }

        return $all_images;

    }

    public function get_all_image_sizes($image_ids) {

        $images = array();

        if (!empty($image_ids)) {
            foreach ($image_ids as $image_id):

                $image_sizes = false;

                if ($image_id == 'placeholder') {
                    $image_sizes = array(
                        'large' => array( wc_placeholder_img_src( 'large' ) ),
                        'single' => array( wc_placeholder_img_src('shop_single') ),
                        'thumb' => array( wc_placeholder_img_src('shop_thumbnail') ),
                        'alt' => '',
                        'title' => ''
                    );

                } else {
                    if (!array_key_exists($image_id, $images)) {
                        $attachment = $this->wp_get_attachment($image_id);

                        if( ! $attachment )
                            continue;

                        $large = wp_get_attachment_image_src( $image_id, 'large' );
                        $single = wp_get_attachment_image_src( $image_id, 'shop_single' );
                        $thumb = wp_get_attachment_image_src( $image_id, 'shop_thumbnail' );

                        $image_sizes = array(
                            'large' => $large,
                            'single' => $single,
                            'thumb' => $thumb,
                            'alt' => $attachment['alt'],
                            'title' => $attachment['title']
                        );

                    }

                }

                if( $image_sizes )
                    $images[] = $image_sizes;

            endforeach;
        }

        return $images;

    }

    public function wp_get_attachment( $attachment_id ) {

        $attachment = get_post( $attachment_id );

        if( $attachment ) {

            return array(
                'alt'         => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
                'caption'     => $attachment->post_excerpt,
                'description' => $attachment->post_content,
                'href'        => get_permalink( $attachment->ID ),
                'src'         => $attachment->guid,
                'title'       => $attachment->post_title
            );

        } else {
            return false;
        }

    }

}


new Noo_Woocommerce_Extensions();