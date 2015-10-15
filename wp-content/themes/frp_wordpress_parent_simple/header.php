<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<title><?php echo wp_title(' | ', false, 'right') . get_bloginfo('name');?></title>
	<?php wp_head();?>
</head>
<body <?php body_class(); ?>>
<div class="container">