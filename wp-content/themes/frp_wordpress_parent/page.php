<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Frp_WordPress_Parent
 * @since v1.0
 */

global $app;
get_header();
get_template_part( 'Partials/Content/Header' );
?>

<section class="module row content">

<?php
	if ( have_posts() ){

		the_post();

		// Include the page content template.
		get_template_part( 'Partials/Content/Page' );

	} else {

		// If no content, include the "No posts found" template.
		get_template_part( 'Partials/Content/None' );

	}
?>

</section>

<?php
get_sidebar();
get_footer();
?>