<?php
/*
Plugin Name: WooCommerce Products Carousel all in one
Plugin URI: http://www.teastudio.pl/produkt/woocommerce-products-carousel-all-in-one/
Description: WooCommerce Products Carousel all in one is a widget to show new, featured or popular products in carousel by <a href="http://www.owlcarousel.owlgraphic.com/" target="_blank" title="OWL Carousel homepage">OWL Carousel</a>.
Version: 1.0.5
Author: Marcin Gierada
Author URI: http://www.teastudio.pl/
Author Email: m.gierada@teastudio.pl
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

/*
 * load i18n
 */
add_action("init", "woocommerce_products_carousel_all_in_one_init");
function woocommerce_products_carousel_all_in_one_init() {
        load_plugin_textdomain("woocommerce-products-carousel-all-in-one", false, dirname(plugin_basename( __FILE__ )) .  "/i18n/languages/");
}


/*
 * plugin
 */
$wooCommerce_Products_Carousel_All_In_One = new WooCommerce_Products_Carousel_All_In_One();
class WooCommerce_Products_Carousel_All_In_One {
        const VERSION = '1.0.5';
        private $plugin_name = 'WooCommerce Products Carousel all in one';
        private $plugin_slug = 'woocommerce-products-carousel-all-in-one';
        private $options = array();
        
	public function __construct() {
                /*
                 * get options
                 */
                $this->options = array_merge( $this->get_defaults(), get_option($this->plugin_slug . '_options') ? get_option($this->plugin_slug . '_options') : array() );
                
                /**
                 * check if WooCommerce is active
                 */
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
                        //include required files based on admin or site
                        if (is_admin()) {                            
                                add_action( 'init', array($this, 'woocommerce_products_carousel_all_in_one_button') );	
                                add_action( 'admin_init', array($this, 'register_settings'));
                                add_action( 'admin_menu', array($this, 'admin_menu_options')); 
                                add_action( 'admin_head',  array($this, 'woocommerce_products_carousel_all_in_one_wp_head') );   
                                add_action( 'admin_head', array($this, 'woocommerce_products_carousel_all_in_one_button') );     
                                
                                /*
                                 * ajax page for shortcode generator
                                 */
                                add_action("wp_ajax_woocommerce_products_carousel_all_in_one_shortcode_generator", array($this, "WooCommerceProductsCarouselAllInOneShortcodeGenerator") );
                               
                                /*
                                 * clear settings
                                 */
                                register_deactivation_hook(__FILE__,  array($this, 'deactivation') );
                        } else {
                                require_once("shortcode-decode.class.php");
                                
                                /*
                                 * register scripts	
                                 */
                                add_action("wp_enqueue_scripts", array($this, "woocommerce_products_carousel_all_in_one_register_scripts") );
                                add_action("wp_head",  array($this, "woocommerce_products_carousel_all_in_one_wp_head") );
                        } 
                        
                        /*
                         * widget
                         */
                        require_once("carousel-generator.class.php");
                        require_once("carousel-widget.class.php");
                } else {
                    /*
                     * need WooCommerce plugin
                     */
                    add_action( 'admin_notices', array($this, 'plugin_required' ) );
                }    
        }          
        
	/**
	 * deactivate the plugin
	 */
	public function deactivation()
	{
                if ( ! current_user_can( 'activate_plugins' ) ) {
                       return;            
                }
                delete_option( $this->plugin_slug . '_options' );
	}       
                
        /**
         * retrieves the plugin options from the database.
         */
        private function get_defaults() {
                return array();
        }     
                
        public function plugin_required() {            
            echo '<div class="error"><p>'. __('The <strong>WooCommerce Products Carousel all in one</strong> plugin require WooCommerce plugin. Please install or active this plugin.', $this->plugin_slug) .'</p></div>'; 
        }         
        
        function WooCommerceProductsCarouselAllInOneShortcodeGenerator() {
                require_once("shortcode-generator.php");
                exit();
        }  
        
        /*
         * adds the plugin url in the head tag
         */
        function woocommerce_products_carousel_all_in_one_wp_head() {
                echo "<script>var woocommerce_products_carousel_all_in_one_url=\"".plugin_dir_url(__FILE__)."\";</script>";
        }
  
        /*
         * registers the scripts and styles
         */
        function woocommerce_products_carousel_all_in_one_register_scripts() {
                wp_register_script("jquery-owl.carousel", plugin_dir_url(__FILE__)."owl.carousel/owl.carousel.js", "jquery");	
                wp_register_script("jquery-mousewheel", plugin_dir_url(__FILE__)."owl.carousel/jquery.mousewheel.min.js", array("jquery", "jquery-owl.carousel", "jquery-effects-core"));	
                wp_register_style("owl.carousel.style", plugin_dir_url(__FILE__). "owl.carousel/assets/owl.carousel.css"); 

                wp_enqueue_script("jquery-mousewheel");
                wp_enqueue_style("owl.carousel.style");
                
                /*
                 * include Font Awesome library from Bootstrap CDN 
                 */
                if( array_key_exists('include_font_awesome', $this->options) && $this->options['include_font_awesome'] == 1 ) {
                        wp_enqueue_style( 'wp-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', false );
                }                
        }  
        
        /*
         * add button to editor
         */
        function woocommerce_products_carousel_all_in_one_button() {
                // check user permissions
                if ( !current_user_can( "edit_posts" ) && !current_user_can( "edit_pages" ) ) {
                        return;
                }        

                //adds button to the visual editor
                add_filter("mce_external_plugins", array($this, "add_woocommerce_products_carousel_all_in_one_plugin") );  
                add_filter("mce_buttons", array($this, "register_add_woocommerce_products_carousel_all_in_one_button") );  
        }

        /*
         * callback function
         */
        function add_woocommerce_products_carousel_all_in_one_plugin($plugin_array) {        
                $blog_version = floatval(get_bloginfo("version"));

                if($blog_version >= 4.0) {
                        $version = "plugin-4.0.js";
                }else if($blog_version < 4.0 && $blog_version >= 3.9) {
                        $version = "plugin-3.9.js";
                } else {
                        $version = "plugin-3.6.js";            
                }

                $plugin_array["woocommerce_products_carousel_all_in_one_button"] = plugin_dir_url(__FILE__)."js/".$version;
                return $plugin_array;
        } 
    
        /* 
         * callback function
         */
        function register_add_woocommerce_products_carousel_all_in_one_button($buttons) {
                array_push($buttons, "woocommerce_products_carousel_all_in_one_button");
                return $buttons;
        } 
        
	/**
	 * add submenu
	 */
	public function admin_menu_options() {
		add_options_page(
			__($this->plugin_name, $this->plugin_slug),
			__($this->plugin_name, $this->plugin_slug),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'settings_page' )
		);
	}   
        
	/**
	 * regiseter plugin settings
	 */
	public function register_settings() {
                register_setting( $this->plugin_slug, $this->plugin_slug . '_options' );
        } 
        
function settings_page() {
?>
<div class="wrap">
        <div id="poststuff" class="metabox-holder has-right-sidebar">    
                <h2><?php _e($this->plugin_name, $this->plugin_slug) ?></h2>

                <div class="inner-sidebar">
                        <div class="postbox">
                                <div class="inside hndle">
                                        <p class="inner"><?php _e('Version') ?>: <?php echo self::VERSION ?></p>
                                </div>
                            
                                <h3 class="hndle">
                                        <span><?php _e('Need support?', $this->plugin_slug) ?></span>
                                </h3>
                                <div class="inside">
                                        <p class="inner">
                                                <?php _e('If you are having problems with this plugin, please contact by', $this->plugin_slug) ?> <a href="mailto:info@teastudio.pl" target="_blank" title="info@teastudio.pl">info@teastudio.pl</a><br />
                                                <?php _e('For more information about this plugin, visit', $this->plugin_slug) ?> <a href="http://www.teastudio.pl/product/woocommerce-products-carousel-all-in-one/" target="_blank" title="http://www.teastudio.pl/product/woocommerce-products-carousel-all-in-one/"><?php _e('plugin page', $this->plugin_slug) ?></a><br />                                                
                                        </p>
    
                                        <hr />
                                        <p>
                                                <a href="http://www.teastudio.pl" target="_blank" title="www.teastudio.pl"><img src="<?php echo plugins_url('/images/teastudio-logo.png' , __FILE__ ) ?>" title="www.teastudio.pl" alt="www.teastudio.pl" /></a>
                                        </p>
                                </div>
                        </div>
                </div>
        
                <div class="has-sidebar sm-padded">
                        <div id="post-body-content" class="has-sidebar-content">
                                <form method="post" action="options.php">
                                    <?php settings_fields( $this->plugin_slug ); ?>

                                    <h3><?php _e('Main settings', $this->plugin_slug) ?></h3>
                                    <hr />
  
                                    <table class="form-table" style="width:auto;clear:initial;">
                                        <tbody>              
                                            <tr valign="top">
                                                <th scope="row"><?php _e('Font Awesome', $this->plugin_slug) ?></th>
                                                <td>
                                                    <label for="<?php echo $this->plugin_slug; ?>_first_paragraph_lock">
                                                        <input type="checkbox" name="<?php echo $this->plugin_slug; ?>_options[include_font_awesome]" id="<?php echo $this->plugin_slug; ?>_include_font_awesome" value="1" <?php array_key_exists('include_font_awesome', $this->options) ? checked( (bool) $this->options["include_font_awesome"], true ): null;; ?> />
                                                        <?php _e('Include Font Awesome', $this->plugin_slug) ?>
                                                    </label>
                                                    <p class="description"><?php _e('Select this option if you would like to include <a href="http://fortawesome.github.io/Font-Awesome/" target="_blank"><b>Font Awesome</b></a> stylesheet.<br />Uncheck if you are already using this library.', $this->plugin_slug) ?></p> 
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <?php submit_button('', 'primary', 'submit', true); ?>
                                </form>
                        </div>
                </div>
        </div>
</div>        
<?php }
}




