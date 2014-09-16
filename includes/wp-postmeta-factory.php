<?php

if ( ! interface_exists( 'PostMetaFactory' ) ) {
    interface PostMetaFactory {

        public static function get_instance();

        public function create( $key, $options = array() );
    }
}

class WP_PostMetaFactory implements PostMetaFactory {

    private static $instance;

    protected function __construct() {
    }

    public static function get_instance() {
        if ( !isset( self::$instance ) ) {
            $class = __CLASS__;
            self::$instance = new $class();
        }

        return self::$instance;
    }

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
                $PostMeta = new WP_MediaMeta( $key, $options );
                break;
            case 'text':
            case 'int':
            default:
                $PostMeta = new WP_TextMeta( $key, $options );
        }

        return $PostMeta;
    }
}