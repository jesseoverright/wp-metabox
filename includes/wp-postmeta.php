<?php

if ( ! interface_exists( 'PostMeta' ) ) {
    interface PostMeta {
        /**
         * Constructor
         */
        public function __construct( $key, $args = array() );

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
     * Placeholder text when no value is present
     * @var string
     */
    protected $placeholder;

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
     * @param array  $args
     */
    public function __construct($key, $args = array() ) {
        $this->key = $key;

        if ( array_key_exists('label', $args ) ) {
            $this->label = $args['label'];
        } else {
            $this->label = $this->key;
        }

        if ( array_key_exists('hidelabel', $args ) ) {
            $this->hidelabel = true;
        }

        if ( array_key_exists('placeholder', $args ) ) {
            $this->placeholder = $args['placeholder'];
        }

        if ( array_key_exists('max_length', $args ) ) {
            $this->max_length = $args['max_length'];
        }

        $this->description = $args['description'];
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

    /**
     * Displays the input box
     * @param  $data content
     * @return html       input field
     */
    protected function display_input( $data ) {
        # additional input properties if any
        $additional = "";

        # limit input depending on max length
        if ( $this->max_length < 100 ) {
            $additional .= ' style="max-width: ' . ( ( 10 * $this->max_length ) - 20 ) . 'px"';
        }
        # add placeholder if entered
        if ( $this->placeholder ) {
            $additional .= " placeholder=\"{$this->placeholder}\"";
        }

        echo "<input type=\"{$this->input_type}\" id=\"{$this->key}\" class=\"widefat wp-metabox-input\" name=\"{$this->key}\" value=\"{$data}\" maxlength=\"{$this->max_length}\"{$additional}>";
    }

    /**
     * Displays the label in the admin area
     * @return html label 
     */
    protected function display_label() {
        if ( $this->hidelabel ) {
            $hide = 'class="screen-reader-text "';
        }

        echo "<label {$hide}for=\"{$this->key}\">{$this->label}</label>";
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

/**
 * Include postmeta types
 */
require_once( plugin_dir_path( __FILE__ ) . '/postmeta-types/text-inputs.php' );
require_once( plugin_dir_path( __FILE__ ) . '/postmeta-types/select.php' );
require_once( plugin_dir_path( __FILE__ ) . '/postmeta-types/textarea.php' );
require_once( plugin_dir_path( __FILE__ ) . '/postmeta-types/media.php' );
require_once( plugin_dir_path( __FILE__ ) . '/postmeta-types/ordered-list.php' );
require_once( plugin_dir_path( __FILE__ ) . '/postmeta-types/date.php' );

// WP_LatLongMeta
// WP_AddressMeta
// WP_FaqMeta
// WP_RadioMeta
// WP_WYSIWYGMeta
// WP_EmailMeta