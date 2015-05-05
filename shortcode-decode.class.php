<?php
/*
Author: Marcin Gierada
Author URI: http://www.teastudio.pl/
Author Email: m.gierada@teastudio.pl
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class WooCommerceProductsCarouselAllInOneShortcodeDecode {	
        public function initialize($atts, $content = null, $code = "") {	
                return WooCommerceProductsCarouselAllInOneGenerator::generate($atts);
        }
}
add_shortcode("woocommerce_products_carousel_all_in_one", array("WooCommerceProductsCarouselAllInOneShortcodeDecode", "initialize"));
?>