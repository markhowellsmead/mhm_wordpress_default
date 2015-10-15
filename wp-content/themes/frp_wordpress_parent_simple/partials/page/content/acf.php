<?php
/**
* Master row controller
* This file loads the sub parts, based on the selected row layout
* @since 	02.04.15
*/

if (get_field('row')){
	while ( has_sub_field('row') ) {
		get_template_part('partials/page/content/acf/', get_row_layout());
	}
}