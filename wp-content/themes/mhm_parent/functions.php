<?php
/*
	anleitungen für wp-standardfunktionen werden nicht hier verlinkt
	bitte http://codex.wordpress.org/Function_Reference als referenz anwenden
	
	diese datei wird als erstes geladen, bevor die seite/page/liste etc zusammengebaut ist
	in backend sowohl frontend. wenn customisierungen im FE hier definiert sind, entsprechend
	nur für FE einschalten:
	
	if(!is_admin()){
		// FE sachen
	}

*/

//	helper bzw. theme classes laden

//	allgemeine funktionen (projekt- und themeunabhängig)
	require_once('cms.class');
	$GLOBALS['FRP'] = new CMS();

	$GLOBALS['FRP']->debugmode = false;

//	theme-spezifische funktionen
	require_once('theme.class');
	$GLOBALS['THEME'] = new THEME();

//	theme als constant definieren
	define('THEME','frappant_default');

// 	mehrsprachigkeit - .PO daten laden
	load_theme_textdomain(THEME);

//	custom menus (wordpress 3+)
//	hier anpassen mit angaben für dieses projekt
//	aber nur wenn nötig!
	/*
	add_theme_support( 'menus' );
	function register_my_menus() {
		register_nav_menus(
			array('frappant_default' => __( 'Ebene 1' ) )
		);
	}
	add_action( 'init', 'register_my_menus' );
	*/

//	initialisierung jeder seite
	function frappant_default_init() {
		if (!is_admin()) {
			// lokale kopie von jquery NUR in BE laden
			// dafür in template die version aus //code.jquery.com/jquery.min.js immer im header.php aufrufen
			wp_deregister_script('jquery');
		}
	}
	add_action('init', 'frappant_default_init');

//	bei suchergebnisse, nur POSTs und PAGEs anzeigen
	function excludePages($query) {
		if($query->is_search) {
			$query->set('post_type',array('post','page'));
		}
		return $query;
	}
	add_filter('pre_get_posts','excludePages');

//	tinymce in BE interface hinzufügen
//	muss im footer gemacht werden nicht im header!
	add_action( 'admin_print_footer_scripts', 'wp_tiny_mce', 25);

//	unterstützung von wp-eigene thumbnail funktionen aktivieren
	add_theme_support('post-thumbnails');

//	custom auswählbare thumbnailgrössen definieren
	add_image_size('quadratischKlein',180,180,true);

//	body classes
	function add_bodyclasses(){
		return $GLOBALS['FRP']->add_bodyclasses();
	}
	add_filter('body_class','add_bodyclasses');
	
	// zb 
	if(is_user_logged_in()){
		$GLOBALS['FRP']->body_classes[] = 'loggedin';
	}

//	damit die funktion get_template_directory_uri in header.php nicht mehrmals aufgerufen werden muss
	$GLOBALS['FRP']->template_uri = get_template_directory_uri();