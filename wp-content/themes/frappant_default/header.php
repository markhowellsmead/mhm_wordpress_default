<?php

if(is_singular() || is_search):
	global $post;
	setup_postdata($post);
	$GLOBALS['FRP']->currentpageID = $post->ID;
endif;

if(!isset($pageimage)||$pageimage==''):
	$pageimage=TEMPLATE_URI.'/images/logo_facebookapp.jpg';
endif;

$pagetitle = trim(wp_title('', false, 'left' ));

?><!DOCTYPE html>
<html <?=$language_attributes?>>
<head>
	<!--[if lt IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<!--[if IE]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->
	<meta charset="<?php bloginfo('charset');?>">
	<title><?=$pagetitle?></title>

	<meta name="description" content="<?php echo get_bloginfo('name').' - '.$pagetitle; ?>">
	<!--meta name="author" content=""-->

	<meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0">

	<!--meta property="og:locale" content=""-->
	<meta property="og:type" content="blog">
	<meta property="og:image" content="<?=$GLOBALS['FRP']->template_uri?>/img/facebook_screenshot.jpg">
	<meta property="og:url" content="<?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>">
	<meta property="og:title" content="<?php echo get_bloginfo('name'); wp_title(' - '); ?>">
	<meta property="og:description" content="<?php the_excerpt_rss();?>">

	<link rel="icon" href="/favicon.ico" type="image/x-icon">

	<!-- standard definitionen -->
	<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>">

	<!-- definitionen nur für dieses PROJEKT -->
	<link rel="stylesheet" href="<?=$GLOBALS['FRP']->template_uri?>/css/custom.css">

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<script src="//code.jquery.com/jquery.min.js"></script>

<?php
	if(is_singular() && get_option( 'thread_comments' )){
		wp_enqueue_script( 'comment-reply' );
	}
	wp_head();
?>
</head>
<body <?php body_class(); ?>>
<div class="container clearfix">