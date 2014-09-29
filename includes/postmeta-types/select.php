<?php

class WP_SelectMeta extends WP_PostMeta {
    /**
     * Select input type
     * @var string
     */
    protected $input_type = 'select';

    /**
     * Choices available for select options
     *  ex. $choices['database_value'] = 'label'
     * @var array
     */
    protected $choices = array();

    /**
     * Constructor adds choices option
     * @param string $key     key for this post meta
     * @param array  $args
     */
    public function __construct( $key, $args = array() ) {
                
        if ( $args['choices'] ) $this->choices = $args['choices'];

        parent::__construct( $key, $args );

    }

    /**
     * Displays the select statement in the WP admin
     * @param  int $post_id  individual post id
     * @return html          input content
     */
    protected function display_input( $data ) {

        echo "<br><select id=\"{$this->key}\" name=\"{$this->key}\">";

        foreach ( $this->choices as $value => $label ) {
            echo "<option value=\"{$value}\"";
            if ( $value == $data ) {
                echo " selected";
            }
            echo ">{$label}</option>";
        }

        echo "</select>";

    }

    /**
     * Validates on select choices and updates post meta for a post in WP database
     * @param  int $post_id individual post id
     * @param  $data    content
     */
    public function update( $post_id, $data ) {

        if ( array_key_exists( $data, $this->choices ) ) $data = $data; else $data = '';

        parent::update( $post_id, $data );
    }

}