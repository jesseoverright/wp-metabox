<?php

class WP_TextMeta extends WP_PostMeta {
    /**
     * Max length of textbox
     * @var integer
     */
    protected $max_length = 255;

    // add basic text validation
}

class WP_NumberMeta extends WP_PostMeta {
    protected $input_type = 'number';
}

class WP_URLMeta extends WP_TextMeta {

    /**
     * Sanitizes and displays postmeta input
     * @param  int $post_id WordPress post id
     * @return html          input content
     */
    public function display_postmeta( $post_id, $data = false ) {
        $data = esc_url( get_post_meta( $post_id, $this->key, true ) );

        parent::display_postmeta( $post_id, $data );
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