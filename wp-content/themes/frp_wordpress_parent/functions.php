<?php
/*
This file initializes the app structure. As a rule, you should not
need to add anything more to this file.

If you want to add new features, functions, actions or hooks, then
add them at the appropriate place within the class structure in
the subfolder “Classes”.

@package WordPress
@subpackage Frp_WordPress_Parent
@since v1.0
*/

require_once(get_template_directory().'/Classes/App.php');
$app = \Frp\WordPress\App::getSingleton();