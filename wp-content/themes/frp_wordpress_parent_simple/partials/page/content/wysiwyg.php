
	<section class="module row wysiwyg">

	<?php
		if ( have_posts() ){

			while ( have_posts() ){

				the_post();
				the_content();

			}

			get_template_part( 'partials/page/pagination' );

		} else {

			get_template_part( 'partials/page/none' );

		}
	?>

	</section>
