<?php

class WP_OrderedListMeta extends WP_PostMeta {
    /**
     * Constructor
     *
     * Enqueues required javascript for sortable array
     * @param string $key     key for this post meta
     * @param array  $options
     */
    public function __construct( $key, $options = array() ) {
        parent::__construct( $key, $options );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Displays input types in order with add new, remove, and sort functionality
     * @param  int $post_id  individual post id
     * @return html          input content
     */
    public function display_postmeta( $post_id ) {
        if ( ! $data ) $data = get_post_meta( $post_id, $this->key, true );
        
        if ( ! is_array( $data ) ) $data = array( 'some text', 'a test' );
        
        echo "<p>";

        $this->display_label();
        
        echo "<ul class=\"wp-metabox-ordered-list\">";

        foreach ( $data as $value ) {
            $this->display_input( $value );
        }

        $this->display_description();

        echo "</p>";

    }

    protected function display_input( $data ) {
        echo "<li class=\"wp-metabox-ordered-item\"><input type=\"{$this->input_type}\" class=\"widefat wp-metabox-input\" name=\"{$this->key}[]\" value=\"{$data}\" maxlength=\"{$this->max_length}\"></li>";
    }

    /**
     * Updates post meta for a post in WP database as a single array
     * @param  int $post_id individual post id
     * @param  $data    content
     */
    public function update( $post_id, $data ) {
        $media = array();
        if ( isset( $_POST[ $this->key . '-src' ] ) ) {
            $media[ $this->key . '-src'] = sanitize_text_field( $_POST[ $this->key . '-src'] );
        }

        if ( isset( $_POST[ $this->key . '-title'] ) ) {
            $media[ $this->key . '-title'] = sanitize_text_field( $_POST[ $this->key . '-title'] );
        }

        if ( isset( $_POST[ $this->key . '-alt'] ) ) {
            $media[ $this->key . '-alt'] = sanitize_text_field( $_POST[ $this->key . '-alt'] );
        }

        parent::update( $post_id, $media );
    }

    /**
     * Enqueues jquery sortable
     */
    public function enqueue_scripts() {
        wp_enqueue_script( 'jquery-ui-sortable' );

        wp_enqueue_script( 'wp-metabox-ordered-list-js', plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'js/ordered-list.js', array( 'jquery', 'jquery-ui-sortable' ), 'version' );

    }
}