<?php
require_once(__DIR__.'/Classes/App.php');

$app = Frp\WordPress\App::getSingleton();

// load your own functions from the Theme.php class file
$app->extend('theme');

//$app->dump($app, 1);