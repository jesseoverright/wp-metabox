<?php

if ( ! interface_exists( 'ContentType' ) ) {
    interface ContentType {
        public function __construct( $key, $options = array() );
    }
}

class WP_ContentType implements ContentType {
    protected $key;
    protected $nonce;

    public function __construct( $key, $options = array() ) {
        $this->key = $key;
        $this->nonce = $key . 'nonce';
        $this->title = $options['title'];

        if ( $this->title ) {
            add_filter( 'manage_edit-' . $this->key . '_columns', array( $this, 'rename_title_column' ) );

        }
    }

    public function rename_title_column( $columns ) {
            $columns['title'] = __( $this->title );

            return $columns;

        }
}