<?php

if ( ! interface_exists( 'PostMeta' ) ) {
    interface PostMeta {

        public function __construct( $key, $options = array() );

        public function display_input( $post_id );

        public function update( $post_id, $data );
    }
}

class WP_PostMeta implements PostMeta {

    protected $key;
    protected $label;
    protected $max_length = 40;

    protected $input_type = 'text';

    public function __construct($key, $options = array() ) {
        $this->key = $key;
        if ( $options['label'] ) $this->label = $options['label']; else $this->label = $this->key;
        if ( $options['label'] == 'none' ) $this->label = '';
    }

    public function display_input( $post_id, $data = false ) {
        if ( ! $data ) $data = get_post_meta( $post_id, $this->key, true );
        
        echo "</p>";

        $this->display_label();
        
        echo "<input type=\"{$this->input_type}\" id=\"{$this->key}\" class=\"widefat\" name=\"{$this->key}\" value=\"{$data}\" maxlength=\"{$this->max_length}\">";

        echo "</p>";
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

    protected function display_label() {
        if ( $this->label ) {
            echo "<label for=\"{$this->key}\">{$this->label}</label>";
        }
    }

}

class WP_TextMeta extends WP_PostMeta {
    protected $max_length = 255;

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
    protected $input_type = 'select';
    protected $choices;

    public function __construct( $key, $options = array() ) {
        
        $this->choices = array();
        
        if ( $options['choices'] ) $this->choices = $options['choices'];

        parent::__construct( $key, $options );

    }

    public function display_input( $post_id, $data = false ) {
        if ( ! $data ) $data = get_post_meta( $post_id, $this->key, true );

        echo "<p>";

        $this->display_label();

        echo "<br />";

        echo "<select id=\"{$this->key}\" name=\"{$this->key}\">";

        foreach ( $this->choices as $value => $label ) {
            echo "<option value=\"{$value}\"";
            if ( $value == $data ) {
                echo " selected";
            }
            echo ">{$label}</option>";
        }

        echo "</select>";

        echo "</p>";
    }

    public function update( $post_id, $data ) {

        if ( array_key_exists( $data, $this->choices ) ) $data = $data; else $data = '';

        parent::update( $post_id, $data );
    }

}

class WP_TextareaMeta extends WP_PostMeta {
    protected $input_type = 'textarea';

    public function display_input( $post_id, $data = false ) {
        if ( ! $data ) $data = get_post_meta( $post_id, $this->key, true );
        
        $this->display_label();

        echo "<textarea id=\"{$this->key}\" name=\"{$this->key}\" class=\"widefat\">{$data}</textarea>";
    }

}

class WP_MediaMeta extends WP_PostMeta {
    protected $input_type = 'media';

    public function __construct( $key, $options = array() ) {
        parent::__construct( $key, $options );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    public function display_input( $post_id ) {
        if ( ! $data ) $data = get_post_meta( $post_id, $this->key, true );
        
        if ( ! array_key_exists( 'wp-media-postmeta-src', $data ) ) $data = array();

        $this->display_label();
        ?>
        <p class="hide-if-no-js">
            <a title="Set Image" href="javascript:;" id="set-wp-media-postmeta-thumbnail">Set Image</a>
        </p>

        <div id="wp-media-postmeta-image-container" class="hidden">
            <img src="<?php echo $data['wp-media-postmeta-src']; ?>" alt="<?php echo $data['wp-media-postmeta-alt']; ?>" title="<?php echo $data['wp-media-postmeta-title']; ?>" />
        </div><!-- #wp-media-postmeta-image-container -->

        <p class="hide-if-no-js hidden">
            <a title="Remove Image" href="javascript:;" id="remove-wp-media-postmeta-thumbnail">Remove Image</a>
        </p><!-- .hide-if-no-js -->

        <p id="wp-media-postmeta-image-info">
            <input type="hidden" id="wp-media-postmeta-src" name="wp-media-postmeta-src" value="<?php echo $data['wp-media-postmeta-src'] ?>" />
            <input type="hidden" id="wp-media-postmeta-title" name="wp-media-postmeta-title" value="<?php echo $data['wp-media-postmeta-title'] ?>" />
            <input type="hidden" id="wp-media-postmeta-alt" name="wp-media-postmeta-alt" value="<?php echo $data['wp-media-postmeta-alt'] ?>" />
        </p><!-- #wp-media-postmeta-image-meta -->
        <?php
    }

    public function update( $post_id, $data ) {
        $media = array();
        if ( isset( $_POST['wp-media-postmeta-src'] ) ) {
            $media['wp-media-postmeta-src'] = sanitize_text_field( $_POST['wp-media-postmeta-src'] );
        }

        if ( isset( $_POST['wp-media-postmeta-title'] ) ) {
            $media['wp-media-postmeta-title'] = sanitize_text_field( $_POST['wp-media-postmeta-title'] );
        }

        if ( isset( $_POST['wp-media-postmeta-alt'] ) ) {
            $media['wp-media-postmeta-alt'] = sanitize_text_field( $_POST['wp-media-postmeta-alt'] );
        }

        parent::update( $post_id, $media );
    }

    public function enqueue_scripts() {
        wp_enqueue_media();

        wp_enqueue_script( $this->key . '-media', plugin_dir_url( __FILE__ ) . 'js/media.js', array( 'jquery' ), 'version' );

    }
}

// WP_DateMeta
// WP_RadioMeta
// WP_WYSIWYGMeta
// WP_EmailMeta