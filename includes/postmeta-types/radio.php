<?php

class WP_RadioMeta extends WP_SelectMeta {
    protected $input_type = 'radio';

    public function display_postmeta( $post_id ) {
        foreach ( $this->choices as $key => $value ) {
            echo "<input type=\"{$this->input_type}\" name=\"{$this->key} ";
            if ( $this->has_custom_labels ) {
                echo "value=\"{$key}\"";
                if ( $key == $data ) {
                    echo " checked";
                }
            } else {
                if ( $value == $data ) {
                    echo " checked";
                }
            }
            
            echo " value=\"{$value}\"><br>";
        }
    }
}