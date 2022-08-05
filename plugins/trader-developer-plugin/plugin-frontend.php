<?php
/**
 * Plugin Frontend
 *
 * @package Plugin_Frontend
 */

add_filter( 'the_content', 'modify_content' );

/**
 * Used to modify the content of the post page.
 *
 * @param string $content the original post content.
 */
function modify_content( $content ) {
	global $post;

	$user_query = new WP_User_Query(
		array(
			'role'   => 'author',
			'number' => '-1',
			'fields' => array(
				'display_name',
				'ID',
				'user_email',
				'user_url',
			),
		)
	);
	$authors    = $user_query->get_results();

	// Used to make sure only the checked authors/developers are displayed.
	$checked_author_id_meta = get_post_meta( $post->ID, '_checked_authors_meta_key' );
	// Handle the exception when there are author(s) but no author is checked.
	$no_author_checked = true;
	// Use CSS border to make the <div> box visible.
	$appended_content = '<div style="border-style: dotted; border-width: 0.1rem; padding: 1rem;"><h3 style="margin:0.5rem;">Developers </h3>';

	if ( ! empty( $authors ) ) {
		foreach ( $authors as $author ) {
			// Make sure to only display if the author has been checked in the post editor page.
			if ( isset( $checked_author_id_meta[0] ) && in_array( esc_attr( $author->ID ), $checked_author_id_meta[0], true ) ) {
				$appended_content  = $appended_content . '<li style="list-style: none; margin: 1rem;">
			<img src="https://www.gravatar.com/avatar/' . md5( strtolower( trim( $author->user_email ) ) ) . '" style="width:3rem; border-radius: 50%; vertical-align:middle;"/>
			<a href="' . ( isset( $author->user_url ) ? $author->user_url : '#' ) . '">' . ( $author->display_name ) . '</a>
			</li>';
				$no_author_checked = false;
			}
		}
		if ( $no_author_checked ) {
			$appended_content .= '<p>No checked author is found.</p>';
		}
	} else {
		$appended_content .= '<p>No author is found.</p>';
	}

	$modified_content = $content . $appended_content . '</div>';
	return $modified_content;
}
