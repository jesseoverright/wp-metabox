<?php

/**
 * Example usage of a custom metabox with several postmeta types included
 */
class Test_Metabox extends WP_Metabox {
    public function __construct( $key, PostMetaFactory $post_meta_factory, $options = array() ) {
        parent::__construct( $key, $post_meta_factory, $options );

        # A basic text box called 'test'
        $this->metadata['test'] = $post_meta_factory->create( 'test' );

        # A select menu with the following options: one, two, three
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

        # an image upload using the media uploader
        $this->metadata['image_upload'] = $post_meta_factory->create( 'image_upload', array( 'type' => 'media' ) );

        # a second image upload
        $this->metadata['second_upload'] = $post_meta_factory->create( 'second_upload', array( 'type' => 'media', 'label' => 'Second Upload' ) );


        add_filter( 'the_content' , array($this, 'display') );
    }

    # displays some of the metadata automatically on the content
    public function display( $content ) {
        global $post;
        if (get_post_meta($post->ID, 'test' ,true) != '') {
            $content = get_post_meta($post->ID,'test',true) . $content;
        }
        return $content;
    }
}

# add the Test_Metabox to the test-content-type content type
$test = new Test_Metabox(
    'test',
    WP_PostMetaFactory::get_instance(),
    array(
        'label' => 'Test',
        'posttype' => 'test-content-type',
    )
);

/**
 * Custom Content Type
 * Sample custom content type using WP Metabox to create custom postmeta boxes
 */
class Test_Content_Type extends WP_ContentType {

    function __construct( $key = 'test-content-type', $options = array() ) {
        parent::__construct( $key, $options );

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

        # creates a simple metabox with one url input using WP_SimpleMetabox
        $this->metaboxes['project-url'] = new WP_SimpleMetabox( 'project-url', WP_PostMetaFactory::get_instance(), array (
            'label' => 'Project URL',
            'type' => 'url',
            'posttype' => $this->key
            )
        );

        # create another simple metabox with one text input using WP_SimpleMetabox
        $this->metaboxes['project-date'] = new WP_SimpleMetabox( 'project-date', WP_PostMetaFactory::get_instance(), array (
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