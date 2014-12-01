<?php
$app = Frp\WordPress\App::getSingleton();

if(is_singular() || is_search()):
	global $post;
	setup_postdata($post);
endif;

if(!isset($pageimage)||$pageimage==''):
	$pageimage = $app->paths['resources_uri'].'/Images/logo_facebookapp.jpg';
endif;

$pagetitle = trim(wp_title('', false, 'left' ));

?><!DOCTYPE html>
<html <?=$app->get_language_attributes()?>>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta charset="<?php bloginfo('charset');?>" />
	<meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0" />
	<!--[if lt IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<title><?=$pagetitle?></title>

	<!-- standard definitionen -->
	<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php
	if(is_singular() && get_option( 'thread_comments' )){
		wp_enqueue_script( 'comment-reply' );
	}
	wp_head();
?>

</head>
<body <?php body_class(); ?>>
<div class="container clearfix">
