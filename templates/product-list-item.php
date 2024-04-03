<?php 
$product = $args['product'];
$key = $args['key'];
$product_id = $product->get_id();

$average_rating = $product->get_average_rating();
$average_rating_percentage = ( $average_rating / 5 ) * 100;

$active_ingredients = Archive_Products::render_active_ingredients_html($product, false);

$manufacturer_id = get_field('manufacturer', $product_id);
$manufacturer_link = ($manufacturer_id) ? get_term_link( $manufacturer_id, "manufacturer" ) : '';
$manufacturer_name = ($manufacturer_id) ? get_term( $manufacturer_id )->name : '';

?>
<div class="product-list-item<?php if ($key > 9) echo " dsp_non";?>">
    <div class="yoko_l">
		<div class="image">
            <a href="<?php echo $product->get_permalink(); ?>">
                <img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" />
            </a>
        </div>
		<div class="check_detail">
			<label><input type="checkbox" name="check_detail<?php echo $product_id; ?>">
				<span class="item">比較してみる</span>
			</label>
		</div>
		<div class="chira_btn green_btn show-on-mobile">詳細を表示</div>
	</div>

	<div class="yoko_c">
	    <div class="title">
		    <a href="<?php echo $product->get_permalink(); ?>"><?php echo $product->get_title(); ?></a>
		</div>
        <?php echo Archive_Products::render_product_categories_html($product_id); ?>
        <div class="review">
    		<div class="star-rating" role="img" aria-label="5段階中<?php echo $average_rating; ?>の評価">
                    <span style="width:<?php echo $average_rating_percentage; ?>%">5段階中<strong class="rating"><?php echo $average_rating; ?></strong>の評価</span>
            </div>
            <p class="total-reviews">(<?php echo $product->get_review_count(); ?>)</p>
        </div>
		<div class="chira_btn green_btn">詳細を表示</div>
		<div class="yoko-r-on-mobile">
		    <div class="stock">
                <?php echo Archive_Products::render_product_stock_html($product_id); ?>
            </div>
            <p class="price">
                <?php echo $product->get_price_html(); ?>
            </p>
            <div class="most-selling-products-inner">
                <div id="div_block-171-22823" class="ct-div-block">
                    <div id="fancy_icon-172-22823" class="ct-fancy-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M400 480H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48v352c0 26.5-21.5 48-48 48zm-204.7-98.1l184-184c6.2-6.2 6.2-16.4 0-22.6l-22.6-22.6c-6.2-6.2-16.4-6.2-22.6 0L184 302.7l-70.1-70.1c-6.2-6.2-16.4-6.2-22.6 0l-22.6 22.6c-6.2 6.2-6.2 16.4 0 22.6l104 104c6.2 6.3 16.4 6.3 22.6 0z"></path></svg>
                    </div>
                    <div id="shortcode-238-22823" class="ct-shortcode"><?php echo Archive_Products::nap_per_tablet_label($product); ?></div>
                    <div id="nestable_shortcode-213-22823" class="ct-nestable-shortcode"><?php echo Archive_Products::per_tablet_min_price($product); ?></div>
                </div>
                <?php echo do_shortcode( '[add_to_cart id='.$product_id.' show_price="false"]' ); ?>
            </div>
		</div>
		<div class="ap-product-list-details">
		    <table>
		        <tbody>
		            <tr>
		                <th>商品名</th>
		                <td><?php echo $product->get_title(); ?></td>
		            </tr>
		            <?php if ($active_ingredients) : ?>
		            <tr>
		                <th>成分</th>
		                <td class="active-ingredients"><?php echo $active_ingredients; ?></td>
		            </tr>
		            <?php endif; ?>
		            <tr>
		                <th>効果</th>
		                <td class="pr_effect">脂肪の吸収を防ぐ</td>
		            </tr>
		            <tr>
		                <th>副作用</th>
		                <td class="pr_side_effect">下痢、不快な臭いのある脂肪便</td>
		            </tr>
		            <tr>
		                <th>飲み方</th>
		                <td>食事の直前、食事中、または食事後1時間以内に服用すること</td>
		            </tr>
		            <?php if ($manufacturer_id) : ?>
		            <tr>
		                <th>製造元</th>
		                <td><a href="<?php echo $manufacturer_link; ?>"><?php echo $manufacturer_name; ?></a></td>
		            </tr>
		            <?php endif; ?>
		        </tbody>
		    </table>
		</div>
	</div>

	<div class="yoko_r">
		<div class="stock">
            <?php echo Archive_Products::render_product_stock_html($product_id); ?>
        </div>
        <p class="price">
            <?php echo $product->get_price_html(); ?>
        </p>
        <div class="most-selling-products-inner">
            <div id="div_block-171-22823" class="ct-div-block">
                <div id="fancy_icon-172-22823" class="ct-fancy-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M400 480H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48v352c0 26.5-21.5 48-48 48zm-204.7-98.1l184-184c6.2-6.2 6.2-16.4 0-22.6l-22.6-22.6c-6.2-6.2-16.4-6.2-22.6 0L184 302.7l-70.1-70.1c-6.2-6.2-16.4-6.2-22.6 0l-22.6 22.6c-6.2 6.2-6.2 16.4 0 22.6l104 104c6.2 6.3 16.4 6.3 22.6 0z"></path></svg>
                </div>
                <div id="shortcode-238-22823" class="ct-shortcode"><?php echo Archive_Products::nap_per_tablet_label($product); ?></div>
                <div id="nestable_shortcode-213-22823" class="ct-nestable-shortcode"><?php echo Archive_Products::per_tablet_min_price($product); ?></div>
            </div>
            <?php echo do_shortcode( '[add_to_cart id='.$product_id.' show_price="false"]' ); ?>
        </div>
	</div>
</div>