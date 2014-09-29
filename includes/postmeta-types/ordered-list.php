<?php

class WP_OrderedListMeta extends WP_PostMeta {
    /**
     * Constructor
     *
     * Enqueues required javascript for sortable array
     * @param string $key     key for this post meta
     * @param array  $args
     */
    public function __construct( $key, $args = array() ) {
        parent::__construct( $key, $args );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Displays input types in order with add new, remove, and sort functionality
     * @param  int $post_id  individual post id
     * @return html          input content
     */
    public function display_postmeta( $post_id ) {
        if ( ! $data ) $data = get_post_meta( $post_id, $this->key, true );
        
        $data[] = '';
        
        echo "<p>";

        $this->display_label();

        echo "</p><ul class=\"wp-metabox-ordered-list\">";

        foreach ( $data as $value ) {
            $this->display_item( $value );
        }

        echo "</ul><p>";

        echo "<button class=\"button button-large wp-metabox-add-new\">Add New</button>";

        $this->display_description();

        echo "</p>";

    }

    /**
     * Displays individual input items and including remove and sort features
     * @param  $data content
     * @return data input wrapped in sortable markup
     */
    protected function display_item( $data ) {
        echo "<li class=\"wp-metabox-ordered-item\">";

        $this->display_input( $data );

        echo "<button class=\"button wp-metabox-remove\">remove</button></li>";
    }

    /**
     * Displays input item
     * @param  $data content
     * @return html       display input
     */
    protected function display_input( $data ) {
        echo "<input type=\"{$this->input_type}\" class=\"wp-metabox-input\" name=\"{$this->key}[]\" value=\"{$data}\" maxlength=\"{$this->max_length}\">";
    }

    /**
     * Updates post meta for a post in WP database as a single array
     * @param  int $post_id individual post id
     * @param  $data    content
     */
    public function update( $post_id, $data ) {
        foreach ( $data as $key => $value ) {
            if ( $value == '' ) {
                unset( $data[ $key ] );
            }
        }

        parent::update( $post_id, $data );
    }

    /**
     * Enqueues jquery sortable
     */
    public function enqueue_scripts() {
        wp_enqueue_script( 'jquery-ui-sortable' );

        wp_enqueue_script( 'wp-metabox-ordered-list-js', plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'js/ordered-list.js', array( 'jquery', 'jquery-ui-sortable' ), 'version' );

    }
}