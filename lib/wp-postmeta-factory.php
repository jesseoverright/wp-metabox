<?php

if ( ! interface_exists( 'PostMetaFactory' ) ) {
    interface PostMetaFactory {
        public static function create( $key, $options = array() );
    }
}

class WP_PostMetaFactory implements PostMetaFactory {

    public static function create( $key, $options = array() ) {

        if ( $options['type'] ) $type = $options['type']; else $type = 'text';
    
        switch ( $meta_type ) {
            case 'url':
                            $PostMeta = new WP_URLMeta( $key, $options );
                            break;
            case 'select':
                            $PostMeta = new WP_SelectMeta( $key, $options );
                            break;
            case 'text':
            case 'int':
            default:
                            $PostMeta = new WP_TextMeta( $key, $options );
        }

        return $PostMeta;
    }
}