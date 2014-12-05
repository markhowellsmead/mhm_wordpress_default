<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Frp_WordPress_Parent
 * @since v1.0
 */
 
global $app;
get_header(); ?>

	<section class="module row main">

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			/*
			 * Include the post format-specific template for the content. If you want to
			 * use this in a child theme, then include a file called called content-___.php
			 * (where ___ is the post format) and that will be used instead.
			 */
			get_template_part( 'content', get_post_format() );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

			// Previous/next post navigation.
			the_post_navigation( array(
				'next_text' => _x( '<span class="meta-nav">Next <span class="screen-reader-text">post:</span></span><span class="post-title">%title</span>', 'Next post link', $app->key ),
				'prev_text' => _x( '<span class="meta-nav">Previous <span class="screen-reader-text">post:</span></span><span class="post-title">%title</span>', 'Previous post link', $app->key )
			) );

		// End the loop.
		endwhile;
		?>

	</section>

<?php get_footer(); ?>
