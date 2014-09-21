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
         * @param  array  $options
         * @return WP_PostMeta          the post meta object
         */
        public function create( $key, $options = array() );
    }
}

class WP_PostMetaFactory implements PostMetaFactory {

    /**
     * The factory instance
     * @var WP_PostMetaFactory
     */
    protected static $instance;

    /**
     * Custom registered postmeta
     * @var array
     */
    protected $registered_postmeta = array();

    /**
     * Disables construct functionality from outside object
     */
    protected function __construct() {
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
     * @param  array  $options
     * @return WP_PostMeta          the post meta object
     */
    public function create( $key, $options = array() ) {

        if ( $options['type'] ) $meta_type = $options['type']; else $meta_type = 'text';

        # if new post meta types have been registered, check for type
        if ( array_key_exists( $meta_type, $this->registered_postmeta ) ) {

            $PostMeta = new $this->registered_postmeta[ $meta_type ]( $key, $options );

        } else {

            switch ( $meta_type ) {
                case 'url':
                    $PostMeta = new WP_URLMeta( $key, $options );
                    break;
                case 'select':
                    $PostMeta = new WP_SelectMeta( $key, $options );
                    break;
                case 'textarea':
                    $PostMeta = new WP_TextareaMeta( $key, $options );
                    break;
                case 'media':
                case 'image':
                    $PostMeta = new WP_MediaMeta( $key, $options );
                    break;
                case 'ordered':
                case 'ordered-list':
                    $PostMeta = new WP_OrderedListMeta( $key, $options );
                    break;
                case 'int':
                case 'number':
                    $PostMeta = new WP_NumberMeta( $key, $options );
                    break;
                case 'text':
                default:
                    $PostMeta = new WP_TextMeta( $key, $options );
            }
        }

        return $PostMeta;
    }

    /**
     * Registers a new post meta type for this post meta factory
     * @param  string $type                the type of this posttype
     * @param  WP_PostMeta $postmeta_class_name the class name of the custom PostMeta class
     */
    public function register_posttype( $type, $postmeta_class_name ) {
        $this->registered_postmeta[ $type ] = $postmeta_class_name;
    }
}