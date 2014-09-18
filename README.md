# WP-Metabox

A lightweight framework for creating custom metaboxes and custom content types in WordPress.

## Usage

Check out `examples.php` for examples of creating custom postmeta input, custom metaboxes, and custom content types. Comment out the include in `wp-metabox.php` to see the examples in action in your WordPress site.

### Post Meta

`WP_PostMeta` handles admin display and saving post meta for the following input types:

- Text input
- URL sanitized text
- dropdowns
- Textareas
- Image upload (using the WordPress Media Uploader)

### Metaboxes

`WP_SimpleMetabox` can be used to create metaboxes with one post meta type.

`WP_Metabox` can be extended with multiple post meta options. Check out [the examples.php](https://github.com/jesseoverright/wp-metabox/blob/master/examples.php) for example usage.

### Custom Content Types

`WP_ContentType` has a few helpers for creating custom content types.
