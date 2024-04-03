<?php 

$product_id = get_the_ID();
$product = wc_get_product($product_id);
$acf_fields = get_fields();
$product_categories = get_the_terms( $product_id, 'product_cat' );

?>
<div class="how-to-choose-item">
    <div class="how-to-choose-item-inner">
        <div class="image">
            <a href="<?php echo $product->get_permalink(); ?>">
                <img src="<?php echo wp_get_attachment_url( $product->get_image_id() ); ?>" />
            </a>
        </div>
        <div class="title">
            <a href="<?php echo $product->get_permalink(); ?>"><?php echo $product->get_title(); ?></a>
        </div>
        <div class="details">
            <?php echo Archive_Products::render_active_ingredients_html($product); ?>
            <?php if (!empty($product_categories)) : ?>
            <p class="product_categories">
                <?php 
                foreach($product_categories as $key => $term) : 
                    $term_link = get_term_link( $term->term_id, "product_cat" );
                ?>
                <a href="<?php echo $term_link; ?>"><?php echo $term->name; ?></a><?php if ($key != array_key_last($product_categories)) echo ", "; ?>
                <?php endforeach; ?>
            </p>
            <?php endif; ?>
        </div>
        <div class="most-selling-products-inner">
            <div id="div_block-171-22823" class="ct-div-block">
                <div id="fancy_icon-172-22823" class="ct-fancy-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M400 480H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48v352c0 26.5-21.5 48-48 48zm-204.7-98.1l184-184c6.2-6.2 6.2-16.4 0-22.6l-22.6-22.6c-6.2-6.2-16.4-6.2-22.6 0L184 302.7l-70.1-70.1c-6.2-6.2-16.4-6.2-22.6 0l-22.6 22.6c-6.2 6.2-6.2 16.4 0 22.6l104 104c6.2 6.3 16.4 6.3 22.6 0z"></path></svg>
                </div>
                <div id="shortcode-238-22823" class="ct-shortcode"><?php echo Archive_Products::nap_per_tablet_label($product); ?></div>
                <div id="nestable_shortcode-213-22823" class="ct-nestable-shortcode"><?php echo Archive_Products::per_tablet_min_price($product); ?></div>
            </div>
        </div>
    </div>
</div>