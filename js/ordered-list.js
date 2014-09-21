(function( $ ) {
    'use strict';

    $(function() {

        $( '.wp-metabox-ordered-list' ).sortable();

        $( '.wp-metabox-add-new' ).on( 'click', function( event ) {
            event.preventDefault();

            var input_item = $( this ).parent().parent().find('.wp-metabox-ordered-list li:last-child');

            input_item.clone().insertAfter( input_item ).find('input').val('');
        });

        $( '.wp-metabox-remove' ).on( 'click', function( event ) {
            event.preventDefault();

            $( this ).parent().remove();
        })

    });

})( jQuery );