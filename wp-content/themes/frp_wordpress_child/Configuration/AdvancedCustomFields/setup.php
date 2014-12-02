<?php

if(function_exists("register_field_group")){

	register_field_group(array (
		'id' => 'acf_additional-fields-post',
		'title' => 'Additional post information',
		'fields' => array (
			array (
				'key' => 'additional-fields-post-location',
				'label' => 'Location',
				'name' => 'location',
				'type' => 'google_map',
				'center_lat' => '46.8131873',
				'center_lng' => '8.2242101',
				'zoom' => 9,
				'height' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'side',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 105,
	));

}