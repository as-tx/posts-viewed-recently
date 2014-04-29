<?php

function ft_shortcode_recentlyViewed( $atts ){
    // Configure defaults and extract the attributes into variables

    $args = array(
        'widget_id' => $atts['widget_id'],
        'by_shortcode' => 'shortcode_',
    );

    ob_start();
    the_widget( 'ftRecentViewedPosts', '', $args);
    $output = ob_get_clean();
    return $output;
}
add_shortcode( 'ft-recentlyviewed', 'ft_shortcode_recentlyViewed' );