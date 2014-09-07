<?php

interface Metabox {
    public function __construct( $options = array() );

    public function add_metabox();

    public function display_metabox();

    public function save( $post_id );
}

class WP_Metabox {
    protected $name;
    protected $metadata;
    protected $label;
    protected $posttype;


    public function __construct( $options = array() ) {
        $this->name = $options['name'];
        $this->label = $options['label'];
        if ( $options['posttype'] ) $this->posttype = $options['posttype']; else $this->posttype = 'post';

        add_action( 'admin_init', array( $this, 'add_metabox' ) );
        add_action( 'save_post', array( $this, 'save' ) );

    }

    public function add_metabox() {
        add_meta_box( $this->name, $this->label, array( $this, 'display_metabox' ), $this->posttype, 'normal', 'high');
    }

    public function display_metabox() {
        global $post;

        echo '<input type="hidden" name="' . $this->name . '_nonce" id="' . $this->name . '_nonce" value="' . wp_create_nonce( $this->name . '_save' ) . '" />';

        foreach ( $this->metadata as $key => $meta ) {
            $meta->display_input( $post->ID );
        }
    }


    public function save( $post_id ) {    
        if ( !wp_verify_nonce( $_POST[ $this->name . '_nonce'], $this->name . '_save' ) )
            return false;

        if ( !current_user_can( 'edit_post', $post_id )) {
            return false;
        }

        foreach ( $this->metadata as $key => $meta ) {
            $meta->update( $post_id, $_POST[ $key ] );
        }
      
    }
    
}

class WP_SimpleMetabox extends WP_Metabox {
    public function __construct( $options ) {
        parent::__construct( $options );
        $this->metadata[ $this->name ] = WP_PostMetaFactory::create( $this->name, array( 'label' => 'none' ) );
    }

}