<?php
/**
 * Special offers Module
 */
namespace Kigo\Themes\instaparent\Widgets\SpecialOffersWidget;
//Special offers widget
include 'SpecialOffersWidget.php';



/* include widget style */
add_action('wp_enqueue_scripts', 'Kigo\Themes\instaparent\Widgets\SpecialOffersWidget\addStyles');

function addStyles(){

    wp_enqueue_style('kigo-SpecialOffersWidget-style',\Kigo\Themes\instaparent\getDirUrl(dirname(__FILE__)) . '/css/main.min.css', array(), '1.0.0');
}


/* include admin widget style */
add_action('admin_enqueue_scripts', 'Kigo\Themes\instaparent\Widgets\SpecialOffersWidget\addAdminStyles');

function addAdminStyles(){

    wp_enqueue_style('kigo-SpecialOffersWidgetAdmin-style', \Kigo\Themes\instaparent\getDirUrl(dirname(__FILE__)) . '/css/admin.css', array(), '1.0.0');
}
