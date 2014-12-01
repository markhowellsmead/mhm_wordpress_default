<?php

global $app,$theme;

?><aside class="clearfix">
	<?php
	echo $app->sidebar1==''?'':$app->related_content($app->sidebar1,'','<section class="section1 clearfix">','</section>');
	echo $app->sidebar2==''?'':$app->related_content($app->sidebar2,'','<section class="section2 clearfix">','</section>');
	echo $app->sidebar3==''?'':$app->related_content($app->sidebar3,'','<section class="section3 clearfix">','</section>');
	echo $app->sidebar4==''?'':$app->related_content($app->sidebar4,'','<section class="section4 clearfix">','</section>');
	?>
</aside>
