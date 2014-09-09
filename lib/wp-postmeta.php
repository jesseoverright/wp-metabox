<?php

if ( ! interface_exists( 'PostMeta' ) ) {
    interface PostMeta {

        public function __construct( $key, $options = array() );

        public function display_input( $post_id );

        public function update($post_id, $data);
    }
}

class WP_PostMeta implements PostMeta {

    protected $key;
    protected $label;
    protected $size = 40;

    protected $input_type = 'text';

    public function __construct($key, $options = array() ) {
        $this->key = $key;
        if ( $options['label'] ) $this->label = $options['label']; else $this->label = $this->key;
        if ( $options['label'] == 'none' ) $this->label = '';
    }

    public function display_input( $post_id, $data = false ) {
        if ( ! $data ) $data = get_post_meta( $post_id, $this->key, true );
        
        if ( $this->label ) {
            echo '<label for="' . $this->key . '">' . $this->label . '</label>';
        }
        echo '<input type="' . $this->input_type . '" id ="'. $this->key . '" name="' . $this->key . '" value="' . $data . '" size="' . $this->size . '" style="width: 100%" >';
    }

    public function update( $post_id, $data ) {

        if ( get_post_meta($post_id, $this->key) == '') {
            add_post_meta($post_id, $this->key, $data, true);
        }
        elseif ( $data != get_post_meta($post_id, $this->key, true) ) {
            update_post_meta($post_id, $this->key, $data);
        }
        elseif ( $data == '' ) {
            delete_post_meta($post_id, $this->key, get_post_meta($post_id, $this->key, true));
        }

    }

}

class WP_TextMeta extends WP_PostMeta {
    // add basic text validation
}

class WP_URLMeta extends WP_TextMeta {

    public function display_input( $post_id ) {
        $data = esc_url( get_post_meta( $post_id, $this->key, true ) );

        parent::display_input( $post_id, $data );
    }

    public function update( $post_id, $data ) {
        $data = esc_url_raw( $_POST[ $this->key ] );

        parent::update( $post_id, $data );
    }
}

class WP_ArrayMeta extends WP_PostMeta {
    // does word press serialize?
}

class WP_SelectMeta extends WP_PostMeta {
    protected $choices;

    public function __construct( $key, $options = array() ) {
        
        $this->choices = array();
        
        if ( $options['choices'] ) $this->choices = $options['choices'];

        parent::__construct( $key, $options );

    }

    public function update( $post_id, $data ) {

        if ( in_array( $data, $this->choices ) ) $data = $data; else $data = '';

        parent::update( $post_id, $data );
    }

}

class WP_TextareaMeta extends WP_PostMeta {
    protected $input_type = 'textarea';

    public function display_input( $post_id, $data = false ) {
        if ( ! $data ) $data = get_post_meta( $post_id, $this->key, true );
        
        if ( $this->label ) {
            echo '<label for="' . $this->key . '">' . $this->label . '</label>';
        }
        echo '<textarea id ="'. $this->key . '" name="' . $this->key . '" size="' . $this->size . '" style="width: 100%" >' . $data . '</textarea>';
    }

}
// WP_MediaMeta