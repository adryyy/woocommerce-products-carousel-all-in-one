<?php
/*
Author: Marcin Gierada
Author URI: http://www.teastudio.pl/
Author Email: m.gierada@teastudio.pl
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

?>
		
<style type="text/css">
table {font-size:12px;}
</style>
<script type="text/javascript">
function insert_shortcode() {
    var shortcode = '[woocommerce_products_carousel_all_in_one';
    
    jQuery('#woocommerce-products-carousel-form').find(':input').filter(function() {
        var val = null;
        if(this.type != "button") {
            if(this.type == "checkbox") {  
                val = this.checked ? "true" : "false";
            }else {
                val = this.value;
            }
            shortcode += ' '+jQuery.trim( this.name )+'="'+jQuery.trim( val )+'"';
        }
    });

    shortcode +=']';

    tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
    tb_remove();
}
</script>

<div class="widget" id="woocommerce-products-carousel-form">
    <table cellspacing="5" cellpadding="5">
        <tr>
            <td colspan="2" align="left"><strong>---<?php _e('Display options', 'woocommerce-products-carousel-all-in-one') ?>---</strong></td>
        </tr>
        <tr>
            <td align="left"><?php _e('Template', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <select name="template" id="template" class="select">
                    <?php
                        $files_list = scandir(plugin_dir_path(__FILE__).'templates');
                        unset($files_list[0]);
                        unset($files_list[1]);
                        foreach($files_list as $filename) {
                            echo "<option value=\"".$filename."\">".$filename."</option>";
                        }
                    ?>
                </select>	
            </td>
        </tr>  
        <tr>
            <td align="left"><?php _e('Products limit', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="text" name="all_items" id="all_items" value="10" size="5">
            </td>
        </tr>          
        <tr>
            <td align="left"><?php _e('Show only', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <select name="show_only" id="show_only" class="select">
                    <?php            
                      $show_list = array('newest' => __("Newset", 'woocommerce-products-carousel-all-in-one'),
                                         'popular' => __("Popular", 'woocommerce-products-carousel-all-in-one'),
                                         'featured' => __("Featured", 'woocommerce-products-carousel-all-in-one')
                                        );
                      foreach($show_list as $key => $list) {
                        echo "<option value=\"".$key."\">".$list."</option>";
                      }
                    ?>
                </select>	
            </td>
        </tr>   
        <tr>
            <td align="left"><?php _e('Ordering', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <select name="ordering" id="ordering" class="select">
                    <option value="asc"><?php _e("Ascending", 'woocommerce-products-carousel-all-in-one') ?></option>
                    <option value="desc"><?php _e("Descending", 'woocommerce-products-carousel-all-in-one') ?></option>              
                    <option value="random"><?php _e("Random", 'woocommerce-products-carousel-all-in-one') ?></option>   
                </select>	
            </td>
        </tr>   
        <tr>
            <td align="left"><?php _e('Category IDs', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="text" name="categories" id="categories" value="" size="30">
                <br />
                <small><?php _e('Please enter Category IDs with comma seperated.', 'woocommerce-products-carousel-all-in-one') ?></small>
            </td>
        </tr> 


        <tr>
            <td colspan="2" align="left">
                <br />
                <strong>---<?php _e('Product options', 'woocommerce-products-carousel-all-in-one') ?>---</strong>
            </td>
        </tr>  
        <tr>
            <td align="left"><?php _e('Show title', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="show_title" id="show_title" checked="checked">
            </td>
        </tr>	
        <tr>
            <td><?php _e('Show tags', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="show_tags" id="show_tags">
            </td>
        </tr>         
       <tr>
            <td align="left"><?php _e('Show price', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="show_price" id="show_price" checked="checked">
            </td>
        </tr>
        <tr>
            <td><?php _e('Show description', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="show_description" id="show_description" checked="checked">
            </td>
        </tr>
        <tr>
            <td><?php _e('Show add to cart button', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="show_add_to_cart_button" id="show_add_to_cart_button" checked="checked">
            </td>
        </tr>        
        <tr>
            <td><?php _e('Show more button', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="show_more_button" id="show_more_button" checked="checked">
            </td>
        </tr>
        <tr>
            <td><?php _e('Show more items button', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="show_more_items_button" id="show_more_items_button" checked="checked">
            </td>
        </tr>   
        <tr>
            <td align="left"><?php echo _e('Image source', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <select name="image_source" id="image_source" class="select">
                    <option value="thumbnail"><?php _e("Thumbnail") ?></option>
                    <option value="medium"><?php _e("Medium") ?></option>
                    <option value="large"><?php _e("Large") ?></option>
                    <option value="full"><?php _e("Full") ?></option>
                </select>
            </td>
        </tr>	
        <tr>
            <td align="left"><?php _e('Image height', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="text" name="image_height" id="image_height" value="100" size="5">%
            </td>
        </tr>
        <tr>
            <td align="left"><?php _e('Image width', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="text" name="image_width" id="image_width" value="100" size="5">%
            </td>
        </tr>	
        

        <tr>
            <td colspan="2" align="left">
                <br />
                <strong>---<?php _e('Carousel options', 'woocommerce-products-carousel-all-in-one') ?>---</strong>
            </td>
        </tr>  
        <tr>
            <td align="left"><?php _e('Items to show', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="text" name="items_to_show" id="items_to_show" value="4" size="5">
            </td>
        </tr>  
        <tr>
            <td align="left"><?php _e('Slide by', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="text" name="slide_by" id="slide_by" value="1" size="5">
                <br />
                <small><?php echo _e("Number of elements to slide.", "woocommerce-products-carousel-all-in-one") ?></small>                
            </td>
        </tr> 
        <tr>
            <td align="left"><?php _e('Margin', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="text" name="margin" id="margin" value="5" size="5">[px]
                <br />
                <small><?php echo _e("Margin between items.", "woocommerce-products-carousel-all-in-one") ?></small>                  
            </td>
        </tr>          
        <tr>
            <td align="left"><?php _e('Inifnity loop', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="loop" id="loop" checked="checked">
                <br />
                <small><?php echo _e("Duplicate last and first items to get loop illusion.", "woocommerce-products-carousel-all-in-one") ?></small>                
            </td>
        </tr>	   
        <tr>
            <td align="left"><?php _e('Auto play', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="auto_play" id="auto_play" checked="checked">
            </td>
        </tr>	 
        <tr>
            <td align="left"><?php _e('Pause on mouse hover', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="stop_on_hover" id="stop_on_hover" checked="checked">
            </td>
        </tr>     
        <tr>
            <td align="left"><?php _e('Autoplay interval timeout', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="text" name="auto_play_timeout" id="auto_play_timeout" value="1200" size="5">[ms]
            </td>
        </tr>
        <tr>
            <td align="left"><?php _e('Show "next" and "prev" buttons', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="nav" id="nav" checked="checked">
            </td>
        </tr>    
        <tr>
            <td align="left"><?php _e('Navigation speed', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="text" name="nav_speed" id="nav_speed" value="800" size="5">[ms]
            </td>
        </tr>   
        <tr>
            <td align="left"><?php _e('Show dots navigation', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="dots" id="dots" checked="checked">
            </td>
        </tr>  
        <tr>
            <td align="left"><?php _e('Dots speed', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="text" name="dots_speed" id="dots_speed" value="800" size="5">[ms]
            </td>
        </tr>         
        <tr>
            <td align="left"><?php _e('Delays loading of images', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="lazy_load" id="lazy_load">
                <br />
                <small><?php echo _e("Images outside of viewport won't be loaded before user scrolls to them. Great for mobile devices to speed up page loadings.","woocommerce-products-carousel-all-in-one"); ?></small>                              
            </td>
        </tr>  	
        <tr>
            <td align="left"><?php _e('Mouse events', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="mouse_drag" id="mouse_drag" checked="checked">
            </td>
        </tr> 
        <tr>
            <td align="left"><?php _e('Mousewheel scrolling', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="mouse_wheel" id="mouse_wheel" checked="checked">
            </td>
        </tr>  
        <tr>
            <td align="left"><?php _e('Touch events', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <input type="checkbox" value="1" name="touch_drag" id="touch_drag" checked="checked">
            </td>
        </tr> 
        <tr>
            <td align="left"><?php echo _e('Animation', 'woocommerce-products-carousel-all-in-one'); ?>:</td>
            <td>
                <select name="easing" id="easing" class="select">
                <?php            
                  $source_list = array("linear"             => "linear",
                                       "swing"              => "swing",
                                       "easeInQuad"         => "easeInQuad",
                                       "easeOutQuad"        => "easeOutQuad",   
                                       "easeInOutQuad"      => "easeInOutQuad",
                                       "easeInCubic"        => "easeInCubic",
                                       "easeOutCubic"       => "easeOutCubic",
                                       "easeInOutCubic"     => "easeInOutCubic",
                                       "easeInQuart"        => "easeInQuart",
                                       "easeOutQuart"       => "easeOutQuart",
                                       "easeInOutQuart"     => "easeInOutQuart",
                                       "easeInQuint"        => "easeInQuint",
                                       "easeOutQuint"       => "easeOutQuint",
                                       "easeInOutQuint"     => "easeInOutQuint",
                                       "easeInExpo"         => "easeInExpo",
                                       "easeOutExpo"        => "easeOutExpo",
                                       "easeInOutExpo"      => "easeInOutExpo",
                                       "easeInSine"         => "easeInSine",
                                       "easeOutSine"        => "easeOutSine",
                                       "easeInOutSine"      => "easeInOutSine",
                                       "easeInCirc"         => "easeInCirc",
                                       "easeOutCirc"        => "easeOutCirc",
                                       "easeInOutCirc"      => "easeInOutCirc",
                                       "easeInElastic"      => "easeInElastic",
                                       "easeOutElastic"     => "easeOutElastic",
                                       "easeInOutElastic"   => "easeInOutElastic",
                                       "easeInBack"         => "easeInBack",
                                       "easeOutBack"        => "easeOutBack",
                                       "easeInOutBack"      => "easeInOutBack",
                                       "easeInBounce"       => "easeInBounce",
                                       "easeOutBounce"      => "easeOutBounce",
                                       "easeInOutBounce"    => "easeInOutBounce"                   
                                      );


                  foreach($source_list as $key => $list) {
                        echo "<option value=\"".$key."\">".$list."</option>";
                  }
                ?>    
                </select>
            </td>
        </tr>	        
        <tr>
            <td colspan="2">
                <input type="button" class="button button-primary button-large" value="<?php _e('Insert Shortcode', 'woocommerce-products-carousel-all-in-one') ?>" onClick="insert_shortcode();">
            </td>
        </tr>
    </table>
</div>
