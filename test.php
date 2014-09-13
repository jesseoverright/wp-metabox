<?php

#include_once( dirname( __FILE__ ) . '/lib/wp-content-types.php' );

class Test_Metabox extends WP_Metabox {
    public function __construct( PostMetaFactory $post_meta_factory, $options = array() ) {
        parent::__construct( $post_meta_factory, $options );

        $this->metadata['test'] = $post_meta_factory->create( 'test' );
        $this->metadata['select'] = $post_meta_factory->create(
            'select',
            array(
                'type' => 'select',
                'choices' => array(
                    1 => 'one',
                    2 => 'two',
                    3 => 'three',
                )
            )
        );
        $this->metadata['image_uload'] = $post_meta_factory->create( 'image_upload', array( 'type' => 'media' ) );

        add_filter( 'the_content' , array($this, 'display') );
    }

    public function display( $content ) {
        global $post;
        if (get_post_meta($post->ID, 'test' ,true) != '') {
            $content = get_post_meta($post->ID,'test',true) . $content;
        }
        return $content;
    }
}

$test = new Test_Metabox( WP_PostMetaFactory::get_instance(), array(
    'name' => 'test',
    'label' => 'Test',
    'posttype' => 'test-content-type',
));

/**
 * Portfolio Content Type
 * custom content type to define Test Content Types and specific details related to them.
 * uses custom taxonomy, featured images, and custom backend displays.
 */
class Test_Content_Type { #extends WP_ContentType {
    var $nonce_action = 'test-content-type';

    function __construct( $key = 'test-content-type' ) {
        #parent::__construct( $key );
        $this->key = $key;

        register_post_type( $this->key , array(
            'labels' => array(
                'name' => __( 'Test'),
                'singular_name' => __( 'Test Content Type' ),
                'add_new' => __( 'Add New' ),
                'add_new_item' => __( 'Add New Test Content Type' ),
                'edit' => __( 'Edit' ),
                'edit_item' => __( 'Edit Test Content Type' ),
                'new_item' => __( 'New Test Content Type' ),
                'view' => __( 'View' ),
                'view_item' => __( 'View Test Content Type' ),
                'search_items' => __('Search Test' ),
                'not_found' => __( 'No Test Content Types found' ),
                'not_found_in_trash' => __( 'No test items found in Trash' ),
            ),
            'description' => 'Test content types',
            'public' => true,
            '_builtin' => false,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-portfolio',
            'supports' => array('title', 'editor', 'revisions', 'thumbnail'),
            'show_in_nav_menus' => true,
            'rewrite' => array('with_front' => false, 'slug' => 'test')
        ));

        $this->metaboxes['project-url'] = new WP_SimpleMetabox( WP_PostMetaFactory::get_instance(), array (
            'name' => 'project-url',
            'label' => 'Project URL',
            'posttype' => $this->key
            )
        );
        $this->metaboxes['project-url'] = new WP_SimpleMetabox( WP_PostMetaFactory::get_instance(), array (
            'name' => 'project-date',
            'label' => 'Project Date',
            'posttype' => $this->key
            )
        );

    }

}

function init_test_content_type() {
    global $test_content_type;
    $test_content_type = new Test_Content_Type();
}

add_action( 'init', 'init_test_content_type');