<?php 
$key = $args['key']; 
$product_id = get_the_ID();
$product = wc_get_product($product_id);
$average_rating = $product->get_average_rating();
$average_rating_percentage = ( $average_rating / 5 ) * 100;
$acf_fields = get_fields();

?>
<div class="most-selling-products-item">
    <p class="rank"><strong><?php echo $key; ?></strong>位</p>
    <div class="most-selling-products-inner">
        <div class="image">
            <a href="<?php echo $product->get_permalink(); ?>">
                <img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" />
            </a>
        </div>
        <div class="title">
            <a href="<?php echo $product->get_permalink(); ?>"><?php echo $product->get_title(); ?></a>
            <div class="star-rating" role="img" aria-label="5段階中<?php echo $average_rating; ?>の評価">
                <span style="width:<?php echo $average_rating_percentage; ?>%">5段階中<strong class="rating"><?php echo $average_rating; ?></strong>の評価</span>
            </div>
            <p class="total-reviews">(<?php echo $product->get_review_count(); ?>)</p>
        </div>
        <div>
            <?php echo Archive_Products::render_product_categories_html($product_id); ?>
        </div>
        <div class="date">
            <p class="delivery_date">
                <strong>配達日数</strong> 
                <span><?php echo do_shortcode("[deliverytime]");?></span>
            </p>
            <?php if (!empty($acf_fields['exp_date'])) : ?>
            <p class="exp_date">
                <strong>消費期限</strong>
                <span><?php echo $acf_fields['exp_date']; ?></span>
            </p>
            <?php endif; ?>
        </div>
        <div class="stock">
            <?php echo Archive_Products::render_product_stock_html($product_id); ?>
        </div>
        <div id="div_block-171-22823" class="ct-div-block">
            <div id="fancy_icon-172-22823" class="ct-fancy-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M400 480H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48v352c0 26.5-21.5 48-48 48zm-204.7-98.1l184-184c6.2-6.2 6.2-16.4 0-22.6l-22.6-22.6c-6.2-6.2-16.4-6.2-22.6 0L184 302.7l-70.1-70.1c-6.2-6.2-16.4-6.2-22.6 0l-22.6 22.6c-6.2 6.2-6.2 16.4 0 22.6l104 104c6.2 6.3 16.4 6.3 22.6 0z"></path></svg>
            </div>
            <div id="shortcode-238-22823" class="ct-shortcode"><?php echo Archive_Products::nap_per_tablet_label($product); ?></div>
            <div id="nestable_shortcode-213-22823" class="ct-nestable-shortcode"><?php echo Archive_Products::per_tablet_min_price($product); ?></div>
        </div>
        <p class="price">
            <?php echo $product->get_price_html(); ?>
        </p>
        <?php echo do_shortcode( '[add_to_cart id='.$product_id.' show_price="false"]' ); ?>
    </div>
</div>