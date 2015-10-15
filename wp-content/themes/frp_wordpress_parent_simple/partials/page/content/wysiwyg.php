<?php

	the_post();
	$the_content = get_the_content();
	
	if( !empty($the_content) ){

?>

<section class="module row constrained wysiwyg">
	
	<div class="inner">

		<?php

			echo $the_content;

			get_template_part( 'partials/page/pagination' );
			
		?>

	</div>

</section>

<?php

	} else {

		get_template_part( 'partials/page/none' );

	}

?>