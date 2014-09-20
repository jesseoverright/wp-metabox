<?php

/**
 * WP_MediaMeta - Images and media uploaded from WordPress
 */
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

        wp_enqueue_script( 'wp-metabox-media-js', plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'js/media.js', array( 'jquery' ), 'version' );

    }
}