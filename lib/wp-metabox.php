<?php

if ( ! interface_exists( 'Metabox' ) ) {
    interface Metabox {
        public function __construct( PostMetaFactory $post_meta_factory, $options = array() );

        public function add_metabox();

        public function display_metabox();

        public function save( $post_id );
    }
}

class WP_Metabox implements Metabox {
    protected $name;
    protected $metadata;
    protected $label;
    protected $posttype;
    protected $_post_meta_factory;


    public function __construct( PostMetaFactory $post_meta_factory, $options = array() ) {
        $this->_post_meta_factory = $post_meta_factory;

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

        echo "<input type=\"hidden\" name=\"{$this->name}_nonce\" id=\"{$this->name}_nonce\" value=\"" . wp_create_nonce( $this->name . '_save' ) . "\" />"; 

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
    public function __construct( PostMetaFactory $post_meta_factory, $options = array() ) {
        parent::__construct( $post_meta_factory, $options );

        // hide the metaboxes label
        $options['label'] = 'none';
        $this->metadata[ $this->name ] = $this->_post_meta_factory->create( $this->name, $options );
    }

}