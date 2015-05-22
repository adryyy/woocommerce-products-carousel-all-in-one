<?php
/*
Author: Marcin Gierada
Author URI: http://www.teastudio.pl/
Author Email: m.gierada@teastudio.pl
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class WooCommerceProductsCarouselAllInOneGenerator {
    
        public static function generateId() {
                return rand();
        }
    
        public static function getDefaults() {
                return array(
                            'id'                      => self::generateId(),
                            'template'                => 'default.css',
                            'show_only'               => 'newest',
                            'ordering'                => 'asc',
                            'categories'              => '',
                            'all_items'               => 10,

                            'show_title'              => 'true',
                            'show_tags'               => 'false',
                            'show_price'              => 'true',
                            'show_description'        => 'true',
                            'show_add_to_cart_button' => 'true',
                            'show_more_button'        => 'true',
                            'show_more_items_button'  => 'true',         
                            'image_source'            => 'thumbnail',            
                            'image_width'             => 100,
                            'image_height'            => 100,  

                            'items_to_show'           => 4,
                            'loop'                    => 'true',
                            'auto_play'               => 'true',
                            'stop_on_hover'           => 'true',
                            'auto_play_timeout'       => 1200,
                            'nav'                     => 'true',
                            'nav_speed'               => 800,  
                            'dots'                    => 'true',
                            'dots_speed'              => 800,
                            'margin'                  => 5,
                            'lazy_load'               => 'false',
                            'mouse_drag'              => 'true',
                            'mouse_wheel'             => 'true',
                            'touch_drag'              => 'true',
                            'slide_by'                => 1,
                            'easing'                  => "linear"
                           );        
        }
    
    
        public static function generate($atts) {
                global $post;

                /*
                 * default parameters
                 */
                $params = self::prepareSettings($atts);

                /*
                 * print styles
                 */
                wp_print_scripts('jquery-owl.carousel');
                wp_print_styles('owl.carousel.style');
        
                /*
                 * theme
                 */
                $theme = str_replace('.css', '', $params['template']);  

                /*
                 * check if template css file exists
                 */
                $theme =  $params['template'];
                $theme_name = str_replace('.css', '', $theme);

                /*
                 * check if template css file exists
                 */
                $plugin_theme_url = plugins_url( dirname(plugin_basename(__FILE__)) ) . '/templates/' . $theme;
                $plugin_theme_file = plugin_dir_path( __FILE__ ) . '/templates/'. $theme;
                
                $site_theme_url = get_template_directory_uri() . '/css/woocommerce_products_carousel_all_in_one/' . $theme;
                $site_theme_file = get_template_directory() . '/css/woocommerce_products_carousel_all_in_one/' . $theme;                

                if ( @file_exists($plugin_theme_file) ) {
                        wp_enqueue_style( 'woocommerce_products_carousel_all_in_one-carousel-style-'. $theme_name, $plugin_theme_url, true );
                } else if ( @file_exists($site_theme_file) ) {
                        wp_enqueue_style( 'woocommerce_products_carousel_all_in_one-carousel-style-'. $theme_name, $site_theme_url, true );                        
                } else {
                    return '<div class="error"><p>'. printf( __('Theme - %.css stylesheet is missing.', 'woocommerce-products-carousel-all-in-one'), $theme ) .'</p></div>'; 
                }        
        
                /*
                 * prepare html and loop
                 */
                $out = '<div id="woocommerce-products-carousel-all-in-one-'. $params['id'] .'" class="'. $theme_name .'-theme woocommerce-products-carousel-all-in-one owl-carousel">';

                /*
                 * prepare sql query
                 */
                $sql_array = array('post_type'      =>  'product',                
                                   'post_status'    =>  'publish',
                                   'order'          =>  $params['ordering'],
                                   'posts_per_page' =>  $params['all_items'],
                                   'no_found_rows'  =>  1,
                                   'post__not_in' =>  array($post->ID) //exclude current post
                                   );

                if ($params['categories'] != "") {
                        $sql_array['tax_query'] = array(array('taxonomy'  =>  'product_cat',
                                                              'field'     =>  'id',
                                                              'terms'     =>  explode(',', $params['categories']),
                                                              'operator'  => 'IN'
                                                             ));
                }

                if ($params['show_only'] == "popular") {
                        $sql_array['meta_key'] = 'total_sales';
                        $sql_array['orderby'] = 'meta_value_num';
                } else if ($params['show_only'] == "featured") {
                        $sql_array['meta_key'] = '_featured';
                        $sql_array['orderby'] = 'date';
                } else {
                        $sql_array['orderby'] = 'date';    
                }
                /*
                 * end sql query
                 */

                $loop = new WP_Query($sql_array);
                
                /*
                 * check if there are more then one item
                 */
                if(!$loop->post_count > 1) {
                    return false;
                }
                
                /*
                 * products loop
                 */
                while($loop->have_posts()) {
                        $loop->the_post();            
   
                        /*
                         * create product object
                         */
                        $product_obj = get_product($post->ID);

                        $title = '';
                        $description = '';
                        $tags = '';
                        $price = '';
                        $buttons = '';

                        $prod_url = esc_url( get_permalink(apply_filters('woocommerce_in_cart_product', $product_obj->id)) );            
                        $shop_url = esc_url( get_permalink(woocommerce_get_page_id('shop')));

            
                        $featured_img = wp_get_attachment_image_src( get_post_thumbnail_id($product_obj->id),$params['image_source']);

                        /*
                         * if no featured image for the product
                         */
                        if($featured_img[0] == '' || $featured_img[0] == '/') {
                                $featured_img[0] = plugin_dir_url( __FILE__ ).'images/placeholder.png';   
                        }

                        /*
                         * show price
                         */
                        if ($params['show_price'] == 'true') {
                                if (get_option('woocommerce_display_cart_prices_excluding_tax') === 'yes') {       
                                        $price = apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product_obj->get_price_excluding_tax() )); 
                                } else {
                                        $price = apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product_obj->get_price() )); 
                                }

                                $price = '<span class="woocommerce-products-carousel-all-in-one-price">'.$price.'</span>';
                                /*
                                 * if product variables
                                 */
                                if ($product_obj->product_type === 'variable') {
                                        $price = '<span class="woocommerce-products-carousel-all-in-one-amount">'.__('From', 'woocommerce-products-carousel-all-in-one').'</span>'.$price;
                                }
                        }

                        /*
                         * show title
                         */
                        if ($params['show_title'] === 'true') {
                                $title = '<h3 class="woocommerce-products-carousel-all-in-one-title">';
                                        $title .= '<a href="'.$prod_url.'" title="'.$product_obj->post->post_title.'">'.$product_obj->post->post_title.'</a>';
                                $title .= '</h3>';                 
                        }

                        /*
                         * show description
                         */
                        if ($params['show_description'] === 'true') {
                                $description = '<div class="woocommerce-products-carousel-all-in-one-desc">'.$product_obj->post->post_excerpt.'</div>';
                        }      
                        
                        /*
                         * show tags
                         */          
                        if ($params['show_tags'] == 'true') {
                                $tags = '<p class="woocommerce-products-carousel-all-in-one-tags">';
                                        $tags .= get_the_term_list(get_the_ID(), 'product_tag', '', ' ', '' );
                                $tags .= '</p>';                                   
                        }                         

                        /*
                         * show buttons
                         */
                        if ($params['show_add_to_cart_button'] === 'true' || $params['show_more_button'] === 'true' || $params['show_more_items_button'] === 'true') {
                                $buttons = '<p class="woocommerce-products-carousel-all-in-one-buttons">';


                                if ($params['show_add_to_cart_button'] === 'true') {
                                        $buttons .= sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="woocommerce-products-carousel-all-in-one-add-to-cart-button button %s product_type_%s" title="%s">%s</a>',
                                                esc_url( $product_obj->add_to_cart_url() ),
                                                esc_attr( $product_obj->id ),
                                                esc_attr( $product_obj->get_sku() ),
                                                esc_attr( 1 ),
                                                $product_obj->is_purchasable() && $product_obj->is_in_stock() ? 'add_to_cart_button' : '',
                                                esc_attr( $product_obj->product_type ),
                                                esc_html( __('Add to cart', 'woocommerce-products-carousel-all-in-one') ),
                                                esc_html( __('add to cart', 'woocommerce-products-carousel-all-in-one') )
                                        );
                                }

                                if ($params['show_more_button'] === 'true') {          
                                        if( $product_obj->is_downloadable() ) {
                                                $buttons .= '<a href="'.$prod_url.'" class="woocommerce-products-carousel-all-in-one-more-button button" title="'.__('Download', 'woocommerce-products-carousel-all-in-one').' '.$product_obj->post->post_title.'">'.__('<i class="icon-download-alt icon-"></i> download', 'woocommerce-products-carousel-all-in-one').'</a>';
                                        } else { 
                                                $buttons .= '<a href="'.$prod_url.'" class="woocommerce-products-carousel-all-in-one-more-button button" title="'.__('Show item', 'woocommerce-products-carousel-all-in-one').' '.$product_obj->post->post_title.'">'.__('show item', 'woocommerce-products-carousel-all-in-one').'</a>';
                                        }
                                }

                                if ($params['show_more_items_button'] === 'true') {
                                        $buttons .= '<a href="'. $shop_url .'" class="woocommerce-products-carousel-all-in-one-more-items-button button" title="'.__('Show more items', 'woocommerce-products-carousel-all-in-one').'">'.__('show more items', 'woocommerce-products-carousel-all-in-one').'</a>';
                                }

                                $buttons .= '</p>';
                        }    
            

                        /*
                         * list products
                         */
                        $out .= '<div class="woocommerce-products-carousel-all-in-one-slide slides-'.$params['items_to_show'].'">';
                                $out .= '<div class="woocommerce-products-carousel-all-in-one-container">';

                                        $out .= '<div class="woocommerce-products-carousel-all-in-one-image">';
                                                $out .= '<a href="'. $prod_url .'" title="'. __('Show item', 'woocommerce-products-carousel-all-in-one') .' '. $product_obj->post->post_title .'">';
                                                        $out .= '<img src="'. $featured_img[0] .'" alt="'. $product_obj->post->post_title .'" style="max-width:'. $params['image_width'] .'%;max-height:'. $params['image_height'] .'%">';
                                                $out .= '</a>';
                                        $out .= '</div>';

                                        $out .= '<div class="woocommerce-products-carousel-all-in-one-details">';
                                                $out .= $title;
                                                $out .= $description;
                                                $out .= $price;
                                                $out .= $tags; 
                                                $out .= $buttons;              
                                        $out .= '</div>';
                                $out .= '</div>';
                        $out .= '</div>';
                }
                $out .= '</div>';        
        
                /*
                 * generate jQuery script for FlexCarousel         
                 */
                $out .= self::carousel($params);  
                return $out;
        }
    
        static function carousel($params = array()) { 
                if (empty($params)) {
                        return false;
                }
                $mouse_wheel = null;

                if ($params['mouse_wheel'] == 'true') {
                        $mouse_wheel = 'wooCommerceCarousel'. $params['id'] .'.on("mousewheel", ".owl-stage", function(e) {
                                        if (e.deltaY > 0) {
                                            wooCommerceCarousel'. $params['id'] .'.trigger("next.owl");
                                        } else {
                                            wooCommerceCarousel'. $params['id'] .'.trigger("prev.owl");
                                        }
                                        e.preventDefault();
                                        });';  
                }

                $out = '<script type="text/javascript">        
                    jQuery(document).ready(function(e) {            
                        var wooCommerceCarousel'. $params['id'] .' = jQuery("#woocommerce-products-carousel-all-in-one-'.$params['id'].'");
                        wooCommerceCarousel'. $params['id'] .'.owlCarousel({
                            loop: '. $params['loop'] .',
                            nav: '. $params['nav'] .',
                            navSpeed: '. $params['nav_speed'] .', 
                            dots: '. $params['dots'] .',
                            dotsSpeed: '. $params['dots_speed'] .',
                            lazyLoad: '. $params['lazy_load'] .',
                            autoplay: '. $params['auto_play'] .',
                            autoplayHoverPause: '. $params['stop_on_hover'] .',
                            autoplayTimeout: '. $params['auto_play_timeout'] .',
                            autoplaySpeed:  '. $params['auto_play_timeout'] .',
                            margin: '. $params['margin'] .',
                            stagePadding: 0,
                            freeDrag: false,      
                            mouseDrag: '. $params['mouse_drag'] .',
                            touchDrag: '. $params['touch_drag'] .',
                            slideBy: '. $params['slide_by'] .',
                            fallbackEasing: "'. $params['easing'] .'",
                            responsiveClass: true,                    
                            navText: [ "'. __('previous product', 'woocommerce-products-carousel-all-in-one') .'", "'. __('next product', 'woocommerce-products-carousel-all-in-one') .'" ],
                            responsive:{
                                0:{
                                    items: 1
                                },
                                600:{
                                    items: '. ceil($params['items_to_show']/2) .',

                                },
                                1000:{
                                    items: '. $params['items_to_show'] .'
                                }
                            },
                            autoHeight: true
                        });
                        '. $mouse_wheel .'
                    });  
                </script>';

                return $out;
        }    
    
    
        public static function prepareSettings($settings) {
                $checkboxes = array(
                                    'show_title'              => 'true',
                                    'show_tags'               => 'false',
                                    'show_price'              => 'true',
                                    'show_description'        => 'true',
                                    'show_add_to_cart_button' => 'true',
                                    'show_more_button'        => 'true',
                                    'show_more_items_button'  => 'true',         

                                    'loop'                    => 'true',
                                    'auto_play'               => 'true',
                                    'stop_on_hover'           => 'true',
                                    'nav'                     => 'true',
                                    'dots'                    => 'true',
                                    'lazy_load'               => 'false',
                                    'mouse_drag'              => 'true',
                                    'mouse_wheel'             => 'true',
                                    'touch_drag'              => 'true'
                                    );

                foreach($checkboxes as $k => $v) {
                        if (!array_key_exists($k, $settings)) {
                                $settings[$k] = 'false';
                        } else { 
                                $settings[$k] = ($settings[$k] == 1 || $settings[$k] == 'true') ? 'true' : 'false';
                        }
                }

                $settings['id'] = self::generateId();    
                
                /*
                 * if there are no all settings
                 */
                $defaults = self::getDefaults();
                foreach($defaults as $k => $v) {
                    if (!array_key_exists($k, $settings)) {
                        $settings[$k] = $defaults[$k];
                    }
                }                
                return $settings;
        }
}
