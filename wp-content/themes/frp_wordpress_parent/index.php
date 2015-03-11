<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Frp_WordPress_Parent
 * @since v1.0
 */

global $app;
get_header();

get_template_part( 'Partials/content', 'header' );

?>

	<section class="module row main">
	
	<?php
		if ( have_posts() ){
	
			// Start the Loop.
			while ( have_posts() ){
				
				the_post();
	
				/*
				 * Include the post format-specific template for the content. If you want to
				 * use this in a child theme, then include a file called called content-___.php
				 * (where ___ is the post format) and that will be used instead.
				 */
				get_template_part( 'content', get_post_format() );
	
			}
	
			// Previous/next page navigation.
			/*the_pagination( array(
				'prev_text'          => __( 'Previous page', $app->key ),
				'next_text'          => __( 'Next page', $app->key ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', $app->key ) . ' </span>',
			) );*/

		} else {
	
			// If no content, include the "No posts found" template.
			get_template_part( 'content', 'none' );
	
		}
	?>
	
	</section>

<?php
get_sidebar();
get_footer();
?>