<?php
//-----------------------------------------------------
// themo_room_custom_post_type
// Room Post Type
//-----------------------------------------------------
aloha_load_wp_options();
use IgniteKit\WP\OptionBuilder\Framework;

if ( ! function_exists('themo_room_custom_post_type') ) {

    // Register Custom Post Type
    function themo_room_custom_post_type() {

        $labels = array(
            'name'                => _x( 'Rooms', 'Post Type General Name', ALOHA_DOMAIN ),
            'singular_name'       => _x( 'Room', 'Post Type Singular Name', ALOHA_DOMAIN ),
            'menu_name'           => __( 'Rooms', ALOHA_DOMAIN ),
            'parent_item_colon'   => __( 'Parent Room:', ALOHA_DOMAIN ),
            'all_items'           => __( 'All Rooms', ALOHA_DOMAIN ),
            'view_item'           => __( 'View Room', ALOHA_DOMAIN ),
            'add_new_item'        => __( 'Add New Rooms', ALOHA_DOMAIN ),
            'add_new'             => __( 'Add New', ALOHA_DOMAIN ),
            'edit_item'           => __( 'Edit Room', ALOHA_DOMAIN ),
            'update_item'         => __( 'Update Room', ALOHA_DOMAIN ),
            'search_items'        => __( 'Search Room', ALOHA_DOMAIN ),
            'not_found'           => __( 'Not found', ALOHA_DOMAIN ),
            'not_found_in_trash'  => __( 'Not found in Trash', ALOHA_DOMAIN ),
        );

        if ( function_exists( 'get_theme_mod' ) ) {
            $custom_slug = get_theme_mod( 'themo_room_rewrite_slug', 'room' );
        } else {
            $custom_slug = 'room';
        }

        $rewrite = array(
            'slug'                => $custom_slug,
            'with_front'          => false,
            'pages'               => true,
            'feeds'               => true,
        );
        $args = array(
            'label'               => __( 'themo_room', ALOHA_DOMAIN ),
            'description'         => __( 'Rooms', ALOHA_DOMAIN ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'post-formats', ),
            'taxonomies'          => array( 'themo_room_type' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-admin-network',
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'rewrite'             => $rewrite,
            'capability_type'     => 'post',
        );
        register_post_type( 'themo_room', $args );

    }

    // Hook into the 'init' action
    add_action( 'init', 'themo_room_custom_post_type', 0 );

}

//-----------------------------------------------------
// themo_room_type
// Project Types Taxonomy
//-----------------------------------------------------

if ( ! function_exists( 'themo_room_type' ) ) {

    // Register Custom Taxonomy
    function themo_room_type() {

        $labels = array(
            'name'                       => _x( 'Room Types', 'Taxonomy General Name', ALOHA_DOMAIN ),
            'singular_name'              => _x( 'Room Type', 'Taxonomy Singular Name', ALOHA_DOMAIN ),
            'menu_name'                  => __( 'Room Types', ALOHA_DOMAIN ),
            'all_items'                  => __( 'All Room Types', ALOHA_DOMAIN ),
            'parent_item'                => __( 'Parent Room Type', ALOHA_DOMAIN ),
            'parent_item_colon'          => __( 'Parent Room Type:', ALOHA_DOMAIN ),
            'new_item_name'              => __( 'New Room Type Name', ALOHA_DOMAIN ),
            'add_new_item'               => __( 'Add New Room Type', ALOHA_DOMAIN ),
            'edit_item'                  => __( 'Edit Room Type', ALOHA_DOMAIN ),
            'update_item'                => __( 'Update Room Type', ALOHA_DOMAIN ),
            'separate_items_with_commas' => __( 'Separate Room Type with commas', ALOHA_DOMAIN ),
            'search_items'               => __( 'Search Room Types', ALOHA_DOMAIN ),
            'add_or_remove_items'        => __( 'Add or remove Room type', ALOHA_DOMAIN ),
            'choose_from_most_used'      => __( 'Choose from the most Room types', ALOHA_DOMAIN ),
            'not_found'                  => __( 'Not Found', ALOHA_DOMAIN ),
        );
        $rewrite = array(
            'slug'                       => 'room-type',
            'with_front'                 => true,
            'hierarchical'               => true,
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'rewrite'                    => $rewrite,
        );
        register_taxonomy( 'themo_room_type', array( 'themo_room' ), $args );

    }

    // Hook into the 'init' action
    add_action( 'init', 'themo_room_type', 0 );

}


add_action( 'admin_init', 'th_register_room_meta_boxes' );

function th_register_room_meta_boxes()
{

    //-----------------------------------------------------
    // Page Layout, Sidebar, Content Editor Sort Order
    //-----------------------------------------------------
    $framework = new Framework();
    $framework->register_metabox( array(
        'id' => 'th_rooms_meta_box',
        'title' => __('Room Grid Options', ALOHA_DOMAIN),
        'pages' => array('themo_room'),
        'context' => 'normal',
        'priority' => 'default',
        'fields' => array(
            // START PAGE LAYOUT META BOX

            array(
                'id' => 'th_room_highlight',
                'label' => 'Highlight',
                'type' => 'text',
                'desc' => __('Displayed at the very top in small text. 1 - 3 words recommended', ALOHA_DOMAIN),
            ),
            array(
                'id' => 'th_room_title',
                'label' => 'Title',
                'type' => 'text',
                'desc' => __('Defaults to the page title.', ALOHA_DOMAIN),
            ),
            array(
                'id' => 'th_room_intro',
                'label' => 'Intro',
                'type' => 'text',
                'desc' => __('Displayed below the title. 8 - 10 words recommended', ALOHA_DOMAIN),
            ),
            array(
                'id'    => "th_room_price",
                'label'  =>  'Price',
                'type'  => 'text',
                'desc' => __('Displayed below the title. e.g.: $99', ALOHA_DOMAIN),
            ),
            array(
                'id'    => "th_room_price_before",
                'label'  =>  'Price before',
                'type'  => 'text',
                'desc' => __('Displayed before the price. e.g.: Starting from', ALOHA_DOMAIN),
            ),
            array(
                'id'    => "th_room_price_per",
                'label'  =>  'Price per',
                'type'  => 'text',
                'desc' => __('Displayed after the price. e.g.: /night', ALOHA_DOMAIN),
            ),
            array(
                'id'    => "th_room_location",
                'label'  =>  'Location',
                'type'  => 'text',
                'desc' => __('e.g.: 2 km away from the center', ALOHA_DOMAIN),
            ),
            array(
                'id'    => "th_room_location_link",
                'label'  =>  'Location Link',
                'type'  => 'text',
                'desc' => __('e.g.: A google maps link', ALOHA_DOMAIN),
            ),
            array(
                'id'          => 'th_room_rating',
                'label'       => __( 'Rating', ALOHA_DOMAIN ),
                'desc'        => __( 'e.g: 4.5', ALOHA_DOMAIN ),
                'type'        => 'numeric-slider',
                'min_max_step'=> '0,5,0.5',
            ),

            array(
                'id' => 'th_room_button_text',
                'label' => 'Button Text',
                'type' => 'text',
                'desc' => __('Displayed below the intro.', ALOHA_DOMAIN),
            ),
            array(
                'id'          => "th_room_thumb",
                'label'       => __( 'Alternative Grid Image', ALOHA_DOMAIN),
                'type'        => 'upload',
                'class'       => 'ot-upload-attachment-id',
                'desc' => 'Helpful when using the "Image Format". The theme will use the Alternative Image for the room grid and the Featured Image for the lightbox.',
            ),
            array(
                'id'          => 'th_gallery',
                'label'       => __( 'Gallery', ALOHA_DOMAIN),
                'desc'        => __( 'This will replace the featured image', ALOHA_DOMAIN),
                'type'        => 'gallery',
            ),
            array(
                'id'          => 'th_room_icons_ordering',
                'label'       => __( 'Icons Ordering', ALOHA_DOMAIN),
                'type'        => 'text',
                'class'       => 'icon-hidden',
           ),
           array(
                'id'          => 'th_room_icons',
                'label'       => __( 'Icons', ALOHA_DOMAIN),
                'desc'        => 'Add icons for the listings',
                'type'        => 'th_room_icons',
           ), 
            
            // END PAGE LAYOUT META BOX
        )
    ));

}

function jt_get_allowed_project_formats() {

    return array( 'image','link' );
}

add_action( 'load-post.php',     'jt_post_format_support_filter' );
add_action( 'load-post-new.php', 'jt_post_format_support_filter' );
add_action( 'load-edit.php',     'jt_post_format_support_filter' );

function jt_post_format_support_filter() {

    $screen = get_current_screen();

    // Bail if not on the projects screen.
    if ( empty( $screen->post_type ) ||  $screen->post_type !== 'themo_room' )
        return;

    // Check if the current theme supports formats.
    if ( current_theme_supports( 'post-formats' ) ) {

        $formats = get_theme_support( 'post-formats' );

        // If we have formats, add theme support for only the allowed formats.
        if ( isset( $formats[0] ) ) {
            $new_formats = array_intersect( $formats[0], jt_get_allowed_project_formats() );

            // Remove post formats support.
            remove_theme_support( 'post-formats' );

            // If the theme supports the allowed formats, add support for them.
            if ( $new_formats )
                add_theme_support( 'post-formats', $new_formats );
        }
    }

    // Filter the default post format.
    add_filter( 'option_default_post_format', 'jt_default_post_format_filter', 95 );
}

function jt_default_post_format_filter( $format ) {

    return in_array( $format, jt_get_allowed_project_formats() ) ? $format : 'standard';
}

// hide any extra fields.
if ( ! function_exists( 'th_hide_meta_box' ) ) {
    function th_hide_meta_box($hidden, $screen)
    {
        //make sure we are dealing with the correct screen
        if ('themo_room' == $screen->post_type) {

            //lets hide everything
            $hidden = array('postexcerpt', 'slugdiv', 'postcustom', 'trackbacksdiv', 'commentstatusdiv', 'commentsdiv', 'authordiv', 'revisionsdiv');
            //$hidden[] = 'my_custom_meta_box';//for custom meta box, enter the id used in the add_meta_box() function.
        }
        return $hidden;
    }
    add_filter('default_hidden_meta_boxes','th_hide_meta_box',10,2);
}

// Turn off commenting by default.

function th_default_comments_off( $data ) {
    if( $data['post_type'] == 'themo_room' && $data['post_status'] == 'auto-draft' ) {
        $data['comment_status'] = 0;
    }

    return $data;
}
add_filter( 'wp_insert_post_data', 'th_default_comments_off' );