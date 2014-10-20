<?php

class WP_RadioMeta extends WP_SelectMeta {
    /**
     * Radio input type
     * @var string
     */
    protected $input_type = 'radio';

    /**
     * Displays the radio buttons in the WP admin area
     * @param  $data content
     * @return html       radio buttons
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

class WP_BooleanMeta extends WP_RadioMeta {
    /**
     * The default choices boolean meta type
     * @var array
     */
    protected $choices = array ( 'true' => 'True', 'false' => 'False' );
}