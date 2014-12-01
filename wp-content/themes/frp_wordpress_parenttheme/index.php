<?php
$app = Frp\WordPress\App::getSingleton();

get_header();
?>

	<section class="content clearfix">

<?php
	if (have_posts()):
		the_post();
?>

		<section>

			<header>
				<?php echo $app->pageTitle(array('showparent' => true)); ?>
				<h2>The quick brown fox jumps over the lazy dog</h2>
				<?php if ( has_post_thumbnail() ) {
					the_post_thumbnail();
				} ?>
			</header>

			<?php the_content('Read the rest of this entry &raquo;'); ?>

		</section>

	<?php else : ?>

		<section>
			<header>
				<h3><?php _e('Page or file not found',$app->key);?></h3>
			</header>
			<p><?php _e('Sorry, there is no article available which matches the website address you requested.',$app->key);?></p>
		</section>
	<?php endif; ?>




	</section>

<?php
get_sidebar();
get_footer();
?>