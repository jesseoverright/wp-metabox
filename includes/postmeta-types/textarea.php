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
     * @param array  $options
     */
    public function __construct( $key, $options = array() ) {
        if ( $options['rows'] ) $this->rows = $options['rows'];

        parent::__construct( $key, $options );

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