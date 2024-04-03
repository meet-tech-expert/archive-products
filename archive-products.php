<?php
/*
Plugin Name: Archive Products
Plugin URI: https://www.upwork.com/freelancers/~019cd2a22666badcb6
Description: Display list of archive products using shortcode.
Version: 1.0
Author: Rinkesh Gupta
Author URI: https://www.upwork.com/freelancers/~019cd2a22666badcb6
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

// Define Constants
define('AP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('AP_PLUGIN_URL', plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)));
define('AP_VERSION', '1.3');

add_action('plugins_loaded', 'archive_products_init', 0);

function archive_products_init() {

	if ( !class_exists( 'WooCommerce' ) ) return;
	if ( !class_exists( 'ACF' ) ) return;
    
	/**
 	 * Gateway class
 	 */
	class Archive_Products {
		
		public function __construct(){
			
			add_shortcode( 'archive_products', array($this, 'archive_products_main') );
			
			// Admin-only hooks
            if (is_admin() && !defined('DOING_AJAX')) {}
            // Frontend-only hooks
            else {
    
                if (!(defined('DOING_AJAX') && DOING_AJAX)) {
                    add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
                }
            }
		   
		}
		
		/**
         * Load frontend assets
         *
         * @access public
         * @return bool
         */
        public function enqueue_frontend_assets()
        {
            // Frontent styles
            wp_register_style('AP_frontend', AP_PLUGIN_URL . '/assets/css/frontend.css', array(), AP_VERSION);
            wp_enqueue_style('AP_frontend');
    
            // Frontent scripts
            wp_register_script('AP_jquery_ui', AP_PLUGIN_URL . '/assets/js/jquery-ui.js', array('jquery'), AP_VERSION);
            wp_enqueue_script('AP_frontend');
            
            wp_register_script('AP_frontend', AP_PLUGIN_URL . '/assets/js/frontend.js', array('AP_jquery_ui'), AP_VERSION);
            wp_enqueue_script('AP_frontend');
        }
		 
		/**
    	 * Get html code for new category page
    	 *
    	 * @return html
    	 */
		public function archive_products_main(){
		    
		    // Get term ogject
		    $term = get_queried_object();
		    
		    // Get all custom fields for term 
		    $acf_fields = get_fields($term);
		    
		    if ( ! $acf_fields['enable_new_template'] ) return '';
		    
		    $html = $this->get_most_selling_products_section_html($term, $acf_fields);
		    $html .= $this->get_main_content_html($term, $acf_fields);
		    
            return $html;
		 
		}
		
		/**
    	 * Get most selling products html code
    	 *
    	 * @param  object $term Product category object
    	 * @param  array $acf_fields ACF fields
    	 * @return html
    	 */
		public function get_most_selling_products_section_html($term, $acf_fields) {
		    
		    $child_categories = get_term_children($term->term_id, 'product_cat');
		    $products_per_page = (isset($acf_fields['max_most_selling_products']) && !empty($acf_fields['max_most_selling_products'])) ? $acf_fields['max_most_selling_products'] : 4;
		    
		    if (!$child_categories || empty($child_categories)) $child_categories = array($term->term_id);
		    
	        $html = $this->get_textual_section_html($acf_fields);
	        $html .= $this->get_most_selling_products_html($child_categories, $products_per_page);
	        
	        return $html;
		    
		}
		
		/**
    	 * Get textual section html code
    	 *
    	 * @param  array $acf_fields ACF fields
    	 * @return html
    	 */
		public function get_textual_section_html($acf_fields) {
		    
		    $html = '';
		    
		    if ($acf_fields['most_selling_products_description']) {
		        ob_start();
                ?>
                <p class="ap-most-selling-desc"><?php echo $acf_fields['most_selling_products_description']; ?></p>
                <?php
                $html .= ob_get_clean();
		    }
	        
	        return $html;
		    
		}
		
		/**
    	 * Get the most selling products html
    	 *
    	 * @param  array $child_categories Child categories
    	 * @param  int $products_per_page Products per page
    	 * @return html
    	 */
		public function get_most_selling_products_html($child_categories, $products_per_page) {
		    
		    $html = '';
		    
		    ob_start();
		    
		    ?>
		    
		    <div id="most-selling-products-tabs">
                <ul>
                    <?php 
                    foreach($child_categories as $k => $child_id) : 
                        $term = get_term_by( 'id', $child_id, 'product_cat' );
                    ?>
                    <li><a href="#tabs-<?php echo $k; ?>"><?php echo $term->name; ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <?php 
                    foreach($child_categories as $k => $child_id) : 
                        $term = get_term_by( 'id', $child_id, 'product_cat' );
                ?>
                <div id="tabs-<?php echo $k; ?>">
                    <div class="row"> 
                        <?php $this->render_most_selling_products($child_id, $products_per_page); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
                
            <?php 
            
            $html .= ob_get_clean();
	        
	        return $html;
		    
		}
		
		/**
    	 * Get main content html
    	 *
    	 * @param  object $term Product category object
    	 * @param  array $acf_fields ACF fields
    	 * @return html
    	 */
		public function get_main_content_html($term, $acf_fields) {
		    
		    $html = '';
		    
		    ob_start();
		    
		    ?>
		    
		    <div id="ap-main-content-tabs">
                <ul>
                    <li><a href="#main-tabs-1">選び方</a></li>
                    <li><a href="#main-tabs-2">ソーシャル</a></li>
                    <li><a href="#main-tabs-3">レビュー</a></li>
                    <li><a href="#main-tabs-4">商品一覧</a></li>
                    <li><a href="#main-tabs-5">コラム</a></li>
                </ul>
                <div id="main-tabs-1">
                    <?php $this->render_how_to_choose_section_html($acf_fields); ?>
                </div>
                <div id="main-tabs-2">
                    <?php $this->render_social_block_html($acf_fields); ?>
                </div>
                <div id="main-tabs-3">
                    <?php $this->render_review_block_html($term, $acf_fields); ?>
                </div>
                <div id="main-tabs-4">
                    <?php $this->render_products_list_html($term, $acf_fields); ?>
                </div>
                <div id="main-tabs-5">
                    <?php $this->render_text_sections_html($acf_fields); ?>
                </div>
            </div>
                
            <?php 
            
            $html .= ob_get_clean();
	        
	        return $html;
		    
		}
		
		/**
    	 * Render how to choose section html
    	 *
    	 * @param  array $acf_fields ACF fields
    	 * @return html
    	 */
		public function render_how_to_choose_section_html($acf_fields) {
		    foreach($acf_fields['types_of_users'] as $user_type) {
		        if (isset($user_type['products']) && !empty($user_type['products'])) {
		            ?>
		            <div class="ap-how-to-choose-type">
		                <p class="ap-how-to-choose-title"><span><?php echo $user_type['text_1']; ?></span><?php echo $user_type['text_2']; ?></p>
		                <div class="ap-how-to-choose-type-inner">
		                    <div class="row">
		                    <?php $this->render_how_ro_choose_products($user_type['products']); ?>
		                    </div>
		                </div>
		            </div>
		            <?php
		        }  
		    }
		}
		
		/**
    	 * Render social block html
    	 *
    	 * @param  array $acf_fields ACF fields
    	 * @return html
    	 */
		public function render_social_block_html($acf_fields) {
		    $html = '';
		    
		    if ($acf_fields['social_title']) {
		        $html .= "<p class='social-title'>". $acf_fields['social_title'] . "</p>";
		    }
		    
		    if ($acf_fields['social_link']) {
		        $html .= '<iframe scrolling="no" frameborder="0" allowtransparency="true" allowfullscreen="true" class="" style="position: static; visibility: visible; max-width: 485px; width: 100%; height: 550px; display: block; flex-grow: 1;" src="'. $acf_fields['social_link'] .'"></iframe>';
		    }
		    
		    echo $html;
		}
		
		/**
    	 * Render review block html
    	 *
    	 * @param  object $term Product category object
    	 * @param  array $acf_fields ACF fields
    	 * @return html
    	 */
		public function render_review_block_html($term, $acf_fields) {
		    
		    $product_id = ($acf_fields['reviews_for_product']) ? $acf_fields['reviews_for_product'] : '';
		    $term = (!$product_id && $acf_fields['reviews_for_category']) ? $acf_fields['reviews_for_category'] : $term;
		  
		    $all_reviews = $this->get_reviews_splitted_by_rating($term, $product_id);
		    
		    $html = '';
		    
		    ob_start();
		    ?>
		    
		    <div id="ap-reviews-block">
		    
		    <?php if ($acf_fields['review_title']) { ?>
		    <p class='review-title'><?php echo $acf_fields['review_title']; ?></p>
		    <?php } ?>
		    
    		    <ul>
                    <li><a href="#good-reviews">良いコメント (★3～5)</a></li>
                    <li><a href="#bad-reviews">悪いコメント (★１～2)</a></li>
                </ul>
                <div id="good-reviews">
                    <?php 
                    foreach($all_reviews['3-5'] as $k => $review) {
                        if ($k >= 5) break;
                        self::include_template('review-item', AP_PLUGIN_PATH, 'nac', array('data' => $review)); 
                    }
                    ?>
                </div>
                <div id="bad-reviews">
                    <?php 
                    foreach($all_reviews['0-2'] as $k => $review) {
                        if ($k >= 5) break;
                        self::include_template('review-item', AP_PLUGIN_PATH, 'nac', array('data' => $review)); 
                    }
                    ?>
                </div>
            </div>
            
            <?php
            
            $html .= ob_get_clean();
		    
		    echo $html;
    
		}
		
		/**
    	 * Render products list html
    	 *
    	 * @param  object $term Product category object
    	 * @param  array $acf_fields ACF fields
    	 * @return html
    	 */
		public function render_products_list_html($term, $acf_fields) {
		    $args = array(
                'category' => array($term->slug),
                'status' => 'publish',
                'limit' => -1,
            );
		    
		    $products = wc_get_products( $args );
		    
		    $html = '';
		    
		    ob_start();
		    ?>
		    <div class="ap-product-list">
		        <?php if ($acf_fields['product_list_title']) { ?>
    		    <p class='product-list-title'><?php echo $acf_fields['product_list_title']; ?></p>
    		    <?php } ?>
    		    <div class="ap-product-list-inner">
    		        <?php 
                    foreach($products as $k => $product) {
                        self::include_template('product-list-item', AP_PLUGIN_PATH, 'nac', array('product' => $product, 'key' => $k)); 
                    }
                    ?>
    		    </div>
    		    <?php if (count($products) > 10) : ?>
    		    <div id="prd_list_yoko_btn" class="more_btn gray_btn normal_btn mb_30" data-more="10">もっと見る</div>
    		    <?php endif; ?>
		    </div>
		     <?php
            
            $html .= ob_get_clean();
		    
		    echo $html;
		}
		
		/**
    	 * Render text sections html
    	 *
    	 * @param  int $cat_id Child category ID
    	 * @param  int $products_per_page Products per page
    	 * @return html
    	 */
		public function render_text_sections_html($acf_fields) {
		    if ($acf_fields['text_section']) {
		        echo "<div class='ap-text-section'>". $acf_fields['text_section'] ."</div>";
		    }
		}
		
		/**
    	 * Render most selling products html
    	 *
    	 * @param  int $cat_id Child category ID
    	 * @param  int $products_per_page Products per page
    	 * @return html
    	 */
		public function render_most_selling_products($cat_id, $products_per_page) {
		    
		    $args = array(
                'post_type'         => 'product',
                'post_status'       => 'publish',
                'posts_per_page'    => $products_per_page,
                'meta_key'          => 'total_sales',
                'orderby'           => 'meta_value_num',
                'order'             => 'desc',
                'tax_query'      => array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'id',
                        'terms' => $cat_id
                    )
                ),
            );
            
            $products_query = new WP_Query($args);
            $i=0;
            
            while($products_query->have_posts()){ 
                $i++; 
                $products_query->the_post();

                self::include_template('most-selling-products-item', AP_PLUGIN_PATH, 'nac', array('key' => $i));
                
            } 
            wp_reset_postdata(); 
		    
		}
		
		/**
    	 * Render how to choose products html
    	 *
    	 * @param  array $product_ids Product IDs
    	 * @return html
    	 */
		public function render_how_ro_choose_products($product_ids) {
		    
		    if (empty($product_ids)) return '';
		    
		    $args = array(
                'post_type'         => 'product',
                'post_status'       => 'publish',
                'posts_per_page'    => 3,
                'post__in'          => $product_ids,
                'orderby'           => 'post__in'
            );
            
            $products_query = new WP_Query($args);
            $i=0;
            
            while($products_query->have_posts()){ 
                $i++; 
                $products_query->the_post();

                self::include_template('how-to-choose-products-item', AP_PLUGIN_PATH, 'nac', array());
                
            } 
            wp_reset_postdata(); 
		    
		}
		
		/**
    	 * Get reviews by category splitted by rating
    	 *
    	 * @param  object $term Product category object
    	 * @param  int $product_id Selected product ID
    	 * @return array
    	 */
		public function get_reviews_splitted_by_rating($term, $product_id) {
		    
		    $all_revies = array(
		        '0-2' => array(),
		        '3-5' => array()
		    );
		    
		    $args = array(
                'category' => array($term->slug),
                'status' => 'publish',
                'limit' => -1,
            );
		    
		    if ($product_id) {
		        $one_product = wc_get_product( $product_id );
		        $products = ($one_product) ? array($one_product) : array();
		    }
		    else $products = wc_get_products( $args );
		    
		    foreach ( $products as $product ) {
		    
    		    $product_id = $product->get_id();
    		    $reviews = get_approved_comments( $product_id );
    		    
    		    foreach( $reviews as $review ) {
                    if( $review->comment_type === 'review' ) {
                        
                        $rating = get_comment_meta($review->comment_ID, 'rating', true);
                        
                        $data = (object) [
                            'author'    => $review->comment_author,
                            'date'      => date_i18n('Y年Md日', strtotime($review->comment_date)),
                            'content'   => $review->comment_content,
                            'rating'    => $rating
                        ];
                        
                        if ($rating >= 3) $all_revies['3-5'][] = $data;
                        else $all_revies['0-2'][] = $data;
                        
                    }
                }
                
		    }
		    
		    return $all_revies;
		    
		}
		
		/**
         * Include template
         *
         * @access public
         * @param string $template
         * @param string $plugin_path
         * @param string $plugin_name
         * @param array $args
         * @return string
         */
        public static function include_template($template, $plugin_path, $plugin_name, $args = array()) 
        {
            if ($args && is_array($args)) {
                extract($args);
            }
    
            // Get template path
            $template_path = self::get_template_path($template, $plugin_path, $plugin_name);
    
            // Check if template exists
    		if (!file_exists($template_path)) {
    
                // Add admin debug notice
                _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $template_path), get_bloginfo('version'));
                return;
    		}
    
            // Include template
            include $template_path;
        }
        
        /**
         * Select correct template (allow overrides in theme folder)
         *
         * @access public
         * @param string $template
         * @param string $plugin_path
         * @param string $plugin_name
         * @return string
         */
        public static function get_template_path($template, $plugin_path, $plugin_name)
        {
            $template = rtrim($template, '.php') . '.php';
    
            // Check if this template exists in current theme
            if (!($template_path = locate_template(array($plugin_name . '/' . $template)))) {
                $template_path = $plugin_path . 'templates/' . $template;
            }
    
            return $template_path;
        }
        
        /**
         * Render categories by product id
         *
         * @access public
         * @param int $product_id
         * @return html
         */
        public static function render_product_categories_html($product_id)
        {
            $terms = get_the_terms( $product_id, 'product_cat' );
            
            $html = "<ul class='ap-product-categories'>";
            
            foreach($terms as $k => $term) {
                $term_link = get_term_link( $term->term_id, "product_cat" );
                $html .= "<li><a href='". $term_link ."'>". $term->name ."</a></li>";
            }
            
            $html .= "</ul>";
    
            return $html;
        }
        
        /**
         * Render Stock / outstock by product id
         *
         * @access public
         * @param int $product_id
         * @return html
         */
        public static function render_product_stock_html($product_id)
        {
            $product = wc_get_product( $product_id );
            // check if stock is managed on a product level
            if( $product->get_manage_stock() ) {
            	$stock_quantity = $product->get_stock_quantity();
            
            	if ($stock_quantity > 0) 
            	    $html = "<span class='instock'>在庫あり</span>";
            	else 
            	    $html = "<span class='outstock'>在庫なし</span>";
            } else {
            	
            	$stock_status = $product->get_stock_status();
            	if( 'instock' === $stock_status ) {
            		$html = "<span class='instock'>在庫あり</span>";
            	}
            	if( 'outofstock' === $stock_status ) {
            		$html = "<span class='outstock'>在庫なし</span>";	
            	}
            	
            }
            
            return $html;
        }
        
        /**
         * Get product min price per tablet
         *
         * @access public
         * @param obj $product
         * @return string
         */
        public static function per_tablet_min_price($product)
        {
            if ($product->is_type( 'variable' )) {
        		$min_price = [];
        		$variations = $product->get_available_variations();
        		foreach ($variations as $variation) {
        			foreach ($variation['attributes'] as $key => $value) { 
        				if (isset($variation['attributes']['attribute_%e6%95%b0%e9%87%8f'])){
        						$tablet_count = $variation['attributes']['attribute_%e6%95%b0%e9%87%8f'];
        						if ($tablet_count){
        							$price_per_tablet = $variation['display_price']?:'0';
        							$price_per_tablet = round($price_per_tablet / $tablet_count);
        							$min_price[] = $price_per_tablet;
        						}
        					}
        			}
        		}
        		if(!empty($min_price)){
        			return min($min_price)." 円";
        		}
        		
        	}
        	
        	return '';
        }
        
        /**
         * Get product per tablet label
         *
         * @access public
         * @param obj $product
         * @return string
         */
        public static function AP_per_tablet_label($product)
        {
    		$per_tablet_label = get_field('per_tablet_label' , $product->get_id());
    		$per_tablet_label = ($per_tablet_label) ? $per_tablet_label: "１錠あたり" ;
    		return $per_tablet_label;

        }
        
        /**
         * Render active ingredients html
         *
         * @access public
         * @param obj $product
         * @param bool $show_label Show label
         * @return html
         */
        public static function render_active_ingredients_html($product, $show_label = true)
        {
    		$html = '';
    		$ingredient = get_field('active_ingredient' , $product->get_id());
		    $Ingredients = get_field('ingredients', 'option');
		    $ing_arr = [];
		    
		    if(!empty($ingredient)){
		        $html .= ($show_label) ? '<p class="active_ingredients"><span class="label">有効成分:</span>' : '';
		        
		        foreach($ingredient as $ingredient){
    				if(isset($Ingredients[$ingredient])){
    					$ing = $Ingredients[$ingredient];
    					$ing_arr[] = "<a href='".$ing['url']."' style='margin-left:20px;'>".$ing['name']."</a>";
    				}
    				//print_r($Ingredients[$ingredient]);
    			}
    			$html .= implode(', ', $ing_arr);
    			$html .= ($show_label) ? "</p>" : "";
		    }
    		
    		return $html;

        }
		 
	}
	
	$Archive_Products = new Archive_Products();
	
}