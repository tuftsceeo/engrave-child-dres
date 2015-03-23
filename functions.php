<?php

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
}

/**
 * Engrave functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link http://codex.wordpress.org/Plugin_API
 *
 * @package ThinkUpThemes
 */
// Set up the content width value based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 625;
/**
 * Engrave setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Twelve supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 */
function engrave_setup() {
	/*
	 * Makes Engrave available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Engrave, use a find and replace
	 * to change 'Engrave' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'Engrave', get_template_directory() . '/languages' );
	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();
	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
    // This theme uses wp_nav_menu() in one location.
    register_nav_menu( 'primary', __( 'Primary Menu', 'Engrave' ) );
	/*
	 * This theme supports custom background color and image,
	 * and here we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	) );
	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 400, 400 ); // Unlimited height, soft crop
}
add_action( 'after_setup_theme', 'Engrave_setup' );
/**
 * Adds an option to create new menu items from the front-end of the site.
 * @param $items
 * @param $args
 * @return mixed
 */
/*
function add_new_menu_item( $items, $args ){
    if( current_user_can( 'edit_themes' ) ){
        $edit_menu_item_args = new stdClass();
        $edit_menu_item_args->ID = 'edit';
        $edit_menu_item_args->post_type = 'nav_menu_item';
        $edit_menu_item_args->url = '#';
        $edit_menu_item_args->post_title = 'Edit Menu';
        $edit_menu_item_args->classes = array('menu-item', 'menu-item-type-custom', 'menu-item-object-custom', 'sp-menu-not-sortable' );
        $edit_menu_item_args->title = '<span id="sp-edit-menu" title="Edit menu" alt="Edit menu"></span>';
        $edit_menu_item_args->menu_order = 0; // Redefine the order
        $edit_menu_item_args->guid = '#';
        $edit_menu_item_args->object = 'custom';
        $edit_menu_item_args->type = 'custom';
        $edit_menu_item_args->type_label = 'Custom';
        $edit_new_menu_item = new WP_Post( $edit_menu_item_args );
        array_unshift( $items, $edit_new_menu_item );
        if( $_GET['edit_menu'] ){
            foreach( array_slice($items, 1) as $item ){
                $item->title .= ' <span id="sp-edit-menu-delete" title="Delete Menu Item" alt="Delete Menu Item"></span>';
            }
        }
    }
    return $items;
}
add_filter( 'wp_nav_menu_objects', 'add_new_menu_item', 10, 2 );
*/
/**
 * Add a menu item to the menu that displays user avatar + login/logout links. Floats menu item to the right.
 * @param $items
 * @return string
 */
function login_menu_item( $items ){
    global $current_user;
    if( is_user_logged_in() ){
        $login_item = '<li id="menu-item-login" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-login sp-menu-not-sortable">';
            $login_item .= '<a href="' . get_author_posts_url($current_user->ID) . '">';
                $login_item .=  get_avatar($current_user->ID, 16) . ' Welcome ' . $current_user->first_name . '!';
            $login_item .= '</a>';
        $login_item .= '<ul class="sub-menu sp-menu-not-sortable">';
            $login_item .= '<li id="menu-item-logout" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-logout sp-menu-not-sortable">';
                $login_item .= '<a href="' . get_author_posts_url($current_user->ID) . '">';
                    $login_item .= 'My Profile';
                $login_item .= '</a>';
            $login_item .= '</li>';
            $login_item .= '<li id="menu-item-logout" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-logout sp-menu-not-sortable">';
                $login_item .= '<a href="' . wp_logout_url() . '&redirect_to=' . home_url() .'">';
                    $login_item .= 'Logout';
                $login_item .= '</a>';
            $login_item .= '</li>';
            if( current_user_can( 'edit_dashboard' ) ){
                $login_item .= '<li id="menu-item-logout" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-logout sp-menu-not-sortable">';
                    $login_item .= '<a href="' . admin_url() . '" target="_new">';
                        $login_item .= 'Dashboard';
                    $login_item .= '</a>';
                $login_item .= '</li>';
            }
            $login_item .= '</ul>';
        $login_item .= '</li>';
    }else{
        $login_item = '<li id="menu-item-login" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item-login">';
            $login_item .= '<a href="' . wp_login_url() . '?redirect_to=' . site_url() .'">';
                $login_item .=  'Login';
            $login_item .= '</a>';
        $login_item .= '</li>';
    }
    return $items . $login_item;
}
add_filter( 'wp_nav_menu_items', 'login_menu_item' );

function Engrave_widgets_init() {
	$sp_cat_sidebar_id = register_sidebar( array(
		'name' => __( 'Category Header Widget Area', 'Engrave' ),
		'id' => 'sidebar-4',
		'description' => __( 'Appears in every category page in the header area below the category title and description', 'Engrave' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	/**
	 * Add SmartPost global definitions to be used by SP plugin (if it is available)
	 */
	define('SP_CAT_SIDEBAR', $sp_cat_sidebar_id);
}
add_action( 'widgets_init', 'Engrave_widgets_init' );

/**
 * Keeps sidebars consistent on empty category pages (i.e. categories with no posts).
 */
function engrave_fix_empty_category_sidebar_issue(){
	global $post;
	if( is_null( $post ) && is_archive() ){
		$post = new stdClass();
		$post->post_type = 'post';
	}
}
add_action( 'wp_enqueue_scripts', 'engrave_fix_empty_category_sidebar_issue', '10' );