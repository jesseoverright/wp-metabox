# WP-Metabox

A lightweight framework for creating custom post meta, metaboxes and custom content types in WordPress.

WP-Metabox can be used to create and extend custom postmeta types, custom metaboxes, and custom content types. Comment out the `examples.php` include in `wp-metabox.php` to see the examples in action in your WordPress site.

## Custom Post Meta

    # creates a new post meta type named 'custom-post-meta'
    $foo = new WP_PostMeta( 'custom-post-meta', array( 'type' => 'text' );

    # displays post meta in your theme template file
    echo get_post_meta( $post_id, 'custom-post-meta', true);

`WP_PostMeta` handles admin display and saving post meta for the following input types:

- Text input
- URL sanitized text
- Numbers
- Dropdowns
- Textareas
- Media & images (using the WordPress media uploader)
- Ordered lists

## Custom Metaboxes

    # creates a simple metabox on posts of ordered lists
    $foo = new WP_SimpleMetabox(
        'custom-metabox',
        WP_PostMetaFactory::get_instance(),
        array(
            'label' => __( 'Custom Metabox' ),
            'posttype' => 'post',
            'type' => 'ordered-list'
        )
    );

`WP_SimpleMetabox` can be used to create metaboxes with one post meta type.

`WP_Metabox` can also be extended with multiple post meta options. Check out [the examples.php](https://github.com/jesseoverright/wp-metabox/blob/master/examples.php) for example custom metaboxes and post meta types.

## Custom Content Types

    # creates a basic custom content type
    $foo = new WP_ContentType( 'foo' , array( 'singular' => 'Content Type Name' ) );

`WP_ContentType` helps create custom content types. It will register the post type using some default arguments and can be overridden as necessary.
