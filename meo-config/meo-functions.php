<?php

/******************************************************************************
    AJOUT DE L'EXTRAIT DANS LES PAGES
******************************************************************************/

add_post_type_support('page','excerpt');

/******************************************************************************
    CHILDREN PAGES QUERY ELEMENTOR : query ID -> child_pages
******************************************************************************/

// Showing children of current page in Posts Widget
add_action( 'elementor/query/child_pages', function( $query ) {
    // Get current post tags
    $current_page = get_queried_object_id();
    // Modify the query
    $query->set( 'post_parent', $current_page );
} );


/******************************************************************************
    AJOUT DES FICHIERS CSS ET JS
******************************************************************************/
function meo_scripts_and_styles() {
    if( !is_admin() ) {
        wp_deregister_script('jquery');
        wp_register_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', false, '');
        wp_enqueue_script('jquery');
        // register main stylesheet
        if (file_exists(get_stylesheet_directory() . '/styles/global.css')) {
           wp_enqueue_style( 'meo-main-child-stylesheet', get_stylesheet_directory_uri() . '/styles/global.css' );
        }
        wp_enqueue_style( 'slick', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css' );
        wp_enqueue_style( 'slick-theme', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css' );
        // register form stylesheet
        if (file_exists(get_stylesheet_directory() . '/styles/form.css')) {
           wp_enqueue_style( 'meo-form-child-stylesheet', get_stylesheet_directory_uri() . '/styles/form.css' );
        }
        // register custom stylesheet
        if (file_exists(get_stylesheet_directory() . '/styles/custom.css')) {
           wp_enqueue_style( 'meo-custom-child-stylesheet', get_stylesheet_directory_uri() . '/styles/custom.css' );
        }        
        // scripts file in the footer
        wp_enqueue_script( 'slick', '//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js' );
        if (file_exists(get_stylesheet_directory() . '/scripts/gsap/gsap.min.js')) {
           wp_enqueue_script( 'gsap-core', get_stylesheet_directory_uri() . '/scripts/gsap/gsap.min.js' );
        }
        if (file_exists(get_stylesheet_directory() . '/scripts/gsap/scrolltrigger.min.js')) {
           wp_enqueue_script( 'gsap-scrolltrigger', get_stylesheet_directory_uri() . '/scripts/gsap/scrolltrigger.min.js' );
        }
		wp_enqueue_script( 'lenis', '//cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.19/bundled/lenis.min.js' );
        if (file_exists(get_stylesheet_directory() . '/scripts/main.js')) {
           wp_enqueue_script( 'meo-child-theme-js', get_stylesheet_directory_uri() . '/scripts/main.js' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'meo_scripts_and_styles', 999 );

/******************************************************************************
    SHORTCODE MENTIONS LÉGALES
******************************************************************************/
function function_shortcode_mention_legales() {
    ob_start();
    $meondd = getenv('HTTP_HOST');
    $meobloginfos = htmlentities(get_bloginfo('name'));
    echo html_entity_decode(file_get_contents('https://www.meosis.fr/mentions-rgpd-full.php?name='.urlencode($meobloginfos).'&ndd='.$meondd.''), ENT_HTML5);
    return ob_get_clean();
}
add_shortcode('mention_legales_shortcode', 'function_shortcode_mention_legales');


/******************************************************************************
    ARTICLES CONNEXES
******************************************************************************/
function add_meosis_post_type() {
	// On ajoute les "Articles Meosis" uniquement pour les admin
	register_post_type( 'meosis', array(
		'labels'              => array( /* Labels du Post Type */
			'name'               => 'Articles connexes',
			'singular_name'      => 'Articles connexes',
			'all_items'          => 'Tous les Articles connexes',
			'add_new'            => 'Nouveau',
			'add_new_item'       => 'Nouvel Article connexe',
			'edit'               => 'Modifier',
			'edit_item'          => 'Modifier Article connexe',
			'new_item'           => 'Nouvel Article connexe',
			'view_item'          => 'Voir l\'Article connexe',
			'search_items'       => 'Rechercher un Article connexe',
			'not_found'          =>  'Aucun résultat dans la BDD.',
			'not_found_in_trash' => 'La corbeille est vide',
			'parent_item_colon'  => ''
		),
		'description'         => 'Listes des Articles connexes',
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'query_var'           => true,
		'show_in_nav_menus'	  => true,
		'menu_position'       => 6,
		'menu_icon'           => 'dashicons-share',
		'rewrite'             => array( 'slug' => 'articles', 'with_front' => false ), /* Slug du Post Stype  */
		'has_archive'         => 'articles', /* Active les archives pour les Post Type + Slug des archives */
		'capability_type'     => 'post',
		'hierarchical'        => false,
		/* Activer ce qui est nécessaire pour ce Réalisation */
		'supports'            => array( 'title', 'editor', /*'author',*/ 'thumbnail', 'excerpt', /*'trackbacks', 'custom-fields', 'comments',*/ 'revisions', 'sticky' )
 	));
}
// Ajoute l'action dans le WordPress init
add_action( 'init', 'add_meosis_post_type');

if( !current_user_can( 'administrator' ) ){
	add_action('admin_menu', 'delete_meosis_menu_items'); //Menu
}
if( !function_exists( 'delete_meosis_menu_items' ) ){
	function delete_meosis_menu_items() {
		remove_menu_page('edit.php?post_type=meosis'); // Meosis CPT
	}
}


/******************************************************************************
    AJOUT DU MENU D'INFORMATIONS
******************************************************************************/

if( function_exists('acf_add_options_page') ) {
    add_meosis_post_type(array(
        'page_title'    => 'Informations',
        'menu_title'    => 'Informations',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false,
        'icon_url'      => 'dashicons-admin-users',
        'position'      => 2
    ));
}

/******************************************************************************
    AJOUT DU .HTML DANS LES PERMALIENS POUR OPIMISER LE SEO
******************************************************************************/
add_action('init', 'html_page_permalink', -1);
register_activation_hook(__FILE__, 'active');
register_deactivation_hook(__FILE__, 'deactive');


function html_page_permalink() {
	global $wp_rewrite;
	if ( !strpos($wp_rewrite->get_page_permastruct(), '.html')){
		$wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
	}
}
add_filter('user_trailingslashit', 'no_page_slash',66,2);
function no_page_slash($string, $type){
	global $wp_rewrite;
	if ($wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes==true && $type == 'page'){
		return untrailingslashit($string);
	}else{
		return $string;
	}
}

function active() {
	global $wp_rewrite;
	if ( !strpos($wp_rewrite->get_page_permastruct(), '.html')){
		$wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
 	}
  	$wp_rewrite->flush_rules();
}	
function deactive() {
	global $wp_rewrite;
	$wp_rewrite->page_structure = str_replace(".html","",$wp_rewrite->page_structure);
	$wp_rewrite->flush_rules();
}

/******************************************************************************
    AUTRES AJOUTS
******************************************************************************/

// Désactivation de Gutemberg
add_filter('use_block_editor_for_post', '__return_false', 10);

// Placer le block yoast en bas des pages du backoffice
function yoasttobottom() {
    return 'low';
}

// Scroll automatique vers le formulaire après l'envoi
add_filter( 'gform_confirmation_anchor', '__return_true' );

// Supprimer la barre d'administration sur le front
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	show_admin_bar(false);
}
