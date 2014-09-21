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

        /**
         * Returns post meta data based on post meta type
         */
        public static function get_post_meta( $post_id, $key, $single);
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

        if ( $options['label'] ) {
            $this->label = $options['label'];
        } else {
            $this->label = $this->key;
        }

        if ( $options['hidelabel'] ) {
            $this->hidelabel = true;
        }

        if ( $options['max_length'] ) {
            $this->max_length = $options['max_length'];
        }

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

    /**
     * Returns post meta data based on provided key and custom post meta type
     * @param  ine  $post_id individual post id
     * @param  string  $key     the post meta key
     * @param  boolean $single  single result or all
     * @return content
     */
    public static function get_post_meta( $post_id, $key, $single = true ) {

        if ( get_post_meta( $post_id, $key, $single ) ) {
            $content = get_post_meta( $post_id, $key, $single );
        }

        return $content;
    }

    /**
     * Displays the input box
     * @param  $data content
     * @return html       input field
     */
    protected function display_input( $data ) {
        // limit input depending on max length
        if ( $this->max_length < 100 ) {
            $style = ' style="max-width: ' . ( ( 10 * $this->max_length ) - 20 ) . 'px"';
        }

        echo "<input type=\"{$this->input_type}\" id=\"{$this->key}\" class=\"widefat wp-metabox-input\" name=\"{$this->key}\" value=\"{$data}\" maxlength=\"{$this->max_length}\"{$style}>";
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

// WP_DateMeta
// WP_RadioMeta
// WP_WYSIWYGMeta
// WP_EmailMeta