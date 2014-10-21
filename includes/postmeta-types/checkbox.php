<?php

class WP_CheckboxMeta extends WP_SelectMeta {
    protected $input_type = 'checkbox';

    /**
     * Displays the checkbox items in the WP admin
     * @param  int $post_id  individual post id
     * @return html          input content
     */
    public function display_input( $data ) {
        foreach ( $this->choices as $key => $value ) {
            echo "<input type=\"{$this->input_type}\" name=\"{$this->key}\"";
            if ( $this->has_custom_labels ) {
                echo " value=\"{$key}\"";
                if ( $key == $data ) {
                    echo " checked";
                }
            } else {
                echo " value=\"{$value}\"";
                if ( $value == $data ) {
                    echo " checked";
                }
            }
            
            echo ">{$value}<br>";
        }
    }
}