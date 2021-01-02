<?php
/*
Plugin Name: Open Graph Meta Tags
Plugin URI:
Description: Add Open Graph meta tags
Version: 0.2
Author: Jay Sitter
Author URI: http://www.jaysitter.com/
Text Domain: ogmt
License: GPL2
*/

global $post;

function ogmt_head( $args ) {
	if ( is_singular( $post ) ) {
		?>

		<meta name="description" content="<?php echo esc_attr( get_the_excerpt( $post ) ); ?>">

		<meta property="og:type" content="article">
		<meta property="og:title" content="<?php echo esc_attr( get_the_title( $post ) ); ?>">
		<meta property="og:description" content="<?php echo esc_attr( get_the_excerpt( $post ) ); ?>">
		<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
		<meta property="og:url" content="<?php echo esc_url( get_the_permalink( $post ) ); ?>">

		<?php

		$ogmt_image_id = 0;

		// Get all image attachments and use the first one if it exists
		$ogmt_attachments = get_attached_media( 'image', $post->ID );

		if ( $ogmt_attachments ) {
			$ogmt_image_id = array_values( $ogmt_attachments )[0]->ID;
		}

		// Use the post thumbnail for the image if it exists
		$ogmt_image_id = get_post_thumbnail_id( get_the_ID() ) ?: $ogmt_image_id;

		$ogmt_attachment_url = wp_get_attachment_image_src( $ogmt_image_id, 'full', false )[0];

		// Use site icon if we don't have an image anywhere else
		$ogmt_attachment_url = $ogmt_attachment_url ?: get_site_icon_url();

		if ( $ogmt_attachment_url ) {
			?>
				<meta property="og:image" content="<?php echo esc_attr( $ogmt_attachment_url ); ?>">
				<link rel="image_src" type="image/jpeg" href="<?php echo esc_attr( $ogmt_attachment_url ); ?>">
			<?php
		}
	}
}

add_filter( 'wp_head', 'ogmt_head' );
