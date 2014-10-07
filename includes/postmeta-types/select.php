<?php

class WP_SelectMeta extends WP_PostMeta {
    /**
     * Select input type
     * @var string
     */
    protected $input_type = 'select';

    /**
     * Choices available for select options
     * can be just an array
     *  ex. $choices = array( 'value', 'value2' )
     * or an associative array for custom labels
     *  ex. $choices['database_value'] = 'label'
     * @var array
     */
    protected $choices = array();

    /**
     * Flag for if choices include custom labels
     * @var boolean
     */
    protected $has_custom_labels;

    /**
     * Constructor adds choices option
     * @param string $key     key for this post meta
     * @param array  $args
     */
    public function __construct( $key, $args = array() ) {
                
        if ( $args['choices'] ) $this->choices = $args['choices'];

        # check if array is associative or not to determine custom labels
        $this->has_custom_labels = array_keys( $this->choices ) !== range(0, count( $this->choices ) - 1 );

        parent::__construct( $key, $args );

    }

    /**
     * Displays the select statement in the WP admin
     * @param  int $post_id  individual post id
     * @return html          input content
     */
    protected function display_input( $data ) {
        
        // add linebreak if label is displayed
        if ( ! $this->hidelabel ) {
            echo "<br>";
        }

        echo "<select id=\"{$this->key}\" name=\"{$this->key}\">";

        foreach ( $this->choices as $key => $value ) {
            if ( $this->has_custom_labels ) {
                echo "<option value=\"{$key}\"";
                if ( $key == $data ) {
                    echo " selected";
                }
            } else {
                echo "<option value=\"{$value}\"";
                if ( $value == $data ) {
                    echo " selected";
                }
            }
            
            echo ">{$value}</option>";
        }

        echo "</select>";

    }

    /**
     * Validates on select choices and updates post meta for a post in WP database
     * @param  int $post_id individual post id
     * @param  $data    content
     */
    public function update( $post_id, $data ) {
        if ( $this->has_custom_labels ) {
            if ( array_key_exists( $data, $this->choices ) ) $data = $data; else $data = '';
        } else {
            if ( in_array( $data, $this->choices ) ) $data = $data; else $data = '';
        }

        parent::update( $post_id, $data );
    }

}