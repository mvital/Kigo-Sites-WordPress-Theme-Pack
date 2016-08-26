<?php

namespace Discovery;

//Helper functions - Contains dependencies for further functionalities (Should be included first)
include_once 'inc/helpers.php';

//Theme core
include_once 'inc/theme/Core.class.php';

//Theme actions
include_once 'inc/theme/Compatibility.class.php';

//Theme activation related functionalities
include_once 'inc/theme/Activation.class.php';

//Theme BAPI helper
include_once 'inc/theme/BAPIHelper.class.php';

//Stack areas landings
//include_once 'inc/stacks-posttype/StackAreasLandings.class.php';

// Bypass
//include "inc/search_bypass/init.php";

// User Defined Fields Admin page
//include "inc/UserDefinedFields/UserDefinedFields.Display.php";

// Market Areas Table.................................................................................................................
if(! class_exists('WP_Posts_List_Table'))
  {
		require_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'class-wp-list-table.php';
		require_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'class-wp-posts-list-table.php';
  }
require_once 'inc' . DIRECTORY_SEPARATOR . 'market-areas' . DIRECTORY_SEPARATOR .  'MarketAreasTable.php';
require_once 'inc' . DIRECTORY_SEPARATOR . 'market-areas'. DIRECTORY_SEPARATOR . 'MarketAreasDisplay.php';



/* Inititalize core theme functionalities */
$site = new Core();
$site->init();

/* Initialize theme activation */
$activation = new Activation();
add_action('after_switch_theme', array($activation, 'init'));

/* Initialize theme compatibility */
$compatibility = new Compatibility();
add_action('init', array($compatibility, 'overwriteIfSynced'));
