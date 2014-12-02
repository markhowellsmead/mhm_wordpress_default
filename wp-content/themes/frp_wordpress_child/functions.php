<?php
require_once(get_template_directory().'/Classes/App.php');

$app = Frp\WordPress\App::getSingleton();

// load your own functions from the class file frp_wordpress_child/Classes/Theme.php
$app->extend('theme', $app->key);

// load additional parent functions from the class file frp_wordpress_parent/Classes/Youtube.php
//$app->extend('youtube');