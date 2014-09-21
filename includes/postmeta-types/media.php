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
                <img src="<?php echo $data[0]['src']; ?>" alt="<?php echo $data[0]['alt']; ?>" title="<?php echo $data[0]['title']; ?>" />

            </div>

            <p class="hide-if-no-js hidden">
                <a title="Remove Image" href="javascript:;" class="remove-thumbnail">Remove Image</a>
            </p>

            <p class="image-info">
                <input type="hidden" class="src" name="<?php echo $this->key ?>[src]" value="<?php echo $data['src'] ?>" />
                <input type="hidden" class="title" name="<?php echo $this->key ?>[title]" value="<?php echo $data['title'] ?>" />
                <input type="hidden" class="alt" name="<?php echo $this->key ?>[alt]" value="<?php echo $data['alt'] ?>" />
            </p>
        </div>
        <?php
    }

    /**
     * Enqueues wordpress media uploader and custom script
     */
    public function enqueue_scripts() {
        wp_enqueue_media();

        wp_enqueue_script( 'wp-metabox-media-js', plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'js/media.js', array( 'jquery' ), 'version' );

    }
}