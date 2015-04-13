<?php
/**
* This file contains the function definitions for the Child Theme.
* In order to maintain the App logic, place your own functions in Classes/Theme.php, not here. 
* This file is loaded BEFORE the equivalent version in the Parent Theme.
*/

require_once(get_template_directory().'/Classes/App.php');

$app = Frp\WordPress\App::getSingleton();

// load your own functions from the class file frp_wordpress_child/Classes/Theme.php
$app->extend('theme', $app->key);

// load additional non-essential parent functions, e.g. from the class file frp_wordpress_parent/Classes/Youtube.php
//$app->extend('youtube');