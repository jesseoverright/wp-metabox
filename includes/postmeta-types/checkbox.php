<?php

class WP_CheckboxMeta extends WP_SelectMeta {
    protected $input_type = 'checkbox';

    /**
     * Displays the checkbox items in the WP admin
     * @param  int $post_id  individual post id
     * @return html          input content
     */
    public function display_input( $data ) {
        if ( ! is_array ( $data ) ) {
            $data = array();
        }
        foreach ( $this->choices as $key => $value ) {
            echo "<input type=\"{$this->input_type}\" name=\"{$this->key}[]\"";
            if ( $this->has_custom_labels ) {
                echo " value=\"{$key}\"";
                if ( in_array( $key, $data ) ) {
                    echo " checked";
                }
            } else {
                echo " value=\"{$value}\"";
                if ( in_array( $value, $data ) ) {
                    echo " checked";
                }
            }
            
            echo ">{$value}<br>";
        }
    }

    /**
     * Updates postmeta for checkbox array
     * @param  id $post_id
     * @param  array $data    checked boxes
     */
    public function update( $post_id, $data ) {
        foreach ( $data as $key => $value ) {
            if ( $this->has_custom_labels ) {
                if ( ! array_key_exists( $value, $this->choices ) ) {
                    unset( $data[ $key ] );
                }
            } else {
                if ( ! in_array( $value, $this->choices ) ) {
                    unset( $data[ $key ] );
                }
            }
        }

        # default postmeta save
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