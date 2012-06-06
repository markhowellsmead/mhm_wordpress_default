
	<?php
	get_sidebar();
	?>

</div>

<footer>
	<div>
		<address>© FIRMA <?=date('Y')?></address>
	</div>
</footer>

<script>window.jQuery || document.write("<script src='//code.jquery.com/jquery.min.js'>\x3C/script>")</script>

<script src="<?=$cms->template_uri?>/js/ui.js"></script>
<!--[if lt IE 7 ]>
<script src="<?=$cms->template_uri?>/js/dd_belatedpng.js"></script>
<script>DD_belatedPNG.fix("img, .png_bg");</script>
<![endif]-->
<!--[if lt IE 9 ]><script src="<?=$cms->template_uri?>/js/mediaqueries.js"></script><![endif]-->
<script>
var _gaq=[["_setAccount","UA-XXXXXXX-1"],["_trackPageview"]];
(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
g.src=("https:"==location.protocol?"//ssl":"//www")+".google-analytics.com/ga.js";
s.parentNode.insertBefore(g,s)}(document,"script"));
</script>
<?php wp_footer();?>
</body>
</html>