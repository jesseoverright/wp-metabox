<?php

if ( ! interface_exists( 'ContentType' ) ) {
    interface ContentType {
        /**
         * Constructor
         */
        public function __construct( $key, $args = array() );
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

    protected $singular_name;
    protected $plural_name;

    /**
     * Constructor
     * @param string $key     key for this custom content type
     * @param array  $args
     */
    public function __construct( $key, $args = array() ) {
        $this->key = $key;
        $this->nonce = $key . 'nonce';
        $this->title = $args['title'];

        $this->set_singular_plural_names( $args['singular'], $args['plural']);

        $defaults = array(
            'labels' => array(
                'name' => __( $this->plural_name ),
                'singular_name' => __( $this->singular_name ),
                'add_new' => __( 'Add New' ),
                'add_new_item' => __( 'Add New ' . $this->singular_name ),
                'edit' => __( 'Edit' ),
                'edit_item' => __( 'Edit ' . $this->singular_name ),
                'new_item' => __( 'New ' . $this->singular_name ),
                'view' => __( 'View' ),
                'view_item' => __( 'View ' . $this->singular_name ),
                'search_items' => __('Search ' . $this->plural_name ),
                'not_found' => __( 'No ' . strtolower( $this->plural_name ) . ' found' ),
                'not_found_in_trash' => __( 'No ' . strtolower( $this->plural_name ) . ' found in Trash' ),
            ),
            'description' => $this->plural_name . ' Custom Content Type',
            'public' => true,
            '_builtin' => false,
            'menu_position' => 5,
            'supports' => array( 'title', 'editor', 'revisions', 'thumbnail' ) ,
            'show_in_nav_menus' => true,
            'rewrite' => array( 'with_front' => false, 'slug' => $key ) );

        // set defaults to register post type
        $args = wp_parse_args( $args, $defaults );

        // register the content type
        register_post_type( $this->key, $args );

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

    protected function set_singular_plural_names( $singular = '', $plural = '') {
        if ( ! $singular ) {
            if ( $this->title ) {
                $this->singular_name = $this->title;
            } else {
                $this->singular_name = $this->key;
            }
        } else {
            $this->singular_name = $singular;
        }

        if ( ! $plural ) {
            $this->plural_name = $this->singular_name . 's';
        } else {
            $this->plural_name = $plural;
        }
    }
}