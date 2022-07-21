<?php
/**
 * Trader Developer Plugin
 *
 * @package Trader_Developer_Plugin
 * @version 1.0.0
 *
 * @wordpress-plugin
 * Plugin Name: Trader Developer Plugin
 * Description: A WordPress plugin built for selecting and displaying more than one author name on a post.
 * Version: 1.0.0
 * Author: Daoxi Sun
 * Author URI: https://daoxisun.com/
 */

/** Organize the frontend part into a separate file */
require plugin_dir_path( __FILE__ ) . 'plugin-frontend.php';

/** Use the action hooks for the meta boxes in posts */
add_action( 'add_meta_boxes', 'author_add_custom_box' );

/** Used for adding meta box to display multiple authors. */
function author_add_custom_box() {
	// adding a meta box to the post edit screen, use an array so that other screens can be added easily if needed.
	$screens = array( 'post' );
	foreach ( $screens as $screen ) {
		add_meta_box(
			'author_box_id',
			'Developers',
			'author_custom_box_html',
			$screen
		);
	}
}

/**
 * Contains the HTML of the displayed meta box.
 *
 * @param object $post the global post variable of WordPress.
 */
function author_custom_box_html( $post ) {
	$checked_author_id_meta = get_post_meta( $post->ID, '_checked_authors_meta_key' );

	$user_query = new WP_User_Query(
		array(
			'role'   => 'author',
			'number' => '-1',
			'fields' => array(
				'display_name',
				'ID',
			),
		)
	);
	$authors    = $user_query->get_results();
	// Generate the checkboxes dynamically using a loop.
	if ( ! empty( $authors ) ) {
		foreach ( $authors as $author ) {
			echo '<input type="checkbox" id="' . esc_attr( $author->ID ) . '" name="' . esc_attr( $author->ID ) . '" value="' . esc_attr( $author->ID ) . '"' . ( isset( $checked_author_id_meta[0] ) ? ( ( in_array( esc_attr( $author->ID ), $checked_author_id_meta[0], true ) ) ? 'checked="checked"' : '' ) : '' ) . '>
					<label for="' . esc_attr( $author->ID ) . '"> ' . esc_attr( $author->display_name ) . '</label><br>';
		}
	} else {
		echo '<p>No author is found.</p>';
	}
}

add_action( 'save_post', 'author_save_postdata' );

/**
 * Used to save the IDs of checked authors in the database
 *
 * @param int $post_id id of the WordPress post.
 */
function author_save_postdata( $post_id ) {

	$user_query = new WP_User_Query(
		array(
			'role'   => 'author',
			'number' => '-1',
			'fields' => array(
				'display_name',
				'ID',
			),
		)
	);
	$authors    = $user_query->get_results();

	$authors_checked_array = array();

	$author_save_nonce = wp_create_nonce();

	foreach ( $authors as $author ) {
		// Only checked checkboxes will be submitted and included in POST, use this behavior to detect which author was checked and update the array accordingly.
		if ( array_key_exists( $author->ID, $_POST ) && wp_verify_nonce( $author_save_nonce ) ) {
			array_push( $authors_checked_array, $author->ID );
		}

		update_post_meta(
			$post_id,
			'_checked_authors_meta_key',
			$authors_checked_array
		);
	}

}

