<?php

if ( ! interface_exists( 'PostMetaFactory' ) ) {
    interface PostMetaFactory {
        /**
         * Singleton get instance
         * @return PostMetaFactory object
         */
        public static function get_instance();

        /**
         * Creates a post meta type
         * @param  string $key     key for this post meta
         * @param  array  $args
         * @return WP_PostMeta          the post meta object
         */
        public function create( $key, $args = array() );
    }
}

class WP_PostMetaFactory implements PostMetaFactory {

    /**
     * The factory instance
     * @var WP_PostMetaFactory
     */
    protected static $instance;

    /**
     * Postmeta types registered to this factory
     * @var array
     */
    protected $registered_postmeta_types = array();

    /**
     * Disables construct functionality from outside object
     */
    protected function __construct() {
        # register default post meta types
        $this->registered_postmeta_types = array (
            'url'           => 'WP_URLMeta',
            'select'        => 'WP_SelectMeta',
            'checkbox'      => 'WP_CheckboxMeta',
            'radio'         => 'WP_RadioCheckbox',
            'textarea'      => 'WP_TextareaMeta',
            'media'         => 'WP_MediaMeta',
            'image'         => 'WP_MediaMeta',
            'ordered'       => 'WP_OrderedListMeta',
            'ordered-list'  => 'WP_OrderedListMeta',
            'int'           => 'WP_NumberMeta',
            'number'        => 'WP_NumberMeta',
            'text'          => 'WP_TextMeta'
        );
    }

    /**
     * Singleton get instance
     * @return WP_PostMetaFactory the object
     */
    public static function get_instance() {
        if ( !isset( self::$instance ) ) {
            $class = __CLASS__;
            self::$instance = new $class();
        }

        return self::$instance;
    }

    /**
     * Creates a post meta type based on option type
     * @param  string $key     key for this post meta
     * @param  array  $args
     * @return WP_PostMeta          the post meta object
     */
    public function create( $key, $args = array() ) {

        if ( array_key_exists( 'type', $args ) ) {
            $meta_type = $args['type'];     
        } else {
            $meta_type = 'text';
        }

        # if new post meta types have been registered, check for type
        if ( array_key_exists( $meta_type, $this->registered_postmeta_types ) ) {

            $PostMeta = new $this->registered_postmeta_types[ $meta_type ]( $key, $args );

        } else {

            $PostMeta = new WP_PostMeta( $key, $args );
            
        }

        return $PostMeta;
    }

    /**
     * Registers a new post meta type for this post meta factory
     * @param  string $type                the type of this posttype
     * @param  WP_PostMeta $postmeta_class_name the class name of the custom PostMeta class
     */
    public function register_postmeta_type( $type, $postmeta_class_name ) {
        $this->registered_postmeta_types[ $type ] = $postmeta_class_name;
    }
}