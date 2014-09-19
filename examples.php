<?php

/**
 * Example usage of a custom metabox with several postmeta types included
 */
class Example_Metabox extends WP_Metabox {
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

# add the Example_Metabox to the example-content-type content type
$example = new Example_Metabox(
    'test',
    WP_PostMetaFactory::get_instance(),
    array(
        'label' => 'Example Metabox',
        'posttype' => 'example-content-type',
    )
);

/**
 * Example Custom Content Type
 * Creates a sample custom content type using WP Metabox to create custom postmeta boxes
 */
class Example_Content_Type extends WP_ContentType {

    function __construct( $key = 'example-content-type', $options = array( 'singular' => 'Content Type') ) {

        # registers the post type with provided options
        parent::__construct( $key, $options );

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

function init_example_content_type() {
    global $example_content_type;
    $example_content_type = new Example_Content_Type();
}

add_action( 'init', 'init_example_content_type');