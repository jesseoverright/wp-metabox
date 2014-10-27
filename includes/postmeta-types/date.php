<?php

/**
 * WP_DateMeta - Date postmeta using jquery ui datepicker
 */
class WP_DateMeta extends WP_PostMeta {
    protected $input_type = 'date';
    protected $max_length = 32;

    /**
     * Enqueues javascript for datepicker ui
     * @param [type] $key  [description]
     * @param array  $args [description]
     */
    public function __construct( $key, $args = array() ) {
        parent::__construct( $key, $args );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * creates default text input with custom id
     * @param   $data 
     * @return html       [description]
     */
    protected function display_input( $data ) {
        
        echo "<input type=\"text\" id=\"{$this->key}-date\" name=\"{$this->key}\" value=\"{$data}\" maxlength=\"{$this->max_length}\" placeholder=\"yyyy-mm-dd\">";
    }

    /**
     * Enqueues jquery ui datepicker and necessary css
     */
    public function enqueue_scripts() {
        wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

        wp_enqueue_script( 'wp-metabox-date-js', plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'js/date.js', array( 'jquery', 'jquery-ui-datepicker' ), WP_METABOX_VERSION );

        wp_localize_script( 'wp-metabox-date-js', 'datepicker_id', $this->key . '-date' );

    }
}