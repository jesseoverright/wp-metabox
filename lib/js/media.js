function renderMediaUploader( $ ) {
    'use strict';

    var file_frame, image_data, json;

    if ( undefined !== file_frame ) {

        file_frame.open();
        return;

    }

    file_frame = wp.media.frames.file_frame = wp.media({
        frame:    'post',
        state:    'insert',
        multiple: false
    });

    file_frame.on( 'insert', function() {

        json = file_frame.state().get( 'selection' ).first().toJSON();

        // First, make sure that we have the URL of an image to display
        if ( 0 > $.trim( json.url.length ) ) {
            return;
        }

        // After that, set the properties of the image and display it
        $( '#wp-media-postmeta-image-container' )
            .children( 'img' )
                .attr( 'src', json.url )
                .attr( 'alt', json.caption )
                .attr( 'title', json.title )
                .show()
            .parent()
            .removeClass( 'hidden' );

        // Next, hide the anchor responsible for allowing the user to select an image
        $( '#wp-media-postmeta-image-container' )
            .prev()
            .hide();

        // Display the anchor for the removing the featured image
        $( '#wp-media-postmeta-image-container' )
            .next()
            .show();

        // Store the image's information into the meta data fields
        $( '#wp-media-postmeta-src' ).val( json.url );
        $( '#wp-media-postmeta-title' ).val( json.title );
        $( '#wp-media-postmeta-alt' ).val( json.title );

    });

    // Now display the actual file_frame
    file_frame.open();

}

function resetUploadForm( $ ) {
    'use strict';

    // First, we'll hide the image
    $( '#wp-media-postmeta-image-container' )
        .children( 'img' )
        .hide();

    // Then display the previous container
    $( '#wp-media-postmeta-image-container' )
        .prev()
        .show();

    // We add the 'hidden' class back to this anchor's parent
    $( '#wp-media-postmeta-image-container' )
        .next()
        .hide()
        .addClass( 'hidden' );

    // Finally, we reset the meta data input fields
    $( '#wp-media-postmeta-image-info' )
        .children()
        .val( '' );

}

function renderFeaturedImage( $ ) {

    if ( '' !== $.trim ( $( '#wp-media-postmeta-src' ).val() ) ) {

        $( '#wp-media-postmeta-image-container' ).removeClass( 'hidden' );

        $( '#set-wp-media-postmeta-thumbnail' )
            .parent()
            .hide();

        $( '#remove-wp-media-postmeta-thumbnail' )
            .parent()
            .removeClass( 'hidden' );

    }

}

(function( $ ) {
    'use strict';

    $(function() {

        renderFeaturedImage( $ );

        $( '#set-wp-media-postmeta-thumbnail' ).on( 'click', function( evt ) {

            evt.preventDefault();

            renderMediaUploader( $ );

        });

        $( '#remove-wp-media-postmeta-thumbnail' ).on( 'click', function( evt ) {

            // Stop the anchor's default behavior
            evt.preventDefault();

            // Remove the image, toggle the anchors
            resetUploadForm( $ );

        });

    });

})( jQuery );