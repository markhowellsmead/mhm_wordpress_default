<?php

global $cms,$theme;

?><aside class="clearfix">
	<?php
	echo $cms->sidebar1==''?'':$cms->related_content($cms->sidebar1,'','<section class="section1 clearfix">','</section>');
	echo $cms->sidebar2==''?'':$cms->related_content($cms->sidebar2,'','<section class="section2 clearfix">','</section>');
	echo $cms->sidebar3==''?'':$cms->related_content($cms->sidebar3,'','<section class="section3 clearfix">','</section>');
	echo $cms->sidebar4==''?'':$cms->related_content($cms->sidebar4,'','<section class="section4 clearfix">','</section>');
	?>
</aside>
