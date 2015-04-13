<?php
/**
 * frp_wordpress_parent back compat functionality
 *
 * Prevents frp_wordpress_parent from running on WordPress versions prior to 4.0,
 * since this theme is not meant to be backward compatible beyond that
 * and relies on many newer functions and markup changes introduced in 4.0.
 *
 * @package WordPress
 * @subpackage frp_wordpress_default
 * @since 11.3.2015
 */

/**
 * Prevent switching to frp_wordpress_parent on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since 11.3.2015
 */
function frp_wordpress_parent_switch_theme() {
	switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'frp_wordpress_parent_upgrade_notice' );
}
add_action( 'after_switch_theme', 'frp_wordpress_parent_switch_theme' );

/**
 * Add message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * frp_wordpress_parent on WordPress versions prior to 4.0.
 *
 * @since 11.3.2015
 */
function frp_wordpress_parent_upgrade_notice() {
	$message = sprintf( __( 'frp_wordpress_parent requires at least WordPress version 4.0. You are running version %s. Please upgrade and try again.', 'frp_wordpress_parent' ), $GLOBALS['wp_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevent the Theme Customizer from being loaded on WordPress versions prior to 4.0.
 *
 * @since 11.3.2015
 */
function frp_wordpress_parent_customize() {
	wp_die( sprintf( __( 'frp_wordpress_parent requires at least WordPress version 4.0. You are running version %s. Please upgrade and try again.', 'frp_wordpress_parent' ), $GLOBALS['wp_version'] ), '', array(
		'back_link' => true,
	) );
}
add_action( 'load-customize.php', 'frp_wordpress_parent_customize' );

/**
 * Prevent the Theme Preview from being loaded on WordPress versions prior to 4.0.
 *
 * @since 11.3.2015
 */
function frp_wordpress_parent_preview() {
	if ( isset( $_GET['preview'] ) ) {
		wp_die( sprintf( __( 'frp_wordpress_parent requires at least WordPress version 4.0. You are running version %s. Please upgrade and try again.', 'frp_wordpress_parent' ), $GLOBALS['wp_version'] ) );
	}
}
add_action( 'template_redirect', 'frp_wordpress_parent_preview' );
