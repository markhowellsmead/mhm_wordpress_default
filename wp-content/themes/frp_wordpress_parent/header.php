<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta charset="<?php bloginfo('charset');?>" />
	<meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1.0" />

	<!--[if lt IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<title><?php echo wp_title(' | ', false, 'right') . get_bloginfo('name');?></title>

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head();?>

</head>
<body <?php body_class(); ?>>

<div class="container _cf">