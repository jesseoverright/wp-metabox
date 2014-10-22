var datepicker_id;

(function( $ ) {
    'use strict';


    $(function() {

        $( '#' + datepicker_id ).datepicker( {
            dateFormat: 'yy-mm-dd'
        });

    });

})( jQuery );