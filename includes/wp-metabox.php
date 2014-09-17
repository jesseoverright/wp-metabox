<?php

if ( ! interface_exists( 'Metabox' ) ) {
    interface Metabox {
        /**
         * Constructor
         */
        public function __construct( PostMetaFactory $post_meta_factory, $options = array() );

        /**
         * Adds the metabox
         */
        public function add_metabox();

        /**
         * Displays the metabox
         */
        public function display_metabox();

        /**
         * Saves the data for this metabox
         */
        public function save( $post_id );
    }
}

class WP_Metabox implements Metabox {
    /**
     * Name of the metabox
     * @var string
     */
    protected $name;

    /**
     * Metadata for this metabox
     *
     * Array of WP_PostMeta objects
     * @var array
     */
    protected $metadata;

    /**
     * Label for this metabox
     * @var [type]
     */
    protected $label;

    /**
     * Post types associated with this metabox
     * @var string or array
     */
    protected $posttype;

    /**
     * The Post Meta Factory object to create post meta types
     * @var PostMetaFactory
     */
    protected $_post_meta_factory;


    /**
     * Constructor
     * @param PostMetaFactory $post_meta_factory PostMeta factory dependency
     * @param array           $options           
     */
    public function __construct( PostMetaFactory $post_meta_factory, $options = array() ) {
        $this->_post_meta_factory = $post_meta_factory;

        $this->name = $options['name'];
        $this->label = $options['label'];
        if ( $options['posttype'] ) $this->posttype = $options['posttype']; else $this->posttype = 'post';

        add_action( 'admin_init', array( $this, 'add_metabox' ) );
        add_action( 'save_post', array( $this, 'save' ) );

    }

    /**
     * Adds the metabox to the WP admin
     */
    public function add_metabox() {
        add_meta_box( $this->name, $this->label, array( $this, 'display_metabox' ), $this->posttype, 'normal', 'high');
    }

    /**
     * Displays the metabox in the WP admin
     * @return html metabox
     */
    public function display_metabox() {
        global $post;

        echo "<input type=\"hidden\" name=\"{$this->name}_nonce\" id=\"{$this->name}_nonce\" value=\"" . wp_create_nonce( $this->name . '_save' ) . "\" />"; 

        foreach ( $this->metadata as $key => $meta ) {
            $meta->display_input( $post->ID );
        }
    }

    /**
     * Saves the metabox for this post
     * @param  int $post_id WordPress post id
     * @return bool          false or save
     */
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
    /**
     * Constructor
     * @param PostMetaFactory $post_meta_factory PostMeta factory dependency
     * @param array           $options           
     */
    public function __construct( PostMetaFactory $post_meta_factory, $options = array() ) {
        parent::__construct( $post_meta_factory, $options );

        // hide the metaboxes label
        $options['label'] = 'none';
        $this->metadata[ $this->name ] = $this->_post_meta_factory->create( $this->name, $options );
    }

}