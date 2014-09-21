# WP-Metabox

A lightweight framework for creating custom metaboxes and custom content types in WordPress.

Check out `examples.php` for examples of creating custom postmeta input, custom metaboxes, and custom content types. You can comment out the include in `wp-metabox.php` to see the examples in action in your WordPress site.

## Custom Post Meta

    # creates a new post meta type named 'custom-post-meta'
    $foo = new WP_PostMeta( 'custom-post-meta', array( 'type' => 'text' );

    # displays post meta in your theme template file
    echo get_post_meta( $post_id, 'custom-post-meta', true);

`WP_PostMeta` handles admin display and saving post meta for the following input types:

- Text input
- URL sanitized text
- numbers
- dropdowns
- textareas
- media & images (using the WordPress media uploader)
- ordered lists

## Custom Metaboxes

    # creates a simple metabox on posts
    $foo = new WP_SimpleMetabox(
        'custom-metabox',
        WP_PostMetaFactory::get_instance(),
        array(
            'label' => 'Custom Metabox',
            'posttype' => 'post'
        )
    );

`WP_SimpleMetabox` can be used to create metaboxes with one post meta type.

`WP_Metabox` can be extended with multiple post meta options. Check out [the examples.php](https://github.com/jesseoverright/wp-metabox/blob/master/examples.php) for example usage.

## Custom Content Types

    # creates a basic custom content type
    $foo = new WP_ContentType( 'foo' , array( 'singular' => 'Content Type Name' ) );

`WP_ContentType` has helps create custom content types. It will register the post type using some default options and can be overridden as necessary.
