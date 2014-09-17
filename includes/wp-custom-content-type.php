<?php

if ( ! interface_exists( 'ContentType' ) ) {
    interface ContentType {
        /**
         * Constructor
         */
        public function __construct( $key, $options = array() );
    }
}

class WP_ContentType implements ContentType {
    /**
     * Custom Content Type key
     * @var string
     */
    protected $key;

    /**
     * Nonce value
     * @var string
     */
    protected $nonce;

    /**
     * Constructor
     * @param string $key     key for this custom content type
     * @param array  $options
     */
    public function __construct( $key, $options = array() ) {
        $this->key = $key;
        $this->nonce = $key . 'nonce';
        $this->title = $options['title'];

        if ( $this->title ) {
            add_filter( 'manage_edit-' . $this->key . '_columns', array( $this, 'rename_title_column' ) );

        }
    }

    /**
     * Renames title column to specifed title
     * @param  array $columns WP admin columns
     * @return array          updated columns
     */
    public function rename_title_column( $columns ) {
            $columns['title'] = __( $this->title );

            return $columns;

        }
}