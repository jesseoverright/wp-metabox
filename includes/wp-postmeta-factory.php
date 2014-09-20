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
    private static $instance;

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

        return $PostMeta;
    }
}