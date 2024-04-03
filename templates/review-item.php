<?php 
$review_data = $args['data'];
$review_data->rating = ($review_data->rating) ? $review_data->rating : 0;
$rating_percentage = ( $review_data->rating / 5 ) * 100;
?>
<div class="review-item">
    <div class="star-rating" role="img" aria-label="<?php echo $review_data->rating; ?>段階中5の評価"><span style="width:<?php echo $rating_percentage; ?>%"><?php echo $review_data->rating; ?>段階中<strong class="rating"><?php echo $review_data->rating; ?></strong>の評価</span></div>
    <p class="meta">
    		<strong class="woocommerce-review__author"><?php echo $review_data->author; ?> </strong>
    		<span class="woocommerce-review__dash">–</span> <time class="woocommerce-review__published-date"><?php echo $review_data->date; ?></time>
    </p>
    
    <div class="description">
        <p><?php echo $review_data->content; ?></p>
    </div>
</div>