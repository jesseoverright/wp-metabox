<?php

class WP_TextareaMeta extends WP_PostMeta {
    /**
     * Textarea input type
     * @var string
     */
    protected $input_type = 'textarea';

    /**
     * Number of rows for textarea
     * @var integer
     */
    protected $rows = 2;

    /**
     * Constructor adds height option
     * @param string $key     key for this post meta
     * @param array  $args
     */
    public function __construct( $key, $args = array() ) {
        if ( array_key_exists( 'rows', $args ) ) {
            $this->rows = $args['rows'];  
        } 

        parent::__construct( $key, $args );

    }

    /**
     * Displays the textarea in the WP admin
     * @param  int $post_id  individual post id
     * @return html          input content
     */
    protected function display_input( $data ) {
        echo "<textarea id=\"{$this->key}\" name=\"{$this->key}\" class=\"widefat\" rows=\"{$this->rows}\">{$data}</textarea>";
    }

}