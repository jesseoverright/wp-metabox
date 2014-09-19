<?php

if ( ! interface_exists( 'PostMeta' ) ) {
    interface PostMeta {
        /**
         * Constructor
         */
        public function __construct( $key, $options = array() );

        /**
         * Displays the postmeta input
         */
        public function display_postmeta( $post_id );

        /**
         * Updates post meta for individual post
         */
        public function update( $post_id, $data );
    }
}

class WP_PostMeta implements PostMeta {
    /**
     * Post meta key
     * @var string
     */
    protected $key;

    /**
     * Label of meta data in admin
     * @var string
     */
    protected $label;

    /**
     * A description of the postmeta for the WordPress admin
     * @var string
     */
    protected $description;

    /**
     * Maximum length of content
     * @var integer
     */
    protected $max_length = 40;

    /**
     * Input type for this post meta
     * @var string
     */
    protected $input_type = 'text';

    /**
     * Constructor
     * @param string $key     key for this post meta
     * @param array  $options
     */
    public function __construct($key, $options = array() ) {
        $this->key = $key;
        if ( $options['label'] ) $this->label = $options['label']; else $this->label = $this->key;
        if ( $options['label'] == 'none' ) $this->label = '';
        $this->description = $options['description'];
    }

    /**
     * Displays the post meta input in the WP admin
     * @param  int $post_id  individual post id
     * @return html          input content
     */
    public function display_postmeta( $post_id, $data = false ) {
        if ( ! $data ) $data = get_post_meta( $post_id, $this->key, true );
        
        echo "<p>";

        $this->display_label();
        
        $this->display_input( $data );

        $this->display_description();

        echo "</p>";
    }

    /**
     * Updates post meta for a post in WP database
     * @param  int $post_id individual post id
     * @param  $data    content
     */
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

    protected function display_input( $data ) {
        echo "<input type=\"{$this->input_type}\" id=\"{$this->key}\" class=\"widefat\" name=\"{$this->key}\" value=\"{$data}\" maxlength=\"{$this->max_length}\">";
    }

    /**
     * Displays the label in the admin area
     * @return html label 
     */
    protected function display_label() {
        if ( $this->label ) {
            echo "<label for=\"{$this->key}\">{$this->label}</label>";
        }
    }

    /**
     * Displays the description of this postmeta in the admin area
     * @return html description
     */
    protected function display_description() {
        if ( $this->description ) {
            echo "<span class=\"wp-metabox-description\">{$this->description}</span>";
        }
    }

}

class WP_TextMeta extends WP_PostMeta {
    /**
     * Max length of textbox
     * @var integer
     */
    protected $max_length = 255;

    // add basic text validation
}

class WP_URLMeta extends WP_TextMeta {

    /**
     * Sanitizes and displays postmeta input
     * @param  int $post_id WordPress post id
     * @return html          input content
     */
    protected function display_input( $post_id ) {
        $data = esc_url( get_post_meta( $post_id, $this->key, true ) );

        parent::display_input( $post_id, $data );
    }

    /**
     * Sanitizes URL and saves to database
     * @param  int $post_id WordPress post id
     * @param  url $data    content
     */
    public function update( $post_id, $data ) {
        $data = esc_url_raw( $_POST[ $this->key ] );

        parent::update( $post_id, $data );
    }
}

class WP_ArrayMeta extends WP_PostMeta {
    // does word press serialize?
}

class WP_SelectMeta extends WP_PostMeta {
    /**
     * Select input type
     * @var string
     */
    protected $input_type = 'select';

    /**
     * Choices available for select options
     *  ex. $choices['database_value'] = 'label'
     * @var array
     */
    protected $choices = array();

    /**
     * Constructor
     * @param string $key     key for this post meta
     * @param array  $options
     */
    public function __construct( $key, $options = array() ) {
                
        if ( $options['choices'] ) $this->choices = $options['choices'];

        parent::__construct( $key, $options );

    }

    /**
     * Displays the select statement in the WP admin
     * @param  int $post_id  individual post id
     * @return html          input content
     */
    protected function display_input( $data ) {

        echo "<br><select id=\"{$this->key}\" name=\"{$this->key}\">";

        foreach ( $this->choices as $value => $label ) {
            echo "<option value=\"{$value}\"";
            if ( $value == $data ) {
                echo " selected";
            }
            echo ">{$label}</option>";
        }

        echo "</select>";

    }

    /**
     * Validates on select choices and updates post meta for a post in WP database
     * @param  int $post_id individual post id
     * @param  $data    content
     */
    public function update( $post_id, $data ) {

        if ( array_key_exists( $data, $this->choices ) ) $data = $data; else $data = '';

        parent::update( $post_id, $data );
    }

}

class WP_TextareaMeta extends WP_PostMeta {
    protected $input_type = 'textarea';

    /**
     * Displays the textarea in the WP admin
     * @param  int $post_id  individual post id
     * @return html          input content
     */
    protected function display_input( $post_id, $data = false ) {
        echo "<textarea id=\"{$this->key}\" name=\"{$this->key}\" class=\"widefat\">{$data}</textarea>";
    }

}

class WP_MediaMeta extends WP_PostMeta {
    protected $input_type = 'media';

    /**
     * Constructor
     *
     * Enqueues required javascript for media upload
     * @param string $key     key for this post meta
     * @param array  $options
     */
    public function __construct( $key, $options = array() ) {
        parent::__construct( $key, $options );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Displays the media upload in the WP admin
     * @param  int $post_id  individual post id
     * @return html          input content
     */
    public function display_postmeta( $post_id ) {
        if ( ! $data ) $data = get_post_meta( $post_id, $this->key, true );
        
        if ( ! is_array( $data ) ) $data = array();
        ?>
        <div class="wp-metabox-media">

            <?php $this->display_label(); ?>
            <p class="hide-if-no-js">
                <a title="Set Image" href="javascript:;" class="set-thumbnail">Set Image</a>
            </p>

            <div class="image-container hidden">
                <img src="<?php echo $data[ $this->key . '-src']; ?>" alt="<?php echo $data[ $this->key . '-alt']; ?>" title="<?php echo $data[ $this->key . '-title']; ?>" />
            </div>

            <p class="hide-if-no-js hidden">
                <a title="Remove Image" href="javascript:;" class="remove-thumbnail">Remove Image</a>
            </p>

            <p class="image-info">
                <input type="hidden" class="src" name="<?php echo $this->key ?>-src" value="<?php echo $data[ $this->key . '-src'] ?>" />
                <input type="hidden" class="title" name="<?php echo $this->key ?>-title" value="<?php echo $data[ $this->key . '-title'] ?>" />
                <input type="hidden" class="alt" name="<?php echo $this->key ?>-alt" value="<?php echo $data[ $this->key . '-alt'] ?>" />
            </p>
        </div>
        <?php
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
     * Enqueues wordpress media uploader and custom script
     */
    public function enqueue_scripts() {
        wp_enqueue_media();

        wp_enqueue_script( 'wp-metabox-media-js', plugin_dir_url( dirname( __FILE__ ) ) . 'js/media.js', array( 'jquery' ), 'version' );

    }
}

// WP_DateMeta
// WP_RadioMeta
// WP_WYSIWYGMeta
// WP_EmailMeta