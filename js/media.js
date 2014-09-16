// modified from https://github.com/tutsplus/acme-footer-image/blob/master/admin/js/admin.js to allow for n media boxes

function renderMediaUploader( $, context ) {
    'use strict';

    var file_frame, image_data, json;

    if ( undefined !== file_frame ) {

        file_frame.open();
        return;

    }

    file_frame = wp.media.frames.file_frame = wp.media({
        frame:    'post',
        state:    'insert',
        multiple: false,
    });


    file_frame.on( 'insert', function() {

        json = file_frame.state().get( 'selection' ).first().toJSON();

        // First, make sure that we have the URL of an image to display
        if ( 0 > $.trim( json.url.length ) ) {
            return;
        }

        // After that, set the properties of the image and display it
        context.children( '.image-container' )
            .children( 'img' )
                .attr( 'src', json.url )
                .attr( 'alt', json.caption )
                .attr( 'title', json.title )
                .show()
            .parent()
            .removeClass( 'hidden' );

        // Next, hide the anchor responsible for allowing the user to select an image
        context.children( '.image-container' )
            .prev()
            .hide();

        // Display the anchor for the removing the featured image
        context.children( '.image-container' )
            .next()
            .show();

        // Store the image's information into the meta data fields
        context.children( '.image-info' ).children( '.src' ).val( json.url );
        context.children( '.image-info' ).children( '.title' ).val( json.title );
        context.children( '.image-info' ).children( '.alt' ).val( json.title );

    });

    // Now display the actual file_frame
    file_frame.open();

}

function resetUploadForm( $, context ) {
    'use strict';

    // First, we'll hide the image
    context.children( '.image-container' )
        .children( 'img' )
        .hide();

    // Then display the previous container
    context.children( '.image-container' )
        .prev()
        .show();

    // We add the 'hidden' class back to this anchor's parent
    context.children( '.image-container' )
        .next()
        .hide()
        .addClass( 'hidden' );

    // Finally, we reset the meta data input fields
    context.children( '.image-info' )
        .children()
        .val( '' );

}

function renderMetaboxImage( $, context ) {
    if ( '' !== $.trim ( context.children( '.image-info').children( '.src' ).val() ) ) {

        context.children( '.image-container' ).removeClass( 'hidden' );

        context.find( '.set-thumbnail' )
            .parent()
            .hide();

        context.find( '.remove-thumbnail' )
            .parent()
            .removeClass( 'hidden' );

    }

}

(function( $ ) {
    'use strict';

    $(function() {

        $( '.wp-metabox-media' ).each( function() {
            renderMetaboxImage( $, $(this) );

            $(this).find( '.set-thumbnail' ).on( 'click', function( event ) {
                event.preventDefault();
                renderMediaUploader( $, $(this).parent().parent() );
            });

            $(this).find( '.remove-thumbnail' ).on( 'click', function( event ) {
                event.preventDefault();
                resetUploadForm( $, $(this).parent().parent() );
            });
        });

    });

})( jQuery );